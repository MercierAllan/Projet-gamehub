<?php
session_start();
include 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password   = $_POST['password'] ?? '';

    // Vérification champs vides
    if (empty($identifier)) $errors[] = "L'identifiant est obligatoire.";
    if (empty($password))   $errors[] = "Le mot de passe est obligatoire.";

    if (empty($errors)) {
        // Récupération de l'utilisateur en base
        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->execute([$identifier]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie : enregistre dans la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login']   = $user['login'];
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Identifiant ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Connexion</title>
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
                    <li class="nav-item"><a class="nav-link" href="register.html">Inscription</a></li>
                    <li class="nav-item"><a class="nav-link active" href="login.html">Connexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="py-5 bg-secondary-subtle text-dark">
        <div class="container text-center">
            <h1 class="display-5 fw-bold">Se connecter</h1>
            <p class="lead mt-3">Accédez à votre espace personnel GameHub.</p>
        </div>
    </header>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5">

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
                        <h2 class="h4 mb-4 text-dark">Formulaire de connexion</h2>
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <label for="identifier" class="form-label text-dark">Identifiant</label>
                                <input type="text" class="form-control" id="identifier" name="identifier"
                                    value="<?php echo htmlspecialchars($_POST['identifier'] ?? ''); ?>"
                                    placeholder="Votre login" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label text-dark">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Votre mot de passe" required>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Se connecter</button>
                                <a href="index.php" class="btn btn-outline-secondary">Retour</a>
                            </div>
                        </form>
                        <hr class="my-3">
                        <p class="text-dark text-center mb-0">
                            Pas encore de compte ? <a href="register.html">S'inscrire</a>
                        </p>
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
