<?php

declare(strict_types=1);
date_default_timezone_set("UTC");

require_once "../../admin/scripts/classes.php";
session_start();
$db = new DataBase();
$userid = $_SESSION["userid"];

$last_feed = $db->executeSql("SELECT feedback_time FROM feedbacks WHERE user_id = ? ORDER BY feedback_time DESC LIMIT 1", [$userid], true);
// die($last_feed[0]["feedback_time"] . "<br>" . $last_feed["rows"]);

if ($last_feed["rows"] != 0) {
    $last_feed = $last_feed[0]["feedback_time"];
    if (time() - $last_feed < 1800) {
        die(json_encode(["ERROR_CODE" => 3001, "ERROR_MESSAGE" => "You have sent a feedback in the last 30 minutes"]));
    }
}


if (empty($_POST["feed"])) {
    die(json_encode(["ERROR_CODE" => 3002, "ERROR_MESSAGE" => "Feedback cannot be empty"]));
}

$feed = $_POST["feed"];
$bug = $_POST["bug"] ? 1 : 0;
$rating = $_POST["rate"] ? $_POST["rate"] : "Not rated";


$db->executeSql("INSERT INTO feedbacks (feedback_body, bug, rating, feedback_time, user_id) VALUES (?, ?, ?, ?, ?)", [$feed, $bug, $rating, time(), $userid]);
$_SESSION["last_feed"] = time();
die(json_encode(["SUCCESS_CODE" => 3000, "SUCCESS_MESSAGE" => "Success"]));
