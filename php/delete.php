<?php

/** @var Connection $connection */
$connection = require_once 'pdo.php';

$connection->removeNote($_POST['id']);

header('Location: ../home.php');
