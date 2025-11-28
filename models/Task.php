<?php
// models/Task.php

class Task {
    private $pdo;
    private $userId;

    public function __construct($pdo, $userId) {
        $this->pdo = $pdo;
        $this->userId = $userId;
    }

    // CREATE
    public function create($titulo, $descricao) {
        $titulo = htmlspecialchars(trim($titulo));
        $descricao = htmlspecialchars(trim($descricao));

        if (empty($titulo)) {
            return false; 
        }

        $sql = "INSERT INTO tarefas (usuario_id, titulo, descricao) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$this->userId, $titulo, $descricao]);
    }

    // UPDATE
    public function update($taskId, $titulo, $descricao) {
        $titulo = htmlspecialchars(trim($titulo));
        $descricao = htmlspecialchars(trim($descricao));

        if (empty($titulo)) {
            return false;
        }

        $sql = "UPDATE tarefas SET titulo = ?, descricao = ? WHERE id = ? AND usuario_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$titulo, $descricao, $taskId, $this->userId]);
    }

    // Marcar como concluída
    public function complete($taskId) {
        $sql = "UPDATE tarefas SET concluida = 1 WHERE id = ? AND usuario_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$taskId, $this->userId]);
    }
    
    // DELETE
    public function delete($taskId) {
        $sql = "DELETE FROM tarefas WHERE id = ? AND usuario_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$taskId, $this->userId]);
    }

    // READ (Com Filtros e Busca)
    public function getAll($status = null, $search = null) {
        $sql = "SELECT id, titulo, descricao, concluida, data_criacao FROM tarefas WHERE usuario_id = ?";
        $params = [$this->userId];

        if ($status !== null && in_array($status, [0, 1])) {
            $sql .= " AND concluida = ?";
            $params[] = $status; 
        }

        if (!empty($search)) {
            $sql .= " AND (titulo LIKE ? OR descricao LIKE ?)";
            $search_param = '%' . htmlspecialchars(trim($search)) . '%'; 
            $params[] = $search_param;
            $params[] = $search_param; 
        }

        $sql .= " ORDER BY data_criacao DESC"; 

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ (Apenas Pendentes para Dashboard)
    public function getPending() {
        $sql = "SELECT id, titulo, descricao, data_criacao FROM tarefas WHERE usuario_id = ? AND concluida = 0 ORDER BY data_criacao ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Contagem para Gráfico
    public function countTasks() {
        $sql_pendentes = "SELECT COUNT(*) FROM tarefas WHERE usuario_id = ? AND concluida = 0";
        $stmt_pendentes = $this->pdo->prepare($sql_pendentes);
        $stmt_pendentes->execute([$this->userId]);
        $pendentes = $stmt_pendentes->fetchColumn();

        $sql_concluidas = "SELECT COUNT(*) FROM tarefas WHERE usuario_id = ? AND concluida = 1";
        $stmt_concluidas = $this->pdo->prepare($sql_concluidas);
        $stmt_concluidas->execute([$this->userId]);
        $concluidas = $stmt_concluidas->fetchColumn();

        return ['pendentes' => (int)$pendentes, 'concluidas' => (int)$concluidas];
    }
    
    // READ (Por ID para Edição)
    public function getById($taskId) {
        $sql = "SELECT id, titulo, descricao FROM tarefas WHERE id = ? AND usuario_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$taskId, $this->userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>