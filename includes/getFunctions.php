<?php

function getSeasonName($log){
	$conn = getDBConnection($log);
	$query = "select * from season_info";
	$seasonsInfo = mysql_query($query) or die("Failed Query of " . $query);  //do the query
	$seasonInfo = mysql_fetch_array($seasonsInfo);
	$seasonName=$seasonInfo[0];
	
	return $seasonName;
}

function getUsersRoles($log,$username){
	$conn = getDBConnection($log);
	$result = mysql_query("select roleid from rolemapping,users where rolemapping.userid = users.ID and users.username='".$_SESSION['username']."'");
	if(!$result){
		$log->LogError("Memebers page failed because something is wrong with the database");
		die( 'connection failed');
	}

	$roles = array();

	while($thisrow=mysql_fetch_array($result)){
		$roles[] = $thisrow['roleid'];
	}

	return $roles;
}

function getTeamForUser($log,$username){
	$conn = getDBConnection($log);

   	$result = mysql_query("select teamid, teamname,teams.division from teams,players, users where users.username='".$username."' and players.player_id = users.playerid and players.team_id = teams.teamid");

   	if(!$result){
   		die( 'connection failed');
   	}
   	$row = mysql_fetch_array($result);

	if($row['teamid']==null){
		$team=null;
	}else{
		$team =new Team();
    	$team->setTeamId($row['teamid']);
    	$team->setTeamName($row['teamname']);
    	$team->setDivision($row['division']);
	}
    return $team;
}

function getPlayersForTeam($log, $teamid){
$conn = getDBConnection($log);

  $result = mysql_query("SELECT * FROM players where team_id=".$teamid." ORDER BY last_name" );

  if(!$result){
    die( 'connection failed');
  }
  while($row = mysql_fetch_array($result))
  {
    $player =new Player();
    $player->setPlayerId($row['player_id']);
    $player->setFirstName($row['first_name']);
    $player->setLastName($row['last_name']);
    $player->setTeamId($row['team_id']);
    $players[] = $player;
  }



  return $players;
}

function getTeams($log){
 $conn = getDBConnection($log);

  $result = mysql_query("SELECT * FROM teams");

  if(!$result){
    die( 'connection failed');
  }
  while($row = mysql_fetch_array($result))
  {
    $team =new Team();
    $team->setTeamId($row['teamid']);
    $team->setTeamName($row['teamname']);
    $team->setDivision($row['division']);
    $teams[] = $team;
  }
  return $teams;
}



