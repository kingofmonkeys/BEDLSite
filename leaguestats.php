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


//$player = getPlayer($playerId,$log);

$header = "";
$seasonName = getSeasonName($log);

$ratingsInfo = getPlayerRatings($log,$requestedWeek);
	
$playerRatings = $ratingsInfo['players'];
$ratingsDetails = $ratingsInfo['details'];
		
$header .= '<div class="halfWidth">'.$seasonName.' League Stats </div>';
$header .= '<div id="printDate" class="halfWidthRight" >'.date("n/j/Y g:i a").'</div>';


$body ="";

if(isset($requestedWeek)){	

	
	$body .= draw2018Summary($log);
	$body .= drawPlayersPerRank($log);
	$body .= drawPlayersPerRating($playerRatings,$log);
	
	$body .= '<a href="./index.php">Back</a>';
}

$output .= drawContainer($header,$body);

$output .= draw_foot();

echo $output;
//end of page


function draw2018Summary($log){
	$query1 = "select * from 2018_season_summary";
	$summary  = mysql_query($query1);
	
	//THIS IS FOR SORTING THE TABLE
		$output .="<script>$(document).ready(function() {";		
		$output .="  $('#2018Summary').DataTable( {";
        $output .='"paging":   false,';
        $output .='"ordering": true,';
		$output .='"asStripeClasses": [ "stattdgray", "stattdltgray" ],';
		$output .='"searching": false,';

		$output .='"aoColumns": [';	
		$output .= '{ "orderSequence": [ "desc", "asc"] },';	
		$output .= '{ "orderSequence": [ "desc", "asc"] },';		
		$output .= '{ "orderSequence": [ "desc", "asc"] },';
		$output .= '{ "orderSequence": [ "desc", "asc"] },';
		$output .= '{ "orderSequence": [ "desc", "asc"] },';
		$output .= '{ "orderSequence": [ "desc", "asc"] },';
		$output .= '{ "orderSequence": [ "desc", "asc"] },';
		$output .= '{ "orderSequence": [ "desc", "asc"] },';
		$output .= '{ "orderSequence": [ "desc", "asc"] },';
		$output .= '{ "orderSequence": [ "desc", "asc"] },';
		$output .= '{ "orderSequence": [ "desc", "asc"] },';
		$output .= '{ "orderSequence": [ "desc", "asc"] }],';		
        $output .='"info":     false';
		$output .='} );';
		$output .='} );';
		
		$output .='</script>';
	
	$output .= '<table class="stripe hover stattable" id="2018Summary" >';
		$output .= '<thead><tr><th>Average Rank</th><th>Adjusted Avg Rank</th><th width="120">Player Name</th><th>PPGA</th><th>Win %</th><th>Rating</th><th>Win% Rank</th><th>Rating Rank</th><th>PPGA Rank</th><th>Rank</th><th>Total Points</th><th>Total Games</th>';
	
	
		$output .= '</tr></thead><tbody>';
		
	while($record=mysql_fetch_array($summary)){
		$name = $record[0];
		$points = $record[1];
		$games = $record[2];
		$ppga = $record[3];
		$winPercent = $record[4];
		$rating = $record[5];
		$rank = $record[6];
		$winRank = $record[7];
		$ratingRank = $record[8];
		$ppgaRank = $record[9];
		
		$averageRank = round((($winRank + $ratingRank + $ppgaRank) / 3),2);
		$adjustedRank = $averageRank;
		if($rank==2){
			$adjustedRank = $adjustedRank + 2;
		} else if($rank==3){
			$adjustedRank = $adjustedRank + 7;
		}else if($rank==4){
			$adjustedRank = $adjustedRank + 12;
		}
		
		$output .= '<tr><td>'.$averageRank.'</td><td>'.$adjustedRank.'</td><td>'.$name.'</td><td>'.$ppga.'</td><td>'.$winPercent.'</td><td>'.$rating.'</td><td>'.$winRank.'</td><td>'.$ratingRank.'</td><td>'.$ppgaRank.'</td><td>'.$rank.'</td><td>'.$points.'</td><td>'.$games.'</td></tr>';
	}
	
	$output .='</tbody></table>';
	
	return $output;
}

function drawPlayersPerRating($playerRatings,$log){
	$output = "";
	$output .= "<div id='playersPerRating'><!-- Plotly chart will be drawn inside this DIV --></div>"."\r\n";;
	$output .=  "<script>"."\r\n";;
	$output .= "var trace = {"."\r\n";;
	$output .= "x: [";
	$first = True;
	foreach($playerRatings as $player_id => $player){		
		
		if($player->getRating()!=0 && $player->getRating()!="" && $player->getRating()!=null){
			if(!$first){
				$output .= ",";
			}else{
				$first = False;
			}
			$output .= $player->getRating();
		}		
		
	}
	$output .= "],type: 'histogram',"."\r\n";
	$output .= "};"."\r\n";;
	$output .= "var layout = {"."\r\n";;
	$output .= "title: 'Players Per Rating'"."\r\n";;
	$output .= "};"."\r\n";;
	$output .= "var data = [trace];"."\r\n";;
	$output .= "Plotly.newPlot('playersPerRating', data,layout);";
	$output .= "</script>"."\r\n";;
	return $output;
}

