<?php
// controllers/TaskController.php

session_start();

// 1. Verifica Autenticação
if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php'); 
    exit();
}

// 2. Dependências
require_once '../config/db.php';
require_once '../models/Task.php';
$taskModel = new Task($pdo, $_SESSION['user_id']);

// 3. Coleta e Sanitiza a Ação
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS) ?? 
          filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS);

$redirect_to = '../views/dashboard.php'; 

if ($action) {
    switch ($action) {
        
        // Ação: CRIAR ou EDITAR Tarefa
        case 'create':
        case 'edit':
            $titulo = $_POST['titulo'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $taskId = filter_input(INPUT_POST, 'task_id', FILTER_VALIDATE_INT);

            if ($action === 'create') {
                if ($taskModel->create($titulo, $descricao)) {
                    $_SESSION['message'] = 'Tarefa criada com sucesso!';
                } else {
                    $_SESSION['error'] = 'Erro ao criar a tarefa. Título é obrigatório.';
                }
            } elseif ($action === 'edit' && $taskId) {
                if ($taskModel->update($taskId, $titulo, $descricao)) {
                    $_SESSION['message'] = 'Tarefa atualizada com sucesso!';
                } else {
                    $_SESSION['error'] = 'Erro ao atualizar a tarefa. Título é obrigatório ou ID inválido.';
                }
            }
            $redirect_to = '../views/task_list.php'; 
            break;

        // Ação: MARCAR COMO CONCLUÍDA
        case 'complete':
            $taskId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($taskId && $taskModel->complete($taskId)) {
                $_SESSION['message'] = 'Tarefa marcada como concluída! 🎉';
            } else {
                $_SESSION['error'] = 'Erro ao concluir a tarefa.';
            }
            $redirect_to = $_SERVER['HTTP_REFERER'] ?? '../views/dashboard.php';
            break;

        // Ação: REMOVER Tarefa
        case 'delete':
            $taskId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($taskId && $taskModel->delete($taskId)) {
                $_SESSION['message'] = 'Tarefa removida com sucesso! 🗑️';
            } else {
                $_SESSION['error'] = 'Erro ao remover a tarefa.';
            }
            $redirect_to = '../views/task_list.php';
            break;
            
        default:
            $_SESSION['error'] = 'Ação inválida.';
            $redirect_to = '../views/dashboard.php';
            break;
    }
}

// 4. Redirecionamento
header("Location: $redirect_to");
exit();
?>