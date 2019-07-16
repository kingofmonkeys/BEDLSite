<?php
include "common.php";

$output = "";
$output .= draw_head("Baltimore English Dart League", "Welcome to the Baltimore English Dart League Website");

$output .= drawHandicapCalculator($log);

$output .= drawSingles01HandicapsTable($log);

$output .= drawSinglesCricketHandicapsTable($log);

$output .= drawDoubles01HandicapsTable($log);

$output .= drawDoublesCricketHandicapsTable($log);

$output .= draw_foot();

echo $output;
//end of page


function drawHandicapCalculator($log){
	
	$players = getPlayersSortedByName($log);
	$output ="";
	$output ='<script type="text/javascript" src="./includes/handicap_calc.js"></script>';

	$output .= "<script>";
	$output .= "var players={};";	
		
	foreach ($players as $i => $player) {
		$output .= "players['".$player->getPlayerId()."'] = {1:'".$player->getPlayerId()."',2:'".$player->getFirstName()."',3:'".$player->getLastName()."',4:'".$player->getRank()."'};"."\r\n";
	}	
	$output .= 'function initHandicap(){'."\r\n";
	$output .= '	document.getElementById("singlesHandicapButton").addEventListener("click", calculateSinglesHandicap);'."\r\n";
	$output .= '	document.getElementById("doublesHandicapButton").addEventListener("click", calculateDoublesHandicap);'."\r\n";
		
	$output .= '};'."\r\n";
	$output .= "document.addEventListener('DOMContentLoaded', initHandicap, false);"."\r\n";
	
	
	
	$output .="</script>";
	
	
	
	
	$header = "Handicap Calculator";
	$body = "";
	$body .= 'Singles: <select name="player1" id="player1">';
	
	$body .="<option value=''>--</option>";
	
	foreach ($players as $i => $player) {
		$body .="<option value='".$player->getPlayerId()."'";
		if($player->getPlayerId()==$selected){
			$body .= " selected";
		}		
		$body .= ">".$player->getFirstName()." ".$player->getLastName()." (".$player->getRank().")</option>";		
	}
	$body .="</select>";
	
	$body .= " vs ";
	
	$body .= '<select name="player2" id="player2">';
	
	$body .="<option value=''>--</option>";
	
	foreach ($players as $i => $player) {
		$body .="<option value='".$player->getPlayerId()."'";
		if($player->getPlayerId()==$selected){
			$body .= " selected";
		}		
		$body .= ">".$player->getFirstName()." ".$player->getLastName()." (".$player->getRank().")</option>";		
	}
	$body .="</select>";
	
	$body .=" Game: ";
	
	$body .= '<select name="singlesGameType" id="singlesGameType">';
	
	$body .="<option value=''>--</option>";
	$body .="<option value='01'>01</option>";
	$body .="<option value='cricket'>Cricket</option>";	
	$body .="</select>";	
	
	$body .="<input type='button' value='Go' id='singlesHandicapButton'/>";
	
	$body .="<div id='singlesHandicapResults'></div>";
	
	$body .="<br/><br/>";
	
	
	$body .= 'Doubles: <select name="team1player1" id="team1player1">';
	
	$body .="<option value=''>--</option>";
	
	foreach ($players as $i => $player) {
		$body .="<option value='".$player->getPlayerId()."'";
		if($player->getPlayerId()==$selected){
			$body .= " selected";
		}		
		$body .= ">".$player->getFirstName()." ".$player->getLastName()." (".$player->getRank().")</option>";		
	}
	$body .="</select> and ";
	
	$body .= '<select name="team1player2" id="team1player2">';
	
	$body .="<option value=''>--</option>";
	
	foreach ($players as $i => $player) {
		$body .="<option value='".$player->getPlayerId()."'";
		if($player->getPlayerId()==$selected){
			$body .= " selected";
		}		
		$body .= ">".$player->getFirstName()." ".$player->getLastName()." (".$player->getRank().")</option>";		
	}
	$body .="</select>";
	
	
	$body .= " vs ";
	
	$body .= '<select name="team2player1" id="team2player1">';
	
	$body .="<option value=''>--</option>";
	
	foreach ($players as $i => $player) {
		$body .="<option value='".$player->getPlayerId()."'";
		if($player->getPlayerId()==$selected){
			$body .= " selected";
		}		
		$body .= ">".$player->getFirstName()." ".$player->getLastName()." (".$player->getRank().")</option>";		
	}
	$body .="</select> and ";
	
	$body .= '<select name="team2player2" id="team2player2">';
	
	$body .="<option value=''>--</option>";
	
	foreach ($players as $i => $player) {
		$body .="<option value='".$player->getPlayerId()."'";
		if($player->getPlayerId()==$selected){
			$body .= " selected";
		}		
		$body .= ">".$player->getFirstName()." ".$player->getLastName()." (".$player->getRank().")</option>";		
	}
	$body .="</select>";
	
	$body .=" Game: ";
	
	$body .= '<select name="doublesGameType" id="doublesGameType">';
	
	$body .="<option value=''>--</option>";
	$body .="<option value='01'>01</option>";
	$body .="<option value='cricket'>Cricket</option>";	
	$body .="</select>";	
	
	$body .="<input type='button' value='Go' id='doublesHandicapButton'/>";
	
	$body .="<div id='doublesHandicapResults'></div>";
	
	
	$output .= drawContainer($header,$body);
	
	return $output;
	
	
}


