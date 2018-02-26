<?php
include "common.php";

$output = "";

$output .= draw_head("Baltimore English Dart League", "Averages Page");

$errors = array();
// log some information
$log->LogDebug("Someone is in the average.php page");


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
	$body .=  "I'm sorry but there are currently no stats to average.<br/><br/>";
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



function cmp ($a,$b){
	$val1=$a->getWeek();
	$val2=$b->getWeek();
	
	if(strlen($val1)==1 && strlen($val2)==1){
		return strcmp($val1, $val2);
	}else if(strlen($val1)==2 && strlen($val2)==2){
		return strcmp($val1, $val2);		
	}else if(strlen($val1)==2){
		return 1;
	}else{
		return  -1;
	}	
	
	//return strcmp($a->getWeek(), $b->getWeek());
}



function drawScoreSheetForm($log,$errors){
	
	
	$matchValue = "--";


	$homeTeamWinsValue = "";
	$visitingTeamWinsValue = "";
	$additionalNotesValue = "";

	if(isset($_POST['match'])){
		$matchValue = $_POST['match'];
	}

	$seasonName = getSeasonName($log);
	$header = $seasonName.' Season Averages';	
	$body = "";
	
	$body .= '<form action="./average.php" method="post">';
	$body .= '<div class="teamInfo">';
	$body .= '  <div class="dl-input">';

	$matchFieldHTML = '<label for="match">Match:</label><select name="match" id="match" onchange="updateAverages()"><option value="--">--</option>'."\r\n";
	//here we need to get all the makeup matches.
   $teams = getTeams($log);
       
	$week = getCurrentWeek($log);
	
	$matches = array();
	
    foreach ($teams as $i => $team) {
		
		$teamQuery = "select hometeam.teamname as hometeamname, visitteam.teamname as visitingteamname,schedule.ID, schedule.week from schedule INNER JOIN teams as hometeam ON schedule.hometeamid=hometeam.teamid INNER JOIN teams as visitteam ON schedule.visitingteamid=visitteam.teamid where hometeamid='".$team->getTeamId()."' and week in (select week from weeks where week<='".$week->getWeekNumber()."' and week=weeks.week and weeks.date<=date('".$week->getWeekDate()."') and week not in (select weeks.week from teamstats, weeks where teamstats.week<='".$week->getWeekNumber()."' and teamstats.week=weeks.week and weeks.date<=date('".$week->getWeekDate()."') and teamid='".$team->getTeamId()."')) order by schedule.week DESC";
		$conn = getDBConnection($log);
		$teamMatches = mysql_query($teamQuery);

		if(!$teamMatches){
			die( 'Could not get team makeups for '.$team->getTeamId().'  week:'.$week->getWeekNumber().'  week date:'.$week->getWeekDate());
		}
		while($row = mysql_fetch_array($teamMatches))
		{
			$match =new Match();
			$match->setID($row['ID']);
			$match->setWeek($row['week']);
			$match->setHomeTeamName($row['hometeamname']);
			$match->setVisitingTeamName($row['visitingteamname']);	
			
			$matches[] = $match;
		}

		mysql_close($conn);
		
	  }
	  
	usort($matches, "cmp");
	  
	     foreach ($matches as $i => $aMatch) {
	  
			$matchFieldHTML = $matchFieldHTML.'<option value="'.$aMatch->getID().'"';
			if($aMatch->getID()==$matchValue){
				$matchFieldHTML = $matchFieldHTML.' selected';
			}
			$matchFieldHTML = $matchFieldHTML.'>Week: '.$aMatch->getWeek().' - '.$aMatch->getHomeTeamName().' vs '.$aMatch->getVisitingTeamName().'</option>."\r\n"';
	    }

	    


	$body .= displayField($matchFieldHTML,"match",$errors);

	$body .= '    </select>';

	$body .= '  </div></div>'."\r\n";

	$body .= '<div id="averages" style="display:none">';
	
	$body .= '</div>';

	$body .= '</form>';
	
	$output .= drawContainer($header,$body);
	return $output;

}

function validateForm($log){
	$errors = array();

return $errors;
}



function processForm($log){
	
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
 
 function getMissedGamesForTeam($log, $teamid){
//run the query  to get the missed games for this teamid
$teams = array();
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
    $teams[] = $team;
  }

  mysql_close($conn);

  return $teams;
 }

 
 
?>