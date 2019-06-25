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
    <link href="css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
        integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
</head>

<body class="bg-light">
    <header class="navbar navbar-expand-lg navbar-light bg-primary mb-2">
        <div class="col-6 text-white p-0 px-sm-3">
            <i class="fas fa-utensils"></i>
            <span class="lead">Dashboard</span>
        </div>
        <div class="col-6 text-right p-0 px-sm-3">
            <a href="plan.php"><i class="fas fa-file-alt mx-2"></i></a>
            <a href="historique.php"><i class="fas fa-history mx-2"></i></a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </header>
    <div class="container-fluid">
        <!-- -->
        <div class="row mb-4">
            <div class="col-12 col-sm-3 ">
                <input id="champDate" type="date" class="form-control d-inline-block">
            </div>

            <div class="col-12 col-sm-9 text-left text-sm-right">
                <span class="badge badge-primary">Ordre réservation</span>
                <span class="badge badge-primary">Prise réservation</span>
            </div>
        </div>
        <!-- -->

        <div class="blocResa">
            <!-- Bloc d'affichage des réservations------------------------------------------------>

            <div class="row d-flex align-items-center">
                <div class="col-8 col-sm-10">
                    <!-- Alert trigger modal -->
                    <div class="alert alert-success m-0" data-toggle="modal" data-target="#resa1">12h15: 3 couverts –
                        Dupont Robert - T10</div>
                    <!-- Modal -->
                    <div class="modal fade" id="resa1" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Réservation 3 personnes à
                                        12h15 - Table 10</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        Dupont Robert
                                    </p>
                                    <p>
                                        06 51 40 87 96
                                    </p>
                                    <p>
                                        En terrasse
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-sm-2 p-0">
                    <button class="btn p-0"><i class="far fa-check-square text-success"></i></button>
                    <button class="btn p-0"><i class="fas fa-utensils text-success"></i></button>
                    <button class="btn p-0"><i class="fas fa-trash-alt text-danger"></i></button>
                </div>
            </div>

            <div class="row d-flex align-items-center">
                <div class="col-8 col-sm-10">
                    <!-- Alert trigger modal -->
                    <div class="alert alert-success m-0" data-toggle="modal" data-target="#resa2">12h30 – 4 personnes –
                        Lefort Max</div>
                    <!-- Modal -->
                    <div class="modal fade" id="resa2" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Réservation pour 4 personnes à
                                        12h30</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        Lefort Max
                                    </p>
                                    <p>
                                        06 51 40 87 96
                                    </p>
                                    <p>
                                        Interieur, Poussette
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-sm-2 p-0">
                    <button class="btn p-0"><i class="far fa-check-square text-success"></i></button>
                    <button class="btn p-0"><i class="fas fa-utensils text-warning"></i></button>
                    <button class="btn p-0"><i class="fas fa-trash-alt text-danger"></i></button>
                </div>
            </div>

            <div class="row d-flex align-items-center">
                <div class="col-8 col-sm-10">
                    <!-- Alert trigger modal -->
                    <div class="alert alert-warning m-0" data-toggle="modal" data-target="#resa3">12h30 – 4 personnes –
                        Petit Tim</div>
                    <!-- Modal -->
                    <div class="modal fade" id="resa3" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Réservation pour 2 personnes à
                                        12h30</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        Petit Tim
                                    </p>
                                    <p>
                                        06 51 40 87 96
                                    </p>
                                    <p>
                                        Interieur
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-sm-2 p-0">
                    <button class="btn p-0"><i class="far fa-check-square text-warning"></i></button>
                    <button class="btn p-0"><i class="fas fa-utensils text-warning"></i></button>
                    <button class="btn p-0"><i class="fas fa-trash-alt text-danger"></i></button>
                </div>
            </div>

        </div>
        <!-- FIN du bloc des réservations----------------------------------------------------------------->

        <!-- Bloc ajouter une réservation -->
        <div class="row mt-3">
            <div class="col-12 col-md-12 formResa">
                <div class="accordion" id="ajoutResa">
                    <div class="card border">
                        <div class="card-header">
                            <span data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                aria-controls="collapseOne">
                                <i class="fas fa-plus"></i>Ajouter une Réservation
                            </span>
                        </div>

                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#ajoutResa">
                            <div class="card-body d-flex flex-wrap">
                                <label for="champHeure" class="m-2">Heure</label>
                                <input class="form-control" id="champHeure" type="time">
                                <label for="champNbr" class="m-2">Couverts</label>
                                <input class="form-control" id="champNbr" type="number">
                                <label for="champTable" class="m-2">Table</label>
                                <input class="form-control" id="champTable" type="number">
                                <label for="champNom" class="m-2">Nom</label>
                                <input class="form-control" id="champNom" type="text">
                                <label for="champTel" class="m-2">Tel</label>
                                <input class="form-control" id="champTel" type="number">
                                <button class="btn btn-primary mx-2">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <!-- FIN Bloc ajouter une réservation -->


    </div>





    <!--js bootstrap-->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/popper.min.js"></script>
    <!-- script js-->
    <script src="js/scripts.js"></script>
</body>

</html