function drawSingles01HandicapsTable($log){
	$output = "";
	$header .= "Singles 01 handicap (75 points per tier)";
	$body .= '<table class="stripe hover stattable" id="singles501"><thead><tr><th>Rank</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th></tr></thead>';
	$body .= "<tbody>";
	$body .= "<tr><td class='rankRowHeader'>1</td><td class='greenCell'>501/501</td><td></td><td></td><td></td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>2</td><td class='yellowCell'>501/426</td><td class='blueCell'>401/401</td><td></td><td></td><td></td></tr>";	
	$body .= "<tr><td class='rankRowHeader'>3</td><td class='orangeCell'>501/351</td><td class='yellowCell'>426/351</td><td class='blueCell'>301/301</td><td></td><td></td></tr>";
	$body .= "<tr><td class='rankRowHeader'>4</td><td class='darkOrangeCell'>501/276</td class='orangeCell'><td class='orangeCell'>426/276</td><td class='yellowCell'>351/276</td><td class='blueCell'>301/301</td><td></td></tr>";
	$body .= "<tr><td class='rankRowHeader'>5</td><td class='redCell'>501/201</td><td class='darkOrangeCell'>426/201</td><td class='orangeCell'>351/201</td><td class='yellowCell'>276/201</td><td class='blueCell'>201/201</td></tr>";
	$body .= "</tbody></table>";
	
	$body .= "<br/>";
	
	$body .= "Lower rated shooter consults left hand side and finds the column of their opponent. Lower number listed is ";
	$body .= "what number the lower rated shooter starts with and the higher number listed is the number the higher rated shooter starts with in 01 (Single In/Double Out). ";
	$body .= "Match-ups in blue play a even game that starts at a lower 01 number, this is to promote moving the night along";
	
	$output = drawContainer($header,$body);
	
	return $output;
}

function drawSinglesCricketHandicapsTable($log){
	$output = "";
	$header .= "Singles Cricket handicap (2 marks and 25 points per tier)";
	$body .= '<table class="stripe hover stattable" id="singlesCricket"><thead><tr><th>Rank</th><th>1</th><th>2</th><th>3</th><th>4</th></tr></thead>';
	$body .= "<tbody>";
	$body .= "<tr><td class='rankRowHeader'>2</td><td class='yellowCell'>2M+25p</td><td></td><td></td><td></td></tr>";	
	$body .= "<tr><td class='rankRowHeader'>3</td><td class='orangeCell'>4M+25p</td><td class='yellowCell'>2M+25p</td><td></td><td></td></tr>";
	$body .= "<tr><td class='rankRowHeader'>4</td><td class='darkOrangeCell'>6M+25p</td><td class='orangeCell'>4M+25p</td><td class='yellowCell'>2M+25p</td><td></td></tr>";
	$body .= "<tr><td class='rankRowHeader'>5</td><td class='redCell'>8M+25p</td><td class='darkOrangeCell'>6M+25p</td><td class='orangeCell'>4M+25p</td><td class='yellowCell'>2M+25p</td></tr>";
	$body .= "</tbody></table>";	
	
	$body .= "<br/>";
	
	$body .= "Lower rated shooter consults left hand side and finds the column of their opponent. Number listed is ";
	$body .= "how many marks and points in Cricket the lower rated shooter receives, (Lower rated shooter may apply marks in ";
	$body .= "any way they choose, this includes closing a box and taking the points and includes bulls.)";
		
	$output = drawContainer($header,$body);
	
	return $output;
}

