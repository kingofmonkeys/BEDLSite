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
$output .='Make-up night: 1/4/2018<br/><br/>';
$output.='End of Season Round Robin: <br/> 1/11/2018: Division 1 [Seed 1 @ Seed 4 and Seed 2 @ Seed 3]; Division 2 [Seed 1 @ Seed 3 and Seed 2 Bye]<br/>';
$output.='1/18/2018: Division 1 [Seed 1 @ Seed 3 and Seed 2 @ Seed 4]; Division 2 [Seed 1 @ Seed 2 and Seed 3 Bye]<br/>';
$output.='1/25/2018: Division 1 [Seed 1 @ Seed 2 and Seed 3 @ Seed 4]; Division 2 [Seed 2 @ Seed 3 and Seed 1 Bye]<br/><br/>';
$output.='Shootoffs - 2/1/18 Division 1 at Brewers, Division 2 at Angle Inn';
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
              if(!(in_array( $thisrow5['teamid'] , $arr ))){
$output .= $thisrow5['teamid']." - BYE</br>";                
			
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
	$itemsperrow = 8;
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
 $arr[] = $thisrow["hometeamid"];
 $arr[] = $thisrow["visitingteamid"];
$output .= $thisrow['hometeamid']." - ".$thisrow['visitingteamid']."<br/>"; 

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