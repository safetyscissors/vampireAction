<?php
    require('queries/profileQueries.php');
    require('password_compat.php');
    $PAGE->id='profileCreate';

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

    //print debug statement
    if($SERVERDEBUG){
        echo "\r\n inputs:";
        echo json_encode($inputs);
    }

    //check for existing user
    $existingStmt = checkExistingUserName($DB, $inputs['name']);
    if(!$existingStmt) return; // checkExistingUserEmail already sent an error
    if(!$existingStmt->execute()) return errorHandler("Failed to check for existing user by email $existingStmt->errno: $existingStmt->error", 503 );

    $existingStmt->bind_result($existingId);
    $existingStmt->fetch();
    $existingStmt->close();
    if($existingId!=0) return errorHandler("Name already exists.", 503);

    //create passwordHash for db
    $passwordHash = password_hash($inputs['password'], PASSWORD_BCRYPT, array('cost'=>11));

    //setup for query
    $stmt = createProfile($DB, $inputs['name'], $passwordHash);
    if(!$stmt) return; // createNewList already sent an error.
    if(!$stmt->execute()) return errorHandler("Failed to create this profile $stmt->errno: $stmt->error", 503);
    echo '{"id":"'.$stmt->insert_id.'"}';


?>