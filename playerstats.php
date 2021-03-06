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
//set the player rating from the calculated playerRatings
$player->setRating($playerRatings[$player->getPlayerId()]->getRating());

$header .= '<div class="halfWidth">'.$seasonName.' Player Stats for '.$player->getFirstName().' '.$player->getLastName().' ('.$playerRatings[$player->getPlayerId()]->getRating().')</div>';
$header .= '<div id="printDate" class="halfWidthRight" >'.date("n/j/Y g:i a").'</div>';

$body ="";
$body .=  drawWeeklyStatsLinks($playerId,$log);

if(isset($requestedWeek)){	

	$body .= drawPersonalPointsPlayerStats($player,$requestedWeek,$playerRatings,$log);	
	$body .= drawBasicPlayerStats($player,$requestedWeek,$playerRatings,$log);	
	$body .= drawVsPlayerStats($player,$requestedWeek,$playerRatings,$log);
	$body .= drawDoublesPartnerPlayerStats($player,$requestedWeek,$playerRatings,$log);
	$body .= drawExpectedWinPlayerStats($player,$requestedWeek,$playerRatings,$log);
	
	$body .= '<br/>';
	$body .= '<br/>';
	$body .= '<a href="./dynastats.php">Back</a>';
}

$output .= drawContainer($header,$body);

$output .= draw_foot();

echo $output;
//end of page


