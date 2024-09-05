<?php
// template qui affiche les erreur
//param : $error (message a afficher)
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/../../public/css/style.css">
    <link rel="icon" href="/public/wacdo/images/logo.png" type="image/png">

    <title>Error</title>
</head>
<body>
    <div class="error width100">
        <h1>Erreur <span><?= $code ?></span></h1>
        <p><?= $error ?></p>
    </div>
    <p class="bg-error">OUPSSS</p>
</body>
</html>