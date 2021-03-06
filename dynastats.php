<?php
include "common.php";

$output = "";
$output .= draw_head("Baltimore English Dart League", "Welcome to the Baltimore English Dart League Website");

$requestedWeek;
if(isset($_GET['week'])){
	$requestedWeek =$_GET['week'];
}else{
	$chandle = getDBConnection($log);
	$query1 = "select distinct week from singles_games order by week DESC";
	$result = mysql_query($query1);  //do the query
	if($result){
		$row=mysql_fetch_array($result);
		$requestedWeek =$row['week'];
	}
}

$header = "";
$seasonName = getSeasonName($log);
		
$header .= '<div class="halfWidth">'.$seasonName.' Season Stats <a style="{font-size: 12pt;	font-weight: normal;}" href="javascript:window.print()"><img src="./images/print.png" width="17" border="0"/>Print stats</a></div>';
$header .= '<div id="printDate" class="halfWidthRight" >'.date("n/j/Y g:i a").'</div>';

$body ="";
$body .=  drawWeeklyStatsLinks($log);

if(isset($requestedWeek)){	
	$body .=  drawWeekStats($log,$requestedWeek);
	$body  .= '<br/>';
	$body .= drawTeamStandings($log,$requestedWeek);
	$body .= '<br/>';
	$body .= drawPlayerStat($log,$requestedWeek);
	
	$body .= "<div><a href='#ratingInformation' id='ratingInformationHideShow'>Show how the rating calculated</a></div>";
	$body .= '<div id="ratingInformation" class="ratingInformation">';
	$body .= 'How is the rating calculated:';
	$body .= '<div class="gameDetails"><ol>';
	$body .= "<li>Base rating is set at 1000 (this is just so we don't have negative ratings)</li>";
	$body .= '<li>Personal Points per Game Average times 20 is added to the Base rating (this is done to get an initial spread in the ratings, 20 is considered the weight the Personal Points per Game Average is given in the ratings)</li>';
	$body .= '<li>For each singles game a player plays we calculate the points exchanged from the loser of the game to the winner of the game.</li>';
	$body .= '<li>We adjust the lower ranked players rating up 50 points per difference in rank (a rank 3 shooter will get 100 points on their rating when facing a rank 1 shooter, this is an attempt to take the handicap into account)</li>';
	$body .= '<li>Given the adjusted ratings we calculated expected win % as 1 / (1 + 10^(($playerBRating - $playerARating)/ 400))  In this equation 400 is double the points difference needed to give one player a 75% chance of beating the other.</li>';
	$body .= '<li>Finally we can calculate the points exchanged between players as K * (1-expectedWin%)  Where K is the weight each game is given in the ratings.  If there is a 50/50 chance  either player could win there would be 16 points exchanged between the players.</li>';
	$body .= '</ol>';
	$body .= '<br/>The variables in these ratings come down to how much weight we put on the PPGA (20), the weight we give the handicap (50) and the weight we give each game (32).  I am still seeing if these numbers make sense and might adjust them as we get more data.   ';
	$body .='</div></div>';
}

$output .= drawContainer($header,$body);

$output .= draw_foot();

echo $output;
//end of page

function drawWeeklyStatsLinks($log){
	$output = "";
	$chandle = getDBConnection($log);
	$query1 = "select distinct week from singles_games order by week ASC";
	$result = mysql_query($query1);  //do the query
	if(!$result){
		$log->LogError("There was an error on the dynastats page running sql: ".$query1);
	 	header("Location: ErrorPage.php");
	}

	$output .= '<div class="whitebg"><div class="halfWidth">Week: ';
	$i=0;
	while($thisrow=mysql_fetch_array($result)){
		if($i!=0){
			$output .= ' | ';
		}
		$i=$i+1;
		$output .= '<a href="./dynastats.php?week='.$thisrow["week"].'">'.$thisrow["week"].'</a>';
	}
	$output .= '</div><div class="halfWidthRight"></div></div>';
	if($i==0){
		//this means no stats are ready.
		$output .= ' There are currently no stats for this season.';
	}
	return $output;
}



