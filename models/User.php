<?php
// models/User.php

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($nome, $email, $senha) {
        $nome = htmlspecialchars(trim($nome));
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if (!$email || empty($nome) || strlen($senha) < 6) {
            return false;
        }

        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        try {
            $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            return $stmt->execute([$nome, $email, $senha_hash]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function login($email, $senha) {
        $user = $this->findByEmail($email);

        if ($user && password_verify($senha, $user['senha'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            return true;
        }
        return false;
    }

    public static function checkAuth() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php'); 
            exit();
        }
    }

    public static function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        // Redireciona para o login na pasta views
        header('Location: views/login.php'); 
        exit();
    }
}
?>