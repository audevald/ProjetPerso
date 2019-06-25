<?php
require_once 'class/Cfg.php';

$reservation = new Reservation($_SESSION['id_reservation']);
if (!$reservation->charger()) {
    header("Location:formulaire.php");
    exit;
}
setlocale(LC_TIME, "fr_FR", "French");
?>
<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Réservation</title>
        <!--js-->
        <script src="js/jquery.min.js"></script>
        <!--css-->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="css/main.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
              integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    </head>

    <body class="bg-light">
        <header class="navbar navbar-expand-lg navbar-light bg-primary mb-3">
            <div class="col-12 text-white">
                <i class="fas fa-utensils"></i>
                <span class="lead">Réservation</span>
            </div>
        </header>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="text-center">Votre réservation pour le <span class="font-weight-bold"><?= strftime("%A %d %B", strtotime($reservation->date_resa)) ?></span> à <span class="font-weight-bold"><?= strftime("%Hh%M", strtotime($reservation->heure)) ?></span> pour <span class="font-weight-bold"><?= $reservation->nb_personne ?> </span>personnes a été enregistrée avec succès !</p>
                    <p class="small text-center">Vous recevrez une notification de confirmation pour votre réservation.</p>
                        
                </div>
                <div class="col-12 text-center mt-5">
                    <a href="formulaire.php" class="btn btn-primary">Retour au Formulaire</a>
                </div>
            </div>
        </div>
        <?= $reservation->id_client ?>


        <!--js bootstrap-->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/popper.min.js"></script>
        <!-- script js-->
        <script src="js/scripts.js"></script>
    </body>

</html>