function drawTeamStandings($log,$weekNumber){
	$output = "";
	$chandle = getDBConnection($log);
	$query = "select distinct division from teams";
	$divisions = mysql_query($query) or die("Failed Query of " . $query);  //do the query

	$output .= '<div class="standingsTitle">Team Standings as of Week '.$weekNumber.'</div>';

	while($division=mysql_fetch_array($divisions)){
		$divisionName=$division[0];

		$output .= '<div class="divisionHeading">Division '.$divisionName.'</div>';
		$output .= '<table width="70%" class="stattable"><tr><th>Team</th><th>Total Wins</th><th>Total Losses</th><th>Win %</th><th>Byes left</th><th>Make ups</th></tr>';	
		
		$query1 = "SELECT teams.teamname,teams.division,teams.teamid,"; 
		$query1 .="(IFNULL((select sum(home_player_wins) from singles_games where week<=".$weekNumber." and home_team_id=teams.teamid),0)";
		$query1 .="+IFNULL((select sum(visit_player_wins) from singles_games where week<=".$weekNumber." and visit_team_id=teams.teamid),0)";
		$query1 .="+IFNULL((select sum(home_wins) from doubles_games where week<=".$weekNumber." and home_team_id=teams.teamid),0)";
		$query1 .="+IFNULL((select sum(visit_wins) from doubles_games where week<=".$weekNumber." and visit_team_id=teams.teamid),0)) as teamWins, ";

		$query1 .="(IFNULL((select sum(visit_player_wins) from singles_games where week<=".$weekNumber." and home_team_id=teams.teamid),0)";
		$query1 .="+IFNULL((select sum(home_player_wins) from singles_games where week<=".$weekNumber." and visit_team_id=teams.teamid),0)";
		$query1 .="+IFNULL((select sum(visit_wins) from doubles_games where week<=".$weekNumber." and home_team_id=teams.teamid),0)";
		$query1 .="+IFNULL((select sum(home_wins) from doubles_games where week<=".$weekNumber." and visit_team_id=teams.teamid),0)) as teamLosses ";
		$query1 .="FROM teams WHERE teams.division=".$divisionName." ";
		$query1 .="ORDER BY teamWins DESC";
		
		
		//$query1 = "SELECT teams.teamname,teams.division,teams.teamid,teamstats.teamid, SUM(teamstats.wins) , SUM(teamstats.losses) FROM teams,teamstats WHERE teamstats.week <= ".$weekNumber." and teamstats.teamid = teams.teamid and teams.division='".$divisionName."' GROUP BY teamstats.teamid ORDER BY SUM(teamstats.wins) DESC";
		$result = mysql_query($query1) or die("Failed Query of " . $query1);  //do the query

		$colorclass = "stattdgray";

		while($thisrow=mysql_fetch_array($result))
		{
			if($colorclass=="stattdgray"){
				$colorclass="stattdltgray";
			}else{
				$colorclass="stattdgray";
			}

			$cutoffdate  = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-4, date("Y")));


			//$query2 = "select count(teamid) as scoresentered,(select count(*) from schedule, weeks where schedule.week<=".$weekNumber." and schedule.week=weeks.week and weeks.date<date('".$cutoffdate."') and (hometeamid=".$thisrow['teamid']." or visitingteamid=".$thisrow['teamid'].")) as expectedscores from teamstats, weeks where teamstats.week<=".$weekNumber." and teamstats.week=weeks.week and weeks.date<date('".$cutoffdate."') and teamid=".$thisrow['teamid'];
			$query2 = "select count(*) as makeups from schedule,weeks where schedule.week=weeks.week and weeks.date<date('".$cutoffdate."') and (hometeamid=".$thisrow['teamid']." or visitingteamid=".$thisrow['teamid'].") and schedule.score_entered='false'";
			
			$results2 = mysql_query($query2) or die("Failed Query of " . $query2);  //do the query

			$result2 = mysql_fetch_array($results2);

			//$makeups = intval($result2['expectedscores']) - intval($result2['scoresentered']);
			$makeups = $result2['makeups'];
			
			if($makeups<0){
				$makeups=0;
			}
			
			$query3 = "select (select count(distinct(week)) from schedule where week>".$weekNumber.") as weeks, count(*) as matchesleft from schedule where week>".$weekNumber." and (hometeamid=".$thisrow['teamid']." or visitingteamid=".$thisrow['teamid'].");";

			$results3 = mysql_query($query3) or die("Failed Query of " . $query3);  //do the query

			$result3 = mysql_fetch_array($results3);

			$byes = intval($result3['weeks']) - intval($result3['matchesleft']);

			$totalGames = ($thisrow["teamLosses"]+$thisrow["teamWins"]);
			if($totalGames==0){
				$winPre = 0;
			}else{
				$winPre = round(($thisrow["teamWins"]/$totalGames)*100,1);
			}
			$output .= '<td class="'.$colorclass.'">'.$thisrow["teamname"].'</td><td class="'.$colorclass.'">'.$thisrow["teamWins"].'</td><td class="'.$colorclass.'">'.$thisrow["teamLosses"].'</td><td class="'.$colorclass.'">'.$winPre.'%</td><td class="'.$colorclass.'">'.$byes.'</td><td class="'.$colorclass.'">'.$makeups.'</td></tr>';
		}
		$output .= '</table>';
	}
	return $output;
}


