<?php

require_once "../../admin/scripts/classes.php";
require_once "../../admin/scripts/validation.php";

if (!(isset($_POST["projId"]) && isset($_POST["name"]) && isset($_POST["projDesc"]))) {
    die(json_encode(["error" => "Fatal error occurred"]));
}

if (empty($_POST["projId"])) {
    die(json_encode(["error" => "Fatal error occurred"]));
}

$projName = true;
$projDesc = true;

// FIXME: It was $_POST["projName"] before
if (empty($_POST["name"])) {
    $projName = false;
}

if (empty($_POST["projDesc"])) {
    $projDesc = false;
}

if ($projDesc or $projName) $db = new DataBase();

if ($projName) {
    if (!validateString($_POST["name"])) {
        die(json_encode(["error" => "Error while validation"]));
    }
    // FIXME: It didn't specify the project id before
    $resp = $db->executeSql("update projects set project_name=? where project_id = ?", [$_POST['name'], $_POST["projId"]], true);
    if (is_object($resp) && get_class($resp) == "PDOException") {
        die(['error' => "Please report this on feedback: " . $resp->getCode()]);
    }
}

if ($projDesc) {
    if (!validateStringWithSpaces($_POST["projDesc"])) {
        die(json_encode(["error" => "Error while validation"]));
    }
    // FIXME: It didn't specify the project id before
    $resp = $db->executeSql("update projects set project_detail=? where project_id = ?", [$_POST['projDesc'], $_POST["projId"]], true);
    if (is_object($resp) && get_class($resp) == "PDOException") {
        die(['error' => "Please report this on feedback: " . $resp->getCode()]);
    }
}

$db->executeSql("update projects set project_time=?", [date('y-m-d h:m:s')]);
die(json_encode(["success" => 1]));
