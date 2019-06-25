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
    <link href="css/plan.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
        integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
</head>

<body class="bg-light">
    <header class="navbar navbar-expand-lg navbar-light bg-primary mb-2">
        <div class="col-6 text-white p-0 px-sm-3">
            <i class="fas fa-utensils"></i>
            <span class="lead">Plan de salle</span>
        </div>
        <div class="col-6 text-right p-0 px-sm-3">
            <a href="dashboard.php"><i class="fas fa-book-open mx-2"></i></a>
            <a href="historique.php"><i class="fas fa-history mx-2"></i></a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </header>
    <div class="container-fluid">
        <!-- -->
        <div class="row mb-4">
            <div class="col-12">
                <input id="champDate" type="date" class="form-control mb-1">
            </div>
        </div>
        <!-- -->
        <div class="blocTable">
            <!-- Bloc d'affiche plan de table -->

            <div class="row">
                <div class="col-12 col-sm-4">

                    <div class="card mb-2">
                        <div class="card-header">
                            Table 1 à 9
                        </div>
                        <ul class="list-group list-group-flush">
                            <!-- bloc Table ----------------------------------------------------------------------------------->
                            <li class="list-group-item" data-toggle="modal" data-target="#table1">
                                Table 1 : 12h30 - 3 couverts
                            </li>
                            <!-- Modal -->
                            <div class="modal fade" id="table1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Table 1 - 12 h 30</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>3 couverts</p>
                                            <p>Dupont Robert</p>
                                            <p>Info : interieur - poussette</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Modal -->
                            <!-- bloc Table ----------------------------------------------------------------------------------->

                            <!-- bloc Table ----------------------------------------------------------------------------------->
                            <li class="list-group-item" data-toggle="modal" data-target="#table2">
                                Table 2 : 12h00 - 4 couverts
                            </li>
                            <!-- Modal -->
                            <div class="modal fade" id="table2" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Table 2 - 12 h 00</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>4 couverts</p>
                                            <p>Lefort Max</p>
                                            <p>Info : terrasse</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Modal -->
                            <!-- bloc Table ----------------------------------------------------------------------------------->

                        </ul>
                    </div>

                </div>
                <div class="col-12 col-sm-4">

                    <div class="card  mb-2">
                        <div class="card-header">
                            Table 10 à 19
                        </div>
                        <ul class="list-group list-group-flush">
                            <!-- bloc Table ----------------------------------------------------------------------------------->
                            <li class="list-group-item" data-toggle="modal" data-target="#...">
                                <!-- Ligne TABLE liste  -->>
                            </li>
                            <!-- Modal -->
                            <div class="modal fade" id="..." role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <!-- TITRE MODAL -->
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                <!-- couverts -->
                                            </p>
                                            <p>
                                                <!-- Nom résa -->
                                            </p>
                                            <p>
                                                <!-- Info complémentaire -->
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Modal -->
                            <!-- bloc Table ----------------------------------------------------------------------------------->
                        </ul>
                    </div>

                </div>
                <div class="col-12 col-sm-4">

                    <div class="card  mb-2">
                        <div class="card-header">
                            Table 20 à 29
                        </div>
                        <ul class="list-group list-group-flush">
                            <!-- bloc Table ----------------------------------------------------------------------------------->
                            <li class="list-group-item" data-toggle="modal" data-target="#...">
                                <!-- Ligne TABLE liste  -->>
                            </li>
                            <!-- Modal -->
                            <div class="modal fade" id="..." tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <!-- TITRE MODAL -->
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                <!-- couverts -->
                                            </p>
                                            <p>
                                                <!-- Nom résa -->
                                            </p>
                                            <p>
                                                <!-- Info complémentaire -->
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Modal -->
                            <!-- bloc Table ----------------------------------------------------------------------------------->
                        </ul>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-4">

                    <div class="card mb-2">
                        <div class="card-header">
                            Table 30 à 39
                        </div>
                        <ul class="list-group list-group-flush">
                            <!-- bloc Table ----------------------------------------------------------------------------------->
                            <li class="list-group-item" data-toggle="modal" data-target="#...">
                                <!-- Ligne TABLE liste  -->>
                            </li>
                            <!-- Modal -->
                            <div class="modal fade" id="..." role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <!-- TITRE MODAL -->
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                <!-- couverts -->
                                            </p>
                                            <p>
                                                <!-- Nom résa -->
                                            </p>
                                            <p>
                                                <!-- Info complémentaire -->
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Modal -->
                            <!-- bloc Table ----------------------------------------------------------------------------------->
                        </ul>
                    </div>

                </div>
                <div class="col-12 col-sm-4">

                    <div class="card  mb-2">
                        <div class="card-header">
                            Table 40 à 49
                        </div>
                        <ul class="list-group list-group-flush">
                            <!-- bloc Table ----------------------------------------------------------------------------------->
                            <li class="list-group-item" data-toggle="modal" data-target="#...">
                                <!-- Ligne TABLE liste  -->>
                            </li>
                            <!-- Modal -->
                            <div class="modal fade" id="..." role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <!-- TITRE MODAL -->
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                <!-- couverts -->
                                            </p>
                                            <p>
                                                <!-- Nom résa -->
                                            </p>
                                            <p>
                                                <!-- Info complémentaire -->
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Modal -->
                            <!-- bloc Table ----------------------------------------------------------------------------------->
                        </ul>
                    </div>

                </div>
                <div class="col-12 col-sm-4">

                    <div class="card  mb-2">
                        <div class="card-header">
                            Table 50 à 59
                        </div>
                        <ul class="list-group list-group-flush">
                            <!-- bloc Table ----------------------------------------------------------------------------------->
                            <li class="list-group-item" data-toggle="modal" data-target="#...">
                                <!-- Ligne TABLE liste  -->>
                            </li>
                            <!-- Modal -->
                            <div class="modal fade" id="..."  role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <!-- TITRE MODAL -->
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                <!-- couverts -->
                                            </p>
                                            <p>
                                                <!-- Nom résa -->
                                            </p>
                                            <p>
                                                <!-- Info complémentaire -->
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Modal -->
                            <!-- bloc Table ----------------------------------------------------------------------------------->
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Fin d'affiche plan de table -->
        </div>





    </div>





    <!--js bootstrap-->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/popper.min.js"></script>
    <!-- script js-->
    <script src="js/scripts.js"></script>
</body>

</html>