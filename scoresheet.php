<?php
include "common.php";

$output = "";
if(!isset($_SESSION['logged_in'])){
	header("Location: login.php");
}

$output .= draw_head("Baltimore English Dart League", "Members Page");
$errors = array();
// log some information
$log->LogDebug($_SESSION['username']." is in the scoresheet.php page");

if(isset($_POST['BTN_BACK'])){
	//this means the back button was hit
 	header("Location: members.php");
}
$homeTeam = getTeamForUser($log,$_SESSION['username']);
//commented this out so the form shows
//$week = getCurrentWeek($log);
$week =new Week();
$week->setWeekNumber('1');
$week->setWeekDate('3/22/2018');


if($week==null){
	$output .= drawOutOfSeason($log);
}else if($homeTeam==null){
	$output .= drawNoTeam($log);
}else{
	$visitingTeam = getVisitingTeam($log,$week,$homeTeam->getTeamId());

	if(isset($_POST['BTN_SUBMIT'])){
		//this means the form was submitted
		if(isset($_POST['visiting'])){
			//we just need to submit the additional notes section
			$log->LogDebug("Visiting team form submitted. (Only looking at Additional Notes field)");
			$conn = getDBiConnection($log);
			if(isset($_POST['additionalNotes']) && $_POST['additionalNotes']!=""){
				//we need to save the additionalNotes
				$log->LogInfo("Additional Notes founds: ".$_POST['additionalNotes']);
				$to ='jimmy@kingofmonkeys.com';
				$subject = $_SESSION['username'].' has submitted additional notes with this weeks scores notes';
				$body = $_SESSION['username'].' has submitted additional notes with this weeks scores notes: '."\r\n";
				$body .= $_POST['additionalNotes'];
				sendEmail($to, $subject, $body);

				$addNotesQuery = "INSERT INTO notes (userId, week_number, notes) VALUES (".$_SESSION['userid'].",".$_POST['week'].", '".mysql_real_escape_string($_POST['additionalNotes'])."')";
				$a5 = mysqli_query($conn,$addNotesQuery);
				if(!$a5){
					$log->LogError("There was an error trying to insert additional notes: sql=".$addNotesQuery);
					header("Location: ErrorPage.php");
				}else{
					$output .= displaySuccessPage($log);
				}
			}else{
				$errors = setError('additionalNotes',"Additional Notes required.",$errors);
				$output .= displayErrors($errors);
				$output .= drawVisitingTeamForm($log,$errors,$week ,$homeTeam,$visitingTeam);
			}
		}else{

			//here is form validation.
			$errors = validateScoreSheet($log);
			if(count($errors)>0){
				$log->LogError("Errors found in Scoresheet.php submitted with the following values.  Week: ".$_POST['week'].", Home Team: ".$_POST['homeTeam'].", Visiting Team: ".$_POST['visitingTeam'].", Home Team Wins: ".$_POST['homeTeamWins'].", Visiting Team Wins: ".$_POST['visitingTeamWins']);
				$output .= displayErrors($errors);
				$output .= drawCaptainScoreSheetForm($log,$errors,$week,$homeTeam,$visitingTeam);
			}else{
				//form submitted and passed validation
				$output .= saveScoreSheet($homeTeam,$visitingTeam,$log);
			}


	}
}else{
	//if the form has not been submited
	if($visitingTeam==null){
		//if the user wasn't on the home team this week.
		$log->LogDebug($_SESSION['username'].' is on the scoresheet.php page but is not a home team this week');
		$visitingTeam = $homeTeam;
		$homeTeam = getHomeTeamFromVisitingTeam($log,$week,$visitingTeam->getTeamId());
		$errors = setError("none","You are not the home team this week.  However, you can still submit additional notes.",$errors);
		$output .= displayErrors($errors);
		$output .= drawVisitingTeamForm($log,$errors,$week ,$homeTeam,$visitingTeam);
	}else{
		if(haveScoresBeenEntered($week->getWeekNumber(),$homeTeam->getTeamId(),$log)){
			$errors = setError("none","Scores for this week have already been submitted.  However, you can still submit more additional notes.",$errors);
	  		$output .= displayErrors($errors);
	  		$output .= drawVisitingTeamForm($log,$errors,$week ,$homeTeam,$visitingTeam);
	  	}else{
			$output .= drawCaptainScoreSheetForm($log,$errors,$week ,$homeTeam,$visitingTeam);
		}
	}
}

}