function drawDoubles01HandicapsTable($log){
	$output = "";
	$header .= "Doubles 01 handicap (75 points per tier)";
	$body .= '<table class="stripe hover stattable" id="doubles501"><thead><tr><th>Rank</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th></tr></thead>';
	$body .= "<tbody>";	
	$body .= "<tr><td class='rankRowHeader'>2</td><td class='greenCell'>501/501</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>3</td><td class='greenCell'>501/501</td><td class='greenCell'>501/501</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>4</td><td class='yellowCell'>501/426</td><td class='greenCell'>501/501</td><td class='greenCell'>501/501</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>5</td><td class='yellowCell'>501/426</td><td class='yellowCell'>501/426</td><td class='greenCell'>501/501</td><td class='greenCell'>501/501</td><td></td><td></td><td></td><td></td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>6</td><td class='orangeCell'>501/351</td><td class='yellowCell'>501/426</td><td class='yellowCell'>501/426</td><td class='greenCell'>501/501</td><td class='greenCell'>501/501</td><td></td><td></td><td></td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>7</td><td class='orangeCell'>501/351</td><td class='orangeCell'>501/351</td><td class='yellowCell'>501/426</td><td class='yellowCell'>501/426</td><td class='greenCell'>501/501</td><td class='greenCell'>501/501</td><td></td><td></td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>8</td><td class='darkOrangeCell'>501/276</td><td  class='orangeCell'>501/351</td><td class='orangeCell'>501/351</td><td class='yellowCell'>501/426</td><td class='yellowCell'>501/426</td><td class='greenCell'>501/501</td><td class='greenCell'>501/501</td><td></td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>9</td><td class='darkOrangeCell'>501/276</td><td class='darkOrangeCell'>501/276</td><td class='orangeCell'>501/351</td><td class='orangeCell'>501/351</td><td class='yellowCell'>501/426</td><td class='yellowCell'>501/426</td><td class='greenCell'>501/501</td><td class='greenCell'>501/501</td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>10</td><td class='redCell'>501/201</td><td class='darkOrangeCell'>501/276</td><td class='darkOrangeCell'>501/276</td><td class='orangeCell'>501/351</td><td class='orangeCell'>501/351</td><td class='yellowCell'>501/426</td><td class='yellowCell'>501/426</td><td class='greenCell'>501/501</td><td class='greenCell'>501/501</td></tr>";		
	
	$body .= "</tbody></table>";
	
	$body .= "<br/>";	
	
	$body .= "Lower rated combined doubles rating consults left hand side and finds the column of their opponents ";
	$body .= "combined rating. The lower number listed is what the lower rated doubles team starts with in 01 (Single In/Double Out)";
	
	$output = drawContainer($header,$body);
	
	return $output;
}

function drawDoublesCricketHandicapsTable($log){
	$output = "";
	$header .= "Doubles Cricket handicap (2 marks and 25 points per tier)";
	$body .= '<table class="stripe hover stattable" id="doubles501"><thead><tr><th>Rank</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th></tr></thead>';
	$body .= "<tbody>";	
	$body .= "<tr><td class='rankRowHeader'>3</td><td class='greenCell'>None</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>4</td><td class='yellowCell'>2M+25p</td><td class='greenCell'>None</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>5</td><td class='yellowCell'>2M+25p</td><td class='yellowCell'>2M+25p</td><td class='greenCell'>None</td><td></td><td></td><td></td><td></td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>6</td><td class='orangeCell'>4M+25p</td><td class='yellowCell'>2M+25p</td><td class='yellowCell'>2M+25p</td><td class='greenCell'>None</td><td></td><td></td><td></td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>7</td><td class='orangeCell'>4M+25p</td><td class='orangeCell'>4M+25p</td><td class='yellowCell'>2M+25p</td><td class='yellowCell'>2M+25p</td><td class='greenCell'>None</td><td></td><td></td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>8</td><td class='darkOrangeCell'>6M+25p</td><td  class='orangeCell'>4M+25p</td><td class='orangeCell'>4M+25p</td><td class='yellowCell'>2M+25p</td><td class='yellowCell'>2M+25p</td><td class='greenCell'>None</td><td></td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>9</td><td class='darkOrangeCell'>6M+25p</td><td class='darkOrangeCell'>6M+25p</td><td class='orangeCell'>4M+25p</td><td class='orangeCell'>4M+25p</td><td class='yellowCell'>2M+25p</td><td class='yellowCell'>2M+25p</td><td class='greenCell'>None</td><td></td></tr>";		
	$body .= "<tr><td class='rankRowHeader'>10</td><td class='redCell'>8M+25p</td><td class='darkOrangeCell'>6M+25p</td><td class='darkOrangeCell'>6M+25p</td><td class='orangeCell'>4M+25p</td><td class='orangeCell'>4M+25p</td><td class='yellowCell'>2M+25p</td><td class='yellowCell'>2M+25p</td><td class='greenCell'>None</td></tr>";		
	
	$body .= "</tbody></table>";
	
	$body .= "<br/>";	
	
	$body .= "Lower rated combined doubles rating consults left hand side and finds the column of their opponents ";
	$body .= "combined rating. Number listed is ";
	$body .= "how many marks and points in Cricket the lower rated team receives, (Lower rated team may apply marks in ";
	$body .= "any way they choose, this includes closing a box and taking the points and includes bulls.)";

	$output = drawContainer($header,$body);
	
	return $output;
}



?>