function drawWeekStats($log,$weekNumber){
	$output = "";
	$output .= '<div class="standingsTitle">Week '.$weekNumber.' Scores</div>';

	$output .= '<table width="60%" class="stattable">';

	$output .= '<tr><th>Home Team</th><th>Wins</th><th>Visiting Team</th><th>Wins</th></tr>';
	$chandle = getDBConnection($log);

	$query = 'select ID,hometeamid,(select teams.teamname from teams where schedule.week='.$weekNumber.' and schedule.hometeamid = teams.teamid) as hometeamname, ';
	$query .= '(select ((select sum(home_player_wins) from singles_games where week='.$weekNumber.' and home_team_id=schedule.hometeamid)+(select sum(home_wins) from doubles_games where week='.$weekNumber.' and home_team_id=hometeamid)) as hometeamwins from singles_games,doubles_games where singles_games.home_team_id=schedule.hometeamid group by singles_games.home_team_id) as hometeamwins, ';
	$query .= '(select ((select sum(visit_player_wins) from singles_games where week='.$weekNumber.' and visit_team_id=schedule.visitingteamid)+(select sum(visit_wins) from doubles_games where week='.$weekNumber.' and visit_team_id=visitingteamid)) as visitingteamwins from singles_games,doubles_games where singles_games.visit_team_id=schedule.visitingteamid group by singles_games.visit_team_id) as visitingteamwins ';
	$query .= ',visitingteamid, ';
	$query .= '(select teams.teamname from teams where schedule.week='.$weekNumber.' and schedule.visitingteamid = teams.teamid) as visitingteamname from schedule where week='.$weekNumber;
	
	//$query='select ID,hometeamid, (select teams.teamname from teams where schedule.week='.$weekNumber.' and schedule.hometeamid = teams.teamid) as hometeamname, (select wins from teamstats where teamstats.week='.$weekNumber.' and schedule.hometeamid=teamstats.teamid) as hometeamwins, visitingteamid, (select teams.teamname from teams where schedule.week='.$weekNumber.' and schedule.visitingteamid = teams.teamid) as visitingteamname, (select wins from teamstats where teamstats.week='.$weekNumber.' and schedule.visitingteamid=teamstats.teamid) as visitingteamwins from schedule where week='.$weekNumber;

	$result = mysql_query($query) or die("Failed Query of " . $query1);  //do the query
	$colorclass = "stattdgray";


	while($thisrow=mysql_fetch_array($result))
	{
		if($colorclass=="stattdgray"){
			$colorclass="stattdltgray";
		}else{
			$colorclass="stattdgray";
		}
                $arr[] = $thisrow["hometeamid"];
                $arr[] = $thisrow["visitingteamid"];



		$output .= '<td class="'.$colorclass.'">'.$thisrow["hometeamname"].'</td><td class="'.$colorclass.'">'.$thisrow["hometeamwins"].'</td><td class="'.$colorclass.'">'.$thisrow["visitingteamname"].'</td><td class="'.$colorclass.'">'.$thisrow["visitingteamwins"].'</td></tr>';
	}
        $query5 = "SELECT DISTINCT (teamid) FROM `teams`;"; 

        $result5 = mysql_query($query5) or die("Failed Query of " . $query5);  //do the query
		$isABye = FALSE;

		while($thisrow5=mysql_fetch_array($result5))
		{
              if(!(in_array( $thisrow5['teamid'] , $arr ))){
                 $isABye = TRUE;
                 $byeTeamId = $thisrow5['teamid']; 
               }
        }
        if($isABye){
                if($colorclass=="stattdgray"){
			$colorclass="stattdltgray";
		}else{
			$colorclass="stattdgray";
		}
                $query6 = "SELECT teamname FROM `teams` WHERE teamid = ".$byeTeamId.";";

                $result6 = mysql_query($query6) or die("Failed Query of " . $query6);  //do the query
                $row6 = mysql_fetch_row($result6); 

                $output .= '<td class="'.$colorclass.'">'.$row6[0].'</td><td class="'.$colorclass.'">BYE</td><td class="'.$colorclass.'"></td><td class="'.$colorclass.'"></td></tr>';

        }

	$output .= '</table>';
	return $output;
}


