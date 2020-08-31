<?php
require_once 'class/Cfg.php';
if (!$user = AbstractUser::getUserSession(User::class)) {
    header('Location:login.php');
    exit;
}
$opt = ['options' => ['min_range' => 1]];
$db = DBMySQL::getInstance();
$regexp = ['options' => ['regexp' => '#^0[1-68]([-. ]?\d{2}){4}$#']];
$regexpHeure = ['options' => ['regexp' => '#(1[2349]|2[0-2])(:[0-5][0-9])#']];
$tabErreur = [];

// variables pour gestion du tri d'affichage des réservations midi, soir et journée
$_SESSION['tri'] = filter_input(INPUT_GET, 'tri', FILTER_VALIDATE_INT, $opt);
$tri = isset($_SESSION['tri']) ? $_SESSION['tri'] : 0;

// Récupération données du formulaire Dashboard.
$reservation = new Reservation();
$client = new Client();
if (filter_input(INPUT_POST, 'submit')) {
    $reservation->date_resa = filter_input(INPUT_POST, 'date');
    $reservation->heure = filter_input(INPUT_POST, 'heure', FILTER_VALIDATE_REGEXP, $regexpHeure);
    $reservation->nb_personne = filter_input(INPUT_POST, 'nb_personne', FILTER_VALIDATE_INT, $opt);
    $client->nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $client->tel = filter_input(INPUT_POST, 'tel', FILTER_VALIDATE_REGEXP, $regexp);
    // Verification Date
    if (!$reservation->date_resa || $reservation->date_resa < date("Y-m-d")) {
        $tabErreur[] = "La date est absente ou invalide.";
    }
    // Vérification Heure
    if ($reservation->heure < date("H:i") && $reservation->date_resa == date('Y-m-d') || $reservation->heure > date('15:00') && $reservation->heure < date('19:00')) {
        // l'heure ne doit pas être inférieure à l'heure actuelle le jour actuel, et ne doit pas être comprise entre 15h et 19h.
        $tabErreur[] = "Impossible de réserver pour cet horaire.";
    } else {
        $_SESSION['heure'] = $reservation->heure;
    }
    // Vérification Nb_personne
    if (!$reservation->nb_personne) {
        $tabErreur[] = "Le nombre de couverts doit être renseigné.";
    } else {
        $_SESSION['nb_personne'] = $reservation->nb_personne;
    }
    // Verification Nom
    if (!$client->nom || mb_strlen($client->nom) > 50) {
        $tabErreur[] = "Le Nom est absent ou invalide.";
    } else {
        $_SESSION['nom'] = $client->nom;
    }
    // Verification Tel
    if (!$client->tel || mb_strlen($client->tel) > 10) {
        $tabErreur[] = "Le numéro de téléphone est absent ou incorrect.";
    } else {
        $_SESSION['tel'] = $client->tel;
    }
    if (!$tabErreur) {
        //Ajout de la réservation et info client en base
        $client->sauver();
        $reservation->id_client = $client->id_client;
        $reservation->confirme = 1;
        $reservation->sauver();
        // Néttoyage des variables de session du formulaire et retour au Dashboard.
        $_SESSION['heure'] = "";
        $_SESSION['nb_personne'] = "";
        $_SESSION['nom'] = "";
        $_SESSION['tel'] = "";
        header("Location:dashboard.php?tri={$tri}");
        exit;
    }
}
// Affichage des réservations
if (filter_input(INPUT_POST, 'dateSelect')) {
    $_SESSION['date'] = filter_input(INPUT_POST, 'dateSelect');
}
$countResa = Reservation::countResa(); 
$dateToday = isset($_SESSION['date']) ? $_SESSION['date'] : date("Y-m-d");
$whereDate = "date_resa = {$db->esc($dateToday)}";
$whereDateMidi = "date_resa = {$db->esc($dateToday)} AND heure BETWEEN '11:00' AND '15:00' ";
$whereDateSoir = "date_resa = {$db->esc($dateToday)} AND heure BETWEEN '19:00' AND '23:00' ";
if ($tri === 1) {
    $tabReservation = Reservation::tabJoin($whereDateMidi, 'heure');
} elseif ($tri === 2) {
    $tabReservation = Reservation::tabJoin($whereDateSoir, 'heure');
} else {
    $tabReservation = Reservation::tabJoin($whereDate, 'heure');
}
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
                <a href="reservation.php"><i class="fas fa-tasks ml-2 mr-1"></i><span class="badge badge-light mr-2 text-danger"><?= $countResa->total ?></span></a>
                <a href="historique.php"><i class="fas fa-history mx-2"></i></a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </header>
        <div class="container-fluid">
            <!-- Selection de la date d'affichage et mode de tri d'affichage -->
            <div class="row" my-2>
                <div class="col-12 col-sm-7 col-md-7">
                    <form name="formDateSelect" action="dashboard.php?tri=<?= $tri ?>" method="post">
                        <input id="champDate" type="date" name="dateSelect" class="form-control d-inline-block" value="<?= $dateToday ?>" min="<?= date("Y-m-d") ?>" />
                        <input type="submit" class="btn btn-primary btn-sm" id="bouton_decalage" value="Afficher / Rafraichir" />
                    </form>
                </div>
                <div class="col-12 col-sm-5 col-md-5 text-left text-sm-right">
                    <span class="badge badge-primary" onclick="triMidi()">MIDI</span>
                    <span class="badge badge-primary" onclick="triSoir()">SOIR</span>
                    <span class="badge badge-primary" onclick="triJournee()">JOURNEE</span>
                </div>
            </div>
            <!-- Affichage des erreurs de saisie du formulaire-->
            <div class="blocResa">
                <div class="erreur" ><?= implode('<br/>', $tabErreur) ?></div>


                <!-- Bloc ajouter une réservation -->
                <div class="row">
                    <div class="col-12 col-md-12 p-0 formResa">
                        <form action="dashboard.php?tri=<?= $tri ?>" method="post">
                            <div class="card-body d-flex flex-column flex-sm-row text-muted m-1 p-0">
                                <input type="hidden" name="date" value="<?= $dateToday ?>" /> 
                                <label for="champHeure" class="m-1"><i class="far fa-clock form"></i></label>
                                <input name="heure" class="form-control" id="champHeure" min="<?= date("12:00") ?>" max="<?= date("22:00") ?>" type="time" value="<?= isset($_SESSION['heure']) ? $_SESSION['heure'] : ''; ?>" />
                                <label for="champNbr" class="m-1"><i class="fas fa-utensils form"></i></label>
                                <input name="nb_personne" class="form-control" id="champNbr" type="number" value="<?= isset($_SESSION['nb_personne']) ? $_SESSION['nb_personne'] : ''; ?>" />

                                <label for="champNom" class="m-1"><i class="fas fa-user-edit form"></i></label>
                                <input name="nom" class="form-control" id="champNom" type="text" value="<?= isset($_SESSION['nom']) ? $_SESSION['nom'] : ''; ?>" />
                                <label for="champTel" class="m-1"><i class="fas fa-phone form"></i></label>
                                <input name="tel" class="form-control mb-1" id="champTel" type="tel" maxlength = "10" value="<?= isset($_SESSION['tel']) ? $_SESSION['tel'] : ''; ?>" />
                                <input type="submit" name="submit" id="bouton_submit" class="btn btn-primary mx-2" value="Ok" />
                            </div>
                        </form>
                    </div>
                </div>
                <!-- FIN Bloc ajouter une réservation -->

                <hr>

                <!-- Bloc d'affichage des réservations MIDI ------------------------------------------------>
                <?php
                foreach ($tabReservation as $reservation) {
                    if ($reservation->confirme == null && $reservation->annuler == null) {
                        // Si la réservation n'est ni confirmée ni annulée
                        $confirmCouleur = "warning";
                        $annuleCouleur = "warning";
                        $alertCouleur = "warning";
                    }
                    if ($reservation->confirme == 1 && $reservation->annuler == null) {
                        // Si la réservation est confirmée
                        $confirmCouleur = "success";
                        $alertCouleur = "success";
                    }
                    if ($reservation->confirme == 2 && $reservation->annuler == null) {
                        // Si l'arrivée des clients à table est confirmée
                        $confirmCouleur = "primary";
                        $alertCouleur = "primary";
                    }
                    if ($reservation->confirme == null && $reservation->annuler == 1) {
                        // Si la réservation est annulée
                        $confirmCouleur = "warning";
                        $annuleCouleur = "danger";
                        $alertCouleur = "danger";
                    }
                    if ($reservation->heure > date("10:00") && $reservation->heure < date("15:00")) {
                        ?>
                        <div class = "row d-flex align-items-center">
                            <div class = "col-12">

                                <!--Alert trigger modal -->
                                <div class = "row alert alert-<?= $alertCouleur ?> m-0" data-toggle = "modal" data-target = "#resa<?= $reservation->id_reservation ?>">
                                    <div class="col-10"><?= strftime("%Hh%M", strtotime($reservation->heure)) ?> : <?= $reservation->nb_personne ?> 
                                        couverts – <?= $reservation->nom ?>
                                    </div>
                                    <div class="col-2 text-right">
                                        <?php
                                        if ($reservation->confirme == null && $reservation->annuler == null) {
                                            // Si la réservation n'est ni confirmée ni annulée
                                            ?>
                                            <i class = "fas fa-exclamation text-warning px-2"></i>
                                            <?php
                                        }
                                        if ($reservation->confirme == 1 && $reservation->annuler == null) {
                                            // Si la réservation est confirmée
                                            ?>
                                            <i class = "fas fa-check text-<?= $confirmCouleur ?> "></i>
                                            <?php
                                        }
                                        if ($reservation->confirme == 2 && $reservation->annuler == null) {
                                            // Si l'arrivée des clients à table est confirmée
                                            ?>
                                            <i class = "fas fa-check text-<?= $confirmCouleur ?>"></i>
                                            <?php
                                        }
                                        if ($reservation->confirme == null && $reservation->annuler == 1) {
                                            // Si la réservation est annulée
                                            ?>                                           
                                            <i class = "fas fa-times text-<?= $annuleCouleur ?> px-1"></i>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                                <!--Modal ------------------------->
                                <div class = "modal fade" id = "resa<?= $reservation->id_reservation ?>" role = "dialog" aria-hidden = "true">
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
                                            <div class="modal-footer">

                                                <?php
                                                if ($reservation->confirme == null && $reservation->annuler == null) {
                                                    // Si la réservation n'est ni confirmée ni annulée
                                                    ?>
                                                    <form action="confirmer.php" method="GET" >  
                                                        <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>" />
                                                        <input type="hidden" name="page" value="dashboard"/>
                                                        <button class = "btn p-0" onclick="return confirm('Confirmer la réservation ?');" >
                                                            <i class = "far fa-check-square text-<?= $confirmCouleur ?>"></i>
                                                        </button>
                                                    </form>
                                                    <form action="annuler.php" method="GET" >
                                                        <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>" />
                                                        <input type="hidden" name="page" value="dashboard"/>
                                                        <button class = "btn p-0" onclick="return confirm('Annuler cette réservation ?');" >                                                            
                                                            <i class = "far fa-times-circle text-<?= $annuleCouleur ?>"></i>
                                                        </button>
                                                    </form>

                                                    <?php
                                                }
                                                if ($reservation->confirme == 1 && $reservation->annuler == null) {
                                                    // Si la réservation est confirmée
                                                    ?>
                                                    <form action="confirmerClient.php" method="GET">
                                                        <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>" />
                                                        <input type="hidden" name="page" value="dashboard"/>
                                                        <button class = "btn p-0" onclick="return confirm('Clients à table ?');" >
                                                            <i class = "far fa-check-square text-<?= $confirmCouleur ?>"></i>
                                                        </button>
                                                    </form>  
                                                    <button class = "btn p-0" onclick="stop(event)" >                                                            
                                                        <i class = "far fa-times-circle text-dark"></i>
                                                    </button>
                                                    <?php
                                                }
                                                if ($reservation->confirme == 2 && $reservation->annuler == null) {
                                                    // Si l'arrivée des clients à table est confirmée
                                                    ?>
                                                    <form action="confirmer.php" method="GET" >  
                                                        <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>" />
                                                        <input type="hidden" name="page" value="dashboard"/>
                                                        <button class = "btn p-0" onclick="return confirm('Annuler clients à table ?');">
                                                            <i class = "far fa-check-square text-<?= $confirmCouleur ?>"></i>
                                                        </button>
                                                    </form>
                                                    <button class = "btn p-0" onclick="stop(event)" >                                                            
                                                        <i class = "far fa-times-circle text-dark"></i>
                                                    </button>
                                                    <?php
                                                }
                                                if ($reservation->confirme == null && $reservation->annuler == 1) {
                                                    // Si la réservation est annulée
                                                    ?>
                                                    <button class = "btn p-0" onclick="stop(event)" >
                                                        <i class = "far fa-check-square text-dark"></i>
                                                    </button>
                                                    <button class = "btn p-0" onclick="stop(event)" >                                                            
                                                        <i class = "far fa-times-circle text-<?= $annuleCouleur ?>"></i>
                                                    </button>
                                                    <?php
                                                }
                                                ?>
                                                <form action="supprimer.php" method="GET">
                                                    <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>" />
                                                    <input type="hidden" name="id_client" value="<?= $reservation->id_client ?>" />
                                                    <input type="hidden" name="page" value="dashboard"/>
                                                    <button class = "btn p-0" onclick="return confirm('Supprimer cette ligne de réservation ?');">
                                                        <i class = "fas fa-trash-alt text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                        <?php
                    }
                }
                ?>
                <!-- FIN Bloc d'affichage des réservations MIDI-------------------------------------------->

                <hr>

                <!-- Bloc d'affichage des réservations SOIR ----------------------------------------------->
                <?php
                foreach ($tabReservation as $reservation) {
                    if ($reservation->confirme == null && $reservation->annuler == null) {
                        // Si la réservation n'est ni confirmée ni annulée
                        $confirmCouleur = "warning";
                        $annuleCouleur = "warning";
                        $alertCouleur = "warning";
                    }
                    if ($reservation->confirme == 1 && $reservation->annuler == null) {
                        // Si la réservation est confirmée
                        $confirmCouleur = "success";
                        $alertCouleur = "success";
                    }
                    if ($reservation->confirme == 2 && $reservation->annuler == null) {
                        // Si l'arrivée des clients à table est confirmée
                        $confirmCouleur = "primary";
                        $alertCouleur = "primary";
                    }
                    if ($reservation->confirme == null && $reservation->annuler == 1) {
                        // Si la réservation est annulée
                        $confirmCouleur = "warning";
                        $annuleCouleur = "danger";
                        $alertCouleur = "danger";
                    }
                    if ($reservation->heure > date("19:00") && $reservation->heure < date("23:00")) {
                        ?>
                        <div class = "row d-flex align-items-center">
                            <div class = "col-12">

                                <!--Alert trigger modal -->
                                <div class = "row alert alert-<?= $alertCouleur ?> m-0" data-toggle = "modal" data-target = "#resa<?= $reservation->id_reservation ?>">
                                    <div class="col-10"><?= strftime("%Hh%M", strtotime($reservation->heure)) ?> : <?= $reservation->nb_personne ?> 
                                        couverts – <?= $reservation->nom ?>
                                    </div>
                                    <div class="col-2 text-right">
                                        <?php
                                        if ($reservation->confirme == null && $reservation->annuler == null) {
                                            // Si la réservation n'est ni confirmée ni annulée
                                            ?>
                                            <i class = "fas fa-exclamation text-warning px-2"></i>
                                            <?php
                                        }
                                        if ($reservation->confirme == 1 && $reservation->annuler == null) {
                                            // Si la réservation est confirmée
                                            ?>
                                            <i class = "fas fa-check text-<?= $confirmCouleur ?> "></i>
                                            <?php
                                        }
                                        if ($reservation->confirme == 2 && $reservation->annuler == null) {
                                            // Si l'arrivée des clients à table est confirmée
                                            ?>
                                            <i class = "fas fa-check text-<?= $confirmCouleur ?>"></i>
                                            <?php
                                        }
                                        if ($reservation->confirme == null && $reservation->annuler == 1) {
                                            // Si la réservation est annulée
                                            ?>                                           
                                            <i class = "fas fa-times text-<?= $annuleCouleur ?> px-1"></i>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                                <!--Modal -------------------------->
                                <div class = "modal fade" id = "resa<?= $reservation->id_reservation ?>" role = "dialog" aria-hidden = "true">
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
                                            <div class="modal-footer">

                                                <?php
                                                if ($reservation->confirme == null && $reservation->annuler == null) {
                                                    // Si la réservation n'est ni confirmée ni annulée
                                                    ?>
                                                    <form action="confirmer.php" method="GET" >  
                                                        <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>" />
                                                        <input type="hidden" name="page" value="dashboard"/>
                                                        <button class = "btn p-0" onclick="return confirm('Confirmer la réservation ?');" >
                                                            <i class = "far fa-check-square text-<?= $confirmCouleur ?>"></i>
                                                        </button>
                                                    </form>
                                                    <form action="annuler.php" method="GET" >
                                                        <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>" />
                                                        <input type="hidden" name="page" value="dashboard"/>
                                                        <button class = "btn p-0" onclick="return confirm('Annuler cette réservation ?');" >                                                            
                                                            <i class = "far fa-times-circle text-<?= $annuleCouleur ?>"></i>
                                                        </button>
                                                    </form>

                                                    <?php
                                                }
                                                if ($reservation->confirme == 1 && $reservation->annuler == null) {
                                                    // Si la réservation est confirmée
                                                    ?>
                                                    <form action="confirmerClient.php" method="GET">
                                                        <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>" />
                                                        <input type="hidden" name="page" value="dashboard"/>
                                                        <button class = "btn p-0" onclick="return confirm('Clients à table ?');" >
                                                            <i class = "far fa-check-square text-<?= $confirmCouleur ?>"></i>
                                                        </button>
                                                    </form> 
                                                    <button class = "btn p-0" onclick="stop(event)" >                                                            
                                                        <i class = "far fa-times-circle text-dark"></i>
                                                    </button>
                                                    <?php
                                                }
                                                if ($reservation->confirme == 2 && $reservation->annuler == null) {
                                                    // Si l'arrivée des clients à table est confirmée
                                                    ?>
                                                    <form action="confirmer.php" method="GET" >  
                                                        <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>" />
                                                        <input type="hidden" name="page" value="dashboard"/>
                                                        <button class = "btn p-0" onclick="return confirm('Annuler clients à table ?');">
                                                            <i class = "far fa-check-square text-<?= $confirmCouleur ?>"></i>
                                                        </button>
                                                    </form>
                                                    <button class = "btn p-0" onclick="stop(event)" >                                                            
                                                        <i class = "far fa-times-circle text-dark"></i>
                                                    </button>
                                                    <?php
                                                }
                                                if ($reservation->confirme == null && $reservation->annuler == 1) {
                                                    // Si la réservation est annulée
                                                    ?>
                                                    <button class = "btn p-0" onclick="stop(event)" >
                                                        <i class = "far fa-check-square text-dark"></i>
                                                    </button>
                                                    <button class = "btn p-0" onclick="stop(event)" >                                                            
                                                        <i class = "far fa-times-circle text-<?= $annuleCouleur ?>"></i>
                                                    </button>
                                                    <?php
                                                }
                                                ?>
                                                <form action="supprimer.php" method="GET">
                                                    <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>" />
                                                    <input type="hidden" name="id_client" value="<?= $reservation->id_client ?>" />
                                                    <input type="hidden" name="page" value="dashboard"/>
                                                    <button class = "btn p-0" onclick="return confirm('Supprimer cette ligne de réservation ?');">
                                                        <i class = "fas fa-trash-alt text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                        <?php
                    }
                }
                ?>
                <!-- FIN du bloc des réservations SOIR----------------------------------------------------------------->
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