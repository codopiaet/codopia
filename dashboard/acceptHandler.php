<?php

declare(strict_types=1);
require_once "../admin/scripts/classes.php";
require_once "../admin/scripts/validation.php";
session_start();

$db = new DataBase();
$userid = (int)$_SESSION["userid"];
$xp = new XPSystem($userid);
$data = file_get_contents("php://input");

function add(int $event_id, int $user_id, int $point, array $array) {
    global $db, $xp;
    array_push($array, $user_id);
    $encode = json_encode($array);
    $db->executeSql("UPDATE events SET accepted_by = ? WHERE event_id = ?", [$encode, $event_id]);
    $db->executeSql("UPDATE events SET accepts = accepts + 1 WHERE event_id = ?", [$event_id]);
    $xp->addXP($point);
    echo "ACCEPTED";
}

function remove(int $event_id, int $user_id, int $point, array $array) {
    global $db, $xp;
    $index = array_search($user_id, $array);
    unset($array[$index]);
    $encode = json_encode($array);
    $db->executeSql("UPDATE events SET accepted_by = ? WHERE event_id = ?", [$encode, $event_id]);
    $db->executeSql("UPDATE events SET accepts = accepts - 1 WHERE event_id = ?", [$event_id]);
    $xp->decreaseXP($point);
    echo "REJECTED";
}

function search(mixed $element, array $array) {
    if (in_array($element, $array) == true) return 1;
    else return -1;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($data) {
        $data = json_decode($data, true);
        if ($data["condition"] == -1) {
            die("You have to acknowledge the conditions before joining the event!");
        }
        $result = (array) $db->executeSql("SELECT accepted_by FROM events WHERE event_id = ?", [$data["event_id"]], true);
        $acceptedBy = json_decode($result[0]["accepted_by"], true);

        $search = search($userid, $acceptedBy);

        if ($search == -1) add((int) $data["event_id"], $userid, (int) $data["xp"], $acceptedBy);
        else remove((int) $data["event_id"], $userid, (int) $data["xp"], $acceptedBy);

    } else {
        die("No data received!");
    }
}

?>