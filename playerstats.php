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

$playerId = $_GET['playerId'];

$player = getPlayer($playerId,$log);

$header = "";
$seasonName = getSeasonName($log);

$ratingsInfo = getPlayerRatings($log,$requestedWeek);
	
$playerRatings = $ratingsInfo['players'];
$ratingsDetails = $ratingsInfo['details'];
		
$header .= '<div class="halfWidth">'.$seasonName.' Player Stats for '.$player->getFirstName().' '.$player->getLastName().' ('.$playerRatings[$player->getPlayerId()]->getRating().')</div>';
$header .= '<div id="printDate" class="halfWidthRight" >'.date("n/j/Y g:i a").'</div>';

$body ="";
$body .=  drawWeeklyStatsLinks($playerId,$log);

if(isset($requestedWeek)){	
	$body .= drawBasicPlayerStats($player,$requestedWeek,$playerRatings,$log);	
	$body .= drawVsPlayerStats($player,$requestedWeek,$playerRatings,$log);
	$body .= '<br/>';
	$body .= '<br/>';
	$body .= '<a href="./dynastats.php">Back</a>';
}

$output .= drawContainer($header,$body);

$output .= draw_foot();

echo $output;
//end of page


function drawVsPlayerStats($player,$week,$playerRatings,$log){
	$conn = getDBiConnection($log);	
	
//the sql to get the stats per opponent
//select opponent_id, players.first_name, players.last_name, sum(501_opponent_wins) as 501_opponent_wins, sum(501_player_wins) as 501_player_wins, sum(cricket_opponent_wins) as cricket_opponent_wins, sum(cricket_player_wins) as cricket_player_wins 
//from (
//	select * from (
//		select 501_stats.opponent_id, sum(501_stats.501_opponent_wins) as 501_opponent_wins,sum(501_stats.501_player_wins) as 501_player_wins, 0 as cricket_opponent_wins, 0 as cricket_player_wins
//			from (
//				select home_player_id as opponent_id, home_player_wins as 501_opponent_wins, visit_player_wins as 501_player_wins from singles_games where visit_player_id=65 and week<=3 and game_type=1
//				union all 
//				select visit_player_id as opponent_id, visit_player_wins as 501_opponent_wins, home_player_wins as 501_player_wins from singles_games WHERE home_player_id=65 and week<=3 and game_type=1
//			) 501_stats group by 501_stats.opponent_id
//		) 501_stats 
//
//	union all
//
//	select * from (
//		select cricket_stats.opponent_id, 0 as 501_opponent_wins,0 as 501_player_wins,sum(cricket_stats.cricket_opponent_wins) as cricket_opponent_wins,sum(cricket_stats.cricket_player_wins) as cricket_player_wins
//			from (
//				select home_player_id as opponent_id, home_player_wins as cricket_opponent_wins, visit_player_wins as cricket_player_wins from singles_games where visit_player_id=65 and week<=3 and game_type=2
//				union all 
//				select visit_player_id as opponent_id, visit_player_wins as cricket_opponent_wins, home_player_wins as cricket_player_wins from singles_games WHERE home_player_id=65 and week<=3 and game_type=2
//			) cricket_stats group by cricket_stats.opponent_id
//		) cricket_stats
//) combined_stats, players where players.player_id=opponent_id group by opponent_id
	
	$query1 = "select opponent_id, players.first_name, players.last_name, sum(501_opponent_wins) as 501_opponent_wins, sum(501_player_wins) as 501_player_wins, sum(cricket_opponent_wins) as cricket_opponent_wins, sum(cricket_player_wins) as cricket_player_wins ";
	$query1 .= "from (";
	$query1 .= "select * from (";
	$query1 .= "select 501_stats.opponent_id, sum(501_stats.501_opponent_wins) as 501_opponent_wins,sum(501_stats.501_player_wins) as 501_player_wins, 0 as cricket_opponent_wins, 0 as cricket_player_wins ";
	$query1 .= "from (";
	$query1 .= "select home_player_id as opponent_id, home_player_wins as 501_opponent_wins, visit_player_wins as 501_player_wins from singles_games where visit_player_id=".$player->getPlayerId()." and week<=".$week." and game_type=1 ";
	$query1 .= "union all ";
	$query1 .= "select visit_player_id as opponent_id, visit_player_wins as 501_opponent_wins, home_player_wins as 501_player_wins from singles_games WHERE home_player_id=".$player->getPlayerId()." and week<=".$week." and game_type=1 ";
	$query1 .= ") 501_stats group by 501_stats.opponent_id) 501_stats ";
	$query1 .= "union all ";
	$query1 .= "select * from (";
	$query1 .= "select cricket_stats.opponent_id, 0 as 501_opponent_wins,0 as 501_player_wins,sum(cricket_stats.cricket_opponent_wins) as cricket_opponent_wins,sum(cricket_stats.cricket_player_wins) as cricket_player_wins ";
	$query1 .= "from (select home_player_id as opponent_id, home_player_wins as cricket_opponent_wins, visit_player_wins as cricket_player_wins from singles_games where visit_player_id=".$player->getPlayerId()." and week<=".$week." and game_type=2 ";
	$query1 .= "union all "; 
	$query1 .= "select visit_player_id as opponent_id, visit_player_wins as cricket_opponent_wins, home_player_wins as cricket_player_wins from singles_games WHERE home_player_id=".$player->getPlayerId()." and week<=".$week." and game_type=2 ";
	$query1 .= ") cricket_stats group by cricket_stats.opponent_id ";
	$query1 .= ") cricket_stats ";
	$query1 .= ") combined_stats, players where players.player_id=opponent_id group by opponent_id";
	
	$log->LogDebug("Query for vs player stats: ".$query1);
	$result = mysqli_query($conn,$query1);  //do the query
	
	if(!$result){
		$log->LogDebug("Sql error: ".mysqli_error ($conn));		
	}
	
	//THIS IS FOR SORTING THE TABLE
	$output .="<script>$(document).ready(function() {";		
	$output .="  $('#playerVsOpponentTable').DataTable( {";
	
	$output .= '"columns": [';
    $output .= '{ "width": "20%" },';
    $output .= 'null,';
    $output .= 'null,';
    $output .= 'null,';
	$output .= 'null,';
	$output .= 'null,';
    $output .= 'null],';	
	
	$output .='"columnDefs": [{"className": "dt-center", "targets": "_all"}],';	
	$output .='"aaSorting": [],';
    $output .='"paging":   false,';
    $output .='"ordering": true,';
	$output .='"asStripeClasses": [ "stattdgray", "stattdltgray" ],';
	$output .='"searching": false,';		
    $output .='"info":     false';
	$output .='} );';
	$output .='} );';
		
	$output .='</script>';
	
	$output .= '<div class="basicPlayerStats">';
	$output .= '<div class="divisionHeading">VS Opponent</div>'."\r\n";
	$output .= '<table class="stripe hover stattable" id="playerVsOpponentTable" ><thead><tr><th>Opponent</th><th>501 Games</th><th>501 %</th><th>Cricket Games</th><th>Cricket %</th><th>Overall Games</th><th>Overall %</th></tr></thead>';
	
	while($thisrow=mysqli_fetch_array($result)){
		
	$output .= "<tr><td>".$thisrow['first_name']." ".$thisrow['last_name'];
	$output .= " (".$playerRatings[$thisrow['opponent_id']]->getRating().")";
	$output .= "</td>";
	
	$opponent_501_wins = $thisrow['501_opponent_wins'];
	$player_501_wins = $thisrow['501_player_wins'];
	$cricket_opponent_wins = $thisrow['cricket_opponent_wins'];
	$cricket_player_wins = $thisrow['cricket_player_wins'];
	$total_501_games= $opponent_501_wins+$player_501_wins;
	$total_cricket_games=$cricket_opponent_wins+$cricket_player_wins;
	
	$output .= "<td>".$total_501_games."</td>";	
	$output .= "<td>";	
	if ($total_501_games==0){
		$output .='';
	}else{
		$output .= round(($player_501_wins/$total_501_games)*100,1);
	}
	$output .=	"</td>";
	
	$output .= "<td>".$total_cricket_games."</td>";		
	$output .= "<td>";	
	if ($total_cricket_games==0){
		$output .='';
	}else{
		$output .= round(($cricket_player_wins/$total_cricket_games)*100,1);
	}
	$output .=	"</td>";
	
	$output .= "<td>".($total_cricket_games+$total_501_games)."</td>";	
	
	$output .= "<td>";	
	if ($total_cricket_games+$total_501_games==0){
		$output .='';
	}else{
		$output .= round((($cricket_player_wins+$player_501_wins)/($total_cricket_games+$total_501_games))*100,1);
	}
	$output .=	"</td>";
	$output .= "</tr>";	
	
	}
	
	$output .= "</table>";
	$output .= "</div>";
	
	return $output;	
}



