<?php

// Validate note object;
$id = $_POST['id'] ?? ''; // Ensure $id is set
$pinned = $_POST['pinned'] ?? ''; // Ensure $pinned is set

/** @var Connection $connection */
$connection = require_once 'pdo.php';

$connection->pinNote($id, $pinned);

header('Location: ../home.php');
