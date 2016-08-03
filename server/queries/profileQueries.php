<?php
    function getProfileByName($DB, $name){
        $stmt = $DB->prepare("SELECT profileId,name,passwordHash FROM zombieProfile WHERE name= ?");
        if(!$stmt->bind_param('s', $name)){
            return errorHandler("getProfileByName failed to bind parameter", 503);
        }
        return $stmt;
    }
?>