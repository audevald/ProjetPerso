<?php
require_once 'class/Cfg.php';
if(!AbstractUser::getUserSession(User::class))
    exit;
$opt = ['options'=>['min_range' => 1]];
$tri = isset($_SESSION['tri']) ? $_SESSION['tri'] : 0;
$id_reservation = filter_input(INPUT_GET, 'id_reservation', FILTER_VALIDATE_INT, $opt);
$page = filter_input(INPUT_GET, 'page');
Reservation::annuler($id_reservation);
if ($page == 'reservation') {
    header('Location:reservation.php');
    exit;
} elseif ($page == 'dashboard') {
    header("Location:dashboard.php?tri={$tri}");
    exit;
}