<?php
require_once 'class/Cfg.php';
if (!$user = AbstractUser::getUserSession(User::class)) {
    header('Location:login.php');
    exit;
}
setlocale(LC_TIME, "fr_FR", "French");
if (filter_input(INPUT_POST, 'submit')) {
    $dateMin = filter_input(INPUT_POST, 'dateMin');
    $dateMax = filter_input(INPUT_POST, 'dateMax');
    // vérification des dates saisies en intervalle
    if ($dateMin > $dateMax || $dateMax < $dateMin) {
        $_SESSION['dateMin'] = $dateMax;
        $_SESSION['dateMax'] = $dateMin;
    } else {
        $_SESSION['dateMin'] = $dateMin;
        $_SESSION['dateMax'] = $dateMax;
    }
}
$countResa = Reservation::countResa();
// Récupération des dates après vérification
$dateMin = isset($_SESSION['dateMin']) ? $_SESSION['dateMin'] : date('Y-m-d', strtotime("1 day ago"));
$dateMax = isset($_SESSION['dateMax']) ? $_SESSION['dateMax'] : date('Y-m-d', strtotime("1 day ago"));
// Affichage de l'historique des réservations
$tab = Reservation::tabStat($dateMin, $dateMax);
$i = 1;
// Affichage des statistiques de réservation
$jourStatMax = Reservation::jourStatMax($dateMin, $dateMax);
$jourMax = isset($jourStatMax->date_resa) ? strftime("%A %d %B", strtotime($jourStatMax->date_resa)) : '...';
$jourCouverts = isset($jourStatMax->couverts) ? $jourStatMax->couverts : '...';
$midiStatMax = Reservation::midiStatMax($dateMin, $dateMax);
$midiMax = isset($midiStatMax->date_resa) ? strftime("%A %d %B", strtotime($midiStatMax->date_resa)) : '...';
$midiCouverts = isset($midiStatMax->couverts) ? $midiStatMax->couverts : '...';
$soirStatMax = Reservation::soirStatMax($dateMin, $dateMax);
$soirMax = isset($soirStatMax->date_resa) ? strftime("%A %d %B", strtotime($soirStatMax->date_resa)) : '...';
$soirCouverts = isset($soirStatMax->couverts) ? $soirStatMax->couverts : '...';
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
                <span class="lead">Historique</span>
            </div>
            <div class="col-6 text-right p-0 px-sm-3">
                <a href="dashboard.php"><i class="fas fa-book-open mx-2"></i></a>
                <a href="reservation.php"><i class="fas fa-tasks ml-2 mr-1"></i><span class="badge badge-light mr-2 text-danger"><?= $countResa->total ?></span></a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </header>
        <div class="container-fluid">
            <!-- -->
            <div class="row mb-4">
                <div class="col-12">
                    <form action="historique.php" method="post">
                        Du <input name="dateMin" type="date" class="form-control d-inline-block mb-1" value="<?= $dateMin ?>" max="<?= date('Y-m-d') ?>" required/>
                        <br />
                        Au <input name="dateMax" type="date" class="form-control d-inline-block mb-1" value="<?= $dateMax ?>" max="<?= date('Y-m-d') ?>" required/>
                        <input type="submit" name="submit" id="bouton_decalage" class="btn btn-primary btn-sm" value="Afficher"/>
                    </form>
                </div>
            </div>
            <!-- -->
            <div class="row">
                <div class="col-12 col-sm-8">
                    <div class="blocHistorique">

                        <!-- Bloc d'affichage historique------------------------------------->
                        <?php
                        foreach ($tab as $reservation) {
                            $i += 1;
                            $statMidi = Reservation::midiStat($reservation->date_resa);
                            $statSoir = Reservation::soirStat($reservation->date_resa);
                            ?>
                            <div class="card histo" data-toggle="modal" data-target="#resa<?= $i ?>">
                                <div class="card-body p-2">
                                    <span><?= utf8_encode(strftime("%A %d %B", strtotime($reservation->date_resa))) ?> -</span>
                                    <span><?= $reservation->resa ?> réservations -</span>
                                    <span><?= $reservation->couverts ?> couverts en réservation</span>
                                </div>
                            </div>
                            <div class="modal fade" id="resa<?= $i ?>" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Réservations du <?= utf8_encode(strftime("%A %d %B", strtotime($reservation->date_resa))) ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Service du Midi : <?= $statMidi->resa_midi ?> 
                                                réservations pour <?= isset($statMidi->couverts_midi) ? $statMidi->couverts_midi : 0 ?>
                                                couverts réservés</p>
                                            <p>Service du Soir : <?= $statSoir->resa_soir ?> 
                                                réservations pour <?= isset($statSoir->couverts_soir) ? $statSoir->couverts_soir : 0 ?>
                                                couverts réservés</p>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                            <?php
                        }
                        ?>
                        <!-- FIN du bloc des historique-------------------------------------->

                    </div>
                </div>
                <div class="col-12 col-sm-4 mt-4 mt-sm-0">
                    <div class="stat">
                        <!-- Affichage statistiques------------------------------------------->
                        <div class="card">
                            <div class="card-header">Statistiques</div>
                            <div class="card-body">
                                <p>Le plus de réservation le midi : <span class="small">
                                        <?= $midiMax ?> avec <?= $midiCouverts ?> 
                                        couverts réservés</span></p>
                                <p>Le plus de réservation le soir : <span class="small">
                                        <?= $soirMax ?> avec <?= $soirCouverts ?> 
                                        couverts réservés</span> </p>
                                <p>Jour cumulant le plus de réservations : <span class="small">
                                        <?= $jourMax ?> avec <?= $jourCouverts ?>
                                        couverts réservés</span> </p>
                            </div>
                        </div>
                        <!-- Fin Statistiques ------------------------------------------------->
                    </div>
                </div>
            </div>
        </div>
        <!--js bootstrap-->
        <script src="js/jquery.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!-- script js-->
        <script src="js/app_dash.js" type="text/javascript"></script>
    </body>

</html>