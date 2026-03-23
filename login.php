<?php
session_start();

// Si déjà connecté -> redirection
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
// Récupérer les données du formulaire
$identifier = trim($_POST['identifier'] ?? '');
$password = $_POST['password'] ?? '';

// Vérification basique
if (empty($identifier) || empty($password)) {
    die("<p style='color:red;'>Veuillez remplir tous les champs.</p><p><a href='login.html'>Retour</a></p>");
}

// Lecture des utilisateurs
$usersFile = 'users.json';
if (!file_exists($usersFile)) {
    die("<p style='color:red;'>Aucun utilisateur enregistré.</p>");
}

$users = json_decode(file_get_contents($usersFile), true);

// Recherche de l'utilisateur
$found = false;
foreach ($users as $user) {
    if ($user['login'] === $identifier || $user['email'] === $identifier) {
        if (password_verify($password, $user['password'])) {
            $found = true;
            $_SESSION['user'] = [
                'login' => $user['login'],
                'email' => $user['email']
            ];
            break;
        }
    }
}

// Si échec
if (!$found) {
    die("<p style='color:red;'>Login ou mot de passe incorrect.</p><p><a href='login.html'>Retour</a></p>");
}

// Redirection vers l'accueil
header('Location: index.php');
exit;
?>