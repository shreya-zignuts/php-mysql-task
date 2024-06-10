<?php
session_start();

function checkRole($requiredRole) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole) {
        header('Location: /login.php');
        exit();
    }
}
?>
