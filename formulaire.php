<?php
require_once 'class/Cfg.php';
$opt = ['options' => ['min_range' => 1]];
$regexp = ['options' => ['regexp' => '#^0[1-68]([-. ]?\d{2}){4}$#']];
$db = DBMySQL::getInstance();
$tabErreur = [];

$reservation = new Reservation();
$client = new Client();
if (filter_input(INPUT_POST, 'submit')) {
    $reservation->date_resa = filter_input(INPUT_POST, 'date_resa');
    $reservation->heure = filter_input(INPUT_POST, 'heure');
    $reservation->nb_personne = filter_input(INPUT_POST, 'nb_personne', FILTER_VALIDATE_INT);
    $reservation->info = filter_input(INPUT_POST, 'info', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $client->nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $client->prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $client->tel = filter_input(INPUT_POST, 'tel', FILTER_VALIDATE_REGEXP, $regexp);
    if ($reservation->heure > date('14:45') || $reservation->heure < date('19:00')) {
        $tabErreur[] = "Impossible de réserver entre 15h00 et 19h00.";
    }
    
    if (!$client->nom || mb_strlen($client->nom) > 50) {
        $tabErreur[] = "Le Nom est absent ou invalide.";
    }
    if (!$client->tel || mb_strlen($client->tel) > 10) {
        $tabErreur[] = "Le numéro de téléphone est absent ou incorrect.";
    }
    if (mb_strlen($client->prenom) > 30) {
        $tabErreur[] = "Le prenom est invalide.";
    }
    if (!$tabErreur) {
        $client->sauver();
        $reservation->id_client = $client->id_client;
        $reservation->sauver();
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
                <div class="col-12 offset-sm-2 col-sm-8 offset-md-4 col-md-4">
                    <form name="form1" action="formulaire.php" method="post">

                        <div class="form-group text-center">
                            <label for="champDate">Date du repas*</label>
                            <input name="date_resa" type="date" class="form-control" id="champDate" min="<?= date("Y-m-d");?>" required />
                        </div>
                        <div class="form-group text-center">
                            <label for="champHeure">Heure*</label>
                            <input name="heure" type="time" class="form-control" id="champHeure" min="<?= date("12:00") ?>" max="<?= date("22:00") ?>" required />
                        </div>
                        <div class="form-group text-center">
                            <label for="champNbr">Nombre de couverts*</label>
                            <select name="nb_personne" class="form-control nbr" id="champNbr" required>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option>10</option>
                                <option>11</option>
                                <option>12</option>
                                <option>13</option>
                                <option>14</option>
                                <option>15</option>
                                <option>16</option>
                                <option>17</option>
                                <option>18</option>
                                <option>19</option>
                                <option>20</option>
                                <option>21</option>
                                <option>22</option>
                                <option>23</option>
                                <option>23</option>
                                <option>24</option>
                                <option>25</option>
                                <option>26</option>
                                <option>27</option>
                                <option>28</option>
                                <option>29</option>
                                <option>30</option>
                            </select>
                        </div>
                        <div class="form-group text-center">
                            <label for="champNom">Nom*</label>
                            <input name="nom" type="text" maxlength="50" class="form-control" id="champNom" required />
                        </div>
                        <div class="form-group text-center">
                            <label for="champPrenom">Prénom</label>
                            <input name="prenom" type="text" maxlength="30" class="form-control" id="champPrenom" />
                        </div>
                        <div class="form-group text-center">
                            <label for="champTel">Téléphone*</label>
                            <input name="tel" type="tel" pattern="[0-9]{10}" maxlength="10" class="form-control" id="champTel" required />
                        </div>
                        <div class="form-group text-center">
                            <label for="champInfo">Informations complémentaires</label>
                            <textarea name="info" class="form-control" id="champInfo" rows="3"
                                      placeholder="Intérieur, Terrasse, poussette, etc..."></textarea>
                        </div>
                        
                        <div class="erreur mb-3 small text-center"><?= implode('<br/>', $tabErreur) ?></div>
                        <div class="text-center">
                            <input name="submit" type="submit" class="btn btn-primary mb-2" value="Réserver !"></button>
                        </div>
                        <div class="small text-center">Les champs marqués d'un astérique (*) sont obligatoires.</div>

                    </form>
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