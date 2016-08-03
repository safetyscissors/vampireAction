<?php
require('authCheck.php');
if(!isset($USER->id)) return;
require('queries/profileQueries.php');
$PAGE->id='profileList';

//setup for query
$stmt = getUnrated($DB, $USER->id);
if(!$stmt) return; // getLists already send error.
if(!$stmt->execute()) return errorHandler("failed to get this list $stmt->errno: $stmt->error", 503);
//format results
$data = array();
$stmt->bind_result($data['profileId']);

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