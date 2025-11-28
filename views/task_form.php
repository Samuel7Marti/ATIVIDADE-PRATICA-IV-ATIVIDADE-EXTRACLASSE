<?php
// views/task_form.php
require_once '../models/User.php';
User::checkAuth();

require_once '../config/db.php';
require_once '../models/Task.php';
$taskModel = new Task($pdo, $_SESSION['user_id']);

$taskId = $_GET['id'] ?? null;
$task = null;
$action = 'create';
$page_title = 'Criar Nova Tarefa';

if ($taskId) {
    $task = $taskModel->getById($taskId);
    if ($task) {
        $action = 'edit';
        $page_title = 'Editar Tarefa';
    } else {
        $_SESSION['error'] = "Tarefa não encontrada.";
        header('Location: task_list.php');
        exit();
    }
}

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<div class="container">
    <h1><?= $page_title ?></h1>
    
    <form method="POST" action="../controllers/TaskController.php">
        <input type="hidden" name="action" value="<?= $action ?>">
        <?php if ($action === 'edit'): ?>
            <input type="hidden" name="task_id" value="<?= htmlspecialchars($taskId) ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label for="titulo" class="form-label">Título da Tarefa</label>
            <input type="text" id="titulo" name="titulo" class="form-control" 
                   value="<?= htmlspecialchars($task['titulo'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição (Detalhes)</label>
            <textarea id="descricao" name="descricao" class="form-control" rows="4"><?= htmlspecialchars($task['descricao'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Tarefa</button>
        <a href="task_list.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php
require_once '../includes/footer.php';
?>