<?php
require_once 'class/Cfg.php';
$opt = ['options' => ['min_range' => 1]];
$regexp = ['options' => ['regexp' => '#(0|\+33)[1-9]( *[0-9]{2}){4}#']];
//$regexpDate = ['options' => ['regexp' => '#/([0-2][0-9]{3})\-([0-1][0-9])\-([0-3][0-9])#']];
$regexpHeure = ['options' => ['regexp' => '#(1[2349]|2[0-2])(:[0-5][0-9])#']];
$db = DBMySQL::getInstance();
$tabErreur = [];
$reservation = new Reservation();
$client = new Client();

// Ma clé privée reCAPTCHA
$secret = "6Le1Ba8UAAAAAKqezm5lVYJza1K_c02ZDEPKZwys";
// Paramètre renvoyé par le recaptcha
$response = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : null;
// On récupère l'IP de l'utilisateur
$remoteip = $_SERVER['REMOTE_ADDR'];
$api_url = "https://www.google.com/recaptcha/api/siteverify?secret="
        . $secret
        . "&response=" . $response
        . "&remoteip=" . $remoteip;
$decode = json_decode(file_get_contents($api_url), true);

if (filter_input(INPUT_POST, 'submit')) {
    $reservation->date_resa = filter_input(INPUT_POST, 'date_resa');
    $reservation->heure = filter_input(INPUT_POST, 'heure', FILTER_VALIDATE_REGEXP, $regexpHeure);
    $reservation->nb_personne = filter_input(INPUT_POST, 'nb_personne', FILTER_VALIDATE_INT, $opt);
    $reservation->info = filter_input(INPUT_POST, 'info', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $client->nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $client->tel = filter_input(INPUT_POST, 'tel', FILTER_VALIDATE_REGEXP, $regexp);

    // Verification Date
    if (!$reservation->date_resa || $reservation->date_resa < date("Y-m-d")) {
        $tabErreur[] = "La date est absente ou invalide.";
    } else {
        $_SESSION['date_resa'] = $reservation->date_resa;
    }
    // Vérification Heure
    if ($reservation->heure < date("H:i") && $reservation->date_resa == date('Y-m-d') || $reservation->heure > date('15:00') && $reservation->heure < date('19:00')) {
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
    // vérification Info
    if (mb_strlen($reservation->info) > 150) {
        $tabErreur[] = "Les informations complémentaires ne peuvent dépasser 150 carractères.";
    } else {
        $_SESSION['info'] = $reservation->info;
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
    if (!$tabErreur && $decode['success'] == true) {
        $client->sauver();
        $reservation->id_client = $client->id_client;
        $reservation->sauver();
        $_SESSION = array();
        $_SESSION['id_reservation'] = $reservation->id_reservation;
        header("Location:success.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?= Cfg::APP_TITRE ?></title>
        <link rel="icon" href="img/favicon-form.ico" />
        <link rel= "manifest" href= "manifest.json">
        <!--css-->
        <link href="css/style.css" rel="stylesheet">
        <link href="css/formulaire.css" rel="stylesheet">
        <!-- font awesome -->
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
                <div class="col-12 offset-sm-2 col-sm-8 offset-md-4 col-md-4">

                    <!-- Début Formulaire -------------------------------------------------------------------------------------------->
                    <form name="form1" action="formulaire.php" method="post"> 
                        <div class="form-group text-center">
                            <label for="champDate">Date du repas*</label>
                            <input name="date_resa" type="date" value="<?= isset($_SESSION['date_resa']) ? $_SESSION['date_resa'] : ''; ?>" class="form-control" id="champDate" min="<?= date("Y-m-d"); ?>" required />
                        </div>
                        <div class="form-group text-center">
                            <label for="champHeure">Heure*</label>
                            <input name="heure" type="time" class="form-control" value="<?= isset($_SESSION['heure']) ? $_SESSION['heure'] : ''; ?>" id="champHeure" min="<?= date("12:00") ?>" max="<?= date("22:00") ?>" required />
                        </div>
                        <div class="form-group text-center">
                            <label for="champNbr">Nombre de couverts*</label>
                            <select name="nb_personne" class="form-control nbr" id="champNbr" required>
                                <?php
                                for ($i = 1; $i < 51; $i++) {
                                    ?>
                                    <option value="<?= $i ?>" <?= isset($_SESSION['nb_personne']) && $_SESSION['nb_personne'] == $i ? 'selected="selected"' : ''; ?>><?= $i ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class = "form-group text-center">
                            <label for = "champNom">Nom*</label>
                            <input name = "nom" type = "text" value = "<?= isset($_SESSION['nom']) ? $_SESSION['nom'] : ''; ?>" maxlength = "50" class = "form-control" id = "champNom" required />
                        </div>
                        <div class = "form-group text-center">
                            <label for = "champTel">Téléphone*</label>
                            <input name = "tel" type = "tel" pattern = "[0-9]{10}" value = "<?= isset($_SESSION['tel']) ? $_SESSION['tel'] : ''; ?>" maxlength = "10" class = "form-control" id = "champTel" required />
                        </div>
                        <div class = "form-group text-center">
                            <label for = "champInfo">Informations complémentaires</label>
                            <textarea name = "info" class = "form-control" id = "champInfo" rows = "3"
                                      placeholder = "Intérieur, Terrasse, poussette, etc..."><?= isset($_SESSION['info']) ? $_SESSION['info'] : '';
                                ?></textarea>
                        </div>

                        <div class="erreur mb-3 small text-center"><?= implode('<br/>', $tabErreur) ?></div>

                        <!-- reCAPTCHA -->
                        <div class="g-recaptcha" data-sitekey="6Le1Ba8UAAAAAHZimMxGjFtZ_q33onK6wGak7ACx"></div>
                        <!-- fin reCAPTCHA -->
                        
                        <div class="text-center mt-3">
                            <input name="submit" type="submit" class="btn btn-primary mb-2" value="Réserver !"></button>
                        </div>
                        <div class="small text-center">Les champs marqués d'un astérique (*) sont obligatoires.</div>
                    </form>
                    <!-- Fin formulaire -------------------------------------------------------------------------------------------->
                </div>
            </div>
        </div>


        <!--js bootstrap-->
        <script src="js/jquery.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!-- script js-->
        <script src="js/app.js" type="text/javascript"></script>
        <!-- reCAPTCHA -->
        <script src='https://www.google.com/recaptcha/api.js'></script>

    </body>

</html>