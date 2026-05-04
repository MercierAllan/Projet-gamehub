<?php
session_start();

$games = [];
if (file_exists('db.php')) {
    include 'db.php';
    try {
        $stmt = $pdo->query("SELECT games.*, users.login AS creator FROM games
                             LEFT JOIN users ON games.user_id = users.id
                             ORDER BY games.id DESC");
        $games = $stmt->fetchAll();
    } catch (Exception $e) {
        //la table n'existe pas encore, on ignore
        $games = [];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Accueil</title>
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
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Accueil</a>
                    </li>

                    <?php if (isset($_SESSION['login'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="add_game.html">Ajouter un jeu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="favorites.php">Mes jeux</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Déconnexion</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="register.html">Inscription</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.html">Connexion</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <header class="py-5 bg-secondary-subtle text-dark">
        <div class="container text-center">
            <?php if (isset($_SESSION['login'])): ?>
                <h1 class="display-4 fw-bold">Bonjour <?php echo htmlspecialchars($_SESSION['login']); ?> 👋</h1>
                <p class="lead mt-3">Retrouvez tous les jeux ajoutés par la communauté GameHub.</p>
                <div class="mt-4">
                    <a href="add_game.html" class="btn btn-primary me-2">Ajouter un jeu</a>
                    <a href="favorites.php" class="btn btn-outline-dark">Mes jeux</a>
                </div>
            <?php else: ?>
                <h1 class="display-4 fw-bold">Bienvenue sur GameHub</h1>
                <p class="lead mt-3">
                    Découvrez une sélection de jeux vidéo et créez votre compte pour accéder à votre futur espace personnel.
                </p>
                <div class="mt-4">
                    <a href="register.html" class="btn btn-primary me-2">S'inscrire</a>
                    <a href="login.html" class="btn btn-outline-dark">Se connecter</a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <main class="container py-5">
        <section class="mb-5">
            <h2 class="mb-3">À propos du projet</h2>
            <p>
                GameHub est un mini site web consacré aux jeux vidéo. Dans cette première version,
                le contenu est statique. Plus tard, le site permettra de gérer des comptes utilisateurs,
                d'afficher les jeux depuis une base de données et d'ajouter des jeux favoris.
            </p>
        </section>

        <section>
            <h2 class="mb-4">Jeux mis en avant</h2>

            <?php if (!empty($games)): ?>
                <div class="row g-4">
                    <?php foreach ($games as $game): ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 shadow-sm">
                                <img src="images/<?php echo htmlspecialchars($game['image']); ?>"
                                     class="card-img-top"
                                     alt="Image du jeu <?php echo htmlspecialchars($game['title']); ?>"
                                     onerror="this.src='images/default.jpg'">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($game['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($game['description']); ?></p>
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">Genre : <?php echo htmlspecialchars($game['genre']); ?></small><br>
                                    <?php if (!empty($game['creator'])): ?>
                                        <small class="text-muted">Ajouté par : <?php echo htmlspecialchars($game['creator']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <img src="images/zelda.jpg" class="card-img-top" alt="Image du jeu Zelda">
                            <div class="card-body">
                                <h5 class="card-title">The Legend of Zelda</h5>
                                <p class="card-text">Un jeu d'aventure emblématique mêlant exploration, énigmes et combats.</p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Genre : Aventure / Action</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <img src="images/minecraft.jpg" class="card-img-top" alt="Image du jeu Minecraft">
                            <div class="card-body">
                                <h5 class="card-title">Minecraft</h5>
                                <p class="card-text">Un jeu de construction et de survie où la créativité est au cœur de l'expérience.</p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Genre : Sandbox / Survie</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <img src="images/hollow-knight.jpg" class="card-img-top" alt="Image du jeu Hollow Knight">
                            <div class="card-body">
                                <h5 class="card-title">Hollow Knight</h5>
                                <p class="card-text">Un jeu d'action et d'exploration en 2D avec une ambiance sombre et soignée.</p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Genre : Metroidvania</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <img src="images/elden-ring.jpg" class="card-img-top" alt="Image du jeu Elden Ring">
                            <div class="card-body">
                                <h5 class="card-title">Elden Ring</h5>
                                <p class="card-text">Un action-RPG en monde ouvert connu pour son univers riche et ses combats exigeants.</p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Genre : RPG / Action</small>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (empty($games) && file_exists('db.php')): ?>
                    <div class="alert alert-info mt-4">
                        Aucun jeu n'a encore été ajouté. <a href="add_game.html" class="alert-link">Ajoutez le premier !</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </main>

    <footer class="bg-black text-center py-3 border-top border-secondary">
        <p class="mb-0">GameHub - Projet fil rouge BTS SIO SLAM</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
