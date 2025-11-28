<?php
// controllers/AuthController.php

session_start();

require_once '../models/User.php';

// Este controlador é usado primariamente para centralizar o Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    User::logout();
}

header('Location: ../views/login.php');
exit();
?>