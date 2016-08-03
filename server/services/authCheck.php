<?php
  //setup login. if it has no time, or time is older than 1 day, logout
  if(!isset($_SESSION['time'])){ //|| $_SESSION['time'] > (time() + (24*60*60)){
    return errorHandler('unauthorized',401);
  }

  $USER->id=$_SESSION['profileId'];
  $USER->name=$_SESSION['profileName'];
?>