function drawPlayersPerRank($log){
	$conn = getDBiConnection($log);	
	$output = "";	
	$query1 = "SELECT rank, COUNT(*) as players FROM players GROUP BY rank order by rank";  
	
	$log->LogDebug("Query for personal player stats: ".$query1);
	$result = mysqli_query($conn,$query1);  //do the query
	
	$output .= "<div id='playersPreRank'><!-- Plotly chart will be drawn inside this DIV --></div>";
	$output .=  "<script>";
	
	$x = array();
	$y = array();
	
	//all this to get the starting rating
	while($thisrow=mysqli_fetch_array($result)){
		$x[] = $thisrow['rank'];
		$y[] = $thisrow['players'];
	}
	
	$output .= "var data = [{";
	$output .= "x: [";
	foreach($x as $r => $r_value){
		if($r !=0){
			$output .= ",";
		}
		$output .= "'Rank ". $r_value."'";
	}
	$output .= "],";
	$output .= "y: [";
	foreach($y as $p => $p_value){
		if($p !=0){
			$output .= ",";
		}
		$output .= $p_value;
	}	
	$output .= "],";
	$output .= "type: 'bar'";
	$output .= "}];";
	
	$output .= "var layout = {";
	$output .= "title: 'Players Per Rank'";
	$output .= "};";

	$output .= "Plotly.newPlot('playersPreRank', data, layout);";
	$output .= "</script>";
	
	return $output;
	
}



