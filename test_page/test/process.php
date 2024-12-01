<?php
/** Note: put a session var in the file that sets to true when a user is done is done in one kind of test.
 * Make the GET request decide if the user has taken the specific test when it is fixed.
 * For now jS id the id of 1.
 */
session_start();
require_once("../../admin/scripts/classes.php");
require_once("../../admin/scripts/dashboard_setup.php");
if (!$_SESSION["loggedin"]){
    header("Location: /login");
    exit;
}
$subjectMap = [1=>"JavaScript",2=>"HTML"];
$cache = new Cache($_SESSION["userid"]);
$db = new DataBase();
define("SUBJECT_ID", $cache->get(TRUE)["subId"]);
$subjectCheck = $db->executeSql("select taken_by from test_list where name=?", [$subjectMap[SUBJECT_ID]], true);

$checker = (array)json_decode($subjectCheck[0]["taken_by"]);
if (in_array(SUBJECT_ID,$checker)){
    die(json_encode(["error"=>"You have already taken the test!"]));
}
$userAnswer = $_POST["answer"];



// We didn't set the number of questions so I just set it to 5 (the number of my questions.)
// TO gain more XP a user may do a test a lot of times over.

if (is_array($cache->get()) &&  $cache->get()["code"] ==88 || ($cache->get() == -1)){
    die("Error occured Code (88|-1).");
}
if  ($cache->get() == -1){
    die("Please <a href='/login'>login</a> first."); // I thought of making the test unique for every get request.
}

if (is_array($cache->get()) && $cache->get()["code"] == -2){
    $cache->set(1, SUBJECT_ID);
}

$userAnswer = strip_tags($userAnswer);
$userAnswer = stripslashes($userAnswer);
$userAnswer = htmlspecialchars($userAnswer);
// search a way to find type of a var.
if (!preg_match("/a|b|c|d/", $userAnswer)){
    die(json_encode(array("error"=> "Validation failed")));
}
$xpSys = new XPSystem((int)$_SESSION["userid"]);
$temp = $db->executeSql("select count(*) as noOfQuestions from tests where subject=?", [$subjectMap[(int)SUBJECT_ID]], TRUE);
if (is_object($temp)){
    header($_SERVER['SERVER_PROTOCOL']." 500 Internal Server Error");
    die(json_encode(array("error"=> "Error occured")));
}

define("NUMBER_OF_QUESTIONS", (int)$temp[0]["noOfQuestions"]);



$realAns = $db->executeParams("select answer from tests where subject=? limit 1 offset ?",[$subjectMap[SUBJECT_ID], (int)($cache->get()) -1],TRUE);
if ($realAns==-1){
    die(json_encode(["type"=>"error", "message"=>"Error occured"]));
}

// After long debuggind and CHAT GPT the answer is use bindValue.

$cache->set($cache->get() + 1, $cache->get(TRUE)["subId"]);


$newQuestion = $db->executeParams("select question,a,b,c,d from tests where subject=? limit 1 offset ?",[$subjectMap[SUBJECT_ID], (int)$cache->get() -1],TRUE);
if ($newQuestion["rows"] ==0){
    $resarr = (array)(json_decode($subjectCheck[0]["taken_by"]));
    array_push($resarr, SUBJECT_ID);
    $db->executeSql("update test_list set taken_by=? where name=?", [json_encode($resarr), $subjectMap[SUBJECT_ID]]);
    $cache->cleanUp();
    $correct = ($realAns[0]["answer"] == $userAnswer) ? 1:0;
    if ($correct==1){
        $xpSys->addXP(10);
    }
    die(json_encode(array("error"=> 12, "correct"=>$correct)));
}
$newQuestionJSON = [
    "question"=>$newQuestion[0]["question"],
    "a"=> $newQuestion[0]["a"],
    "b"=> $newQuestion[0]["b"],
    "c"=>$newQuestion[0]["c"],
    "d"=>$newQuestion[0]["d"],
    "topic"=> $subjectMap[SUBJECT_ID]
];
if ($realAns[0]["answer"] == $userAnswer) {
    $xpSys->addXP(10);
    die(json_encode(["correct"=>1, "nextQuestion"=>json_encode($newQuestionJSON)]));
}
else {
    $xpSys->decreaseXP(10);
    die(json_encode(["correct"=>0, "ans"=>$realAns[0], "nextQuestion"=>json_encode($newQuestionJSON)]));
}