<?php
include "common.php";

$output = "";
$requestedFunction =$_GET['function'];


if($requestedFunction=="getSchedule"){
	$errors = array();
	$requestedWeek =$_GET['week'];
	$output .= getSchedule($log,$requestedWeek,$errors);
}else if($requestedFunction=="getAdminScoreSheet"){
	$errors = array();
	$requestedMatch =$_GET['match'];	
	//get the match to get home and visiting teamsForWeek
	$match = getMatchForId($requestedMatch,$log);
	if($match->getScoresEntered()=="true"){
		$output .= "Scores already entered for this match";
	}else{
		$homeTeam = getTeamForId($match->getHomeTeamId(),$log);
		$visitingTeam = getTeamForId($match->getVisitingTeamId(),$log);
		$output .= drawScoreSheetForm($log,$errors,$homeTeam,$visitingTeam);
	}
}


//commented this out as its out of date
//else if($requestedFunction=="getPlayersForTeams"){
//	$errors = array();
//	$requestedMatch =$_GET['match'];
//	$output.= drawPlayersForMatch($log,$requestedMatch,$errors);
//}else if($requestedFunction=="getWinsForTeams"){
//	$errors = array();
//	$requestedMatch =$_GET['match'];
//	$output.= drawWinsInputForMatch($log,$requestedMatch,$errors);
//}else if($requestedFunction=="getAverages"){
//	$requestedMatch =$_GET['match'];
//	$output .= getAverages($log,$requestedMatch,$errors);		
//}


echo $output;
//end of page




?>


