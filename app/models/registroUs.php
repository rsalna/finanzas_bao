<?php
include_once __DIR__.'/connection.php';

function register_user(string $username, string $password, string $correo): bool {
    $conn = db();
    $stmt = $conn->prepare("INSERT INTO users (username, password, correo) VALUES (?, ?, ?)");
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("sss", $username, $hashed_password, $correo);
    return $stmt->execute();
}

?>