<?php
require_once "../admin/scripts/classes.php";
session_start();

if (isset($_SESSION["loggedin"]) and $_SESSION["loggedin"]) {
    $tracker= new Tracker("logout-log.log");
    $time = date("y-m-d h:m:s");
    $tracker->log("[INFO] a logout by the user " . $_SESSION["username"] . "and IP {$_SERVER['REMOTE_ADDR']} AT $time");
    session_unset();
    session_destroy();
    setcookie(session_name(), "",time()-31536000, "/");
}

header("Location: /login");