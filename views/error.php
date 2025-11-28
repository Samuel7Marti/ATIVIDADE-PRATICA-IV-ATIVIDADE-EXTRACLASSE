<?php
// views/error.php
session_start();

require_once '../includes/header.php';
// Nota: A navbar só será exibida se a sessão estiver ativa, mas o header e footer garantem o layout base.
// Pode ser necessário incluir uma versão simplificada da navbar para usuários deslogados.
if (isset($_SESSION['user_id'])) {
    require_once '../includes/navbar.php';
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-danger text-center" role="alert">
                <h4 class="alert-heading">🚫 Ocorreu um Erro!</h4>
                <p>Desculpe, a página que você tentou acessar não está disponível ou ocorreu um problema inesperado.</p>
                <hr>
                <p class="mb-0">
                    <?php 
                    $error_message = $_GET['message'] ?? "Não foi possível processar sua solicitação.";
                    echo htmlspecialchars($error_message);
                    ?>
                </p>
            </div>
            <div class="text-center">
                <a href="dashboard.php" class="btn btn-primary">Voltar para a Dashboard</a>
                <a href="../index.php" class="btn btn-secondary">Página Inicial</a>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>