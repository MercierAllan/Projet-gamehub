<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $genre       = trim($_POST['genre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image       = trim($_POST['image'] ?? '');
    $user_id     = $_SESSION['user_id'];

    // Vérification champs vides
    if (empty($title))       $errors[] = "Le titre est obligatoire.";
    if (empty($genre))       $errors[] = "Le genre est obligatoire.";
    if (empty($description)) $errors[] = "La description est obligatoire.";
    if (empty($image))       $errors[] = "Le nom du fichier image est obligatoire.";

    // Insertion en base
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO games (title, genre, description, image, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $genre, $description, $image, $user_id]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Ajouter un jeu</title>
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
                    <li class="nav-item"><a class="nav-link active" href="add_game.html">Ajouter un jeu</a></li>
                    <li class="nav-item"><a class="nav-link" href="favorites.php">Mes jeux</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="py-5 bg-secondary-subtle text-dark">
        <div class="container text-center">
            <h1 class="display-5 fw-bold">Ajouter un jeu vidéo</h1>
            <p class="lead mt-3">Remplissez le formulaire ci-dessous pour enregistrer un nouveau jeu dans GameHub.</p>
        </div>
    </header>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <strong>Jeu ajouté avec succès !</strong>
                        <a href="index.php" class="alert-link">Voir sur la page d'accueil</a>
                        ou <a href="add_game.html" class="alert-link">ajouter un autre jeu</a>.
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
                        <h2 class="h4 mb-4 text-dark">Formulaire d'ajout</h2>
                        <form action="add_game.php" method="POST">
                            <div class="mb-3">
                                <label for="title" class="form-label text-dark">Titre du jeu</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
                                    placeholder="Exemple : Hollow Knight" required>
                            </div>
                            <div class="mb-3">
                                <label for="genre" class="form-label text-dark">Genre</label>
                                <input type="text" class="form-control" id="genre" name="genre"
                                    value="<?php echo htmlspecialchars($_POST['genre'] ?? ''); ?>"
                                    placeholder="Exemple : Action / Aventure" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label text-dark">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="5"
                                    placeholder="Décrivez le jeu en quelques lignes" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="image" class="form-label text-dark">Nom du fichier image</label>
                                <input type="text" class="form-control" id="image" name="image"
                                    value="<?php echo htmlspecialchars($_POST['image'] ?? ''); ?>"
                                    placeholder="Exemple : hollow-knight.jpg" required>
                                <div class="form-text">L'image doit déjà être placée dans le dossier <strong>images/</strong>.</div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Enregistrer le jeu</button>
                                <a href="index.php" class="btn btn-outline-secondary">Retour à l'accueil</a>
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