function drawBasicPlayerStats($player,$week,$playerRatings,$log){
	$conn = getDBiConnection($log);
	$query1 = "select players.player_id as did, players.rank as rank ";  
	$query1 .=",(select IFNULL((sum(home_player_wins)+sum(visit_player_wins)),0) as singles_501_games_played from singles_games where (home_player_id=did or visit_player_id=did) and game_type=1 and week<=".$week.") as singles_501_games_played";
	$query1 .=",((select  IFNULL(sum(home_player_wins),0) as singles_501_home_games_won from singles_games where home_player_id=did and week<=".$week." and game_type=1) +(select  IFNULL(sum(visit_player_wins),0) as singles_501_visit_games_won from singles_games where visit_player_id=did and week<=".$week." and game_type=1)) as singles_501_games_won";
	
	$query1 .=",(select IFNULL((sum(home_player_wins)+sum(visit_player_wins)),0) as singles_cricket_games_played from singles_games where (home_player_id=did or visit_player_id=did) and week<=".$week." and game_type=2) as singles_cricket_games_played";
	$query1 .=",((select  IFNULL(sum(home_player_wins),0) as singles_cricket_home_games_won from singles_games where home_player_id=did and week<=".$week." and game_type=2) +(select  IFNULL(sum(visit_player_wins),0) as singles_cricket_visit_games_won from singles_games where visit_player_id=did and week<=".$week." and game_type=2)) as singles_cricket_games_won";
	
	
	$query1 .=",(select IFNULL((sum(home_wins)+sum(visit_wins)),0) as doubles_501_games_played from doubles_games where (home_player1_id=did or home_player2_id=did or visit_player1_id=did or visit_player2_id=did) and week<=".$week." and game_type=1) as doubles_501_games_played";
	$query1 .=",((select  IFNULL(sum(home_wins),0) as doubles_501_home_games_won from doubles_games where (home_player1_id=did or home_player2_id=did) and week<=".$week." and game_type=1) + (select  IFNULL(sum(visit_wins),0) as doubles_501_visit_games_won from doubles_games where (visit_player1_id=did or visit_player2_id=did) and week<=".$week." and game_type=1)) as doubles_501_games_won";
	
	$query1 .=",(select IFNULL((sum(home_wins)+sum(visit_wins)),0) as doubles_cricket_games_played from doubles_games where (home_player1_id=did or home_player2_id=did or visit_player1_id=did or visit_player2_id=did) and week<=".$week." and game_type=2) as doubles_cricket_games_played";
	$query1 .=",((select  IFNULL(sum(home_wins),0) as doubles_cricket_home_games_won from doubles_games where (home_player1_id=did or home_player2_id=did) and week<=".$week." and game_type=2) + (select  IFNULL(sum(visit_wins),0) as doubles_cricket_visit_games_won from doubles_games where (visit_player1_id=did or visit_player2_id=did) and week<=".$week." and game_type=2)) as doubles_cricket_games_won";
	
	
	for ($x = 1; $x <= 5; $x++) {	
		$query1 .=",(select (select IFNULL((sum(home_player_wins)+sum(visit_player_wins)),0) as rank".$x."_501_home_games_played from singles_games where home_player_id=did and visit_player_id in (select player_id from players where rank=".$x.") and week<=".$week." and game_type=1) + ";
		$query1 .="(select IFNULL((sum(home_player_wins)+sum(visit_player_wins)),0) as rank".$x."_501_visit_games_played from singles_games where visit_player_id=did and home_player_id in (select player_id from players where rank=".$x.") and week<=".$week." and game_type=1)) as rank".$x."_501_games_played";
	
		$query1 .=",(select (select IFNULL((sum(home_player_wins)),0) as rank".$x."_501_home_games_played from singles_games where home_player_id=did and visit_player_id in (select player_id from players where rank=".$x.") and week<=".$week." and game_type=1) + ";
		$query1 .="(select IFNULL((sum(visit_player_wins)),0) as rank".$x."_501_home_games_played from singles_games where visit_player_id=did and home_player_id in (select player_id from players where rank=".$x.") and week<=".$week." and game_type=1)) as rank".$x."_501_games_won";
	
		$query1 .=",(select (select IFNULL((sum(home_player_wins)+sum(visit_player_wins)),0) as rank".$x."_cricket_home_games_played from singles_games where home_player_id=did and visit_player_id in (select player_id from players where rank=".$x.") and week<=".$week." and game_type=2) + ";
		$query1 .="(select IFNULL((sum(home_player_wins)+sum(visit_player_wins)),0) as rank".$x."_cricket_visit_games_played from singles_games where visit_player_id=did and home_player_id in (select player_id from players where rank=".$x.") and week<=".$week." and game_type=2)) as rank".$x."_cricket_games_played";
	
		$query1 .=",(select (select IFNULL((sum(home_player_wins)),0) as rank".$x."_cricket_home_games_played from singles_games where home_player_id=did and visit_player_id in (select player_id from players where rank=".$x.") and week<=".$week." and game_type=2) + ";
		$query1 .="(select IFNULL((sum(visit_player_wins)),0) as rank".$x."_cricket_home_games_played from singles_games where visit_player_id=did and home_player_id in (select player_id from players where rank=".$x.") and week<=".$week." and game_type=2)) as rank".$x."_cricket_games_won";
	}
	
	
	$query1 .= " from players ";
	$query1 .= "where players.player_id=".$player->getPlayerId();
	
	$log->LogDebug("Query for basic player stats: ".$query1);
	$result = mysqli_query($conn,$query1);  //do the query
	
	$thisrow=mysqli_fetch_array($result);
	//THIS IS FOR SORTING THE TABLE
	$output .="<script>$(document).ready(function() {";		
	$output .="  $('#playerSinglesStatsTable').DataTable( {";
	$output .='"columnDefs": [{"className": "dt-center", "targets": "_all"}],';	
	$output .='"aaSorting": [],';
    $output .='"paging":   false,';
    $output .='"ordering": true,';
	$output .='"asStripeClasses": [ "stattdgray", "stattdltgray" ],';
	$output .='"searching": false,';		
    $output .='"info":     false';
	$output .='} );';
	$output .='} );';
		
	$output .='</script>';
	
	$output .="<script>$(document).ready(function() {";		
	$output .="  $('#playerDoublesStatsTable').DataTable( {";
	$output .='"columnDefs": [{"className": "dt-center", "targets": "_all"}],';	
	$output .='"aaSorting": [],';
    $output .='"paging":   false,';
    $output .='"ordering": true,';
	$output .='"asStripeClasses": [ "stattdgray", "stattdltgray" ],';
	$output .='"searching": false,';		
    $output .='"info":     false';
	$output .='} );';
	$output .='} );';
		
	$output .='</script>';
	
	
	$output .= '<div class="halfWidth">';	
	$output .= '<div class="divisionHeading">Singles</div>'."\r\n";
	$output .= '<div class="basicPlayerStats">';
	$output .= '<table class="stripe hover stattable" id="playerSinglesStatsTable" ><thead><tr><th>Game</th><th>Played</th><th>Wins</th><th>Win %</th></tr></thead>';
	
	
	$totalSinglesGames = $thisrow['singles_501_games_played']+$thisrow['singles_cricket_games_played'];
	$totalSinglesWins = $thisrow['singles_501_games_won']+$thisrow['singles_cricket_games_won'];	
	
	$output .= "<tr><td>501</td><td>".$thisrow['singles_501_games_played']."</td><td>".$thisrow['singles_501_games_won']."</td><td>";
	
	if ($thisrow['singles_501_games_played']==0){
					$output .='';
				}else{
					$output .= round(($thisrow['singles_501_games_won']/$thisrow['singles_501_games_played'])*100,1);
				}
	$output .=	"</td></tr>";
	
	$output .= "<tr><td>Cricket</td><td>".$thisrow['singles_cricket_games_played']."</td><td>".$thisrow['singles_cricket_games_won']."</td><td>";
	
	if ($thisrow['singles_cricket_games_played']==0){
					$output .='';
				}else{
					$output .= round(($thisrow['singles_cricket_games_won']/$thisrow['singles_cricket_games_played'])*100,1);
				}
	$output .=	"</td></tr>";
	
	$output .= "<tr><td>Total</td><td>".$totalSinglesGames."</td><td>".$totalSinglesWins."</td><td>";
	
	if ($totalSinglesGames==0){
					$output .='';
				}else{
					$output .= round(($totalSinglesWins/$totalSinglesGames)*100,1);
				}
	$output .=	"</td></tr>";
	
	$output .= "</table>";
	$output .="</div>";
	//doubles
	
	$output .= '<div class="divisionHeading">Doubles</div>'."\r\n";
	$output .= '<div class="basicPlayerStats">';
	$output .= '<table class="stripe hover stattable" id="playerDoublesStatsTable" ><thead><tr><th>Game</th><th>Played</th><th>Wins</th><th>Win %</th></tr></thead>';
		
	$totalDoublesGames = $thisrow['doubles_501_games_played']+$thisrow['doubles_cricket_games_played'];
	$totalDoublesWins = $thisrow['doubles_501_games_won']+$thisrow['doubles_cricket_games_won'];
	
	$output .= "<tr><td>501</td><td>".$thisrow['doubles_501_games_played']."</td><td>".$thisrow['doubles_501_games_won']."</td><td>";
	
	if ($thisrow['doubles_501_games_played']==0){
					$output .='';
				}else{
					$output .= round(($thisrow['doubles_501_games_won']/$thisrow['doubles_501_games_played'])*100,1);
				}
	$output .=	"</td></tr>";
	
	$output .= "<tr><td>Cricket</td><td>".$thisrow['doubles_cricket_games_played']."</td><td>".$thisrow['doubles_cricket_games_won']."</td><td>";
		
	if ($thisrow['doubles_cricket_games_played']==0){
					$output .='';
				}else{
					$output .= round(($thisrow['doubles_cricket_games_won']/$thisrow['doubles_cricket_games_played'])*100,1);
				}
	$output .=	"</td></tr>";
	
	$output .= "<tr><td>Total</td><td>".$totalDoublesGames."</td><td>".$totalDoublesWins."</td><td>";
	
	if ($totalDoublesGames==0){
					$output .='';
				}else{
					$output .= round(($totalDoublesWins/$totalDoublesGames)*100,1);
				}
	$output .=	"</td></tr>";
	
	$output .= "</table>";	
	$output .='</div>';
	$output .='</div>';
	
	
	$output .= '<div class="halfWidth">';
	
	
	$playerRank = $thisrow['rank'];
	//Singles Per Rank
	for ($x = 1; $x <= 5; $x++) {	
	
	
	$totalRankGames = $thisrow['rank'.$x.'_501_games_played']+$thisrow['rank'.$x.'_cricket_games_played'];
	$totalRankWins = $thisrow['rank'.$x.'_501_games_won']+$thisrow['rank'.$x.'_cricket_games_won'];
	
	if($totalRankGames!=0){
	
	$output .="<script>$(document).ready(function() {";		
	$output .="  $('#playerPerRank".$x."StatsTable').DataTable( {";
	$output .='"columnDefs": [{"className": "dt-center", "targets": "_all"}],';	
	$output .='"aaSorting": [],';
    $output .='"paging":   false,';
    $output .='"ordering": true,';
	$output .='"asStripeClasses": [ "stattdgray", "stattdltgray" ],';
	$output .='"searching": false,';		
    $output .='"info":     false';
	$output .='} );';
	$output .='} );';
		
	$output .='</script>';
	
	
	$output .= '<div class="divisionHeading">Singles Vs Rank '.$x;
	if($playerRank<$x){
		$output .= " (Give Handicap)";
	}else if($playerRank==$x){
		$output .= " (No Handicap)";
	}else if($playerRank>$x){
		$output .= " (Get Handicap)";
	}
	$output .='</div>'."\r\n";
	$output .= '<div class="basicPlayerStats">';
	$output .= '<table class="stripe hover stattable" id="playerPerRank'.$x.'StatsTable" ><thead><tr><th>Game</th><th>Played</th><th>Wins</th><th>Win %</th></tr></thead>';
		
	
	
	$output .= "<tr><td>501</td><td>".$thisrow['rank'.$x.'_501_games_played']."</td><td>".$thisrow['rank'.$x.'_501_games_won']."</td><td>";
	
	if ($thisrow['rank'.$x.'_501_games_played']==0){
					$output .='';
				}else{
					$output .= round(($thisrow['rank'.$x.'_501_games_won']/$thisrow['rank'.$x.'_501_games_played'])*100,1);
				}
	$output .=	"</td></tr>";
	
	$output .= "<tr><td>Cricket</td><td>".$thisrow['rank'.$x.'_cricket_games_played']."</td><td>".$thisrow['rank'.$x.'_cricket_games_won']."</td><td>";
		
	if ($thisrow['rank'.$x.'_cricket_games_played']==0){
					$output .='';
				}else{
					$output .= round(($thisrow['rank'.$x.'_cricket_games_won']/$thisrow['rank'.$x.'_cricket_games_played'])*100,1);
				}
	$output .=	"</td></tr>";
	
	$output .= "<tr><td>Total</td><td>".$totalRankGames."</td><td>".$totalRankWins."</td><td>";
	
	if ($totalRankGames==0){
					$output .='';
				}else{
					$output .= round(($totalRankWins/$totalRankGames)*100,1);
				}
	$output .=	"</td></tr>";
	
	$output .= "</table>";
	$output .= "</div>";
	}
	}
	
	$output .= "</div>";
	
	return $output;
}


