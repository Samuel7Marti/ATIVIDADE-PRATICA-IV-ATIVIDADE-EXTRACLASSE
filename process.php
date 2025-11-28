<?php
// process.php
session_start();

// Verifica autenticação
if (!isset($_SESSION['user_id'])) {
    header('Location: views/login.php');
    exit();
}

require_once 'config/db.php';
require_once 'models/Task.php';
$taskModel = new Task($pdo, $_SESSION['user_id']);

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$redirect_to = 'views/dashboard.php';

if ($action) {
    switch ($action) {
        case 'create':
        case 'edit':
            // Validação e Sanitização (a validação básica está no modelo)
            $titulo = $_POST['titulo'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $taskId = $_POST['task_id'] ?? null;

            if ($action === 'create' && $taskModel->create($titulo, $descricao)) {
                $_SESSION['message'] = 'Tarefa criada com sucesso!';
            } elseif ($action === 'edit' && $taskId && $taskModel->update($taskId, $titulo, $descricao)) {
                $_SESSION['message'] = 'Tarefa atualizada com sucesso!';
            } else {
                $_SESSION['error'] = 'Erro ao salvar a tarefa. Verifique os dados.';
            }
            $redirect_to = 'views/task_list.php';
            break;

        case 'complete':
            $taskId = $_GET['id'] ?? null;
            if ($taskId && $taskModel->complete($taskId)) {
                $_SESSION['message'] = 'Tarefa marcada como concluída!';
            } else {
                $_SESSION['error'] = 'Erro ao concluir a tarefa.';
            }
            // Redireciona para a página anterior, ou para a dashboard
            $redirect_to = $_SERVER['HTTP_REFERER'] ?? 'views/dashboard.php';
            break;

        case 'delete':
            $taskId = $_GET['id'] ?? null;
            if ($taskId && $taskModel->delete($taskId)) {
                $_SESSION['message'] = 'Tarefa removida com sucesso!';
            } else {
                $_SESSION['error'] = 'Erro ao remover a tarefa.';
            }
            $redirect_to = 'views/task_list.php';
            break;
            
        default:
            $_SESSION['error'] = 'Ação inválida.';
            break;
    }
}

header("Location: $redirect_to");
exit();
?>