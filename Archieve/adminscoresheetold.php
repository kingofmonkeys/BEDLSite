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
		$errors = validateForm($log);
		if(count($errors)>0){
			$output .= displayErrors($errors);
			$output .= drawScoreSheetForm($log,$errors);
		}else{
			$output .= processForm($log);
		}

	}else{
		//if the form has not been submited
		$output .= drawScoreSheetForm($log,$errors);

	}

}

$output .= draw_foot();


echo $output;
//this is the end of the page.


function drawOutOfSeason($log){
	$seasonName = getSeasonName($log);
	$header = $seasonName.' Season';	
	$body = "";		
	$body .=  '<form action="./members.php" method="post">';
	$body .=  '<div><br/>';
	$body .=  "I'm sorry but there are currently no stats due.<br/><br/>";
	$body .=  '<input type="submit" name="BTN_BACK" value="Back"/>';
	$body .=  '</div>';
	$body .=  '</form>';	
	$output .= drawContainer($header,$body);
	return $output;
}


function displaySuccessPage($log){	
	$seasonName = getSeasonName($log);
	$header = $seasonName.' Season';	
	$body = "";	
	$body .= '<form action="./members.php" method="post">';
	$body .= '<div><br/>';
	$body .= 'Success!  Weekly stats submitted.<br/><br/>';
	$body .= '<input type="submit" name="BTN_BACK" value="Back"/>';
	$body .= '</div>';
	$body .= '</form>';	
	$output .= drawContainer($header,$body);
	return $output;
}





function drawScoreSheetForm($log,$errors){
	$output = "";
	$weekValue = "--";
	$matchValue = "--";


	$homeTeamWinsValue = "";
	$visitingTeamWinsValue = "";
	$additionalNotesValue = "";
	if(isset($_POST['homeTeamWins']) &&$_POST['homeTeamWins']!=null){
		$homeTeamWinsValue=$_POST['homeTeamWins'];
	}

	if(isset($_POST['visitingTeamWins']) &&$_POST['visitingTeamWins']!=null){
		$visitingTeamWinsValue=$_POST['visitingTeamWins'];
	}
	if(isset($_POST['additionalNotes']) &&$_POST['additionalNotes']!=null){
			$additionalNotesValue=$_POST['additionalNotes'];
	}
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
		$body .= ' <div id="winsInput" class="teamInfo">';
		$body .= drawWinsInputForMatch($log,$matchValue,$errors);
		$body .= '</div>';
		$body .= '<div id="playersForTeams">';
		$body .= drawPlayersForMatch($log,$matchValue,$errors);
		$body .= '</div>'."\r\n";


	}else{
		$body .= ' <div id="winsInput" class="teamInfo" style="display:none">';
		$body .= '</div>';
		$body .= '<div id="playersForTeams" style="display:none">';
		$body .= '</div>'."\r\n";
		$body .= '  <div class="additionalNotes dl-input" style="display:none">';
		$body .= '  </div>';
	}

	$body .= '<div><input type="submit" name="BTN_SUBMIT" value="Submit"/>';
	$body .= '<input type="submit" name="BTN_BACK" value="Back"/></div>';
	$body .= '</div>';

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
	}else if(isset($_POST['scoresSubmitted'])){
		if(!(isset($_POST['additionalNotes']) && $_POST['additionalNotes']!="")){
			$errors = setError('additionalNotes',"Additional Notes required.",$errors);
		}


	}else{
	if($_POST['homeTeamWins']==null){
					$errors = setError("homeTeamWins","Home Team, Games Won field is required.",$errors);
		}

		if($_POST['homeTeamWins']!=null){

			if(!is_numeric($_POST['homeTeamWins'])){
				$errors = setError("homeTeamWins","Home team games won must be a number.",$errors);

			}else{
				$homeTeamWins = intval($_POST['homeTeamWins']);
				if($homeTeamWins>24){
					$errors = setError("homeTeamWins","Home team games won, a team cannot have more than 24 wins for a night.",$errors);
				}
			}
		}
		if($_POST['visitingTeamWins']==null){
							$errors = setError("visitingTeamWins","Visiting Team, Games Won field is required.",$errors);
				}

		if($_POST['visitingTeamWins']!=null){
			if(!is_numeric($_POST['visitingTeamWins'])){
				$errors = setError("visitingTeamWins","Visiting team games won must be a number.",$errors);
			}else{
				$visitingTeamWins = intval($_POST['visitingTeamWins']);
				if($visitingTeamWins>24){
					$errors = setError("visitingTeamWins","Visiting team games won, a team cannot have more than 24 wins for a night.",$errors);
				}
			}
		}

		if($_POST['homeTeamWins']!=null and $_POST['visitingTeamWins']!=null){
			$homeTeamWins = intval($_POST['homeTeamWins']);
			$visitingTeamWins = intval($_POST['visitingTeamWins']);
			$totalWins = $homeTeamWins+$visitingTeamWins;
			if($totalWins>36){
				$errors = setError("homeTeamWins","It is not possible to have more than 36 total wins for a night.",$errors);
			}
			if($totalWins<24){
				$errors = setError("homeTeamWins","It is not possible to have less than 24 total wins for a night.",$errors);
			}
		}


		$homeTeamPlayers = getPlayersForTeam($log, $_POST["homeTeam"]);
		$visitingTeamPlayers = getPlayersForTeam($log, $_POST["visitingTeam"]);

		foreach ($homeTeamPlayers as $i => $player) {
			$playerId=$player->getPlayerId();
			$fieldName = 'player'.$playerId.'points';
			if(isset($_POST[$fieldName]) && $_POST[$fieldName]!=""){
				if(!is_numeric($_POST[$fieldName])){
				$errors = setError($fieldName,"Personal points must be a number.",$errors);
				}
				//this is for games played
				$gamesPlayedFieldName = 'player'.$playerId.'games';
				if(!(isset($_POST[$gamesPlayedFieldName])) || $_POST[$gamesPlayedFieldName]==""){
					//if the players games are not set.
					$errors = setError($gamesPlayedFieldName,"Games Played is required if personal point are entered.",$errors);
				} else if(!is_numeric($_POST[$gamesPlayedFieldName])){
					$errors = setError($gamesPlayedFieldName,"Games Played must be a number.",$errors);
				}				
			}			
		}

		foreach ($visitingTeamPlayers as $i => $player) {
			$playerId=$player->getPlayerId();
			$fieldName = 'player'.$playerId.'points';
			if(isset($_POST[$fieldName]) && $_POST[$fieldName]!=""){
				if(!is_numeric($_POST[$fieldName])){
					$errors = setError($fieldName,"Personal points must be a number.",$errors);
				}
				//this is for games played
				$gamesPlayedFieldName = 'player'.$playerId.'games';
				if(!(isset($_POST[$gamesPlayedFieldName])) || $_POST[$gamesPlayedFieldName]==""){
					//if the players games are not set.
					$errors = setError($gamesPlayedFieldName,"Games Played is required if personal point are entered.",$errors);
				} else if(!is_numeric($_POST[$gamesPlayedFieldName])){
					$errors = setError($gamesPlayedFieldName,"Games Played must be a number.",$errors);
				}	
			}
		}


	}