function drawWeeklyStatsLinks($playerId,$log){
	$output = "";
	$chandle = getDBConnection($log);
	$query1 = "select distinct week from singles_games order by week ASC";
	$result = mysql_query($query1);  //do the query
	if(!$result){
		$log->LogError("There was an error on the playerStats page running sql: ".$query1);
	 	header("Location: ErrorPage.php");
	}

	$output .= '<div class="whitebg"><div class="halfWidth">Week: ';
	$i=0;
	while($thisrow=mysql_fetch_array($result)){
		if($i!=0){
			$output .= ' | ';
		}
		$i=$i+1;
		$output .= '<a href="./playerstats.php?week='.$thisrow["week"].'&playerId='.$playerId.'">'.$thisrow["week"].'</a>';
	}
	$output .= '</div><div class="halfWidthRight"></div></div>';
	if($i==0){
		//this means no stats are ready.
		$output .= ' There are currently no stats for this season.';
	}
	return $output;
}



//this is using elo, the base player score is 1000+(personal points per game average * 20);
function getPlayerRatings($log,$week){
	$players = getPlayers($log);
	$conn = getDBiConnection($log);
	$debug = False;
	
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
				$player->setRating(round(1000+($ppga*20)));	
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
					$newRatings = getNewRatings($homePlayer, $visitPlayer, $homePlayerId,$debug,$log);		
					$homePlayer=$newRatings[$homePlayer->getPlayerId()];
					$visitPlayer=$newRatings[$visitPlayer->getPlayerId()];			
					$players[$homePlayer->getPlayerId()] = $newRatings[$homePlayer->getPlayerId()];
					$players[$visitPlayer->getPlayerId()] = $newRatings[$visitPlayer->getPlayerId()];
					$details .= "<div class='gameDetails'>For winning a game <span class='playerName'>".$homePlayer->getFirstName()." ".$homePlayer->getLastName()."</span> gained ".$newRatings['pointsExchanged'] ." points from <span class='playerName'>" . $visitPlayer->getFirstName()." ".$visitPlayer->getLastName()."</span>. New ratings: <span class='playerName'>".$homePlayer->getFirstName()." ".$homePlayer->getLastName()."</span>: ".$homePlayer->getRating().", <span class='playerName'>".$visitPlayer->getFirstName()." ".$visitPlayer->getLastName()."</span>: ".$visitPlayer->getRating()."</div>";
					
				}
		
				//loop thru visit wins
				for ($v = 0; $v < $visitWins; $v++) {
					//for each home win compute the elo 			
					$newRatings = getNewRatings($homePlayer, $visitPlayer, $visitPlayerId,$debug,$log);		
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