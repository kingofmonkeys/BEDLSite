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
?>