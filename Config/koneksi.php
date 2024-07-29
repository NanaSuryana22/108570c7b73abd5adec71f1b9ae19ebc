<?php
    $host = 'localhost';
    $db = 'TugasEmailNanaSuryana';
    $user = 'postgres'; // sesuaikan dengan username postgre yang ada di local
    $pass = 'nanasuryana';// sesuaikan dengan password postgre yang ada di local
    $port = '5432'; 

    $dsn = "pgsql:host=$host;port=$port;dbname=$db;user=$user;password=$pass";

    try {
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
?>
