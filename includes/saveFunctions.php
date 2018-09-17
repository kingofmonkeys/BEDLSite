<?php


function saveScoreSheet($homeTeam,$visitingTeam,$log){
	
	$conn = getDBiConnection($log);
			$autoCommit = mysqli_autocommit($conn, FALSE);	
			$log->LogInfo("AutoCommit: ".$autoCommit);
			$log->LogDebug("Scoresheet.php form has been submitted and passed validation");
			$log->LogInfo("Scoresheet.php submitted with the following values.  Week: ".$_POST['week'].", Home Team: ".$_POST['homeTeam'].", Visiting Team: ".$_POST['visitingTeam']);
			$isSQLError = FALSE;
			
			if(!saveSingles01('1',$_POST['week'],$conn,$log)){
				$isSQLError = TRUE;
			}			
			if(!saveSingles01('2',$_POST['week'],$conn,$log)){
				$isSQLError = TRUE;
			}			
			if(!saveSingles01('3',$_POST['week'],$conn,$log)){
				$isSQLError = TRUE;
			}			
			if(!saveSingles01('4',$_POST['week'],$conn,$log)){
				$isSQLError = TRUE;
			}
			
			if(!saveSinglesCricket('1',$_POST['week'],$conn,$log)){
				$isSQLError = TRUE;
			}			
			if(!saveSinglesCricket('2',$_POST['week'],$conn,$log)){
				$isSQLError = TRUE;
			}			
			if(!saveSinglesCricket('3',$_POST['week'],$conn,$log)){
				$isSQLError = TRUE;
			}			
			if(!saveSinglesCricket('4',$_POST['week'],$conn,$log)){
				$isSQLError = TRUE;
			}
			
			//save doubles games here
			
			if(!saveDoubles01('1',$_POST['week'],$conn,$log)){
				$isSQLError = TRUE;
			}
			if(!saveDoubles01('2',$_POST['week'],$conn,$log)){
				$isSQLError = TRUE;
			}
			
			if(!saveDoublesCricket('1',$_POST['week'],$conn,$log)){
				$isSQLError = TRUE;
			}
			if(!saveDoublesCricket('2',$_POST['week'],$conn,$log)){
				$isSQLError = TRUE;
			}
		
			

			$homeTeamPlayers = getPlayersForTeam($log, $homeTeam->getTeamId());
			$visitingTeamPlayers = getPlayersForTeam($log, $visitingTeam->getTeamId());

			//submit the home team personals
			
			foreach ($homeTeamPlayers as $i => $player) {
				$playerId=$player->getPlayerId();
				$singles01Points = getFieldValue('player'.$player->getPlayerId().'Singles01Points');
				$singlesCricketPoints = getFieldValue('player'.$player->getPlayerId().'SinglesCricketPoints');
				$doubles01Points = getFieldValue('player'.$player->getPlayerId().'Doubles01Points');
				$doublesCricketPoints = getFieldValue('player'.$player->getPlayerId().'DoublesCricketPoints');
			
				if($singles01Points==""){
					$singles01Points=0;
				}
				if($singlesCricketPoints==""){
					$singlesCricketPoints=0;
				}
				if($doubles01Points==""){
					$doubles01Points=0;
				}
				if($doublesCricketPoints==""){
					$doublesCricketPoints=0;
				}
			
				$log->LogInfo("Points submitted for player id: ".$playerId." Singles 01 Points: ".$singles01Points." Singles Cricket points: ".$singlesCricketPoints." Doubles 01 points: ".$doubles01Points." Doubles Cricket points: ".$doublesCricketPoints);
				if(!savePersonalPoints($playerId,$_POST['week'],$singles01Points,$singlesCricketPoints,$doubles01Points,$doublesCricketPoints,$conn,$log)){
					$isSQLError = TRUE;
				}					
			}

			//submit the visiting team personals
			foreach ($visitingTeamPlayers as $i => $player) {
				$playerId=$player->getPlayerId();
				$singles01Points = getFieldValue('player'.$player->getPlayerId().'Singles01Points');
				$singlesCricketPoints = getFieldValue('player'.$player->getPlayerId().'SinglesCricketPoints');
				$doubles01Points = getFieldValue('player'.$player->getPlayerId().'Doubles01Points');
				$doublesCricketPoints = getFieldValue('player'.$player->getPlayerId().'DoublesCricketPoints');
			
				if($singles01Points==""){
					$singles01Points=0;
				}
				if($singlesCricketPoints==""){
					$singlesCricketPoints=0;
				}
				if($doubles01Points==""){
					$doubles01Points=0;
				}
				if($doublesCricketPoints==""){
					$doublesCricketPoints=0;
				}
			
				$log->LogInfo("Points submitted for player id: ".$playerId." Singles 01 Points: ".$singles01Points." Singles Cricket points: ".$singlesCricketPoints." Doubles 01 points: ".$doubles01Points." Doubles Cricket points: ".$doublesCricketPoints);
				if(!savePersonalPoints($playerId,$_POST['week'],$singles01Points,$singlesCricketPoints,$doubles01Points,$doublesCricketPoints,$conn,$log)){
					$isSQLError = TRUE;
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
				if($_POST['specialShotType'.$i]=="5" || $_POST['specialShotType'.$i]=="6"){
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
						$isSQLError = TRUE;					
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
					$isSQLError = TRUE;		
				}

			}
			
			//need to save that the stats were input 
			$updateScoreEnteredSQL = "update schedule set score_entered='true' where week='".$_POST['week']."' and hometeamid='".$_POST['homeTeam']."' and visitingteamid='".$_POST['visitingTeam']."'";
			$u1	= mysqli_query($conn,$updateScoreEnteredSQL);
			if(!$u1){
				$log->LogError("There was an error trying to update that scores were entered: sql=".$updateScoreEnteredSQL);
				$isSQLError = TRUE;
			}
			
			sendEmail($to, $subject, $body);

			if(!$isSQLError){
				$log->LogDebug("Commiting transaction");
				mysqli_commit($conn);
				mysqli_close($conn);
				return displaySuccessPage($log);

			}else{
				$log->LogDebug("Rolling back transaction");
				$rolledBack = mysqli_rollback($conn);
				$log->LogDebug("Transaction Rolled back: ".$rolledBack);
				mysqli_close($conn);
				header("Location: ErrorPage.php");
			}	
}


function savePersonalPoints($playerId,$week,$singles01Points,$singlesCricketPoints,$doubles01Points,$doublesCricketPoints,$conn,$log){
	$success = TRUE;
	
	$insertPointsSQL = "INSERT INTO player_stats (player_id, week_number,s_01_points,s_cricket_points,d_01_points,d_cricket_points) ";
	$insertPointsSQL .= "VALUES (".$playerId.",".$week.", ".$singles01Points.", ".$singlesCricketPoints.", ".$doubles01Points.", ".$doublesCricketPoints.")";
				
	$a1 = mysqli_query($conn,$insertPointsSQL);
	if(!$a1){
		$log->LogError("There was an error trying to insert peronal points for player ".$playerId.": sql=".$insertPointsSQL);
		$success = FALSE;
	}
	return $success;
}


function saveDoublesCricket($index,$week,$conn,$log){
	$success = TRUE;
	$doublesCricketHomePlayer1 = getFieldValue("doublesCricket".$index."HomePlayer1");
	$doublesCricketHomePlayer2 = getFieldValue("doublesCricket".$index."HomePlayer2");
	$doublesCricketHomeWins = getFieldValue("doublesCricket".$index."HomeWins");
	$doublesCricketVisitPlayer1 = getFieldValue("doublesCricket".$index."VisitPlayer1");
	$doublesCricketVisitPlayer2 = getFieldValue("doublesCricket".$index."VisitPlayer2");
	$doublesCricketVisitWins = getFieldValue("doublesCricket".$index."VisitWins");	
	
	$log->LogInfo("Doubles cricket game info submitted: Home team id: ".$_POST['homeTeam']." Visiting team id: ".$_POST['visitingTeam']." Home player1 id".$doublesCricketHomePlayer1." Home player2 id".$doublesCricketHomePlayer2." Home wins: ".$doublesCricketHomeWins." Visiting player 1 id: ".$doublesCricketVisitPlayer1." Visiting player 2 id: ".$doublesCricketVisitPlayer2." Visiting wins: ".$doublesCricketVisitWins);				
	
	
	$insertDoubleCricketSQL = "insert into doubles_games (week,game_type,home_team_id,visit_team_id,home_player1_id,home_player2_id,home_wins,visit_player1_id,visit_player2_id,visit_wins) values ";
	$insertDoubleCricketSQL .= "(".$week.", '2', ".$_POST['homeTeam'].",".$_POST['visitingTeam'].",".$doublesCricketHomePlayer1.",".$doublesCricketHomePlayer2.",".$doublesCricketHomeWins.", ".$doublesCricketVisitPlayer1.", ".$doublesCricketVisitPlayer2.", ".$doublesCricketVisitWins.")";
	$a1 = mysqli_query($conn,$insertDoubleCricketSQL);
	if(!$a1){
		$log->LogError("There was an error trying to insert doubles Cricket game ".$index." stats: sql=".$insertDoubleCricketSQL);
		$success = FALSE;
	}
	return $success;
}



function saveDoubles01($index,$week,$conn,$log){
	$success = TRUE;
	$doubles01HomePlayer1 = getFieldValue("doubles01".$index."HomePlayer1");
	$doubles01HomePlayer2 = getFieldValue("doubles01".$index."HomePlayer2");
	$doubles01HomeWins = getFieldValue("doubles01".$index."HomeWins");
	$doubles01VisitPlayer1 = getFieldValue("doubles01".$index."VisitPlayer1");
	$doubles01VisitPlayer2 = getFieldValue("doubles01".$index."VisitPlayer2");
	$doubles01VisitWins = getFieldValue("doubles01".$index."VisitWins");	
	
	$log->LogInfo("Doubles 01 game info submitted: Home team id: ".$_POST['homeTeam']." Visiting team id: ".$_POST['visitingTeam']." Home player1 id".$doubles01HomePlayer1." Home player2 id".$doubles01HomePlayer2." Home wins: ".$doubles01HomeWins." Visiting player 1 id: ".$doubles01VisitPlayer1." Visiting player 2 id: ".$doubles01VisitPlayer2." Visiting wins: ".$doubles01VisitWins);				
	
			
	$insertDouble01SQL = "insert into doubles_games (week,game_type,home_team_id,visit_team_id,home_player1_id,home_player2_id,home_wins,visit_player1_id,visit_player2_id,visit_wins) values ";
	$insertDouble01SQL .= "(".$week.", '1', ".$_POST['homeTeam'].",".$_POST['visitingTeam'].",".$doubles01HomePlayer1.",".$doubles01HomePlayer2.",".$doubles01HomeWins.", ".$doubles01VisitPlayer1.", ".$doubles01VisitPlayer2.", ".$doubles01VisitWins.")";
	$a1 = mysqli_query($conn,$insertDouble01SQL);
	if(!$a1){
		$log->LogError("There was an error trying to insert doubles 01 game ".$index." stats: sql=".$insertDouble01SQL);
		$success = FALSE;
	}
	return $success;
}


function saveSinglesCricket($index,$week,$conn,$log){
	$success = TRUE;
	$singleCricket1HomePlayer = getFieldValue("singleCricket".$index."HomePlayer");
	$singleCricket1HomeWins = getFieldValue("singleCricket".$index."HomeWins");
	$singleCricket1VisitPlayer = getFieldValue("singleCricket".$index."VisitPlayer");
	$singleCricket1VisitWins = getFieldValue("singleCricket".$index."VisitWins");
			
	$log->LogInfo("Singles cricket game info submitted: Home team id: ".$_POST['homeTeam']." Visiting team id: ".$_POST['visitingTeam']." Home player id".$singleCricket1HomePlayer." Home wins: ".$singleCricket1HomeWins." Visiting player id: ".$singleCricket1VisitPlayer." Visiting wins: ".$singleCricket1VisitWins);				
	
	$insertSingleCricket1SQL = "insert into singles_games (week,game_type,home_team_id,visit_team_id,home_player_id,home_player_wins,visit_player_id,visit_player_wins) values ";
	$insertSingleCricket1SQL .= "(".$week.", '2', ".$_POST['homeTeam'].",".$_POST['visitingTeam'].",".$singleCricket1HomePlayer.",".$singleCricket1HomeWins.", ".$singleCricket1VisitPlayer.", ".$singleCricket1VisitWins.")";
	$a1 = mysqli_query($conn,$insertSingleCricket1SQL);
	if(!$a1){
		$log->LogError("There was an error trying to insert singles Cricket game ".$index." stats: sql=".$insertSingleCricket1SQL);
		$success = FALSE;
	}
	return $success;
}


function saveSingles01($index,$week,$conn,$log){
	$success = TRUE;
	$single011HomePlayer = getFieldValue("single01".$index."HomePlayer");
	$single011HomeWins = getFieldValue("single01".$index."HomeWins");
	$single011VisitPlayer = getFieldValue("single01".$index."VisitPlayer");
	$single011VisitWins = getFieldValue("single01".$index."VisitWins");
			
	$log->LogInfo("Singles 01 game info submitted: Home team id: ".$_POST['homeTeam']." Visiting team id: ".$_POST['visitingTeam']." Home player id".$single011HomePlayer." Home wins: ".$single011HomeWins." Visiting player id: ".$single011VisitPlayer." Visiting wins: ".$single011VisitWins);				
			
	$insertSingle011SQL = "insert into singles_games (week,game_type,home_team_id,visit_team_id,home_player_id,home_player_wins,visit_player_id,visit_player_wins) values ";
	$insertSingle011SQL .= "(".$week.", '1', ".$_POST['homeTeam'].",".$_POST['visitingTeam'].",".$single011HomePlayer.",".$single011HomeWins.", ".$single011VisitPlayer.", ".$single011VisitWins.")";
	$a1 = mysqli_query($conn,$insertSingle011SQL);
	if(!$a1){
		$log->LogError("There was an error trying to insert singles 01 game ".$index." stats: sql=".$insertSingle011SQL);
		$success = FALSE;
	}
	return $success;
}


?>