<?php
session_start();

//Fonction de validation du mot de passe
function validatePassword($password) {
    return preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password);
}