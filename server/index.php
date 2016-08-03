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
    var_dump($path);
    $serviceDir = "services";
    $path=strToLower($path);
    switch($path){
      case "get:healthcheck": return "$serviceDir/healthCheck.php";

      case "get:auth":      return "$serviceDir/authCheck.php";
      case "post:auth":     return "$serviceDir/authLogin.php";

      case "get:profile":   return "$serviceDir/profileGet.php";
      case "post:profile":  return "$serviceDir/profileCreate.php";
      case "put:profile":   return "$serviceDir/profileUpdate.php";

      case "post:image":    return "$serviceDir/imageAdd.php";

      case "post:action":   return "$serviceDir/actionCreate.php";

      case "get:matchList": return "$serviceDir/matchList.php";

      case "get:newProfile":return "$serviceDir/newProfileGet.php";
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
