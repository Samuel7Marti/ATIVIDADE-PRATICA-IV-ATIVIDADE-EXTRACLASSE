<?php
// views/login.php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

require_once '../config/db.php';
require_once '../models/User.php';
$userModel = new User($pdo);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    if ($userModel->login($email, $senha)) {
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Email ou senha incorretos.';
    }
}

require_once '../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2>Login no To-Do List</h2>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['registered'])): ?>
                         <div class="alert alert-success">Conta criada com sucesso! Faça login.</div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha:</label>
                            <input type="password" id="senha" name="senha" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>
                    <p class="mt-3 text-center">
                        Ainda não tem conta? <a href="register.php">Cadastre-se</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>