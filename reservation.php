<?php
require_once 'class/Cfg.php';
if (!$user = AbstractUser::getUserSession(User::class)) {
    header('Location:login.php');
    exit;
}
setlocale(LC_TIME, "fr_FR", "French");
$dateToday = date("Y-m-d");
$where = "confirme IS NULL";
// Récupération de toutes les réservations non confirmées
$tabReservation = Reservation::tabJoin($where, 'date_resa DESC');
?>
<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?= Cfg::APP_TITRE ?></title>
        <link rel="icon" href="img/favicon-dash.ico" />
        <link rel= "manifest" href= "dash-manifest.json">
        <!--css-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
              integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/dashboard.css" rel="stylesheet">

    </head>

    <body class="bg-light">
        <header class="navbar navbar-expand-lg navbar-light bg-primary mb-2">
            <div class="col-6 text-white p-0 px-sm-3">
                <i class="fas fa-utensils"></i>
                <span class="lead">A traiter</span>
            </div>
            <div class="col-6 text-right p-0 px-sm-3">
                <a href="dashboard.php"><i class="fas fa-book-open mx-2"></i></a>
                <a href="historique.php"><i class="fas fa-history mx-2"></i></a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </header>
        <div class="container-fluid">

            <!-- Bloc d'affichage des réservations à traiter ------------------------------------------------------------------>
            <?php
            foreach ($tabReservation as $reservation) {
                if ($reservation->confirme == null && $reservation->annuler == null) {
                    // Si la réservation est pas confirmée et pas annulée
                    if ($reservation->date_resa >= $dateToday) {
                        // Et si la date de la réservation est supérieure ou égale à la date d'aujourd'hui
                        ?>
                        <div class = "row d-flex align-items-center">
                            <div class = "col-8 col-sm-10">
                                <!--Alert trigger modal -->
                                <div class = "alert alert-warning m-0" data-toggle = "modal" data-target = "#resa<?= $reservation->id_reservation ?>">
                                    <div class="p-0 m-0"><?= utf8_encode(strftime("%d %B", strtotime($reservation->date_resa))) ?> - <?= $reservation->nb_personne ?> couverts
                                        <?= strftime("%Hh%M", strtotime($reservation->heure)) ?>                                  
                                    </div>
                                </div>
                                <!--Modal -->
                                <div class = "modal fade" id = "resa<?= $reservation->id_reservation ?>" tabindex = "-1" role = "dialog" aria-hidden = "true">
                                    <div class = "modal-dialog modal-dialog-centered" role = "document">
                                        <div class = "modal-content">
                                            <div class = "modal-header">
                                                <h5 class = "modal-title">Réservation <?= $reservation->nb_personne ?> personnes à
                                                    <?= strftime("%Hh%M", strtotime($reservation->heure)) ?>                                         </h5>
                                                <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
                                                    <span aria-hidden = "true">&times;</span>
                                                </button>
                                            </div>
                                            <div class = "modal-body">
                                                <p><?= $reservation->nom ?> </p>
                                                <p><?= wordwrap($reservation->tel, 2, " ", 1) ?></p>
                                                <p><?= $reservation->info ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="col-4 col-sm-2 p-0">
                                <form action="confirmer.php" method="GET">
                                    <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>" />
                                    <input type="hidden" name="page" value="reservation"/>
                                    <button class = "btn p-0" onclick="return confirm('Confirmer cette réservation ?');"  >
                                        <i class = "far fa-check-square text-success"></i>
                                    </button>
                                </form>
                                <form action="annuler.php" method="GET">
                                    <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>" />
                                    <input type="hidden" name="page" value="reservation"/>
                                    <button class = "btn p-0" onclick="return confirm('Refuser cette réservation ?');" >                                                            
                                        <i class = "far fa-times-circle text-danger"></i>
                                    </button>
                                </form>
                                <form action="supprimer.php" method="GET">
                                    <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>" />
                                    <input type="hidden" name="id_client" value="<?= $reservation->id_client ?>" />
                                    <input type="hidden" name="page" value="reservation"/>
                                    <button class = "btn p-0" onclick="return confirm('Supprimer cette ligne de réservation ?');">
                                        <i class = "fas fa-trash-alt text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>
            <!-- Fin du bloc d'affichage des réservations à traiter ------------------------------------------------------------------->

            <hr>

            <!-- Bloc d'affichage des réservations qui n'ont pas été traitées à temps --------------------------------------------------->
            <?php
            foreach ($tabReservation as $reservation) {
                if ($reservation->date_resa < $dateToday) {
                    // Si la date de la réservation est inférieure à la date du jour et qu'elle n'est pas confirmée
                    ?>
                    <div class = "row d-flex align-items-center">
                        <div class = "col-9 col-sm-10">
                            <!--Alert trigger modal -->
                            <div class = "alert alert-dark m-0" data-toggle = "modal" data-target = "#resa<?= $reservation->id_reservation ?>">
                                <div ><?= utf8_encode(strftime("%A %d %B", strtotime($reservation->date_resa))) ?> - <?= $reservation->nb_personne ?> 
                                    couverts <?= strftime("%Hh%M", strtotime($reservation->heure)) ?>                                   
                                </div>
                            </div>
                            <!--Modal -->
                            <div class = "modal fade" id = "resa<?= $reservation->id_reservation ?>" tabindex = "-1" role = "dialog" aria-hidden = "true">
                                <div class = "modal-dialog modal-dialog-centered" role = "document">
                                    <div class = "modal-content">
                                        <div class = "modal-header">
                                            <h5 class = "modal-title">Réservation <?= $reservation->nb_personne ?> personnes à
                                                <?= strftime("%Hh%M", strtotime($reservation->heure)) ?> 
                                            </h5>
                                            <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
                                                <span aria-hidden = "true">&times;</span>
                                            </button>
                                        </div>
                                        <div class = "modal-body">
                                            <p><?= $reservation->nom ?> </p>
                                            <p><?= wordwrap($reservation->tel, 2, " ", 1) ?></p>
                                            <p><?= $reservation->info ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="col-3 col-sm-2 p-0">
                            <form action="supprimer.php" method="GET">
                                <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>" />
                                <input type="hidden" name="id_client" value="<?= $reservation->id_client ?>" />
                                <input type="hidden" name="page" value="reservation"/>
                                <button class = "btn p-0" onclick="return confirm('Supprimer cette ligne de réservation ?');">
                                    <i class = "fas fa-trash-alt text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
            <!-- Fin du bloc des réservations pas traitées à temps ------------------------------------------------>
        </div>
        <!--js bootstrap-->
        <script src="js/jquery.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
        <script src="js/bootstrap.min.js"></script>   
        <!-- script js-->
        <script src="js/scripts.js"></script>
        <script src="js/app_dash.js" type="text/javascript"></script>
    </body>
</html>