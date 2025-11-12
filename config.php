<?php
$host = 'localhost';
$port = '8002';
$db = 'projectdbactual';
$user = 'root';
$pass = 'abc';
$charset = 'utf8mb4';
global $pdo;

// Data Source Name string
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try{
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connected successfully";
}catch(PDOException $e){
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

?>