function drawPlayerStat($log,$week){
	$output ="";
	$colSpan = $week+7;
	$chandle = getDBConnection($log);
	
	$ratingsInfo = getPlayerRatings($log,$week);
	
	$playerRatings = $ratingsInfo['players'];
	$ratingsDetails = $ratingsInfo['details'];
	
	//than we generate the sql call based on the weeks
	$output .= '<div style="page-break-before:always">'."\r\n";
	$output .= '<div class="standingsTitle">Player Standings as of Week '.$week.'</div>';

	$query2 = "select distinct division from players order by division";
	$divisions  = mysql_query($query2);
	$firstPass =TRUE;
	while($division=mysql_fetch_array($divisions)){
		$division = $division[0];
		//THIS IS FOR SORTING THE TABLE
		$output .="<script>$(document).ready(function() {";		
		$output .="  $('#personalsDivision".$division."').DataTable( {";
        $output .='"paging":   false,';
        $output .='"ordering": true,';
		$output .='"asStripeClasses": [ "stattdgray", "stattdltgray" ],';
		$output .='"searching": false,';

		$output .='"aoColumns": [null,null,';
		for($i=1;$i<=$week;$i++){
			$output .= '{ "orderSequence": [ "desc", "asc"] },';
		}
		$output .= '{ "orderSequence": [ "desc", "asc"] },';
		$output .= '{ "orderSequence": [ "desc", "asc"] },';
		$output .= '{ "orderSequence": [ "desc", "asc"] },';
		$output .= '{ "orderSequence": [ "desc", "asc"] },';
		$output .= '{ "orderSequence": [ "desc", "asc"] }],';		
        $output .='"info":     false';
		$output .='} );';
		$output .='} );';
		
		$output .='</script>';
		
		
		
		if($firstPass){
			$firstPass=FALSE;
		}else{
			$output .= '<div style="page-break-before:always">'."\r\n";
		}
		$output .= '<div class="divisionHeading">Division '.$division.'</div>'."\r\n";
		$output .= '<table class="stripe hover stattable" id="personalsDivision'.$division.'" >';
		$output .= '<thead><tr><th width="50px">Place</th><th width="120">Player Name</th>';

		


		
		$query1 = "select distinct(player_stats.player_id) as did, players.division, players.first_name, players.last_name";		
		
		for($i=1;$i<=$week;$i++){
			$output .= '<th width="20px">'.$i.'</th>';
			$query1 .=", (select IFNULL((IFNULL(s_01_points,0)+IFNULL(s_cricket_points,0)+IFNULL(d_01_points,0)+IFNULL(d_cricket_points,0)),0) as personal_points".$i." from player_stats where player_stats.week_number=".$i." and player_stats.player_id=did) as week".$i;
			$query1 .=" , ((select IFNULL((sum(home_player_wins)+sum(visit_player_wins)),0) as singles_games_played from singles_games where (home_player_id=did or visit_player_id=did) and week=".$i.") + ";
			$query1 .= "(select IFNULL((sum(home_wins)+sum(visit_wins)),0) as double_games_played from doubles_games where (home_player1_id=did or visit_player1_id=did or home_player2_id=did or visit_player2_id=did) and week=".$i."))as week".$i."GamesPlayed ";
		}
		
		$query1 .= ",(select (IFNULL(sum((IFNULL(s_01_points,0)+IFNULL(s_cricket_points,0)+IFNULL(d_01_points,0)+IFNULL(d_cricket_points,0))),0)) as personal_points from player_stats where player_stats.week_number<=".$week." and player_stats.player_id=did) as total ";
			

		$query1 .= ",((select IFNULL((sum(home_player_wins)+sum(visit_player_wins)),0) as singles_games_played from singles_games where (home_player_id=did or visit_player_id=did) and week<=".$week.") + ";
		$query1 .= "(select IFNULL((sum(home_wins)+sum(visit_wins)),0) as double_games_played from doubles_games where (home_player1_id=did or visit_player1_id=did or home_player2_id=did or visit_player2_id=did) and week<=".$week.")) as total_games "; 
	
		$query1 .= ",(select IFNULL((sum(home_player_wins)+sum(visit_player_wins)),0) as singles_games_played from singles_games where (home_player_id=did or visit_player_id=did) and week<=".$week.") as singles_games_played";
		$query1 .= ",((select  IFNULL(sum(home_player_wins),0) as singles_home_games_won from singles_games where home_player_id=did and week<=".$week.") +(select  IFNULL(sum(visit_player_wins),0) as singles_visit_games_won from singles_games where visit_player_id=did and week<=".$week.")) as singles_games_won ";
		$query1 .= "from player_stats, players ";
		$query1 .= "where players.player_id=player_stats.player_id and division=".$division." and week_number<=".$week;
		$query1 .= " group by player_stats.player_id ";
		$query1 .= "order by total DESC";		
		
		//$log->LogDebug("Query for player personal points: ".$query1);
		$result = mysql_query($query1);  //do the query
		$output .= '<th>Total</th><th><div class="tooltip">TGP<span class="tooltiptext">Total Games Played (Singles and Doubles)</span></div></th><th><div class="tooltip">PPGA<span class="tooltiptext">Personal Points Per Game Average</span></div></th><th><div class="tooltip">Win %<span class="tooltiptext">Singles Games Win %</span></div></th><th>Rating</th></tr></thead><tbody>';
		$place = 0;
		$lastPoints=0;
		$colorclass = "stattdgray";

		while($thisrow=mysql_fetch_array($result))
		{
			if($thisrow['total_games']!=0){
				//if($colorclass=="stattdgray"){
					//$colorclass="stattdltgray";
				//}else{
					//$colorclass="stattdgray";
				//}
				if(!($lastPoints==$thisrow['total'])){
					//this means we dont have a tie at this place
					$place++;
				}
				$output .= '<tr><td><center>'.$place.'</center></td><td><a href="./playerstats.php?week='.$week.'&playerId='.$thisrow['did'].'">'.$thisrow['first_name'].' '.$thisrow['last_name'].'</a></td>';

				for($i=1;$i<=$week;$i++){
					$gamesPlayed = $thisrow['week'.$i.'GamesPlayed'];
					if($gamesPlayed==0){
						$output .= '<td><center>--';	
					}else{						
						$output .= '<td class="statsWeekPlayed"><center>';	
						if($thisrow['week'.$i]==null){
							$output .= 'M';
						}else{
							$output .= $thisrow['week'.$i];
						}
					}					
					$output .= '</center></td>';
				}
				$output .= '<td><center>'.$thisrow['total'].'</center></td>';
				$output .= '<td><center>'.$thisrow['total_games'].'</center></td>';
				$output .= '<td><center>';
				if ($thisrow['total_games']==0){
					$output .='0';
				}else{
					$output .= round($thisrow['total']/$thisrow['total_games'],2);
				}
				$output .= '</center></td>';
			
				$output .= '<td><center>';
				if ($thisrow['singles_games_played']==0){
					$output .='0';
				}else{
					$output .= round(($thisrow['singles_games_won']/$thisrow['singles_games_played'])*100,1);
				}
				$output .= '</center></td>';		
			
				$output .= '<td><center>';
				if ($thisrow['singles_games_played']==0){
					$output .='N/A';
				}else{
					$currentPlayer = $playerRatings[$thisrow['did']];
					$output .= $currentPlayer->getRating();
				}
				$output .= '</center></td>';		
			
			
				$output .= '</tr>'."\r\n";
				$lastPoints=$thisrow['total'];
			}
		}
		$output .="</tbody></table>";
		
		$output .= '<table class="stattable" >';
		$output .= '<tr><td colspan="'.$colSpan.'" class="noSpace">';
		$output .= drawSpecialShots($log, $division, $week);
		$output .= '</td></tr></table>';

		$output .= '<br/></div>'."\r\n";
		
	}
	$output .= "<div><a href='#ratingDetails' id='ratingDetailsHideShow'>Show Player Rating Details</a></div>";
	$output .= '<div id="ratingDetails" class="ratingDetails">';
	$output .= $ratingsDetails;
	$output .= "</div>";
	
	return $output;
}



