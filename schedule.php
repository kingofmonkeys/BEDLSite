<?php
include "common.php";
$output = "";
$output .= draw_head("Baltimore English Dart League", "Welcome to the Baltimore English Dart League Website");


#$output .= Loadinfo("./properties/schedule.txt");
#$output .= '<div class="halfWidthTitle">Fall/Winter 2017 Season Schedule</div>';

$seasonName = getSeasonName($log);
$header = $seasonName.' Season Schedule';

$body = drawTeams($log);
$body .= drawSchedule($log);
$body .= drawScheduleNotes($log);

$output .= drawContainer($header,$body);


$output .= draw_foot();

echo $output;

function drawScheduleNotes($log){
$output = "";
$output .='<div class="bedl-scheduleTitle">Schedule Notes:</div>';
$output.='<div class="bedl-schedulenotes">';
$output.='Make-up Week - 7/11/19';
$output .= '<br/>Post Season Round Robin: 7/18/19: Div 2: Seed #1 vs Seed #4, Seed #2 vs Seed #3  Div 1: Seed #1 vs Seed #4, Seed #2 vs Seed #3';
$output .= '<br/>Post Season Round Robin: 7/25/19: Div 2: Seed #1 vs Seed #3, Seed #2 vs Seed #4  Div 1: Seed #1 vs Seed #3, Seed #2 vs Seed #4';
$output .= '<br/>Post Season Round Robin: 8/1/19: Div 2: Seed #1 vs Seed #2, Seed #3 vs Seed #4  Div 1: Seed #1 vs Seed #2,  Seed #3 vs Seed #4';
$output.='</div>';
$output.='</div>';

return $output;
}



function drawByeTeam($log, $arr){
$output ="";
$chandle = getDBConnection($log);
$query5 = "SELECT DISTINCT (teamid)FROM `teams`;"; 
$result5 = mysql_query($query5) or die("Failed Query of " . $query5);  //do the query
$isABye = FALSE;

while($thisrow5=mysql_fetch_array($result5))
	{
		if(is_null($arr) || !(in_array( $thisrow5['teamid'] , $arr ))){
			$query7 = "SELECT * FROM teams where teamid='".$thisrow5['teamid']."'";
			$result7 = mysql_query($query7) or die("Failed Query of " . $query7);  //do the query
			$byeTeamRow = mysql_fetch_array($result7);	  
			$byeTeamShortName = $byeTeamRow['short_name'];	  
			//$output .= $thisrow5['teamid']." - BYE</br>";  
			$output .= $byeTeamShortName." - BYE</br>";                    
			
		}
    }
	
return $output;
}

function drawSchedule($log){
$output ="";
$chandle = getDBConnection($log);
	$query1 = "SELECT * FROM weeks;";
	$result = mysql_query($query1) or die("Failed Query of " . $query1);  //do the query
	$numrows = mysql_num_rows($result);
	
		
	$output .='<table class="bedl-scheduletable" >';
	$itemsperrow = 6;
	$percent = 100/$itemsperrow;
	$rowcount = round(($numrows/$itemsperrow)+.5,0,PHP_ROUND_HALF_UP);
	
	$currentweek = '1';
	
	$itemsinrow=0;
	$currentweek=0;
	

for($i=0;$i<$rowcount;$i++){
$output .= "<tr>";
if($i==$rowcount-1){
//this means its the last row so we should make sure we dont put too many there.
	$itemsperrow = $numrows;
}else{
	$numrows = $numrows-$itemsperrow;
}
for($ii=0;$ii<$itemsperrow;$ii++){
$thisweek=mysql_fetch_array($result);

$output .= '<td width="'.$percent.'%">';
$output .= '<div class="bedl-scheduleweek">Week '. $thisweek['week'] .'<br/>';
$phpdate = strtotime( $thisweek['date'] );
$output .=  date( 'n/j', $phpdate ).'</div><br/>';


$query1 = "SELECT * FROM schedule where week='".$thisweek['week']."'";
$result2 = mysql_query($query1) or die("Failed Query of " . $query1);  //do the query

$output .='<div class="bedl-scheduleteamlist">';
unset($arr);
while($thisrow=mysql_fetch_array($result2)){
$query2 = "SELECT * FROM teams where teamid='".$thisrow["hometeamid"]."'";
$result3 = mysql_query($query2) or die("Failed Query of " . $query2);  //do the query

$query3 = "SELECT * FROM teams where teamid='".$thisrow["visitingteamid"]."'";
$result4 = mysql_query($query3) or die("Failed Query of " . $query3);  //do the query

 $arr[] = $thisrow["hometeamid"];
 $arr[] = $thisrow["visitingteamid"];
 $homeTeamRow = mysql_fetch_array($result3);
 $visitTeamRow = mysql_fetch_array($result4);
 
//$output .= $thisrow['hometeamid']." - ".$thisrow['visitingteamid']."<br/>"; 
$output .= "<b>".$homeTeamRow['short_name']."</b> - ".$visitTeamRow['short_name']."<br/>";
}
$output .= drawByeTeam($log, $arr);
$output .= "</td>";
}
$output .= "</tr>";
}	
	
	
	$output .='</table>';
	return $output;
}

function drawTeams($log){
$chandle = getDBConnection($log);
$query = "select distinct division from teams";
	$divisions = mysql_query($query) or die("Failed Query of " . $query);  //do the query

	$output .= '<div class="bedl-scheduleTitle">Teams'.$weekNumber.'</div>';
	$output .= '<div class="bedl-scheduleteams">';

	while($division=mysql_fetch_array($divisions)){
		$divisionName=$division[0];

		$output .= '<div class="bedl-scheduledivisions"><div class="divisionHeading">Division '.$divisionName.'</div>';
		

		$query1 = "SELECT teams.oname,teams.teamname,teams.division,teams.teamid FROM teams WHERE teams.division='".$divisionName."' ORDER BY teams.teamid";
		$result = mysql_query($query1) or die("Failed Query of " . $query1);  //do the query

		$colorclass = "stattdgray";

		while($thisrow=mysql_fetch_array($result))
		{
            
			$output .="<div>".$thisrow[teamid] ." - ".$thisrow[teamname];
            if(!is_null($thisrow[oname])){
                $output .= " (".$thisrow[oname].")";
            }
            $output .="</div>";
            
		}
		$output .="</div>";
		}
		$output .="</div>";
return $output;
}
?>