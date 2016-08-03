<?php
require('authCheck.php');
if(!isset($USER->id)) return;
require('queries/actionQueries.php');
$PAGE->id='actionCreate';

$fields=array('targetId','action');
$inputs=array();
$body = json_decode(file_get_contents("php://input"));

//check POST object for variables from front end
foreach($fields as $key=>$field){
    if(isset($body->{$field}) && !empty($body->{$field})){
        $inputs[$field] = $body->{$field};
    }else{
        if($field == 'action') {
            $inputs['action']= 0;
        }else {
            return errorHandler("Missing required field $field", 503);
        }
    }
}

//print debug statement
if($SERVERDEBUG){
    echo "\r\n inputs:";
    echo json_encode($inputs);
}

//check source = target
if($inputs['targetId'] == $USER->id) return errorHandler("You liked yourself... Probably shouldnt have happened.", 503);

//setup for query
$stmt = createAction($DB, intval($inputs['targetId']), $USER->id, intval($inputs['action']));
if(!$stmt) return; // createNewList already sent an error.
if(!$stmt->execute()) return errorHandler("Failed to create this action $stmt->errno: $stmt->error", 503);
$stmt->close();

//check for a match
if($inputs['action']==1) {
    $matchStmt = matchAction($DB, intval($inputs['targetId']), $USER->id);
    if(!$matchStmt) return;
    if(!$matchStmt->execute()) return errorHandler("Failed to create this action $matchStmt->errno: $matchStmt->error", 503);
    $matchStmt->bind_result($existingId);
    $matchStmt->fetch();
    $matchStmt->close();

    echo intval($inputs['targetId']);
    echo $USER->id;
    echo $existingId;
    if($existingId!=0) {
        echo '{"match":true}';

        //save match
        $saveStmt = saveMatch($DB, intval($inputs['targetId']), $USER->id);
        if(!$saveStmt) return;
        if(!$saveStmt->execute()) return errorHandler("Failed to create this action $saveStmt->errno: $saveStmt->error", 503);
        $saveStmt->close();
        return;
    }
}

echo '{"match":false}';
?>