function getVisitingTeam($log,$week,$homeTeamId){


$conn = getDBConnection($log);

$result = mysql_query("select teamid, teamname, division from schedule, teams where week=".$week->getWeekNumber()." and hometeamid=".$homeTeamId." and schedule.visitingteamid=teams.teamid");
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

function getCurrentWeek($log){

	$currentDate = getCurrentDate($log);
	
	$cd = strtotime($currentDate);
	$minusSeven = date('Y-m-d', mktime(0,0,0,date('m',$cd),date('d',$cd)-7,date('Y',$cd)));


	$conn = getDBConnection($log);
  	$result = mysql_query("select * from weeks where date<='".$currentDate."' and date>'".$minusSeven."'");

  	if(!$result){
  	  die( 'connection failed');
  	}
 	$row = mysql_fetch_array($result);

	if($row['week']==null)
	{
		$log->LogError("No weeks found for current date: ".$currentDate);
		$week = null;
	}else{
	    $week =new Week();
	    $week->setWeekNumber($row['week']);
	    $week->setWeekDate($row['date']);
	}
	return $week;
}

function getCurrentDate($log){
$currentDate = date("Y-m-d");
//$currentDate = '2012-03-08';

return $currentDate;
}

function haveScoresBeenEntered($week,$homeTeamId,$log){	
	$conn = getDBiConnection($log);	
	$sql = "select * from schedule where week=".$week." and hometeamid=".$homeTeamId;
	$log->LogDebug("haveScoresBeenEntered sql: ". $sql);
	$result = mysqli_query($conn,$sql);	
	
	$row = mysqli_fetch_array($result);

	if($row['score_entered']=="true"){
		return true;
	}else{
		return false;
	}
}

function getHomeTeamFromVisitingTeam($log,$week,$visitingTeamId){
	$conn = getDBiConnection($log);

	$result = mysqli_query($conn,"select teamid, teamname, division from schedule, teams where week=".$week->getWeekNumber()." and visitingteamid=".$visitingTeamId." and schedule.hometeamid=teams.teamid");
	if(!$result){
		return null;
	}
	$row = mysqli_fetch_array($result);

	if($row['teamid']==null){
		return null;
	}else{
		$team =new Team();
		$team->setTeamId($row['teamid']);
		$team->setTeamName($row['teamname']);
		$team->setDivision($row['division']);
		return $team;
	}
}

function getFieldValue($fieldName){
	$result = "";
	if(isset($_POST[$fieldName]) &&$_POST[$fieldName]!=null){
		$result=$_POST[$fieldName];
	}
	return $result;	
}

function getPlayerDropdown($players,$fieldName,$selected){
	$output ="";
	$output .= '<select name="'.$fieldName.'">';
	
	$output .="<option value=''>--</option>";
	
	foreach ($players as $i => $player) {
		$output .="<option value='".$player->getPlayerId()."'";
		if($player->getPlayerId()==$selected){
			$output .= " selected";
		}		
		$output .= ">".$player->getFirstName()." ".$player->getLastName()."</option>";		
	}
	$output .="<option value='-1'";
	if(-1==$selected){
			$output .= " selected";
	}	
	$output .=">Sub</option>";
	$output .="<option value='-2'";
	if(-2==$selected){
		$output .= " selected";
	}	
	$output .=">No Player</option>";
	$output .="</select>";
	return $output;
}

function getGamesWonDropdown($fieldName,$selected){
	$output ="";
	$output .= '<select name="'.$fieldName.'">';
	
	$output .="<option value='0'";
		if("0"==$selected){
			$output .= " selected";
		}		
	$output .= ">0</option>";		
	
	$output .="<option value='1'";
		if("1"==$selected){
			$output .= " selected";
		}		
	$output .= ">1</option>";
	
	$output .="<option value='2'";
		if("2"==$selected){
			$output .= " selected";
		}		
	$output .= ">2</option>";
	
	$output .="</select>";
	return $output;
}

function getWeeks($log){

$weeks = array();
$conn = getDBiConnection($log);
  $result = mysqli_query($conn,"SELECT * FROM weeks");

  if(!$result){
    die( 'connection failed');
  }
  while($row = mysqli_fetch_array($result))
  {
    $week =new Week();
    $week->setWeekNumber($row['week']);
    $week->setWeekDate($row['date']);
    $weeks[] = $week;
  }

  mysqli_close($conn);

  return $weeks;

}

function getHomeTeamForMatch($matchId,$log){
	$conn = getDBiConnection($log);

	$result = mysqli_query($conn,"select hometeamid from schedule where ID=".$matchId);
	if(!$result){
		return null;
	}
	$row = mysqli_fetch_array($result);
	
	if($row['hometeamid']==null){
		return null;
	}else{
		$result2 = mysqli_query($conn,"select teamid, teamname, division from teams where teamid=".$row['hometeamid']);
		if(!$result2){
			return null;
		}
		$row2 = mysqli_fetch_array($result2);
		if($row2['teamid']==null){
			return null;
		}else{
			$team =new Team();
			$team->setTeamId($row2['teamid']);
			$team->setTeamName($row2['teamname']);
			$team->setDivision($row2['division']);
		return $team;
		}	
	}	
}


function getMatchForId($requestedMatch,$log){
	$conn = getDBiConnection($log);
	$result = mysqli_query($conn,"select ID, week,hometeamid,visitingteamid,score_entered from schedule where ID=".$requestedMatch);
	if(!$result){
		return null;
	}
	$row = mysqli_fetch_array($result);
	if($row['ID']==null){
		return null;
	}else{
		$match = new Match();	
		$match->setID($row['ID']);
		$match->setWeek($row['week']);
		$match->setHomeTeamId($row['hometeamid']);
		$match->setVisitingTeamId($row['visitingteamid']);
		$match->setScoresEntered($row['score_entered']);
		return $match;
	}	
}
	
function getTeamForId($teamId,$log){
	$conn = getDBiConnection($log);
	
	$result = mysqli_query($conn,"select teamid, teamname, division, oname, short_name from teams where teamid=".$teamId);
	if(!$result){
		return null;
	}
	$row = mysqli_fetch_array($result);
	if($row['teamid']==null){
		return null;
	}else{
		$team =new Team();
		$team->setTeamId($row['teamid']);
		$team->setTeamName($row['teamname']);
		$team->setDivision($row['division']);
		$team->setOname($row['oname']);
		$team->setShortName($row['short_name']);
		return $team;
	}
}

function getPlayers($log){
	$conn = getDBConnection($log);
	
  $result = mysql_query("SELECT * FROM players");

  if(!$result){
    die( 'connection failed');
  }
  while($row = mysql_fetch_array($result))
  {
    $player =new Player();
    $player->setPlayerId($row['player_id']);
    $player->setFirstName($row['first_name']);
    $player->setLastName($row['last_name']);
    $player->setTeamId($row['team_id']);
	$player->setRank($row['rank']);
    $players[$row['player_id']] = $player;
  }



  return $players;
}

function getPlayer($playerId,$log){
	$conn = getDBConnection($log);
	$player =new Player();
  $result = mysql_query("SELECT * FROM players where player_id=".$playerId);

  if(!$result){
    die( 'connection failed');
  }
  while($row = mysql_fetch_array($result))
  {    
    $player->setPlayerId($row['player_id']);
    $player->setFirstName($row['first_name']);
    $player->setLastName($row['last_name']);
    $player->setTeamId($row['team_id']); 
	$player->setRank($row['rank']);	
  }



  return $player;
}

//this is using elo, the base player score is 1000+(personal points per game average * 20);
function getPlayerRatings($log,$week){
	$k=32;
	$rankOffset=50;
	$players = getPlayers($log);
	$conn = getDBiConnection($log);
	$debug = False;
	
	$ppgaWeight = 20;
	
	if(isset($_GET['debug']) && $_POST['debug']!="true"){
		$debug=True;
	}
	
	//foreach($players as $x => $x_value) {
	//	$log->LogDebug($x."Player ".$x_value->getFirstName()." rating: ".$x_value->getRating());		
	//}
	
	$query1 = "select distinct(player_stats.player_id) as did, players.division, players.first_name, players.last_name";		
	$query1 .= ",(select (IFNULL(sum((IFNULL(s_01_points,0)+IFNULL(s_cricket_points,0)+IFNULL(d_01_points,0)+IFNULL(d_cricket_points,0))),0)) as personal_points from player_stats where player_stats.week_number<=".$week." and player_stats.player_id=did) as total ";
	
	
	$query1 .= ",((select IFNULL((sum(home_player_wins)+sum(visit_player_wins)),0) as singles_games_played from singles_games where (home_player_id=did or visit_player_id=did) and week<=".$week.") + ";
	$query1 .= "(select IFNULL((sum(home_wins)+sum(visit_wins)),0) as double_games_played from doubles_games where (home_player1_id=did or visit_player1_id=did or home_player2_id=did or visit_player2_id=did) and week<=".$week.")) as total_games "; 
	//$query1 .= ",(select (sum(home_player_wins)+sum(visit_player_wins)) as singles_games_played from singles_games where (home_player_id=did or visit_player_id=did) and week<=".$week.") as singles_games_played";
	//$query1 .= ",(IFNULL((select sum(home_player_wins) as singles_home_games_won from singles_games where home_player_id=did and week<=".$week."),0) + IFNULL((select sum(visit_player_wins) as singles_visit_games_won from singles_games where visit_player_id=did and week<=".$week."),0)) as singles_games_won ";
	$query1 .= "from player_stats, players ";
	$query1 .= "where players.player_id=player_stats.player_id and week_number<=".$week;
	$query1 .= " group by player_stats.player_id ";
	$query1 .= "order by total DESC";		
		
	$log->LogDebug("Query for player personal points: ".$query1);
	$result = mysqli_query($conn,$query1);  //do the query
	
	//all this to get the starting rating
	while($thisrow=mysqli_fetch_array($result)){
			$playerId =$thisrow['did'];			
			
			$player = $players[$playerId];
			
			if($thisrow['total_games']!=0){
				$ppga = round($thisrow['total']/$thisrow['total_games'],2);
				$player->setRating(round(1000+($ppga*$ppgaWeight)));	
			}else{
				$player->setRating(0);
			}				
			$players[$player->getPlayerId()] = $player;		
	}	
	$details .= "<div class='initRantings'><h3>Initial player ratings: </h3></div>";
	foreach($players as $x => $x_value) {
			$details.= "<div class='playerRating'>Player: <span class='playerName'>".$x_value->getFirstName()." ".$x_value->getLastName()."</span> Rating: ".$x_value->getRating()."</div>";
			//$log->LogDebug("Player ".$x_value->getFirstName()." rating: ".$x_value->getRating());		
	}
	
	//get each weeks singles stats, loop thru each game  and do the magic
	for ($x = 1; $x <= $week; $x++) {
		$details .= "<div class='ratingsWeek'><h3>Week ".$x.":</h3> </div>";
		$query2 = "select * from singles_games where week=".$x." order by game_type ASC";	
		
		$result2 = mysqli_query($conn,$query2);  //do the query
	
		//process each match
		while($thisrow=mysqli_fetch_array($result2)){
			$homePlayerId=$thisrow['home_player_id'];
			$homePlayer=$players[$homePlayerId];
			$homeWins=$thisrow['home_player_wins'];
			$visitPlayerId=$thisrow['visit_player_id'];
			$visitPlayer=$players[$visitPlayerId];
			$visitWins=$thisrow['visit_player_wins'];
			
			//count subs as 1000 rating
			if($homePlayerId == -1){
				$player =new Player();
				$player->setPlayerId(-1);
				$player->setFirstName("A");
				$player->setLastName("Sub");			
				$player->setRating(1000);
				$homePlayer = $player;
			}
			if($visitPlayerId == -1){
				$player =new Player();
				$player->setPlayerId(-1);
				$player->setFirstName("A");
				$player->setLastName("Sub");			
				$player->setRating(1000);
				$visitPlayer = $player;				
			}			
			//skip no opponents
			if(!($homePlayerId == -2 or $visitPlayerId==-2)){
				//loop thru home wins
				for ($h = 0; $h < $homeWins; $h++) {
					//for each home win compute the elo 			
					$newRatings = getNewRatings($homePlayer, $visitPlayer,$homePlayerId,$k,$rankOffset,$debug,$log);		
					$homePlayer=$newRatings[$homePlayer->getPlayerId()];
					$visitPlayer=$newRatings[$visitPlayer->getPlayerId()];			
					$players[$homePlayer->getPlayerId()] = $newRatings[$homePlayer->getPlayerId()];
					$players[$visitPlayer->getPlayerId()] = $newRatings[$visitPlayer->getPlayerId()];
					$details .= "<div class='gameDetails'>For winning a game <span class='playerName'>".$homePlayer->getFirstName()." ".$homePlayer->getLastName()."</span> gained ".$newRatings['pointsExchanged'] ." points from <span class='playerName'>" . $visitPlayer->getFirstName()." ".$visitPlayer->getLastName()."</span>. New ratings: <span class='playerName'>".$homePlayer->getFirstName()." ".$homePlayer->getLastName()."</span>: ".$homePlayer->getRating().", <span class='playerName'>".$visitPlayer->getFirstName()." ".$visitPlayer->getLastName()."</span>: ".$visitPlayer->getRating()."</div>";
					
				}
		
				//loop thru visit wins
				for ($v = 0; $v < $visitWins; $v++) {
					//for each home win compute the elo 			
					$newRatings = getNewRatings($homePlayer, $visitPlayer, $visitPlayerId,$k,$rankOffset,$debug,$log);		
					$homePlayer=$newRatings[$homePlayer->getPlayerId()];
					$visitPlayer=$newRatings[$visitPlayer->getPlayerId()];					
					$players[$homePlayer->getPlayerId()] = $newRatings[$homePlayer->getPlayerId()];
					$players[$visitPlayer->getPlayerId()] = $newRatings[$visitPlayer->getPlayerId()];		
					$details .= "<div class='gameDetails'>For winning a game <span class='playerName'>".$visitPlayer->getFirstName()." ".$visitPlayer->getLastName()."</span> gained ".$newRatings['pointsExchanged'] ." points from <span class='playerName'>" . $homePlayer->getFirstName()." ".$homePlayer->getLastName()."</span>. New ratings: <span class='playerName'>".$homePlayer->getFirstName()." ".$homePlayer->getLastName()."</span>: ".$homePlayer->getRating().", <span class='playerName'>".$visitPlayer->getFirstName()." ".$visitPlayer->getLastName()."</span>: ".$visitPlayer->getRating()."</div>";
										
				}
			}		
		}		
	} 	
	
	
	
	return array('players'=>$players,'details'=>$details);
	
}

?>