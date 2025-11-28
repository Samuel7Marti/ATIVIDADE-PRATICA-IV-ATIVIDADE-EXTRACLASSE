<?php
// views/register.php
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
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';

    if (empty($nome) || empty($email) || empty($senha) || empty($confirma_senha)) {
        $error = 'Todos os campos são obrigatórios.';
    } elseif ($senha !== $confirma_senha) {
        $error = 'As senhas não coincidem.';
    } elseif (strlen($senha) < 6) {
        $error = 'A senha deve ter no mínimo 6 caracteres.';
    } else {
        if ($userModel->register($nome, $email, $senha)) {
            header('Location: login.php?registered=1');
            exit();
        } else {
            $error = 'Erro ao criar conta. O email pode já estar registrado.';
        }
    }
}

require_once '../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h2>Criar Conta</h2>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome:</label>
                            <input type="text" id="nome" name="nome" class="form-control" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha (Mín. 6 caracteres):</label>
                            <input type="password" id="senha" name="senha" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirma_senha" class="form-label">Confirmar Senha:</label>
                            <input type="password" id="confirma_senha" name="confirma_senha" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Registrar</button>
                    </form>
                    <p class="mt-3 text-center">
                        Já tem uma conta? <a href="login.php">Fazer Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>