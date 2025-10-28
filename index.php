<?php
// Démarre une session pour conserver les données entre les rechargements de page
session_start();

// Liste de mots possibles pour le jeu du pendu
$trouverMot = array('SEMILLANT', 'COLLINAIRE', 'DAMASQUINE', 'CHASUBLE', 'HIEMALE', 'EXHAUSTEUR', 'PERCLUS', 'PETRICHOR', 'IMMARCESCIBLE', 'CALLIPYGE', 'OBJURGATION', 'DYSTOPIE', 'PENDRILLON', 'ASSUETUDE', 'VERBATIM', 'BERGAMASQUE', 'ANONCHALIR', 'COMPENDIEUX');

// Affichage du titre du jeu
echo "<h1>Jeu du pendu (avec pendu...)</h1>";
echo "<hr></hr>";

// Si l'utilisateur clique sur "Nouveau mot", on réinitialise la session
if (isset($_POST['nouveau_mot']))
{
    unset($_SESSION['mot']);
    unset($_SESSION['mot_cacher']);
    unset($_SESSION['erreurs']);
    header('Location: but.php'); // Recharge la page
    exit();
}

// Si aucun mot n'est encore choisi dans la session, on en sélectionne un aléatoirement
if (!isset($_SESSION['mot']))
{
    $size = count($trouverMot);                   // Nombre total de mots disponibles
    $mot = $trouverMot[rand(0, $size - 1)];       // Choix aléatoire
    $_SESSION['mot'] = $mot;                      // Enregistrement du mot dans la session
}

// Initialisation du nombre d'erreurs si ce n'est pas déjà fait
if (!isset($_SESSION['erreurs']))
{
    $_SESSION['erreurs'] = 0;
}

$mot = $_SESSION['mot']; // Récupération du mot choisi

// Si le mot caché n'est pas encore créé, on affiche seulement la première et la dernière lettre
if (!isset($_SESSION['mot_cacher']))
{
    $_SESSION['mot_cacher'] = $mot[0] . str_repeat("_", (int)(iconv_strlen($mot) - 2)) . $mot[iconv_strlen($mot) - 1];
}

// Fonction d'affichage du mot caché sous forme de tableau HTML
function ft_update_word()
{
    echo "<table>";
    echo "<tr>";
    for ($i=0; $i < (int)(iconv_strlen($_SESSION['mot_cacher'])); $i++) { 
        echo "<td>" . $_SESSION['mot_cacher'][$i] . "</td>";  // Chaque lettre (ou "_") dans une cellule
    }
    echo "</tr>";
    echo "</table>";
}

// Si l'utilisateur soumet une lettre
if (isset($_POST['lettre']))
{
    $lettre = strtoupper($_POST['lettre']);  // Conversion en majuscule
    if (iconv_strlen($lettre) == 1 && ctype_alpha($lettre)) // Vérifie que c'est bien une seule lettre alphabétique
    {
        $lettre_trouvee = false;
        // Parcourt le mot pour vérifier si la lettre est présente
        for ($i=0; $i < iconv_strlen($mot); $i++)
        { 
            if ($mot[$i] == $lettre)
            {
                // Si la lettre existe, on la dévoile dans le mot caché
                $_SESSION['mot_cacher'][$i] = $mot[$i];
                $lettre_trouvee = true;
            }
        }
        // Si la lettre n'existe pas, on ajoute une erreur
        if ($lettre_trouvee == false) {
            $_SESSION["erreurs"] += 1;
        }
        ft_update_word();
    }
    else
        // Si la saisie n’est pas valide, on affiche simplement le mot caché sans changer quoi que ce soit
        ft_update_word();
}
else
    // Si aucune lettre n’a encore été envoyée, on affiche le mot caché initial
    ft_update_word();

// Affichage du nombre d’erreurs (sur 5 maximum)
if ((int)($_SESSION['erreurs']) != 0) {
    echo "<p>Erreurs : " . $_SESSION['erreurs'] . " / 5</p>";
}

// Fonction de réinitialisation complète du jeu
function ft_reset()
{
    unset($_SESSION['mot']);
    unset($_SESSION['mot_cacher']);
    unset($_SESSION['erreurs']);
    header('Location: but.php'); // Recharge la page avec un nouveau mot
    exit();
}

// Si le joueur a atteint 5 erreurs ou s’il a trouvé le mot, on recommence une partie
if ((int)($_SESSION['erreurs']) == 5 || $mot == $_SESSION['mot_cacher']) {
    ft_reset();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pendu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Formulaire pour proposer une lettre -->
    <form action="but.php" method="post">
        <input type="text" id="lettre" name="lettre" required>
        <button type="submit">Essayer</button>
    </form>

    <!-- Formulaire pour recommencer une nouvelle partie -->
    <form action="but.php" method="post">
        <button type="submit" name="nouveau_mot" id="nouveau_mot">Nouveau mot</button>
    </form>
</body>
</html>
