<?php
include "common.php";

$output = "";
$requestedFunction =$_GET['function'];


if($requestedFunction=="getSchedule"){
	$errors = array();
	$requestedWeek =$_GET['week'];
	$output .= getSchedule($log,$requestedWeek,$errors);
}else if($requestedFunction=="getPlayersForTeams"){
	$errors = array();
	$requestedMatch =$_GET['match'];
	$output.= drawPlayersForMatch($log,$requestedMatch,$errors);
}else if($requestedFunction=="getWinsForTeams"){
	$errors = array();
	$requestedMatch =$_GET['match'];
	$output.= drawWinsInputForMatch($log,$requestedMatch,$errors);
}else if($requestedFunction=="getAverages"){
	$requestedMatch =$_GET['match'];
	$output .= getAverages($log,$requestedMatch,$errors);
		
}


echo $output;
//end of page




?>


