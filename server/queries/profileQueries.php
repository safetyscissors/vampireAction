<?php
    function getProfileByName($DB, $name){
        $stmt = $DB->prepare("SELECT profileId,name,passwordHash FROM zombieProfile WHERE name= ?");
        if(!$stmt->bind_param('s', $name)){
            return errorHandler("getProfileByName failed to bind parameter", 503);
        }
        return $stmt;
    }

    function checkExistingUserName($DB, $name){
        $stmt = $DB->prepare("SELECT profileId FROM zombieProfile WHERE name = ?");
        if(!$stmt->bind_param('s', $name)){
            return errorHandler("checkExistingUserName failed to bind parameter", 503);
        }
        return $stmt;
    }

    function createProfile($DB, $name, $password){
        $stmt = $DB->prepare("INSERT INTO zombieProfile (name,passwordHash) VALUES (?,?)");

        if(!$stmt->bind_param('ss', $name, $password)){
            return errorHandler("createUser failed to bind parameter", 503);
        }
        return $stmt;
    }

    function getProfile($DB, $profileId){
        $stmt = $DB->prepare("SELECT profileId,name,image,description,age,lastLogin FROM zombieProfile WHERE profileId = ?");
        if(!$stmt->bind_param('i', $profileId)){
            return errorHandler("getProfile failed to bind parameter", 503);
        }
        return $stmt;
    }

    function updateProfile($DB, $profileId, $description, $age){
        $stmt = $DB->prepare("UPDATE zombieProfile SET description=?, age=? WHERE profileId=?");

        if(!$stmt->bind_param('ssi', $description, $age, $profileId)){
            return errorHandler("updateUser failed to bind parameter", 503);
        }
        return $stmt;
    }

    function getUnrated($DB, $profileId){
       $stmt = $DB->prepare("SELECT profileId FROM zombieProfile WHERE profileId NOT IN(SELECT targetId FROM zombieAction WHERE sourceId = ?) LIMIT 20");
        if(!$stmt->bind_param('i', $profileId)){
            return errorHandler("getUnrated failed to bind parameter", 503);
        }
        return $stmt;
    }
?>