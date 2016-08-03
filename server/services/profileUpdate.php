<?php
require('authCheck.php');
if(!isset($USER->id)) return;
require('queries/profileQueries.php');
$PAGE->id='profileUpdate';

$fields = array('profileId','description','age');
$requiredFields=array('profileId');
$inputs=array();
$body = json_decode(file_get_contents("php://input"));

//check POST object for variables from front end
foreach($fields as $key=>$field){
    if(isset($body->{$field}) && !empty($body->{$field})){
        $inputs[$field] = $body->{$field};
    }else{
        $inputs[$field] = '';
    }
}

//check inputs for all required fields
foreach($requiredFields as $postKey){
    if(!isset($inputs[$postKey]) || empty($inputs[$postKey])){
        return errorHandler("missing $postKey", 503);
    }
}

//print debug statement
if($SERVERDEBUG){
    echo "\r\n inputs:";
    echo json_encode($inputs);
}

//setup for query
$stmt = updateProfile($DB, $USER->id, $inputs['description'], $inputs['age']);
if(!$stmt) return; // createNewList already send error.
if(!$stmt->execute()) return errorHandler("failed to create this list $stmt->errno: $stmt->error");

if($stmt->affected_rows != 1){
    return errorHandler("Updated $stmt->affected_rows rows", 503);
}
echo json_encode($inputs);
?>