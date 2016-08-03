<?php
    require('authCheck.php');
    if(!isset($USER->id)) return;
    $PAGE->id='imageAdd';

    //save new image
    $filename = "img/".$USER->id."profile.jpg";
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $filename)) {
        return errorHandler("Failed image upload",503);
    }
?>