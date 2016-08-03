<?php

/* ********************************************************************* *\
        MAIN SERVER
\* ********************************************************************* */
  //setup session 
  session_start();

  //setup database connection
  $DB = require('dbConfig.php');

  //setup global object
  $USER = new stdClass();
  $PAGE = new stdClass();

  //get path to a service
  $service = getRoute(getUri());

  //set the debug flag
  $SERVERDEBUG = setDebug();

  //exit with msg if path doesnt exist
  if($service==false) return errorHandler('Invalid Path', 501);

  //if path was valid, load service
  require($service);

  //if debug, dump server response
  if($SERVERDEBUG){
    echo "\r\n page:";
    echo json_encode($PAGE); return;
  }

  //at the end of it all, close db
  $DB->close();



/* ********************************************************************* *\
          HELPER FUNCTIONS
\* ********************************************************************* */
  function setDebug(){
    if(isset($_GET['debug']) && $_GET['debug']=="true"){
      return true;
    }
    return false;
  }

  /*
    Reads a method:path string and returns a path to a service OR false
    param $path string
    returns string || false
  */
  function getRoute($path){
    $serviceDir = "services";
    $path=strToLower($path);
    switch($path){
      case "get:":
      case "get:index.php": return "$serviceDir/main.php";
      case "get:healthcheck": return "$serviceDir/healthCheck.php";
      
      case "post:user":       return "$serviceDir/userCreate.php";
      case "get:user":        return "$serviceDir/userGet.php";
      case "get:userlist":    return "$serviceDir/userlistGet.php";
      case "put:user":        return "$serviceDir/userUpdate.php";
      case "delete:user":     return "$serviceDir/userDelete.php";

      case "get:auth":        return "$serviceDir/authCheckResponse.php";
      case "post:authreset":  return "$serviceDir/authResetPassword.php"; //auth not needed
      case "post:auth":       return "$serviceDir/authLogin.php"; //auth not needed
      case "put:auth":        return "$serviceDir/authNewPassword.php";
      case "delete:auth":     return "$serviceDir/authLogout.php";

      case "get:growerclublist": return "$serviceDir/growerClubList.php";
      case "get:growerclub":  return "$serviceDir/growerClubGet.php";
      case "get:growerlist":  return "$serviceDir/growerList.php";
      case "get:grower":      return "$serviceDir/growerGet.php";
      case "post:grower":     return "$serviceDir/growerCreate.php";
      case "delete:grower":   return "$serviceDir/growerDelete.php";
      case "put:grower":      return "$serviceDir/growerUpdate.php";

      case "get:productlist": return "$serviceDir/productList.php";
      case "get:product":     return "$serviceDir/productGet.php";
      case "post:product":    return "$serviceDir/productCreate.php";
      case "put:product":     return "$serviceDir/productUpdate.php";
      case "delete:product":  return "$serviceDir/productDelete.php";

      case "get:fieldlist":   return "$serviceDir/fieldListByProduct.php";
      case "get:field":       return "$serviceDir/fieldGet.php";
      case "post:field":      return "$serviceDir/fieldCreate.php";
      case "put:field":       return "$serviceDir/fieldUpdate.php";
      case "delete:field":    return "$serviceDir/fieldDelete.php";
    }
    return false;
  }

  /*
    Reads SERVER var requestUri and requestMethod and returns a route string
    returns string [method:path]
  */
  function getUri(){
    $uri=explode("/",$_SERVER['REQUEST_URI']);

    //get rid of extra directory depth
    array_shift($uri);
    array_shift($uri);
    array_shift($uri);
    $uri=join("/",$uri);

    //get rid of param string
    $uri=explode("?",$uri);

    $params="";
    if(count($uri)>1){
        $params=$uri[1];
    }
    $uri=$uri[0];

    //get GET params
    if(strlen($params)>0){
    $params=explode("&",$params);
    foreach($params as $param){
      $param=explode("=",$param);
      $_GET[$param[0]]=$param[1];
    }
    }

    $method=$_SERVER['REQUEST_METHOD'];
    return "$method:$uri";
  }

  /*
    Prints a message, sets the response error code
  */
  function errorHandler($message, $code){
    echo '{"errors":"'.$message.'"}';
    http_response_code($code);
    return false;
  }

?>
