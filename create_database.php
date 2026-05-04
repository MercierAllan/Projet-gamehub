<?php
// Connexion sans sélectionner de base de données pour pouvoir la créer
$host     = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;charset=utf8",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Création de la base de données
    $pdo->exec("CREATE DATABASE IF NOT EXISTS gamehub CHARACTER SET utf8 COLLATE utf8_general_ci");
    echo "Base de données <strong>gamehub</strong> créée (ou déjà existante).<br>";

    // Sélection de la base
    $pdo->exec("USE gamehub");

    // Création de la table users
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id       INT AUTO_INCREMENT PRIMARY KEY,
            login    VARCHAR(50)  NOT NULL UNIQUE,
            email    VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )
    ");
    echo "Table <strong>users</strong> créée (ou déjà existante).<br>";

    // Création de la table games
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS games (
            id          INT AUTO_INCREMENT PRIMARY KEY,
            title       VARCHAR(100) NOT NULL,
            genre       VARCHAR(50)  NOT NULL,
            description TEXT,
            image       VARCHAR(100),
            user_id     INT NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "Table <strong>games</strong> créée (ou déjà existante).<br>";

    echo "<br><strong>Base de données prête !</strong> Vous pouvez supprimer ce fichier ou ne pas y accéder en production.";

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
