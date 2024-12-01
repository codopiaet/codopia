<?php

require("../admin/scripts/classes.php");
session_start();

if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {
    if (!isset($_POST["username"],$_POST["password"])){
        echo json_encode(array("type"=> "error","message"=> "Fill out the fields first!"));
        exit;
    }
    if (!preg_match("/^[a-zA-Z1-9]+$/",$_POST["username"])){
        echo json_encode(array("type"=> "error","message"=> "Username is invalid"));
        exit;
    }
    
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);
    
    $user = new User($username, $password);
    
    if ($user->login()){
        echo json_encode(["type"=>"success"]);
    }
    else {
        echo json_encode(array("type"=> "error", "message"=> "Incorrect username and/or passwords"));
    }
} else {
    die(json_encode(["message" =>"You've Already logged in", "type"=>"error"]));
}