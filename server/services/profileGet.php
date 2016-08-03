<?php
require('authCheck.php');
if(!isset($USER->id)) return;
require('queries/profileQueries.php');
$PAGE->id='profileGet';

//get inputs. requires listId
$requiredField='profileId';
$input='';
if(isset($_GET[$requiredField]) && !empty($_GET[$requiredField])){
    $input=$_GET[$requiredField];
}else{
    return errorHandler("Missing $requiredField", 503);
}

//setup for query
$stmt = getProfile($DB, $input);
if(!$stmt) return; // getLists already send error.
if(!$stmt->execute()) return errorHandler("failed to get this list $stmt->errno: $stmt->error", 503);
//format results
$data = array();
$stmt->bind_result($data['profileId'],$data['name'],$data['image'],$data['description'],$data['age'],$data['lastLogin']);

/* fetch values */
$listResults = array();
while ($stmt->fetch()) {
    $row = arrayCopy($data);
    array_push($listResults, $row);
}
echo json_encode($listResults);

function arrayCopy( array $array ) {
    $result = array();
    foreach( $array as $key => $val ) {
        if( is_array( $val ) ) {
            $result[$key] = arrayCopy( $val );
        } elseif ( is_object( $val ) ) {
            $result[$key] = clone $val;
        } else {
            $result[$key] = $val;
        }
    }
    return $result;
}
?>