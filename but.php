<?php

session_start();

$trouverMot = array('SEMILLANT', 'COLLINAIRE', 'DAMASQUINE', 'CHASUBLE', 'HIEMALE', 'EXHAUSTEUR', 'PERCLUS', 'PETRICHOR', 'IMMARCESCIBLE', 'CALLIPYGE', 'OBJURGATION', 'DYSTOPIE', 'PENDRILLON', 'ASSUETUDE', 'VERBATIM', 'BERGAMASQUE', 'ANONCHALIR', 'COMPENDIEUX');

echo "<h1>Jeu du pendu (avec pendu...)</h1>";
echo "<hr></hr>";

if (isset($_POST['nouveau_mot']))
{
    unset($_SESSION['mot']);
    unset($_SESSION['mot_cacher']);
    unset($_SESSION['erreurs']);
    header('Location: but.php');
    exit();
}

if (!isset($_SESSION['mot']))
{
    $size = count($trouverMot);
    $mot = $trouverMot[rand(0, $size - 1)];
    $_SESSION['mot'] = $mot;
}

if (!isset($_SESSION['erreurs']))
{
    $_SESSION['erreurs'] = 0;
}

$mot = $_SESSION['mot'];

if (!isset($_SESSION['mot_cacher']))
{
    $_SESSION['mot_cacher'] = $mot[0] . str_repeat("_", (int)(iconv_strlen($mot) - 2)) . $mot[iconv_strlen($mot) - 1];
}


function ft_update_word()
{
    echo "<table>";
    echo "<tr>";
    for ($i=0; $i < (int)(iconv_strlen($_SESSION['mot_cacher'])); $i++) { 
        echo "<td>" . $_SESSION['mot_cacher'][$i] . "</td>";
    }
    echo "</tr>";
    echo "</table>";
}

if (isset($_POST['lettre']))
{
    $lettre = strtoupper($_POST['lettre']);
    if (iconv_strlen($lettre) == 1 && ctype_alpha($lettre))
    {
        $lettre_trouvee = false;
        for ($i=0; $i < iconv_strlen($mot); $i++)
        { 
            if ($mot[$i] == $lettre)
            {
                $_SESSION['mot_cacher'][$i] = $mot[$i];
                $lettre_trouvee = true;
            }
        }
        if ($lettre_trouvee == false) {
            $_SESSION["erreurs"] += 1;
        }
        ft_update_word();
    }
    else
        ft_update_word();
}

else
    ft_update_word();

if ((int)($_SESSION['erreurs']) != 0) {
    echo "<p>Erreurs : " . $_SESSION['erreurs'] . " / 5</p>";
}

function ft_reset()
{
    unset($_SESSION['mot']);
    unset($_SESSION['mot_cacher']);
    unset($_SESSION['erreurs']);
    header('Location: but.php');
    exit();
}

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
    <form action="but.php" method="post">
        <input type="text" id="lettre" name="lettre" required>
        <button type="submit">Essabyer</button>
    </form>
    <form action="but.php" method="post">
        <button type="submit" name="nouveau_mot" id="nouveau_mot">Nouveau mot</button>
    </form>
</body>
</html>