<?php

declare(strict_types=1);
require_once("../admin/scripts/classes.php");
require_once "../admin/scripts/validation.php";
session_start();

// OPTIMIZE: ABOUT USERNAME
if (isset($_POST["type"]) && (int)$_POST["type"] == 99) {
    $db = new DataBase();

    if (!validateString($_POST["username"])) {
        die(json_encode(["error" => "Error while validation"]));
    }
    $r = $db->executeSql("select username from users where username=? limit 1", [$_POST["username"]], true);
    if ((int)$r["rows"] !== 0) {
        die(json_encode(["info" => "Username is taken"]));
    } else {
        die(json_encode(["success" => "Username is valid"]));
    }
}

// OPTIMIZE: IF SET AND NOT EMPTY
// function both($val)
// {
//     return isset($val) && !empty($val);
// }

// // OPTIMIZE: IF NOT LOGGED IN
// if (!isset($_SESSION["loggedin"]) && !isset($_SESSION) || !isset($_SESSION["loggedin"])) {
//     // OPTIMIZE: IF ALL FIELDS ARE EMPTY
//     if (!(both($_POST["full_name"]) && both($_POST["email"]) && both($_POST["email"]) && both($_POST["cpassword"]) && both($_POST["username"]))) {
//         die(json_encode(["error" => "Error while validation"]));
//     }

//     $full_name = htmlspecialchars($_POST["full_name"]);
//     $email = htmlspecialchars($_POST["email"]);
//     $password = htmlspecialchars($_POST["password"]);
//     $cpassword = htmlspecialchars($_POST["cpassword"]);

//     if (isset($_POST['bio']) && !empty($_POST['bio'])) {
//         $bio = $_POST["bio"];
//     } else {
//         $bio = "No bio for the moment";
//     }

//     $username = htmlspecialchars($_POST["username"]);

//     // --- --- --- --- --- --- --- EMAIL VERIFICATION



//     // --- --- --- --- --- --- ---

//     // $user = new User($username, $password);
//     // $e = $user->createUser($username, $password, $cpassword, $bio, $full_name, $email);
//     // // die(print_r($e));
//     // if ($e["code"] == 0) {
//     //     echo json_encode(["type" => "success"]);
//     //     exit;
//     // } else if ($e["code"] == 1) {
//     //     die(json_encode($e["data"]));
//     // } else if ($e["code"] == 2) {
//     //     $obj = $e["obj"];
//     //     if ($obj->getCode() == 23000) {
//     //         $code = $obj->getCode();
//     //         echo json_encode(["type" => "error", "message" => "User already registered  code:($code)"]);
//     //     } else {
//     //         echo json_encode(["type" => "error", "message" => $obj->getMessage(), "code" => $obj->getCode()]);
//     //     }
//     //     exit;
//     // } else {
//     //     die(json_encode(["type" => "error", "message" => "Unknown error occured"]));
//     // }
// } else {
//     die(json_encode(["type" => "error", "message" => "You've already logged in with an account"]));
// }
