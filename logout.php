<?php
session_start();

// Destruction complète de la session
session_unset();
session_destroy();

// Redirection vers la page d'accueil
header("Location: index.php");
exit;
