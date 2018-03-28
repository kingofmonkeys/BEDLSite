<?php
include "common.php";

$output = "";
if(!isset($_SESSION['logged_in'])){
	header("Location: login.php");
}

$output .= draw_head("Baltimore English Dart League", "Members Page");

$errors = array();
// log some information
$log->LogDebug($_SESSION['username']." is in the adminscoresheet.php page");

if(isset($_POST['BTN_BACK'])){
	//this means the back button was hit
 	header("Location: members.php");
}


$week = getCurrentWeek($log);

if($week==null){
	$output .= drawOutOfSeason($log);
}else{
	if(isset($_POST['BTN_SUBMIT'])){
		//this means the form was submitted
		if(haveScoresBeenEntered($_POST['week'],$_POST['homeTeam'],$log)){	
			header("Location: members.php");
		}
		
		$errors = validateForm($log);
		if(count($errors)>0){
			$output .= displayErrors($errors);
			$output .= drawAdminScoreSheetForm($log,$errors);
		}else{
			$homeTeam = getTeamForId($_POST['homeTeam'],$log);
			$visitingTeam = getTeamForId($_POST['visitingTeam'],$log);
			$output .= saveScoreSheet($homeTeam,$visitingTeam,$log);
		}

	}else{
		//if the form has not been submited
		$output .= drawAdminScoreSheetForm($log,$errors);

	}

}

$output .= draw_foot();


echo $output;
//this is the end of the page.





function drawAdminScoreSheetForm($log,$errors){
	$output = "";
	$weekValue = "--";
	$matchValue = "--";
	
	if(isset($_POST['week'])){
		$weekValue = $_POST['week'];
	}
	if(isset($_POST['match'])){
		$matchValue = $_POST['match'];
	}
	$seasonName = getSeasonName($log);
	$header = $seasonName.' Season';
	
	$body = "";

	$body .= '<form action="./adminscoresheet.php" method="post">';
	$body .= '<div class="teamInfo">';	
	$body .= '  <div class="dl-input">';

	$weekFieldHTML = '<label for="week">Week:</label><select name="week" id="week" onchange="updateTeams()"><option value="--">--</option>'."\r\n";
    $weeks = getWeeks($log);

    foreach ($weeks as $i => $week) {
	   $weekFieldHTML = $weekFieldHTML.'<option value="'.$week->getWeekNumber().'"';
	   if($week->getWeekNumber()==$weekValue){
	    $weekFieldHTML = $weekFieldHTML.' selected';
	    }

	    $weekFieldHTML = $weekFieldHTML.'>'.$week->getWeekNumber().' - ('.date("n/j/Y", strtotime($week->getWeekDate())).')</option>."\r\n"';
	  }


	$body .= displayField($weekFieldHTML,"week",$errors);

	$body .= '    </select>';

	$body .= '  </div>'."\r\n";

	if($weekValue != "--"){
		$body .= '<div id="teamsForWeek">';
		$body .= getSchedule($log,$weekValue,$errors);
		$body .= '</div>'."\r\n";
	}else{
		$body .= '<div id="teamsForWeek"style="display:none">';
		$body .= '</div>'."\r\n";
	}

	$body .= '</div>';

	if($matchValue != "--"){
		//this means the match is selected so we need to display the scoresheet form
		$homeTeam = getTeamForId($_POST['homeTeam'],$log);
		$visitingTeam = getTeamForId($_POST['visitingTeam'],$log);
		
		$body .= '<div id="scoreSheet">';
		$body .= drawScoreSheetForm($log,$errors,$homeTeam,$visitingTeam);
		$body .= '</div>';	
	}else{
		$body .= '<div id="scoreSheet" style="display:none">';
		$body .= '</div>';	
	}

	$body .= '<div><input type="submit" name="BTN_SUBMIT" value="Submit"/>';
	$body .= '<input type="submit" name="BTN_BACK" value="Back"/></div>';
	

	$body .= '</form>';
	
	$output .= drawContainer($header,$body);
	return $output;	
}


function validateForm($log){
	$errors = array();
	
	if($_POST['week']=="--"){
		$errors = setError("week","You must select a week.",$errors);
	}else if($_POST['match']=="--"){
		$errors = setError("match","You must select a match.",$errors);
	}else{		
		$errors = validateScoreSheet($log);
	}
	
	return $errors;
}

?>