<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header("Location: /QodexV2/auth/login.php");
    exit;
}

require_once('../config/database.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete the category
    $sql = "DELETE FROM category WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: categories.php");
    exit;
}
?>