return $errors;
}



function processForm($log){
	$output ="";

	if(isset($_POST['scoresSubmitted'])){
		$conn = getDBiConnection($log);
				
		$to ='jimmy@kingofmonkeys.com';
		$subject = $_SESSION['username'].' has submitted this weeks scores';
		$body = $_SESSION['username'].' has submitted this weeks scores: '."\r\n"."\r\n";
		$body .= "Special Shots:"."\r\n";
		for($i = 1;$i<10;$i += 1){
			if(isset($_POST['specialShotPlayerName'.$i]) && $_POST['specialShotPlayerName'.$i]!="NONE"){
				$str = "Special Shot PlayerName".$i." found: ".$_POST['specialShotPlayerName'.$i].", Shot Type: ".$_POST['specialShotType'.$i].", Shot Value: ".$_POST['specialShotValue'.$i]."\r\n";
				$log->LogInfo($str);
				$body .= $str;
				if($_POST['specialShotType'.$i]=="1"||$_POST['specialShotType'.$i]=="5"){
				$normShotValue='null';
				$shotValue='null';			
				}else{
				$shotValue=$_POST['specialShotValue'.$i];
				if(strlen($shotValue)>2){
				$normShotValue=$shotValue;
				}else{
				$normShotValue="0".$shotValue;
				}			
				}
				
				
		$insertSpecialSQL = "INSERT INTO player_shots (player_id, week_number, shotId, shotvalue, normshotvalue) VALUES (".$_POST['specialShotPlayerName'.$i].",".$_POST['week'].", ".$_POST['specialShotType'.$i].",".$shotValue.",".$normShotValue.")";
		$log->LogInfo("Trying to insert player special shots: sql=".$insertSpecialSQL);			
		$a5 = mysqli_query($conn,$insertSpecialSQL);

		if(!$a5){
				$log->LogError("There was an error trying to insert player special shots: playerid=".$_POST['specialShotPlayerName'.$i]);										
		}
				
			}
		}		
		
		
		//we need to save the additionalNotes
		$log->LogInfo("Additional Notes founds: ".$_POST['additionalNotes']);
		$body .= "\r\n".$_SESSION['username'].' has submitted additional notes with this weeks scores notes: '."\r\n";
		$body .= $_POST['additionalNotes'];		
		
		sendEmail($to, $subject, $body);

		$addNotesQuery = "INSERT INTO notes (userId, week_number, notes) VALUES (".$_SESSION['userid'].",".$_POST['week'].", '".mysql_real_escape_string($_POST['additionalNotes'])."');";
		$a5 = mysqli_query($conn,$addNotesQuery);
		if(!$a5){
			$log->LogError("There was an error trying to insert additional notes: sql=".$addNotesQuery);
			header("Location: ErrorPage.php");
		}else{
			$output .= displaySuccessPage($log);
		}
	}else{
		//form submitted and passed validation
		$conn = getDBiConnection($log);
		mysqli_autocommit($conn, FALSE);
		$log->LogDebug("Scoresheet.php form has been submitted and passed validation");
		$log->LogInfo("Scoresheet.php submitted with the following values.  Week: ".$_POST['week'].", Home Team: ".$_POST['homeTeam'].", Visiting Team: ".$_POST['visitingTeam'].", Home Team Wins: ".$_POST['homeTeamWins'].", Visiting Team Wins: ".$_POST['visitingTeamWins']);
		$insertWinsSQL = "INSERT INTO teamstats (teamid, week, wins, losses) VALUES (".$_POST['homeTeam'].",".$_POST['week'].", ".$_POST['homeTeamWins'].", ".$_POST['visitingTeamWins'].")";
		$a1 = mysqli_query($conn,$insertWinsSQL);
		if(!$a1){
				$log->LogError("There was an error trying to insert team stats: sql=".$insertWinsSQL);
		}
		$insertWinsSQL ="INSERT INTO teamstats (teamid, week, wins, losses) VALUES (".$_POST['visitingTeam'].",".$_POST['week'].", ".$_POST['visitingTeamWins'].", ".$_POST['homeTeamWins'].")";
		$a2 = mysqli_query($conn,$insertWinsSQL);

		if(!$a2){
			$log->LogError("There was an error trying to insert team stats: sql=".$insertWinsSQL);
		}

		$homeTeamPlayers = getPlayersForTeam($log, $_POST['homeTeam']);
		$visitingTeamPlayers = getPlayersForTeam($log, $_POST['visitingTeam']);
		//submit the home team personals
		$isSQLError = FALSE;
		foreach ($homeTeamPlayers as $i => $player) {
			$playerId=$player->getPlayerId();

			if(isset($_POST['player'.$playerId.'points']) && $_POST['player'.$playerId.'points']!=""){
				$log->LogInfo("Points submitted for player id: ".$playerId." Points: ".$_POST['player'.$playerId.'points']);
				$insertPointsSQL = "INSERT INTO player_stats (player_id, week_number,personal_points, games_played) VALUES (".$playerId.",".$_POST['week'].", ".$_POST['player'.$playerId.'points'].", ".$_POST['player'.$playerId.'games'].")";
				$a3 = mysqli_query($conn,$insertPointsSQL);
				if(!$a3){
					$log->LogError("There was an error trying to insert player stats: sql=".$insertPointsSQL);
					$isSQLError = TRUE;
				}
			}else if($_POST['player'.$playerId.'points']==""){
				$log->LogInfo("Player with id ".$playerId." on form but left blank, assuming didn't play this week");
			}
		}

		//submit the visiting team personals
		foreach ($visitingTeamPlayers as $i => $player) {
			$playerId=$player->getPlayerId();
			if(isset($_POST['player'.$playerId.'points']) && $_POST['player'.$playerId.'points']!=""){
				$log->LogInfo("Points submitted for player id: ".$playerId." Points: ".$_POST['player'.$playerId.'points']);
				$insertPointsSQL = "INSERT INTO player_stats (player_id, week_number,personal_points, games_played) VALUES (".$playerId.",".$_POST['week'].", ".$_POST['player'.$playerId.'points'].", ".$_POST['player'.$playerId.'games'].")";
				$a4 = mysqli_query($conn,$insertPointsSQL);

				if(!$a4){
					$log->LogError("There was an error trying to insert player stats for values: playerid=".$_POST['player'.$playerId.'points']);
					$log->LogError("There was an error trying to insert player stats: sql=".$insertPointsSQL);
					$isSQLError = TRUE;
				}
			}else if($_POST['player'.$playerId.'points']==""){
				$log->LogInfo("Player with id ".$playerId." on form but left blank, assuming didn't play this week");
			}

		}

		
		$to ='jimmy@kingofmonkeys.com';
			$subject = $_SESSION['username'].' has submitted this weeks scores';
			$body = $_SESSION['username'].' has submitted this weeks scores: '."\r\n"."\r\n";
			$body .= "Special Shots:"."\r\n";
			for($i = 1;$i<10;$i += 1){
			if(isset($_POST['specialShotPlayerName'.$i]) && $_POST['specialShotPlayerName'.$i]!="NONE"){
				$str = "Special Shot PlayerName".$i." found: ".$_POST['specialShotPlayerName'.$i].", Shot Type: ".$_POST['specialShotType'.$i].", Shot Value: ".$_POST['specialShotValue'.$i]."\r\n";
				$log->LogInfo($str);
				$body .= $str;
				if($_POST['specialShotType'.$i]=="1"||$_POST['specialShotType'.$i]=="5"){
				$normShotValue='null';
				$shotValue='null';			
				}else{
				$shotValue=$_POST['specialShotValue'.$i];
				if(strlen($shotValue)>2){
				$normShotValue=$shotValue;
				}else{
				$normShotValue="0".$shotValue;
				}			
				}
				
				
				$insertSpecialSQL = "INSERT INTO player_shots (player_id, week_number, shotId, shotvalue, normshotvalue) VALUES (".$_POST['specialShotPlayerName'.$i].",".$_POST['week'].", ".$_POST['specialShotType'.$i].",".$shotValue.",".$normShotValue.")";
				$log->LogInfo("Trying to insert player special shots: sql=".$insertSpecialSQL);			
					$a5 = mysqli_query($conn,$insertSpecialSQL);

					if(!$a5){
						$log->LogError("There was an error trying to insert player special shots: playerid=".$_POST['specialShotPlayerName'.$i]);										
					}
				
			}
			}		
		

		if(isset($_POST['additionalNotes']) && $_POST['additionalNotes']!=""){
			//we need to save the additionalNotes
			$log->LogInfo("Additional Notes founds: ".$_POST['additionalNotes']);
			
			$body .= "\r\n".$_SESSION['username'].' has submitted additional notes with this weeks scores notes: '."\r\n";
			$body .= $_POST['additionalNotes'];			

			$addNotesQuery = "INSERT INTO notes (userId, week_number, notes) VALUES (".$_SESSION['userid'].",".$_POST['week'].", '".mysql_real_escape_string($_POST['additionalNotes'])."');";
			$a5 = mysqli_query($conn,$addNotesQuery);
			if(!$a5){
				$log->LogError("There was an error trying to insert additional notes: sql=".$addNotesQuery);
			}

		}else{
			$a5=TRUE;
		}

		sendEmail($to, $subject, $body);
		
		if($a1 && $a2 && !$isSQLError && $a5){
			$log->LogDebug("Commiting transaction");
			mysqli_commit($conn);
			mysqli_autocommit($conn, TRUE);
			$output .= displaySuccessPage($log);
		}else{
		$log->LogDebug("Rolling back transaction");
			mysqli_rollback($conn);
			mysqli_autocommit($conn,TRUE);
			header("Location: ErrorPage.php");
		}
	}
	return $output;
}


function getWeeks($log){

$weeks = array();
$conn = getDBConnection($log);
  $result = mysql_query("SELECT * FROM weeks");

  if(!$result){
    die( 'connection failed');
  }
  while($row = mysql_fetch_array($result))
  {
    $week =new Week();
    $week->setWeekNumber($row['week']);
    $week->setWeekDate($row['date']);
    $weeks[] = $week;
  }

  mysql_close($conn);

  return $weeks;

}

?>