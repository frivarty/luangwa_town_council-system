<?php

// Database credentials
$dbHost = "localhost";
$dbName = "club";
$dbUsername = "root";
$dbPassword = "";

// Establish database connection
try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Start session
session_start();
