<?php
session_start();

// Fonction de validation du mot de passe
function validate_password($password) {
    return preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password);
}

// Récupérer les données du formulaire
$login = trim($_POST['login'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Tableau pour stocker les erreurs
$errors = [];

// Vérification du login
if (empty($login) || strlen($login) < 3) {
    $errors[] = "Le login doit contenir au moins 3 caractères.";
}

// Vérification de l'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Adresse email invalide.";
}

// Vérification du mot de passe
if (!validate_password($password)) {
    $errors[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.";
}

// Vérification de la confirmation du mot de passe
if ($password !== $confirm_password) {
    $errors[] = "Les mots de passe ne correspondent pas.";
}

// Si des erreurs, affichage et sortie
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
    echo "<p><a href='register.html'>Retour au formulaire</a></p>";
    exit;
}

// Hachage du mot de passe
$hashed_password = password_hash($password, PASSWORD_DEFAULT);


// Vérifier si le login ou email existe déjà
foreach ($users as $user) {
    if ($user['login'] === $login) {
        die("<p style='color:red;'>Ce login est déjà utilisé.</p><p><a href='register.html'>Retour</a></p>");
    }
    if ($user['email'] === $email) {
        die("<p style='color:red;'>Cette adresse email est déjà utilisée.</p><p><a href='register.html'>Retour</a></p>");
    }
}

// Ajouter le nouvel utilisateur
$users[] = [
    'login' => $login,
    'email' => $email,
    'password' => $hashed_password
];

// Sauvegarder dans le fichier
file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

// Redirection vers login
header('Location: login.html');
exit;
?>