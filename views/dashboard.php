<?php
// views/dashboard.php
require_once '../models/User.php';
User::checkAuth(); // Verifica autenticação

require_once '../config/db.php';
require_once '../models/Task.php';
$taskModel = new Task($pdo, $_SESSION['user_id']);

$counts = $taskModel->countTasks();
$tarefas_pendentes = $taskModel->getPending();

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<div class="container">
    <h1>Dashboard 👋</h1>
    <p>Status das suas tarefas:</p>
    
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Tarefas Pendentes vs. Concluídas</h5>
                    <canvas id="taskChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <hr>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Tarefas Pendentes (<?= $counts['pendentes'] ?>)</h2>
        <a href="task_form.php" class="btn btn-primary">➕ Adicionar Nova Tarefa</a>
    </div>

    <?php if (empty($tarefas_pendentes)): ?>
        <div class="alert alert-info">
            <p class="mb-0">🎉 Você não tem tarefas pendentes. Mantenha o bom trabalho!</p>
        </div>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($tarefas_pendentes as $tarefa): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($tarefa['titulo']) ?></strong>
                        <p class="mb-0 text-muted small"><?= htmlspecialchars($tarefa['descricao']) ?></p>
                        <small class="text-secondary">Criada em: <?= date('d/m/Y H:i', strtotime($tarefa['data_criacao'])) ?></small>
                    </div>
                    <div>
                        <a href="../controllers/TaskController.php?action=complete&id=<?= $tarefa['id'] ?>" class="btn btn-success btn-sm me-2">✅ Concluir</a>
                        <a href="task_form.php?id=<?= $tarefa['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('taskChart');
        if (ctx) {
            const taskChart = new Chart(ctx.getContext('2d'), {
                type: 'doughnut', 
                data: {
                    labels: ['Pendentes', 'Concluídas'],
                    datasets: [{
                        data: [<?= $counts['pendentes'] ?>, <?= $counts['concluidas'] ?>],
                        backgroundColor: ['#dc3545', '#28a745'], 
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                    }
                }
            });
        }
    });
</script>

<?php
require_once '../includes/footer.php';
?>