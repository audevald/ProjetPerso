<?php
require_once 'class/cfg.php';
session_destroy();
header('Location:login.php');