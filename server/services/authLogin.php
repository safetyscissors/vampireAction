<?php
require('queries/profileQueries.php');
require('password_compat.php');
$fields=array('name','password');
$inputs=array();
$body = json_decode(file_get_contents("php://input"));

//check POST object for variables from front end
foreach($fields as $key=>$field){
    if(isset($body->{$field}) && !empty($body->{$field})){
        $inputs[$field] = $body->{$field};
    }else{
        return errorHandler("Missing required field $field",503);
    }
}

//get the user's password
$stmt = getProfileByName($DB, $inputs['name']);
if(!$stmt) return; //authLogin already sent an error.
if(!$stmt->execute()) return errorHandler("failed to create this list $stmt->errno: $stmt->error");
$data = array();
$stmt->bind_result($data['id'],$data['name'],$data['password']);
$stmt->fetch();

if(password_verify($inputs['password'], $data['password'])){
    $_SESSION['time'] = time();
    $_SESSION['profileId'] = $data['id'];
    $_SESSION['profileName'] = $data['name'];
}else{
    return errorHandler("password failed",503);
}
?>