function drawPersonalPointsPlayerStats($player,$week,$playerRatings,$log){
	$conn = getDBiConnection($log);	
	$output = "";	
	
	$query1 = "select players.player_id as did, players.rank as rank ";  
	$query1 .=",(select IFNULL((sum(home_player_wins)+sum(visit_player_wins)),0) as singles_501_games_played from singles_games where (home_player_id=did or visit_player_id=did) and game_type=1 and week<=".$week.") as singles_501_games_played";
	$query1 .=",((select  IFNULL(sum(home_player_wins),0) as singles_501_home_games_won from singles_games where home_player_id=did and week<=".$week." and game_type=1) +(select  IFNULL(sum(visit_player_wins),0) as singles_501_visit_games_won from singles_games where visit_player_id=did and week<=".$week." and game_type=1)) as singles_501_games_won";
	
	$query1 .=",(select IFNULL((sum(home_player_wins)+sum(visit_player_wins)),0) as singles_cricket_games_played from singles_games where (home_player_id=did or visit_player_id=did) and week<=".$week." and game_type=2) as singles_cricket_games_played";
	$query1 .=",((select  IFNULL(sum(home_player_wins),0) as singles_cricket_home_games_won from singles_games where home_player_id=did and week<=".$week." and game_type=2) +(select  IFNULL(sum(visit_player_wins),0) as singles_cricket_visit_games_won from singles_games where visit_player_id=did and week<=".$week." and game_type=2)) as singles_cricket_games_won";
	
	
	$query1 .=",(select IFNULL((sum(home_wins)+sum(visit_wins)),0) as doubles_501_games_played from doubles_games where (home_player1_id=did or home_player2_id=did or visit_player1_id=did or visit_player2_id=did) and week<=".$week." and game_type=1) as doubles_501_games_played";
	$query1 .=",((select  IFNULL(sum(home_wins),0) as doubles_501_home_games_won from doubles_games where (home_player1_id=did or home_player2_id=did) and week<=".$week." and game_type=1) + (select  IFNULL(sum(visit_wins),0) as doubles_501_visit_games_won from doubles_games where (visit_player1_id=did or visit_player2_id=did) and week<=".$week." and game_type=1)) as doubles_501_games_won";
	
	$query1 .=",(select IFNULL((sum(home_wins)+sum(visit_wins)),0) as doubles_cricket_games_played from doubles_games where (home_player1_id=did or home_player2_id=did or visit_player1_id=did or visit_player2_id=did) and week<=".$week." and game_type=2) as doubles_cricket_games_played";
	$query1 .=",((select  IFNULL(sum(home_wins),0) as doubles_cricket_home_games_won from doubles_games where (home_player1_id=did or home_player2_id=did) and week<=".$week." and game_type=2) + (select  IFNULL(sum(visit_wins),0) as doubles_cricket_visit_games_won from doubles_games where (visit_player1_id=did or visit_player2_id=did) and week<=".$week." and game_type=2)) as doubles_cricket_games_won";
	$query1 .=",(select sum(s_01_points) from player_stats where player_id=".$player->getPlayerId()." and week_number<=".$week.") as total_s_01_points";
	$query1 .=",(select sum(s_cricket_points) from player_stats where player_id=".$player->getPlayerId()." and week_number<=".$week.") as total_s_cricket_points";
	$query1 .=",(select sum(d_01_points) from player_stats where player_id=".$player->getPlayerId()." and week_number<=".$week.") as total_d_01_points";
	$query1 .=",(select sum(d_cricket_points) from player_stats where player_id=".$player->getPlayerId()." and week_number<=".$week.") as total_d_cricket_points";	
	$query1 .= " from players ";
	$query1 .= "where players.player_id=".$player->getPlayerId();
	
	$log->LogDebug("Query for personal player stats: ".$query1);
	$result = mysqli_query($conn,$query1);  //do the query
	
	$thisrow=mysqli_fetch_array($result);
	//THIS IS FOR SORTING THE TABLE
	$output .="<script>$(document).ready(function() {";		
	$output .="  $('#playerPersonalsTable').DataTable( {";
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
	
	$total_s_01_points = $thisrow['total_s_01_points'];
	$total_s_cricket_points = $thisrow['total_s_cricket_points'];
	$total_d_01_points = $thisrow['total_d_01_points'];
	$total_d_cricket_points = $thisrow['total_d_cricket_points'];
	$singles_501_games_played = $thisrow['singles_501_games_played'];
	$singles_501_games_won = $thisrow['singles_501_games_won'];
	$singles_cricket_games_played = $thisrow['singles_cricket_games_played'];
	$singles_cricket_games_won = $thisrow['singles_cricket_games_won'];
	$doubles_501_games_played = $thisrow['doubles_501_games_played'];
	$doubles_501_games_won = $thisrow['doubles_501_games_won'];
	$doubles_cricket_games_played = $thisrow['doubles_cricket_games_played'];
	$doubles_cricket_games_won = $thisrow['doubles_cricket_games_won'];
	
	$output .= '<div class="basicPlayerStats">';
	$output .= '<div class="divisionHeading">Player Personal Points</div>'."\r\n";
	$output .= '<table class="stripe hover stattable" id="playerPersonalsTable" ><thead><tr><th>Game</th><th>Played</th><th>Won</th><th>Personals</th><th>PPGA</th><th>PPGA Minus CDs</th></tr></thead>';
	$output .= "<tr><td>Singles 501</td>";	
	$output .= "<td>".$singles_501_games_played."</td>";
	$output .= "<td>".$singles_501_games_won."</td>";
	$output .= "<td>".$total_s_01_points."</td>";	
	
	$output .= "<td>";
	if($singles_501_games_played==0){
		$output .= "N/A";
	}else{
		$output .= round($total_s_01_points/$singles_501_games_played,1);
	}
	$output .="</td>";
	$output .= "<td>";
	if($singles_501_games_played==0){
		$output .= "N/A";
	}else{
		$output .= round(($total_s_01_points-($singles_501_games_won*2))/$singles_501_games_played,1);
	}
	$output .="</td>";
	$output .= "</tr>";
	
	$output .= "<tr><td>Singles Cricket</td>";
	$output .= "<td>".$singles_cricket_games_played."</td>";
	$output .= "<td>".$singles_cricket_games_won."</td>";
	$output .= "<td>".$total_s_cricket_points."</td>";
	
	$output .= "<td>";
	if($singles_cricket_games_played==0){
		$output .= "N/A";
	}else{
		$output .= round($total_s_cricket_points/$singles_cricket_games_played,1);
	}
	$output .="</td>";
	$output .= "<td>";
	if($singles_cricket_games_played==0){
		$output .= "N/A";
	}else{
		$output .= round(($total_s_cricket_points-($singles_cricket_games_won*2))/$singles_cricket_games_played,1);
	}
	$output .="</td>";
	$output .= "</tr>";
	
	$output .= "<tr><td>Doubles 501</td>";
	$output .= "<td>".$doubles_501_games_played."</td>";
	$output .= "<td>".$doubles_501_games_won."</td>";
	$output .= "<td>".$total_d_01_points."</td>";
	
	$output .= "<td>";
	if($doubles_501_games_played==0){
		$output .= "N/A";
	}else{
		$output .= round($total_d_01_points/$doubles_501_games_played,1);
	}
	$output .="</td>";
	$output .= "<td>";
	if($doubles_501_games_played==0){
		$output .= "N/A";
	}else{
		$output .= round(($total_d_01_points-($doubles_501_games_won*2))/$doubles_501_games_played,1);
	}
	$output .="</td>";
	$output .= "</tr>";
	
	$output .= "<tr><td>Doubles Cricket</td>";	
	$output .= "<td>".$doubles_cricket_games_played."</td>";
	$output .= "<td>".$doubles_cricket_games_won."</td>";
	$output .= "<td>".$total_d_cricket_points."</td>";
	$output .= "<td>";
	if($doubles_cricket_games_played==0){
		$output .= "N/A";
	}else{
		$output .= round($total_d_cricket_points/$doubles_cricket_games_played,1);
	}
	$output .="</td>";
	$output .= "<td>";
	if($doubles_cricket_games_played==0){
		$output .= "N/A";
	}else{
		$output .= round(($total_d_cricket_points-($doubles_cricket_games_won*2))/$doubles_cricket_games_played,1);
	}
	$output .="</td>";
	$output .= "</tr>";
	
	
	$output .= "</table>";
	$output .= "</div>";
	
	return $output;	
	
	
	
}








?>