function drawExpectedWinPlayerStats($player,$week,$playerRatings,$log){	
	$rankOffset = 50;
	$output = "";	
	
	
	//THIS IS FOR SORTING THE TABLE
	$output .="<script>$(document).ready(function() {";		
	$output .="  $('#expectedWinsTable').DataTable( {";
	$output .='"columnDefs": [{"className": "dt-center", "targets": "_all"}],';	
	//$output .='"aaSorting": [],';
    $output .='"paging":   false,';
    $output .='"ordering": true,';
	$output .='"asStripeClasses": [ "stattdgray", "stattdltgray" ],';
	$output .='"searching": false,';		
    $output .='"info":     false';
	$output .='} );';
	$output .='} );';
		
	$output .='</script>';
	
	
	$output .= '<div class="basicPlayerStats">';
	$output .= '<div class="divisionHeading">Expected Win %</div>'."\r\n";
	$output .= '<table class="stripe hover stattable" id="expectedWinsTable" ><thead><tr><th>Opponent(Rank)(Rating)</th><th>Expected Win % Heads up</th><th>Expected Win % with Handicap</th></tr></thead>';
	
	//loop thru all players here
	foreach($playerRatings as $x => $opponent) {
		//if here because we dont want to display the player...
		//also exclude players with no ratings
		//also exclude subs and no opponents
		if($x!=$player->getPlayerId()&&$opponent->getRating()!=0 && $x!=-1 && $x!=-2){
			$output .= '<tr><td>'.$opponent->getFirstName().' '.$opponent->getLastName().'('.$opponent->getRank().')('.$opponent->getRating().')</td>';
			
			// This gets the expected score for the ratingA player
			$expectedWinsHeadsUp = getExpectedScore($player->getRating(), $opponent->getRating());			
			$output .= '<td>'. round($expectedWinsHeadsUp,2)*100 .'%</td>';		
			
			
			$playerAdjustedRating = $player->getRating();
			$opponentAdjustedRating = $opponent->getRating();
			if($rankOffset!=0){
				if($player->getRank()>$opponent->getRank()){
					//player a is a lower rank than b so they get a handicap we should raise their ranking for this match.
					$adjust = ($player->getRank()-$opponent->getRank())*$rankOffset;
					$playerAdjustedRating = $player->getRating()+$adjust;		
				}else if($opponent->getRank()>$player->getRank()){
					//player b is a lower rank than player a so they get a handicap we should raise their ranking for this match.
					$adjust = ($opponent->getRank()-$player->getRank())*$rankOffset;
					$opponentAdjustedRating = $opponent->getRating()+$adjust;		
				}	
			}
			$expectedWinsAdjusted = getExpectedScore($playerAdjustedRating, $opponentAdjustedRating);	
			
			
			$output .= '<td>'. round($expectedWinsAdjusted,2)*100 .'%</td>';
					
			$output .= '</tr>';
		}
			
	}
	
	$output .= "</table>";
	$output .= "</div>";
	
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
	
	//$log->LogDebug("Query for personal player stats: ".$query1);
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


function drawDoublesPartnerPlayerStats($player,$week,$playerRatings,$log){
	$conn = getDBiConnection($log);	

//select player_id, partner_id, sum(501_wins) as 501_wins, sum(501_loses) as 501_loses, sum(cricket_wins) as cricket_wins, sum(cricket_losses) as cricket_loses 
//from (
//select player_id, partner_id, sum(wins) as 501_wins, sum(loses) as 501_loses, 0 as cricket_wins, 0 as cricket_losses
//	from (		
//		select player_id, partner_id, sum(wins) as wins, sum(loses) as loses 
//		from (
//			select home_player1_id as player_id, home_player2_id as partner_id, sum(home_wins) as wins, sum(visit_wins) as loses FROM doubles_games where home_player1_id=65 and week<=3 and game_type=1 group by partner_id
//			union all
//			select home_player2_id as player_id, home_player1_id as partner_id, sum(home_wins) as wins, sum(visit_wins) as loses from doubles_games where home_player2_id=65 and week<=3 and game_type=1 group by partner_id
//		) home_games group by partner_id
//		union all	
//		select player_id, partner_id, sum(wins) as wins, sum(loses) as loses 
//		from ( 
//			select visit_player1_id as player_id, visit_player2_id as partner_id, sum(visit_wins) as wins, sum(home_wins) as loses FROM doubles_games where visit_player1_id=65 and week<=3 and game_type=1 group by partner_id
//			union all
//			select visit_player2_id as player_id, visit_player1_id as partner_id, sum(visit_wins) as wins, sum(home_wins) as loses from doubles_games where visit_player2_id=65 and week<=3 and game_type=1 group by partner_id
//		) visit_games group by partner_id
//) 501_games group by partner_id
//union all
//select player_id, partner_id, 0 as 501_wins, 0 as 501_loses, sum(wins) as cricket_wins, sum(loses) as cricket_losses
//	from (		
//		select player_id, partner_id, sum(wins) as wins, sum(loses) as loses 
//		from (
//			select home_player1_id as player_id, home_player2_id as partner_id, sum(home_wins) as wins, sum(visit_wins) as loses FROM doubles_games where home_player1_id=65 and week<=3 and game_type=2 group by partner_id
//			union all
//			select home_player2_id as player_id, home_player1_id as partner_id, sum(home_wins) as wins, sum(visit_wins) as loses from doubles_games where home_player2_id=65 and week<=3 and game_type=2 group by partner_id
//		) home_games group by partner_id
//		union all	
//		select player_id, partner_id, sum(wins) as wins, sum(loses) as loses 
//		from ( 
//			select visit_player1_id as player_id, visit_player2_id as partner_id, sum(visit_wins) as wins, sum(home_wins) as loses FROM doubles_games where visit_player1_id=65 and week<=3 and game_type=2 group by partner_id
//			union all
//			select visit_player2_id as player_id, visit_player1_id as partner_id, sum(visit_wins) as wins, sum(home_wins) as loses from doubles_games where visit_player2_id=65 and week<=3 and game_type=2 group by partner_id
//		) visit_games group by partner_id
//) cricket_games group by partner_id
//	) combined_stats group by partner_id
	
	$query1 = "select combined_stats.player_id, partner_id, players.first_name, players.last_name, sum(501_wins) as 501_wins, sum(501_loses) as 501_loses, sum(cricket_wins) as cricket_wins, sum(cricket_losses) as cricket_loses ";
	$query1 .= "from (";
	$query1 .="select player_id, partner_id, sum(wins) as 501_wins, sum(loses) as 501_loses, 0 as cricket_wins, 0 as cricket_losses ";
    $query1 .="from (";		
	$query1 .="select player_id, partner_id, sum(wins) as wins, sum(loses) as loses ";
	$query1 .="from (";
	$query1 .="select home_player1_id as player_id, home_player2_id as partner_id, sum(home_wins) as wins, sum(visit_wins) as loses FROM doubles_games where home_player1_id=".$player->getPlayerId()." and week<=".$week." and game_type=1 group by partner_id ";
	$query1 .="union all ";
	$query1 .="select home_player2_id as player_id, home_player1_id as partner_id, sum(home_wins) as wins, sum(visit_wins) as loses from doubles_games where home_player2_id=".$player->getPlayerId()." and week<=".$week." and game_type=1 group by partner_id ";
	$query1 .=") home_games group by partner_id ";
	$query1 .="union all ";	
	$query1 .="select player_id, partner_id, sum(wins) as wins, sum(loses) as loses ";
	$query1 .="from ("; 
	$query1 .="select visit_player1_id as player_id, visit_player2_id as partner_id, sum(visit_wins) as wins, sum(home_wins) as loses FROM doubles_games where visit_player1_id=".$player->getPlayerId()." and week<=".$week." and game_type=1 group by partner_id ";
	$query1 .="union all ";
	$query1 .="select visit_player2_id as player_id, visit_player1_id as partner_id, sum(visit_wins) as wins, sum(home_wins) as loses from doubles_games where visit_player2_id=".$player->getPlayerId()." and week<=".$week." and game_type=1 group by partner_id ";
	$query1 .=") visit_games group by partner_id ";
	$query1 .=") 501_games group by partner_id ";
	$query1 .="union all ";
	$query1 .="select player_id, partner_id, 0 as 501_wins, 0 as 501_loses, sum(wins) as cricket_wins, sum(loses) as cricket_losses ";
	$query1 .="from (";		
	$query1 .="select player_id, partner_id, sum(wins) as wins, sum(loses) as loses ";
	$query1 .="from (";
	$query1 .="select home_player1_id as player_id, home_player2_id as partner_id, sum(home_wins) as wins, sum(visit_wins) as loses FROM doubles_games where home_player1_id=".$player->getPlayerId()." and week<=".$week." and game_type=2 group by partner_id ";
	$query1 .="union all ";
	$query1 .="select home_player2_id as player_id, home_player1_id as partner_id, sum(home_wins) as wins, sum(visit_wins) as loses from doubles_games where home_player2_id=".$player->getPlayerId()." and week<=".$week." and game_type=2 group by partner_id ";
	$query1 .=") home_games group by partner_id ";
	$query1 .="	union all ";
	$query1 .="	select player_id, partner_id, sum(wins) as wins, sum(loses) as loses ";
	$query1 .="from ("; 
	$query1 .="select visit_player1_id as player_id, visit_player2_id as partner_id, sum(visit_wins) as wins, sum(home_wins) as loses FROM doubles_games where visit_player1_id=".$player->getPlayerId()." and week<=".$week." and game_type=2 group by partner_id ";
	$query1 .="union all ";
	$query1 .="select visit_player2_id as player_id, visit_player1_id as partner_id, sum(visit_wins) as wins, sum(home_wins) as loses from doubles_games where visit_player2_id=".$player->getPlayerId()." and week<=".$week." and game_type=2 group by partner_id ";
	$query1 .=") visit_games group by partner_id ";
	$query1 .=") cricket_games group by partner_id ";
	$query1 .=") combined_stats, players where players.player_id=partner_id group by partner_id ";
	
		
	//$log->LogDebug("Query for doubles partner player stats: ".$query1);
	$result = mysqli_query($conn,$query1);  //do the query
	
	if(!$result){
		$log->LogDebug("Sql error: ".mysqli_error ($conn));		
	}
	
	//THIS IS FOR SORTING THE TABLE
	$output .="<script>$(document).ready(function() {";		
	$output .="  $('#playerWithPartnerTable').DataTable( {";
	
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
	$output .= '<div class="divisionHeading">Doubles with Partner</div>'."\r\n";
	$output .= '<table class="stripe hover stattable" id="playerWithPartnerTable" ><thead><tr><th>Partner</th><th>501 Games</th><th>501 %</th><th>Cricket Games</th><th>Cricket %</th><th>Overall Games</th><th>Overall %</th></tr></thead>';
	
	while($thisrow=mysqli_fetch_array($result)){
		
	$output .= "<tr><td>".$thisrow['first_name']." ".$thisrow['last_name'];
	$output .= " (".$playerRatings[$thisrow['partner_id']]->getRating().")";
	$output .= "</td>";
	
	
	//player_id, partner_id, sum(501_wins) as 501_wins, sum(501_loses) as 501_loses, sum(cricket_wins) as cricket_wins, sum(cricket_losses) as cricket_loses 
	$wins_501 =$thisrow['501_wins'];
	$loses_501 =$thisrow['501_loses'];	
	$wins_cricket = $thisrow['cricket_wins'];
	$loses_cricket = $thisrow['cricket_loses'];
	$total_501_games= $wins_501 + $loses_501;
	$total_cricket_games=$wins_cricket + $loses_cricket;
	
	$output .= "<td>".$total_501_games."</td>";	
	$output .= "<td>";	
	if ($total_501_games==0){
		$output .='';
	}else{
		$output .= round(($wins_501/$total_501_games)*100,1);
	}
	$output .=	"</td>";
	
	$output .= "<td>".$total_cricket_games."</td>";		
	$output .= "<td>";	
	if ($total_cricket_games==0){
		$output .='';
	}else{
		$output .= round(($wins_cricket/$total_cricket_games)*100,1);
	}
	$output .=	"</td>";
	
	$output .= "<td>".($total_cricket_games+$total_501_games)."</td>";	
	
	$output .= "<td>";	
	if ($total_cricket_games+$total_501_games==0){
		$output .='';
	}else{
		$output .= round((($wins_cricket+$wins_501)/($total_cricket_games+$total_501_games))*100,1);
	}
	$output .=	"</td>";
	$output .= "</tr>";	
	
	}
	
	$output .= "</table>";
	$output .= "</div>";
	
	return $output;	
}


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
	
	//$log->LogDebug("Query for vs player stats: ".$query1);
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
	
	//$log->LogDebug("Query for basic player stats: ".$query1);
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
	$output .= '<div class="divisionHeading">Singles Games</div>'."\r\n";
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
	
	$output .= '<div class="divisionHeading">Doubles Games</div>'."\r\n";
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
	
	
	$output .= '<div class="divisionHeading">Singles Vs Rank '.$x.' Games';
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






?>