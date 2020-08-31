<?php
require_once 'class/Cfg.php';
$tabErreur = [];
$user = new User();
if (filter_input(INPUT_POST, 'submit')) {
    // récupérer les données POST.
    $user->log = filter_input(INPUT_POST, 'log', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $user->mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    // vérifier les données POST.
    if (!$user->log)
        $tabErreur[] = "Identifiant absent.";
    if (!$user->mdp)
        $tabErreur[] = "Mot de passe absent.";
    if (!$tabErreur && $user->login()) {
        header('Location:dashboard.php');
        exit;
    }
    $tabErreur[] = "Identifiant ou mot de passe incorrect.";
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
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
              integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/dashboard.css" rel="stylesheet">

    </head>

    <body class="bg-light">
        <header class="navbar navbar-expand-lg navbar-light bg-primary mb-5">
            <div class="col-12 text-white p-0 px-sm-3">
                <i class="fas fa-utensils"></i>
                <span class="lead">Login</span>
            </div>
        </header>
        <div class="container">
            <div class="row">
                <div class="col-12 offset-sm-2 col-sm-8 offset-md-4 col-md-4">
                    
                    <div class="erreur mb-3 small"><?= implode('<br/>', $tabErreur) ?></div>
                    
                    <form name="form1" action="login.php" method="post">
                        <div class="form-group">
                            <input name="log" type="text" class="form-control" id="ChampUser" placeholder="Identifiant" required="required" />
                        </div>
                        <div class="form-group">
                            <input name="mdp" type="password" class="form-control" id="champPassword" placeholder="Mot de passe" required="required" />
                        </div>
                        <div class="text-center">
                            <input type="submit" name="submit" class="btn btn-primary" value="Connexion"></button>
                        </div>
                    </form>
                    
                </div>
            </div>
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