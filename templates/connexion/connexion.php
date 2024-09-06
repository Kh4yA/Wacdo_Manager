<?php
// Template qui met en forme la le formulaire de connexion
// Param : neant
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../../public/css/style.css">
    <link rel="icon" href="/public/wacdo/images/logo.png" type="image/png">

    <title>Bienvenue chez wacdo</title>
</head>

<body>
<?php include_once "templates/fragments/header.php" ?>
    <main>
        <div class="connexion-main">
            <form action="/connexion" method="POST">
                <div class="flex flex-wrap">
                    <label for="id_connexion">identifiant</label>
                    <input type="text" name="id_connexion" id="id_connexion">
                </div>
                <div class="flex flex-wrap">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password">
                </div>
                <div class="error-connexion"><p><?php echo isset($error) ? $error : '' ?></p></div>
                <div class="flex justify-center"><button type="submit">Connexion</button></div>
            </form>
        </div>
    </main>
</body>

</html>