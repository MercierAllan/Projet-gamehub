<?php
session_start();
include 'db.php';

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login    = trim($_POST['login'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    // Vérification champs vides
    if (empty($login))    $errors[] = "Le login est obligatoire.";
    if (empty($email))    $errors[] = "L'email est obligatoire.";
    if (empty($password)) $errors[] = "Le mot de passe est obligatoire.";
    if (empty($confirm))  $errors[] = "La confirmation du mot de passe est obligatoire.";

    // Validation login : lettres et chiffres et min 3 caractères
    if (!empty($login) && !preg_match('/^[a-zA-Z0-9]{3,}$/', $login)) {
        $errors[] = "Le login doit contenir uniquement des lettres et des chiffres (minimum 3 caractères).";
    }

    // Validation email
    if (!empty($email) && !preg_match('/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/', $email)) {
        $errors[] = "L'adresse email n'est pas valide.";
    }

    // Validation mot de passe : min 8 caractères, 1 majuscule et 1 chiffre
    if (!empty($password) && !preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.";
    }

    // Vérification correspondance
    if (!empty($password) && !empty($confirm) && $password !== $confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    //vérification login et email
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ? OR email = ?");
        $stmt->execute([$login, $email]);
        if ($stmt->fetch()) {
            $errors[] = "Ce login ou cet email est déjà utilisé.";
        }
    }

    //insertion en base
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (login, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$login, $email, $hashedPassword]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-black border-bottom border-secondary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">GameHub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link active" href="register.html">Inscription</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.html">Connexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="py-5 bg-secondary-subtle text-dark">
        <div class="container text-center">
            <h1 class="display-5 fw-bold">Créer un compte</h1>
            <p class="lead mt-3">Rejoignez GameHub et commencez à gérer vos jeux favoris.</p>
        </div>
    </header>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <strong>Inscription réussie !</strong> Votre compte a bien été créé.
                        <a href="login.html" class="alert-link">Se connecter maintenant</a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <strong>Erreurs :</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-4 text-dark">Formulaire d'inscription</h2>
                        <form action="register.php" method="POST">
                            <div class="mb-3">
                                <label for="login" class="form-label text-dark">Login</label>
                                <input type="text" class="form-control" id="login" name="login"
                                    value="<?php echo htmlspecialchars($_POST['login'] ?? ''); ?>"
                                    placeholder="Exemple : gamer42" required>
                                <div class="form-text">Lettres et chiffres uniquement, minimum 3 caractères.</div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label text-dark">Adresse email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                    placeholder="exemple@mail.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label text-dark">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Min. 8 caractères, 1 majuscule, 1 chiffre" required>
                            </div>
                            <div class="mb-4">
                                <label for="confirm" class="form-label text-dark">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="confirm" name="confirm"
                                    placeholder="Répétez votre mot de passe" required>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">S'inscrire</button>
                                <a href="index.php" class="btn btn-outline-secondary">Retour</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-black text-center py-3 border-top border-secondary">
        <p class="mb-0">GameHub - Projet fil rouge BTS SIO SLAM</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
