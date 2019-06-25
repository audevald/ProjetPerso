<?php
require_once 'class/Cfg.php';
if (!$user = AbstractUser::getUserSession(User::class)) {
    header('Location:login.php');
    exit;
}

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
    <link href="css/historique.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
        integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
</head>

<body class="bg-light">
    <header class="navbar navbar-expand-lg navbar-light bg-primary mb-2">
        <div class="col-6 text-white p-0 px-sm-3">
            <i class="fas fa-utensils"></i>
            <span class="lead">Historique</span>
        </div>
        <div class="col-6 text-right p-0 px-sm-3">
            <a href="dashboard.php"><i class="fas fa-book-open mx-2"></i></a>
            <a href="plan.php"><i class="fas fa-file-alt mx-2"></i></a> 
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </header>
    <div class="container-fluid">
        <!-- -->
        <div class="row mb-4">
            <div class="col-12">
                Du <input id="champDate" type="date" class="form-control d-inline-block mb-1">
                <br />
                Au <input id="champDate" type="date" class="form-control d-inline-block mb-1">
                <button class="btn btn-primary btn-sm">Afficher</button>
            </div>
        </div>
        <!-- -->
        <div class="row">
            <div class="col-12 col-sm-8">
                <div class="blocHistorique">
                    <!-- Bloc d'affichage historique------------------------------------->

                    <div class="card" data-toggle="modal" data-target="#resa1">
                        <div class="card-body p-2">
                            <span>09/05 -</span>
                            <span>75 réservations -</span>
                            <span>200 couverts en réservation</span>
                        </div>
                    </div>
                    <div class="modal fade" id="resa1" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Réservations du 09/05</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Service du Midi : 52 réservations pour 70 couverts réservés</p>
                                    <p>Service du Soir : 90 réservations pour 130 couverts réservés</p>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card" data-toggle="modal" data-target="#resa1">
                        <div class="card-body p-2">
                            <span>09/05 -</span>
                            <span>75 réservations -</span>
                            <span>200 couverts en réservation</span>
                        </div>
                    </div>
                    <div class="modal fade" id="resa1" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Réservations du 09/05</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Service du Midi : 52 réservations pour 70 couverts réservés</p>
                                    <p>Service du Soir : 90 réservations pour 130 couverts réservés</p>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FIN du bloc des historique-------------------------------------->
                </div>
            </div>
            <div class="col-12 col-sm-4 mt-4 mt-sm-0">
                <div class="stat">
                    <!-- Affichage statistiques------------------------------------------->
                    <div class="card">
                        <div class="card-header">Statistiques</div>
                        <div class="card-body">
                            <p>Le plus de réservation le midi : <span class="small">Lundi 09/05 avec 70 couverts
                                    réservés</span></p>
                            <p>Le plus de réservation le soir : <span class="small">Lundi 09/05 avec 130 couverts
                                    réservés</span> </p>
                            <p>Jour cumulant le plus de réservations : <span class="small">Lundi 09/05 avec 200 couverts
                                    réservés</span> </p>

                        </div>
                    </div>
                    <!-- Fin Statistique ------------------------------------------------->
                </div>
            </div>
        </div>



    </div>





    <!--js bootstrap-->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/popper.min.js"></script>
    <!-- script js-->
    <script src="js/scripts.js"></script>
</body>

</html>