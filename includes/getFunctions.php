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
	$output .=">No Opponent</option>";
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

?>