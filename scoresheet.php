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
$week = getCurrentWeek($log);

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
		$errors = validateForm($log);
		if(count($errors)>0){
			$log->LogError("Errors found in Scoresheet.php submitted with the following values.  Week: ".$_POST['week'].", Home Team: ".$_POST['homeTeam'].", Visiting Team: ".$_POST['visitingTeam'].", Home Team Wins: ".$_POST['homeTeamWins'].", Visiting Team Wins: ".$_POST['visitingTeamWins']);
			$output .= displayErrors($errors);
			$output .= drawScoreSheetForm($log,$errors,$week,$homeTeam,$visitingTeam);
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

			$homeTeamPlayers = getPlayersForTeam($log, $homeTeam->getTeamId());
			$visitingTeamPlayers = getPlayersForTeam($log, $visitingTeam->getTeamId());

			//submit the home team personals
			$isSQLError = FALSE;
			foreach ($homeTeamPlayers as $i => $player) {
				$playerId=$player->getPlayerId();

				if(isset($_POST['player'.$playerId.'points']) && $_POST['player'.$playerId.'points']!=""){
					$log->LogInfo("Points submitted for player id: ".$playerId." Points: ".$_POST['player'.$playerId.'points']." Games Played: ".$_POST['player'.$playerId.'games']);
					$insertPointsSQL = "INSERT INTO player_stats (player_id, week_number,personal_points, games_played,wins) VALUES (".$playerId.",".$_POST['week'].", ".$_POST['player'.$playerId.'points'].", ".$_POST['player'.$playerId.'games'].", ".$_POST['player'.$playerId.'wins'].")";
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
					$log->LogInfo("Points submitted for player id: ".$playerId." Points: ".$_POST['player'.$playerId.'points']." Games Played: ".$_POST['player'.$playerId.'games']);
					$insertPointsSQL = "INSERT INTO player_stats (player_id, week_number,personal_points, games_played, wins) VALUES (".$playerId.",".$_POST['week'].", ".$_POST['player'.$playerId.'points'].", ".$_POST['player'.$playerId.'games'].", ".$_POST['player'.$playerId.'wins'].")";

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
				$log->LogInfo("Additional Notes found: ".$_POST['additionalNotes']);
				$body .= "\r\n".$_SESSION['username'].' has submitted additional notes with this weeks scores notes: '."\r\n";
				$body .= $_POST['additionalNotes'];				

				$addNotesQuery = "INSERT INTO notes (userId, week_number, notes) VALUES (".$_SESSION['userid'].",".$_POST['week'].", '".mysql_real_escape_string($_POST['additionalNotes'])."')";
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
		$conn = getDBConnection($log);
		$result = mysql_query("select * from teamstats where teamid='".$homeTeam->getTeamId()."' and week=".$week->getWeekNumber());

		$row = mysql_fetch_array($result,MYSQL_BOTH);

		if($row['teamid']==$homeTeam->getTeamId()){
			$errors = setError("none","Scores for this week have already been submitted.  However, you can still submit more additional notes.",$errors);
	  		$output .= displayErrors($errors);
	  		$output .= drawVisitingTeamForm($log,$errors,$week ,$homeTeam,$visitingTeam);
	  	}else{
			$output .= drawScoreSheetForm($log,$errors,$week ,$homeTeam,$visitingTeam);
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
	$body .=  "I'm sorry but you do not currently have a team assoicated with you.<br/><br/>";
	$body .=  '<input type="submit" name="BTN_BACK" value="Back"/>';
	$body .=  '</div>';
	$body .=  '</form>';
	$output .= drawContainer($header,$body);	
	return $output;
}


function drawOutOfSeason($log){
	$seasonName = getSeasonName($log);
	$header = $seasonName.' Season';	
	$body = "";		
	$body .=  '<form action="./scoresheet.php" method="post">';
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
	$body .= '<form action="./scoresheet.php" method="post">';
	$body .= '<div><br/>';
	$body .= 'Success!  Weekly stats submitted.<br/><br/>';
	$body .= '<input type="submit" name="BTN_BACK" value="Back"/>';
	$body .= '</div>';
	$body .= '</form>';
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




function drawScoreSheetForm($log,$errors,$week,$homeTeam,$visitingTeam){
	$output = "";
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

	$seasonName = getSeasonName($log);
	$header = $seasonName.' Season';
	
	$body = "";
	$body .= '<form action="./scoresheet.php" method="post">';
	//$output .= '<div class="seasonInfo">';

	$body .= '  <div class="dropdown dl-input">';
	$body .= '<input type="hidden" name="week" value="'.$week->getWeekNumber().'"/>';
	$body .= 'Week '.$week->getWeekNumber().' - ('.date("n/j/Y", strtotime($week->getWeekDate())).')';
	$body .= '  </div>';
	//$output .= '</div>';
	$body .= '<div class="gameInfo">';
	$body .= ' <div class="teamInfo">';
	$body .= '   <div class="halfWidth">';
	$body .= '     <div class="textbox dl-input">';

	$body .= '<input type="hidden" name="homeTeam" value="'.$homeTeam->getTeamId().'"/>';
	$body .= '<h3>Home Team:</h3>';
	$body .= $homeTeam->getTeamName();


	$body .= '      </div>';
	$body .= '    </div>';
	$body .= '    <div class="halfWidth">';
	$body .= '      <div class="textbox dl-input">';

	$body .= '<input type="hidden" name="visitingTeam" value="'.$visitingTeam->getTeamId().'"/>';
	$body .= '<h3>Visiting Team:</h3>';
	$body .=  $visitingTeam->getTeamName();

	$body .= '     </div>';
	$body .= '    </div>';
	$body .= '    <div class="halfWidth">';
	$body .= '      <div class="textbox dl-input">';

	$body .= displayField('<label for="homeTeamWins">Games Won</label><input type="text" name="homeTeamWins" id="homeTeamWins" size="2" maxlength="2" value="'.$homeTeamWinsValue.'"/>',"homeTeamWins",$errors);

	$body .= '      </div>';
	$body .= '    </div>';
	$body .= '    <div class="halfWidth">';
	$body .= '     <div class="textbox dl-input">';
	$body .= displayField('<label for="visitingTeamWins">Games Won</label><input type="text" name="visitingTeamWins" id="visitingTeamWins" size="2" maxlength="2" value="'.$visitingTeamWinsValue.'"/>',"visitingTeamWins",$errors);

	$body .= '      </div>';
	$body .= '    </div>';
	$body .= '  </div>';
	$body .= '  <div class="playerInfo">';
	$body .= '    <h3>Player Points</h3>';
	$body .= '    <p class="instructions">Select a player from the list and enter the number of points that player received this week.</p>';
	$body .= '    <div id="homePlayers" class="halfWidth">';
	$body .= '      <h4>Home Team</h4>';

	$homeplayers = getPlayersForTeam($log, $homeTeam->getTeamId());
	$body .= '<table><tr><td width="150" class="playerTableHeader">Player</td><td class="playerTableHeader">Points</td><td class="playerTableHeader">Games Played</td><td class="playerTableHeader">Singles Won</td></tr>';
	foreach ($homeplayers as $i => $player) {
			$fieldValue = "";
			if(isset($_POST['player'.$player->getPlayerId().'points']) &&$_POST['player'.$player->getPlayerId().'points']!=null){
				$fieldValue=$_POST['player'.$player->getPlayerId().'points'];
			}
			$playerGamesValue ="";
			if(isset($_POST['player'.$player->getPlayerId().'games']) &&$_POST['player'.$player->getPlayerId().'games']!=null){
				$playerGamesValue=$_POST['player'.$player->getPlayerId().'games'];
			}
			$playerWinsValue ="";
			if(isset($_POST['player'.$player->getPlayerId().'wins']) &&$_POST['player'.$player->getPlayerId().'wins']!=null){
				$playerWinsValue=$_POST['player'.$player->getPlayerId().'wins'];
			}
			$body .= '<tr><td width="150">';
			$body .= displayField($player->getFirstName()." ".$player->getLastName().'</td><td><input type="text" name="player'.$player->getPlayerId().'points" id="player'.$player->getPlayerId().'points" size="3" maxlength="3" value="'.$fieldValue.'"/>'."\r\n","player".$player->getPlayerId()."points",$errors);
			$body .= '</td><td>';
			$body .= displayField('<input type="text" name="player'.$player->getPlayerId().'games" id="player'.$player->getPlayerId().'games" size="3" maxlength="3" value="'.$playerGamesValue.'"/>'."\r\n","player".$player->getPlayerId()."games",$errors);			
			$body .= '</td><td>';
			$body .= displayField('<input type="text" name="player'.$player->getPlayerId().'wins" id="player'.$player->getPlayerId().'wins" size="3" maxlength="3" value="'.$playerWinsValue.'"/>'."\r\n","player".$player->getPlayerId()."wins",$errors);			
			$body .= '</td></tr>';

		}
	$body .= '</table>';
	$body .= '    </div>';
	$body .= '    <div class="halfWidth">';
	$body .= '      <h4>Visiting Team</h4>';
	$visitplayers = getPlayersForTeam($log, $visitingTeam->getTeamId());
	$body .= '<table><tr><td width="150" class="playerTableHeader">Player</td><td class="playerTableHeader">Points</td><td class="playerTableHeader">Games Played</td><td class="playerTableHeader">Singles Won</td></tr>';
	foreach ($visitplayers as $i => $player) {
			$fieldValue = "";
			if(isset($_POST['player'.$player->getPlayerId().'points']) &&$_POST['player'.$player->getPlayerId().'points']!=null){
						$fieldValue=$_POST['player'.$player->getPlayerId().'points'];
			}
			$playerGamesValue ="";
			if(isset($_POST['player'.$player->getPlayerId().'games']) &&$_POST['player'.$player->getPlayerId().'games']!=null){
				$playerGamesValue=$_POST['player'.$player->getPlayerId().'games'];
			}
			$playerWinsValue ="";
			if(isset($_POST['player'.$player->getPlayerId().'wins']) &&$_POST['player'.$player->getPlayerId().'wins']!=null){
				$playerWinsValue=$_POST['player'.$player->getPlayerId().'wins'];
			}
			$body .= '<tr><td width="150">';
			$body .= displayField($player->getFirstName()." ".$player->getLastName().'</td><td><input type="text" name="player'.$player->getPlayerId().'points" id="player'.$player->getPlayerId().'points" size="3" maxlength="3" value="'.$fieldValue.'"/>'."\r\n","player".$player->getPlayerId()."points",$errors);
			$body .= '</td><td>';
			$body .= displayField('<input type="text" name="player'.$player->getPlayerId().'games" id="player'.$player->getPlayerId().'games" size="3" maxlength="3" value="'.$playerGamesValue.'"/>'."\r\n","player".$player->getPlayerId()."games",$errors);			
			$body .= '</td><td>';
			$body .= displayField('<input type="text" name="player'.$player->getPlayerId().'wins" id="player'.$player->getPlayerId().'wins" size="3" maxlength="3" value="'.$playerWinsValue.'"/>'."\r\n","player".$player->getPlayerId()."wins",$errors);			
			$body .= '</td></tr>';
		}
	$body .= '</table>';
	$body .= '  </div>';
	$body .= '</div>';
	
	
	$body .= '<div class="bedl-specialShots">';
	$body .= '<h3>Player Shots</h3>';
	$body .=  '<div class="bedl-inlineInputs">'."\r\n";
	
	$body .=  '<div class="bedl-repeatingFields bedl-inlineInputs" data-bedl-maxfieldsets="10">'."\r\n";
	
	$allplayers = array_merge($visitplayers,$homeplayers);
	
	$body .= generateShotFieldSet($log,$allplayers,"1",$errors);
	
	for($i = 2;$i<10;$i += 1){
		if(isset($_POST['specialShotPlayerName'.$i])){
		$body .= generateShotFieldSet($log,$allplayers,$i,$errors);
		
			$str = "Special Shot PlayerName".$i." found: ".$_POST['specialShotPlayerName'.$i].", Shot Type: ".$_POST['specialShotType'.$i].", Shot Value: ".$_POST['specialShotValue'.$i]."\r\n";
		
		}
	}
	
	
	$body .= '</div>'."\r\n";
	$body .= '</div>'."\r\n";
	$body .= '</div>'."\r\n";	
	
	
	$body .= '  <div class="additionalNotes dl-input">';
	$body .= '    <label for="additionalNotes">Additional Notes</label>';
	$body .= '    <textarea name="additionalNotes" id="additionalNotes" cols="60" rows="10" value="'.$additionalNotesValue.'">'.$additionalNotesValue.'</textarea>';
	$body .= '  </div>';
	$body .= '<input type="submit" name="BTN_SUBMIT" value="Submit"/>';
	$body .= '<input type="submit" name="BTN_BACK" value="Back"/>';
	

	$body .= '</form>';
	
	
	$output .= drawContainer($header,$body);
	
	return $output;

}



function getHomeTeamFromVisitingTeam($log,$week,$visitingTeamId){


$conn = getDBConnection($log);

$result = mysql_query("select teamid, teamname, division from schedule, teams where week=".$week->getWeekNumber()." and visitingteamid=".$visitingTeamId." and schedule.hometeamid=teams.teamid");
if(!$result){

    return null;
  }
$row = mysql_fetch_array($result);

if($row['teamid']==null)
{

return null;
}else{

$team =new Team();
$team->setTeamId($row['teamid']);
$team->setTeamName($row['teamname']);
$team->setDivision($row['division']);


return $team;
}
}


function validateForm($log){
$errors = array();
if($_POST['homeTeamWins']==null){
				$errors = setError("homeTeamWins","Home Team, Games Won field is required.",$errors);
	}
	if($_POST['visitingTeamWins']==null){
				$errors = setError("visitingTeamWins","Visiting Team, Games Won field is required.",$errors);
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
			//this means the players point are set.
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
			//This if for wins			/
			$winsFieldName = 'player'.$playerId.'wins';
			if(!(isset($_POST[$winsFieldName])) || $_POST[$winsFieldName]==""){
					//if the players games are not set.
					$errors = setError($winsFieldName,"Wins is required if personal point are entered, you may enter 0 wins.",$errors);
			} else if(!is_numeric($_POST[$winsFieldName])){
				$errors = setError($winsFieldName,"Wins must be a number.",$errors);
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
			}else if(!is_numeric($_POST[$gamesPlayedFieldName])){
				$errors = setError($gamesPlayedFieldName,"Games Played must be a number.",$errors);
			}
			//This if for wins			/
			$winsFieldName = 'player'.$playerId.'wins';
			if(!(isset($_POST[$winsFieldName])) || $_POST[$winsFieldName]==""){
					//if the players games are not set.
					$errors = setError($winsFieldName,"Wins is required if personal point are entered, you may enter 0 wins.",$errors);
			} else if(!is_numeric($_POST[$winsFieldName])){
				$errors = setError($winsFieldName,"Wins must be a number.",$errors);
			}
		}
	}

	
return $errors;
}

?>