$output .= draw_foot();


echo $output;
//this is the end of the page.


 
function drawNoTeam($log){
	$seasonName = getSeasonName($log);
	$header = $seasonName.' Season';	
	$body = "";		
	$body .=  '<form action="./scoresheet.php" method="post">';
	$body .=  '<div><br/>';
	$body .=  "I'm sorry but you do not currently have a team associated with you.<br/><br/>";
	$body .=  '<input type="submit" name="BTN_BACK" value="Back"/>';
	$body .=  '</div>';
	$body .=  '</form>';
	$output .= drawContainer($header,$body);	
	return $output;
}

function drawVisitingTeamForm($log,$errors,$week,$homeTeam,$visitingTeam){
	$additionalNotesValue = "";
	if(isset($_POST['homeTeamWins']) &&$_POST['homeTeamWins']!=null){
		$homeTeamWinsValue=$_POST['homeTeamWins'];
	}
	
	$seasonName = getSeasonName($log);
	$header = $seasonName.' Season';	
	$body = "";		
	
	
	$body .= '<form action="./scoresheet.php" method="post">';
	$body .= '<input type="hidden" name="visiting" value="true"/>';
	$body .= '<div class="seasonInfo">';
	$body .= '  <div class="dropdown dl-input">';

	$body .= '<input type="hidden" name="week" value="'.$week->getWeekNumber().'"/>';
	$body .= 'Week '.$week->getWeekNumber().' - ('.date("n/j/Y", strtotime($week->getWeekDate())).')';

	$body .= '  </div>';
	$body .= '</div>';
	$body .= '<div class="gameInfo">';
	$body .= ' <div class="teamInfo">';
	$body .= '   <div class="halfWidth">';
	$body .= '     <div class="textbox dl-input">';

	$body .= '<input type="hidden" name="homeTeam" value="'.$homeTeam->getTeamId().'"/>';
	$body .= '<h3>Home Team:</h3>';
	$body .=  $homeTeam->getTeamName();

	$body .= '      </div>';
	$body .= '    </div>';
	$body .= '    <div class="halfWidth">';
	$body .= '      <div class="textbox dl-input">';


	$body .= '<input type="hidden" name="visitingTeam" value="'.$visitingTeam->getTeamId().'"/>';
	$body .= '<h3>Visiting Team:</h3>';
	$body .=  $visitingTeam->getTeamName();
	$body .= '      </div>';
	$body .= '    </div>';
	$body .= '  </div>';
	$body .= '  <div class="additionalNotes dl-input">';

	$body .= displayField('<label for="additionalNotes">Additional Notes</label><textarea name="additionalNotes" id="additionalNotes" cols="60" rows="10">'.$additionalNotesValue.'</textarea>',"additionalNotes",$errors);

	$body .= '  </div>';
	$body .= '<input type="submit" name="BTN_SUBMIT" value="Submit"/>';
	$body .= '<input type="submit" name="BTN_BACK" value="Back"/>';
	$body .= '</div>';

	$body .= '</form>';
	
	$output .= drawContainer($header,$body);	
	
	return $output;
}

function drawCaptainScoreSheetForm($log,$errors,$week,$homeTeam,$visitingTeam){
	$seasonName = getSeasonName($log);
	$header = $seasonName.' Season';
	
	$body = "";
	$body .= '<form action="./scoresheet.php" method="post">';
	
	$body .= '  <div class="dropdown dl-input">';
	$body .= '<input type="hidden" name="week" value="'.$week->getWeekNumber().'"/>';
	$body .= 'Week '.$week->getWeekNumber().' - ('.date("n/j/Y", strtotime($week->getWeekDate())).')';
	$body .= '  </div>';
	
	$body .= drawScoreSheetForm($log,$errors,$homeTeam,$visitingTeam);
	
	$body .= '<input type="submit" name="BTN_SUBMIT" value="Submit"/>';
	$body .= '<input type="submit" name="BTN_BACK" value="Back"/>';	

	$body .= '</form>';	
	
	$output .= drawContainer($header,$body);
	return $output;
	
}



?>