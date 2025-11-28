<?php
// logout.php
require_once 'models/User.php';
// Usa o método estático do Model User para limpar a sessão
User::logout();
// O método logout() já redireciona para views/login.php
?>