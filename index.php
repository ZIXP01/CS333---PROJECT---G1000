<?php

session_start();
$_SESSION['logged_in'] = $_SESSION['logged_in'] ?? false;
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'Database.php';

$db = (new Database())->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

$requestData = json_decode(file_get_contents('php://input'), true);

$queryParams = $_GET;

function getStudents($db) {

    $sql = "SELECT id, student_id, name, email, created_at FROM students";
    $conditions = [];
    global $queryParams;
    $params = [];
    if (!empty($queryParams['search'])) {
        $conditions[] = "(name LIKE :search OR student_id LIKE :search OR email LIKE :search)";
        $params[':search'] = "%{$queryParams['search']}%";
    }

    if ($conditions) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $allowedSort = ['name', 'student_id', 'email'];
    $sort = in_array($queryParams['sort'] ?? '', $allowedSort) ? $queryParams['sort'] : 'student_id';
    $order = ($queryParams['order'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';
    $sql .= " ORDER BY $sort $order";
    
    $stmt = $db->prepare($sql);
  
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
   
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
     sendResponse(['success' => true, 'data' => $students]);
}

function getStudentById($db, $studentId) {
    
     $stmt = $db->prepare("SELECT id, student_id, name, email, created_at FROM students WHERE student_id = :student_id");
   
     $stmt->bindParam(':student_id', $studentId);
    
     $stmt->execute();
    
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
   
    if ($student) {
     
        sendResponse(['success' => true, 'data' => $student]);
    } else {
        
        sendResponse(['success' => false, 'message' => 'Student not found'], 404);
    }
}

function createStudent($db, $data) {
   
    $required = ['student_id', 'name', 'email', 'password'];
    foreach ($required as $field) {
        if (empty($data[$field])) sendResponse(['success' => false, 'message' => "$field is required"], 400);
    }
   
    $student_id = sanitizeInput($data['student_id']);
    $name = sanitizeInput($data['name']);
    $email = sanitizeInput($data['email']);
    $password = $data['password'];

    if (!validateEmail($email)) sendResponse(['success' => false, 'message' => "Invalid email"], 400);
   
    $check = $db->prepare("SELECT * FROM students WHERE student_id = :student_id OR email = :email");
    $check->execute([':student_id' => $student_id, ':email' => $email]);
    if ($check->fetch()) sendResponse(['success' => false, 'message' => "Student ID or Email already exists"], 409);
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  
     $stmt = $db->prepare("INSERT INTO students (student_id, name, email, password, created_at) VALUES (:student_id, :name, :email, :password, NOW())");
   
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
   
      $success = $stmt->execute();
 
        if ($success) {
        sendResponse(['success' => true, 'message' => 'Student created successfully'], 201);
    } else {
        sendResponse(['success' => false, 'message' => 'Failed to create student'], 500);
    }
}

function updateStudent($db, $data) {
    
    if (empty($data['student_id'])) sendResponse(['success' => false, 'message' => 'student_id is required'], 400);
    $student_id = sanitizeInput($data['student_id']);
    
    $stmt = $db->prepare("SELECT * FROM students WHERE student_id = :student_id");
    $stmt->execute([':student_id' => $student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$student) sendResponse(['success' => false, 'message' => 'Student not found'], 404);
   
     $fields = [];
    $params = [];
    if (!empty($data['name'])) {
        $fields[] = "name = :name";
        $params[':name'] = sanitizeInput($data['name']);
    }
    if (!empty($data['email'])) {
        if (!validateEmail($data['email'])) sendResponse(['success' => false, 'message' => 'Invalid email'], 400);
   
            $check = $db->prepare("SELECT * FROM students WHERE email = :email AND student_id != :student_id");
        $check->execute([':email' => $data['email'], ':student_id' => $student_id]);
        if ($check->fetch()) sendResponse(['success' => false, 'message' => 'Email already exists'], 409);

        $fields[] = "email = :email";
        $params[':email'] = sanitizeInput($data['email']);
    }

    if (!$fields) sendResponse(['success' => false, 'message' => 'No fields to update'], 400);

    $params[':student_id'] = $student_id;
    $sql = "UPDATE students SET " . implode(", ", $fields) . " WHERE student_id = :student_id";

    $stmt = $db->prepare($sql);
   
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
  
    $success = $stmt->execute();
  
    if ($success) {
        sendResponse(['success' => true, 'message' => 'Student updated successfully']);
    } else {
        sendResponse(['success' => false, 'message' => 'Failed to update student'], 500);
    }
}


function deleteStudent($db, $studentId) {
   
     if (empty($studentId)) sendResponse(['success' => false, 'message' => 'student_id is required'], 400);
  
    $stmt = $db->prepare("SELECT * FROM students WHERE student_id = :student_id");
    $stmt->execute([':student_id' => $studentId]);
    if (!$stmt->fetch()) sendResponse(['success' => false, 'message' => 'Student not found'], 404);
   
      $stmt = $db->prepare("DELETE FROM students WHERE student_id = :student_id");
    
     $stmt->bindParam(':student_id', $studentId);
    
    $success = $stmt->execute();

        if ($success) {
        sendResponse(['success' => true, 'message' => 'Student deleted successfully']);
    } else {
        sendResponse(['success' => false, 'message' => 'Failed to delete student'], 500);
    }
}


function changePassword($db, $data) {
    
    $required = ['student_id', 'current_password', 'new_password'];
    foreach ($required as $field) {
        if (empty($data[$field])) sendResponse(['success' => false, 'message' => "$field is required"], 400);
    }
    
     if (strlen($data['new_password']) < 8) sendResponse(['success' => false, 'message' => 'Password must be at least 8 characters'], 400);
   
    $stmt = $db->prepare("SELECT password FROM students WHERE student_id = :student_id");
    $stmt->execute([':student_id' => $data['student_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) sendResponse(['success' => false, 'message' => 'Student not found'], 404);
    
    if (!password_verify($data['current_password'], $row['password'])) {
        sendResponse(['success' => false, 'message' => 'Current password is incorrect'], 401);
    }
   
    $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);
   
    $stmt = $db->prepare("UPDATE students SET password = :password WHERE student_id = :student_id");
   
    $success = $stmt->execute([':password' => $hashedPassword, ':student_id' => $data['student_id']]);
 
    if ($success) {
    sendResponse(['success' => true, 'message' => 'Password changed successfully']);
  } else {
    sendResponse(['success' => false, 'message' => 'Failed to change password'], 500);
 }
}



try {


    if ($method === 'GET') {
       
        if (!empty($queryParams['student_id'])) {
           
            getStudentById($db, $queryParams['student_id']);
        } else {
          
            getStudents($db);
        }
    } elseif ($method === 'POST') {
       
        if (!empty($queryParams['action']) && $queryParams['action'] === 'change_password') {
            
            changePassword($db, $requestData);
        } else {
           
            createStudent($db, $requestData);
        }
    } elseif ($method === 'PUT') {
        
        updateStudent($db, $requestData);
    } elseif ($method === 'DELETE') {
        $studentId = $queryParams['student_id'] ?? $requestData['student_id'] ?? '';
        deleteStudent($db, $studentId);
    } else {
        
        http_response_code(405);
        sendResponse(['success' => false, 'message' => 'Method Not Allowed'], 405);
    }
    
} catch (PDOException $e) {
   
    sendResponse(['success' => false, 'message' => 'Database error'], 500);
} catch (Exception $e) {
  
    sendResponse(['success' => false, 'message' => 'Server error'], 500);
}

function sendResponse($data, $statusCode = 200) {
    // TODO: Set HTTP response code
    http_response_code($statusCode);
    // TODO: Echo JSON encoded data
    echo json_encode($data);
    // TODO: Exit to prevent further execution
    exit();
}

function validateEmail($email) {
   
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}


function sanitizeInput($data) {  
    $data = trim($data);

    $data = strip_tags($data);
 
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    
    return $data;
}

?>
