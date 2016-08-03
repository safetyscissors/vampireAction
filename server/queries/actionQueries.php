<?php
    function createAction($DB, $targetId, $sourceId, $action){
        $stmt = $DB->prepare("INSERT INTO zombieAction (targetId,sourceId,action) VALUES (?,?,?)");

        if(!$stmt->bind_param('iii', $targetId, $sourceId, $action)){
            return errorHandler("createUser failed to bind parameter", 503);
        }
        return $stmt;
    }

    function matchAction($DB, $sourceId, $targetId){
        $stmt = $DB->prepare("SELECT actionId FROM zombieAction WHERE sourceId=? AND targetId=? AND action=1");

        if(!$stmt->bind_param('ii', $sourceId, $targetId)){
            return errorHandler("matchAction failed to bind parameter", 503);
        }
        return $stmt;
    }

    function saveMatch($DB, $id1, $id2){
        $stmt = $DB->prepare("INSERT INTO zombieMatches (profileId1,profileId2) VALUES (?,?)");

        if(!$stmt->bind_param('ii', $id1, $id2)){
            return errorHandler("saveMatch failed to bind parameter", 503);
        }
        return $stmt;
    }
?>