function drawSpecialShots($log, $division, $week){

	$output ="";
	$output .= '<table width="100%" class="shottable">';
	$query = "select * from shots;";
	$shots  = mysql_query($query);
	$numShots = mysql_num_rows($shots);
	$numRows = round($numShots/2,0,PHP_ROUND_HALF_UP);
	
	
	for($i = 1;$i<=$numRows;$i += 1){
		
		$shot=mysql_fetch_array($shots);
		
		//this means its a shot that we want to display all of them
		$olclass ="bedl-listManager";
		
		
		$output .= '<tr><td width="100" class="stattdgray">'.$shot['shotname'].'</td><td class="stattdltgray">';
		$query1 = 'select shotvalue, shots.shotname, players.first_name, players.last_name, normshotvalue from player_shots, players, shots where players.player_id = player_shots.player_id and player_shots.shotId = shots.ID and player_shots.week_number<='.$week.' and shotId='.$shot['ID'].' and players.division="'.$division.'" order by normshotvalue DESC;';
		$playershots  = mysql_query($query1);
		$aNumRows = mysql_num_rows($playershots);
		$islist = true;
		if(($shot['ID']==1)||($shot['ID']==5)||($shot['ID']==6)||$aNumRows==1){
			$olclass ="bedl-listNone";
			$islist = false;
		}
		
		if((!($playershots==false))&& $aNumRows!=0){
		$output .= '<ol class="'.$olclass.'">';
		$tempshotvalue = "0";
		$writeendli = false;
		while($aplayershot=mysql_fetch_array($playershots)){
			
			
			if($aplayershot['shotvalue']==$tempshotvalue){
				$sameshotvalue=true;
			}else{
				$sameshotvalue=false;
			}
			if(((!$sameshotvalue)&&$islist)||!$islist){
				if($writeendli){				
				$output .= "</li>\r\n";
				$writeendli = false;
			}				
				$output .= "<li>";
			}else{
				$output.="<br/>";
			}
			$output .= $aplayershot['first_name'] . ' ' .$aplayershot['last_name'];
			if(isset($aplayershot['shotvalue'])){
				$output.= ' - '.$aplayershot['shotvalue'];
			}
			if(((!$sameshotvalue)&&$islist)||!$islist){
				$writeendli = true;
			}
			$tempshotvalue=$aplayershot['shotvalue'];
		}
		$output .='</ol>';
		}
		$output .='</td>';
		$shot2=mysql_fetch_array($shots);
		if($shot2){
		//this means its a shot that we want to display all of them
		$olclass ="bedl-listManager";
		
		$output.='<td width="100" class="stattdgray">'.$shot2['shotname'].'</td><td class="stattdltgray">';
		$query2 = 'select shotvalue, shots.shotname, players.first_name, players.last_name, normshotvalue from player_shots, players, shots where players.player_id = player_shots.player_id and player_shots.shotId = shots.ID and player_shots.week_number<='.$week.' and shotId='.$shot2['ID'].' and players.division="'.$division.'" order by normshotvalue DESC;';
		$playershots  = mysql_query($query2);
		$aNumRows = mysql_num_rows($playershots);
		$islist = true;
		if(($shot2['ID']==1)||($shot2['ID']==5)||($shot2['ID']==6)||$aNumRows==1){
		$olclass ="bedl-listNone";
		$islist = false;
		}
		
		
		if((!($playershots==false)) &&$aNumRows!=0){
		$output .= '<ol class="'.$olclass.'">';
		$writeendli = false;
		while($aplayershot=mysql_fetch_array($playershots)){
			
			if($aplayershot['shotvalue']==$tempshotvalue){
				$sameshotvalue=true;
			}else{
				$sameshotvalue=false;
			}
			if(((!$sameshotvalue)&&$islist)||!$islist){
				if($writeendli){				
				$output .= "</li>\r\n";
				$writeendli = false;
			}				
				$output .= "<li>";
			}else{
				$output.="<br/>";
			}
			$output .= $aplayershot['first_name'] . ' ' .$aplayershot['last_name'];
			if(isset($aplayershot['shotvalue'])){
				$output.= ' - '.$aplayershot['shotvalue'];
			}
			if(((!$sameshotvalue)&&$islist)||!$islist){
				$writeendli = true;
			}
			$tempshotvalue=$aplayershot['shotvalue'];
		}
		$output .= '</ol>';
		}
		$output .= '</td>';
		}else{
		$output .= '<td class="noFormat"></td><td class="noFormat"></td>';
		}
		
		$output .='</tr>';
	}
	//if( $numShots%2 != 0 )
	//{
	//	$shot=mysql_fetch_array($shots);
	 // 	$output .= '<tr><td width="100" class="stattdgray">'.$shot['shotname'].'</td><td class="stattdltgray">';
	 // 	$query1 = 'select shotvalue, shots.shotname, players.first_name, players.last_name, normshotvaluefrom player_shots, players, shots where players.player_id = player_shots.player_id and player_shots.shotId = shots.ID and player_shots.week_number<='.$week.' and shotId='.$shot['ID'].' and players.division="'.$division.'" order by normshotvalue DESC;';
	 // 	$playershots  = mysql_query($query1);
	 // 	if(!($playershots==false)){
//		while($aplayershot=mysql_fetch_array($playershots)){
//	  		$output .= $aplayershot['first_name'] . ' ' .$aplayershot['last_name'];
//	  		if(isset($aplayershot['shotvalue'])){
//	  			$output.= ' - '.$aplayershot['shotvalue'];
//	  		}
//	  		$output .= "<br>\r\n";
//	  	}
//		}
//	$output .= '</td><td class="noFormat"></td><td class="noFormat"></td></tr>';
//	}
	$output .= '</table>';
	return $output;
}





?>