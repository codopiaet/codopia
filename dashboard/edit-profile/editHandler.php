<?php

declare(strict_types=1);

require_once "../../admin/scripts/classes.php";
require_once "../../admin/scripts/validation.php";
session_start();
$db = new DataBase();

echo '<link rel="stylesheet" href="/static/bootstrap.min.css">';

if (!(
    isset($_POST["username"]) &&
    isset($_POST["full_name"]) &&
    isset($_POST["bio"]) &&
    isset($_POST["old_pwd"]) &&
    isset($_POST["new_pwd"]) &&
    isset($_POST["conf_pwd"])
)) {
    die("<div class='alert alert-danger'>Please resubmit your form</div>");
}

if (!empty($_POST["username"])) {
    if (!validateString($_POST["username"])) {
        die("<div class='alert alert-danger'>Validation error occurred!</div>");
    }
    $db->executeSql("update users set username=? where id=?", [$_POST["username"], (int)$_SESSION["userid"]]);
    $_SESSION["username"] = $_POST["username"];
} //username update


if (!empty($_POST["full_name"])) {
    if (!validateStringWithSpaces($_POST["full_name"])) {
        die("<div class='alert alert-danger'>Validation error!</div>");
    }
    $db->executeSql("UPDATE users SET full_name = ? WHERE id = ?", [$_POST["full_name"], (int) $_SESSION["userid"]]);
    $_SESSION["full_name"] = $_POST["full_name"];
} // full_name update

if (!empty($_POST["bio"])) {
    if (!validateStringWithSpaces($_POST["bio"])) {
        die("<div class='alert alert-danger'>Validation error!</div>");
    }
    $db->executeSql("UPDATE users SET bio = ? WHERE id = ?", [$_POST["bio"], (int)$_SESSION["userid"]]);
} // bio update


if (!empty($_POST["old_pwd"] && !empty($_POST["new_pwd"]) && !empty($_POST["conf_pwd"]))) {
    $user = $db->executeSql("Select password from users where id=?", [(int)$_SESSION["userid"]], true);
    // die(print_r($user));
    if (!password_verify($_POST["old_pwd"], $user[0]["password"])) {
        die("<div class='alert alert-danger'>Incorrect password!</div>");
    }
    if ($_POST["new_pwd"] !== $_POST["conf_pwd"]) {
        die("<div class='alert alert-danger'>Passwords don't match!</div>");
    }
    $new_hash = password_hash($_POST["conf_pwd"], PASSWORD_ARGON2ID);
    $db->executeSql("UPDATE users set password = ? WHERE id = ?", [$new_hash, (int)$_SESSION["userid"]]);
} else {
    die("<div class='alert alert-danger'>Please make sure that you submitted the password forms right!!</div>");
} // Password update


header("Location: /dashboard");
exit;
// if (isset($_POST["submit"])) {


//     if (!empty($username) || !empty($full_name) || !empty($bio) || !empty($old_pwd) || !empty($new_pwd) || !empty($conf_pwd)) {
//         $result = $db->executeSql("SELECT * FROM users WHERE id = ?", [(int)$_SESSION["userid"]], true);
//         if (!empty($_POST["old_pwd"] && )){
//             if (!password_verify($_POST["old_pwd"], $result[0]["password"])) {
//                 die("Incorrect old password!");
//             }
//             if ($_POST["new_pwd"] !== $_POST["conf_pwd"]) {
//                 die("Password does not match!");
//             }
//         }
//         $new_hash = password_hash($conf_pwd, PASSWORD_ARGON2ID);
//         $db->executeSql("UPDATE users SET username = ?, full_name = ?, bio = ?, password = ? WHERE id = ?", [$username, $full_name, $bio, $new_hash, $_SESSION["userid"]]);
//         $_SESSION["username"] = $username;
//         $_SESSION["full_name"] = $full_name;
//         $_SESSION["bio"] = $bio;
//         header("Location: ./edit.php");
//     } else {
//         die("Please fill in all the fields!");
//     }
// }

// 
