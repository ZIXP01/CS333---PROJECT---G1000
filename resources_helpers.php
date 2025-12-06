<?php
require_once __DIR__ . "/config.php";

function getAllResources($conn) {
    $stmt = $conn->query("SELECT * FROM resources ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getResource($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM resources WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createResource($conn, $title, $description) {
    $stmt = $conn->prepare("INSERT INTO resources(title, description) VALUES(?,?)");
    return $stmt->execute([$title, $description]);
}

function updateResource($conn, $id, $title, $description) {
    $stmt = $conn->prepare("UPDATE resources SET title=?, description=? WHERE id=?");
    return $stmt->execute([$title, $description, $id]);
}

function deleteResource($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM resources WHERE id=?");
    return $stmt->execute([$id]);
}

function addComment($conn, $resource_id, $user_id, $comment) {
    $stmt = $conn->prepare("INSERT INTO resource_comments(resource_id, user_id, comment) VALUES(?,?,?)");
    return $stmt->execute([$resource_id, $user_id, $comment]);
}

function getComments($conn, $resource_id) {
    $stmt = $conn->prepare("
        SELECT c.*, u.name 
        FROM resource_comments c 
        JOIN users u ON c.user_id = u.id
        WHERE resource_id = ?
        ORDER BY c.id DESC
    ");
    $stmt->execute([$resource_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteComment($conn, $id, $user_role, $user_id) {
    if ($user_role === 'admin') {
        $stmt = $conn->prepare("DELETE FROM resource_comments WHERE id=?");
        return $stmt->execute([$id]);
    } else {
        $stmt = $conn->prepare("DELETE FROM resource_comments WHERE id=? AND user_id=?");
        return $stmt->execute([$id, $user_id]);
    }
}
?>
