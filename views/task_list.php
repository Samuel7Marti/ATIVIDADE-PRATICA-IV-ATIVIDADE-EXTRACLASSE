<?php
// views/task_list.php
require_once '../models/User.php';
User::checkAuth();

require_once '../config/db.php';
require_once '../models/Task.php';
$taskModel = new Task($pdo, $_SESSION['user_id']);

$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
$status = filter_input(INPUT_GET, 'status', FILTER_VALIDATE_INT, ['options' => ['default' => null]]);

$tasks = $taskModel->getAll($status, $search);

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<div class="container">
    <h1>Todas as Tarefas</h1>
    
    <form method="GET" class="row g-3 mb-4 align-items-end">
        <div class="col-md-5">
            <label for="search" class="form-label">Buscar por Descrição/Título</label>
            <input type="text" id="search" name="search" class="form-control" 
                   value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Digite a busca...">
        </div>
        <div class="col-md-4">
            <label for="status" class="form-label">Filtrar por Status</label>
            <select id="status" name="status" class="form-select">
                <option value="">Todos</option>
                <option value="0" <?= $status === 0 ? 'selected' : '' ?>>Pendentes</option>
                <option value="1" <?= $status === 1 ? 'selected' : '' ?>>Concluídas</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
            <a href="task_list.php" class="btn btn-secondary">Limpar</a>
        </div>
    </form>

    <?php if (empty($tasks)): ?>
        <div class="alert alert-warning">Nenhuma tarefa encontrada com os filtros aplicados.</div>
    <?php else: ?>
        <ul class="list-group mt-3">
            <?php foreach ($tasks as $task): ?>
                <?php 
                    $is_concluida = $task['concluida'] == 1;
                    $list_class = $is_concluida ? 'list-group-item-success' : '';
                    $text_class = $is_concluida ? 'task-done' : ''; 
                ?>
                <li class="list-group-item d-flex justify-content-between align-items-center <?= $list_class ?>">
                    <div class="<?= $text_class ?>">
                        <strong>#<?= $task['id'] ?> - <?= htmlspecialchars($task['titulo']) ?></strong>
                        <p class="mb-0 text-muted small"><?= htmlspecialchars($task['descricao']) ?></p>
                        <small class="text-secondary">Criada em: <?= date('d/m/Y H:i', strtotime($task['data_criacao'])) ?></small>
                    </div>
                    <div class="btn-group" role="group">
                        <?php if (!$is_concluida): ?>
                             <a href="../controllers/TaskController.php?action=complete&id=<?= $task['id'] ?>" class="btn btn-success btn-sm" title="Concluir">✅</a>
                        <?php endif; ?>
                        <a href="task_form.php?id=<?= $task['id'] ?>" class="btn btn-warning btn-sm" title="Editar">✏️</a>
                        <a href="../controllers/TaskController.php?action=delete&id=<?= $task['id'] ?>" 
                           onclick="return confirm('Tem certeza que deseja remover esta tarefa?')" 
                           class="btn btn-danger btn-sm" title="Remover">🗑️</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php
require_once '../includes/footer.php';
?>