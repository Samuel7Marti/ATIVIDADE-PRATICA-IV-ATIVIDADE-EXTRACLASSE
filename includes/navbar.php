<?php 
// includes/navbar.php
// Assume que a sessão já foi iniciada e $_SESSION['user_nome'] existe
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">📝 To-Do List</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="task_list.php">Todas as Tarefas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="task_form.php">Nova Tarefa</a>
        </li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item">
            <span class="nav-link text-white">Olá, <?= htmlspecialchars($_SESSION['user_nome']) ?></span>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-outline-danger" href="../logout.php">Sair</a> 
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-3">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
</div>