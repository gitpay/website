<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gitpay";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // sql to create table
    $sql = "CREATE TABLE users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) NOT NULL,
    name VARCHAR(255) NULL,
    company VARCHAR(255) NULL,
    location VARCHAR(255) NULL,
    email VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL
    )";


    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Table users created successfully";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
?>
