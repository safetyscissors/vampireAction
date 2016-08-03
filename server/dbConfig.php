<?php
  //create connection
  //PLACEHOLDER for actual
  $configDB = new mysqli('localhost','root','password','sips');

  //check connection
  if($configDB->connect_error){
    die('db connection failed:'.$configDB->connect_error);
  }
  return $configDB;
?>
