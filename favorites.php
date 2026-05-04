<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupération des jeux de l'utilisateur connecté
$stmt = $pdo->prepare("SELECT * FROM games WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$games = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Mes jeux</title>
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
                    <li class="nav-item"><a class="nav-link" href="add_game.html">Ajouter un jeu</a></li>
                    <li class="nav-item"><a class="nav-link active" href="favorites.php">Mes jeux</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="py-5 bg-secondary-subtle text-dark">
        <div class="container text-center">
            <h1 class="display-5 fw-bold">Mes jeux vidéo</h1>
            <p class="lead mt-3">
                Bonjour <strong><?php echo htmlspecialchars($_SESSION['login']); ?></strong>,
                voici les jeux que vous avez ajoutés.
            </p>
        </div>
    </header>

    <main class="container py-5">
        <section class="mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h2 class="mb-1">Liste de mes jeux</h2>
                    <p class="text-light-emphasis mb-0">Vous retrouvez ici uniquement les jeux que vous avez ajoutés.</p>
                </div>
                <div>
                    <a href="add_game.html" class="btn btn-primary">Ajouter un nouveau jeu</a>
                </div>
            </div>
        </section>

        <section>
            <div class="row g-4">
                <?php if (!empty($games)): ?>
                    <?php foreach ($games as $game): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                <img src="images/<?php echo htmlspecialchars($game['image']); ?>"
                                     class="card-img-top"
                                     alt="Image du jeu <?php echo htmlspecialchars($game['title']); ?>"
                                     onerror="this.src='images/default.jpg'">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($game['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($game['description']); ?></p>
                                </div>
                                <div class="card-footer d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Genre : <?php echo htmlspecialchars($game['genre']); ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            Vous n'avez encore enregistré aucun jeu.
                            <a href="add_game.html" class="alert-link">Ajouter votre premier jeu</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="bg-black text-center py-3 border-top border-secondary">
        <p class="mb-0">GameHub - Projet fil rouge BTS SIO SLAM</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
