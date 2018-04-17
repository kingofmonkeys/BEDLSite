<?php
include "common.php";
$output = "";
$output .= draw_head("Baltimore English Dart League", "Baltimore English Dart League Roster");



$header = getSeasonName($log).' Roster';


$body .= drawRoster($log);

$output .= drawContainer($header,$body);


$output .= draw_foot();

echo $output;




function drawRoster($log){

$output = drawTeams($log);

return $output;
}


function drawTeams($log){
$chandle = getDBConnection($log);
$query = "select distinct division from teams";
	$divisions = mysql_query($query) or die("Failed Query of " . $query);  //do the query

	

	while($division=mysql_fetch_array($divisions)){
		$divisionName=$division[0];

		$output .= '<div class="divisionHeading">Division '.$divisionName.'</div>';
		

		$query1 = "SELECT teams.oname,teams.teamname,teams.division,teams.teamid FROM teams WHERE teams.division='".$divisionName."' ORDER BY teams.teamid";
		$result = mysql_query($query1) or die("Failed Query of " . $query1);  //do the query

		while($thisrow=mysql_fetch_array($result))
		{
		    //this is looping thru the teams in this division.
			//$output .="<div>".$thisrow[teamid] ." - ".$thisrow[teamname];
           // if(!is_null($thisrow[oname])){
            //    $output .= " (".$thisrow[oname].")";
          //  }
          //  $output .="</div>";
		  $output .= drawTeamRoster($thisrow[teamid],$log);
            
		}	
		
	}
		
	$output .= '<div class="divisionHeading">Subs</div>';
		
	$output .= '<div class="bedl-rostertablediv"><table class="bedl-rosterteamtable">';	
         
	$output .= '<tr><td class="bedl-rosterplayertitle">Name</td><td class="bedl-rostertdcenter bedl-rosterplayertitle" width="75px">Division</td><td class="bedl-rostertdcenter bedl-rosterplayertitle" width="50px">Rank</td></tr>';
	
		
	$query1 = "select * from players where team_id=0 order by first_name ASC";
	$result1 = mysql_query($query1) or die("Failed Query of " . $query1);  //do the query
	$colorclass = "stattdgray";
	
	while($thisrow=mysql_fetch_array($result1))
		{
			if($colorclass=="stattdgray"){
				$colorclass="stattdltgray";
			}else{
				$colorclass="stattdgray";
			}
			
			$output .= '<tr><td class="'.$colorclass.'">'.$thisrow[first_name].' '.$thisrow[last_name].'</td><td class="bedl-rostertdcenter '.$colorclass.'">'.$thisrow[division].'</td><td class="bedl-rostertdcenter '.$colorclass.'">'.$thisrow[rank]."</td></tr>";
		}
		
	$output .= "</table></div>";
		
		
return $output;
}

function drawTeamRoster($teamid,$log){
	$output = "";
	
	$query = "select * from teams where teamid=".$teamid;
	$result = mysql_query($query) or die("Failed Query of " . $query);  //do the query
	$teaminfo = mysql_fetch_array($result);
	
	$output .= '<div class="bedl-rostertablediv"><table class="bedl-rosterteamtable"><tr><td class="bedl-rosterteamtitle" colspan="3">';

	$output .= $teaminfo[teamid] ." - ".$teaminfo[teamname];
            if(!is_null($teaminfo[oname])){
                $output .= " (".$teaminfo[oname].")";
            }
    $output .= '</td></tr>';        
	$output .= '<tr><td class="bedl-rosterplayertitle">Name</td><td class="bedl-rostertdcenter bedl-rosterplayertitle" width="75px">Division</td><td class="bedl-rostertdcenter bedl-rosterplayertitle" width="50px">Rank</td></tr>';
	
	
	
	$query1 = "select * from players where team_id=".$teamid;
	$result1 = mysql_query($query1) or die("Failed Query of " . $query1);  //do the query
	$colorclass = "stattdgray";
	
	while($thisrow=mysql_fetch_array($result1))
		{
			if($colorclass=="stattdgray"){
				$colorclass="stattdltgray";
			}else{
				$colorclass="stattdgray";
			}
			
			$output .= '<tr><td class="'.$colorclass.'">'.$thisrow[first_name].' '.$thisrow[last_name].'</td><td class="bedl-rostertdcenter '.$colorclass.'">'.$thisrow[division].'</td><td class="bedl-rostertdcenter '.$colorclass.'">'.$thisrow[rank]."</td></tr>";
		}
		
	$output .= "</table></div>";
	return $output;
}

	
?>