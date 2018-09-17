<?php
include "common.php";
$output = "";
$output .= draw_head("Baltimore English Dart League", "Baltimore English Dart League Roster");



$header = 'Summer 2018 Player Rankings';


$body .= drawRoster($log);

$output .= drawContainer($header,$body);


$output .= draw_foot();

echo $output;




function drawRoster($log){

$output = drawPlayerRankings($log);

return $output;
}



function drawPlayerRankings($log){
	$output = "";
	
	//THIS IS FOR SORTING THE TABLE
		$output .="<script>$(document).ready(function() {";		
		$output .="  $('#playerRankings').DataTable( {";
        $output .='"paging":   false,';
        $output .='"ordering": true,';
		$output .='"order": [[ 2, "asc" ]],';
		$output .='"asStripeClasses": [ "stattdgray", "stattdltgray" ],';
		$output .='"searching": false,';
		$output .='"aoColumns": [';	
		$output .= '{ "orderSequence": [ "asc","desc"] },';	
		$output .= '{ "orderSequence": [ "asc","desc"] },';		
		$output .= '{ "orderSequence": [ "asc","desc"] }],';		
        $output .='"info":     false';
		$output .='} );';
		$output .='} );';
		
		$output .='</script>';	
	$output .='<div style="display: inline-block;">';
	$output .= '<table class="stripe hover stattable" id="playerRankings" width="450px">';
		$output .= '<thead><tr><th width="250px">Player Name</th><th width="50px">Division</th><th width="50px">Rank</th>';	
		$output .= '</tr></thead><tbody>';
		
	
	
	$output .= '<tr><td>Mark Fair</td><td>1</td><td>1</td></tr>';	
	$output .= '<tr><td>Mike Lagana</td><td>1</td><td>1</td></tr>';
	$output .= '<tr><td>Daryl</td><td>1</td><td>1</td></tr>';
	$output .= '<tr><td>Tom Conrad Jr</td><td>1</td><td>1</td></tr>';
	$output .= '<tr><td>Lee B</td><td>1</td><td>1</td></tr>';
	$output .= '<tr><td>Dave Bonham</td><td>1</td><td>1</td></tr>';
	$output .= '<tr><td>Chuck</td><td>1</td><td>1</td></tr>';
	$output .= '<tr><td>Lee F</td><td>1</td><td>1</td></tr>';
	$output .= '<tr><td>John Downes</td><td>1</td><td>1</td></tr>';
	$output .= '<tr><td>TJ</td><td>2</td><td>2</td></tr>';
	$output .= '<tr><td>Joe Lagana</td><td>2</td><td>2</td></tr>';
	$output .= '<tr><td>Dre</td><td>2</td><td>2</td></tr>';
	$output .= '<tr><td>Mike Lantz</td><td>2</td><td>2</td></tr>';
	$output .= '<tr><td>Mike Webster</td><td>2</td><td>2</td></tr>';
	$output .= '<tr><td>Nick C</td><td>2</td><td>2</td></tr>';
	
$output .= '<tr><td>Tom Conrad Sr</td><td>2</td><td>2</td></tr>';
$output .= '<tr><td>Jimmy O</td><td>2</td><td>2</td></tr>';
$output .= '<tr><td>Greg O</td><td>2</td><td>2</td></tr>';
$output .= '<tr><td>Kelli</td><td>2</td><td>2</td></tr>';
$output .= '<tr><td>Darby</td><td>2</td><td>2</td></tr>';

$output .= '<tr><td>John M</td><td>2</td><td>3</td></tr>';
$output .= '<tr><td>Fred</td><td>2</td><td>3</td></tr>';
$output .= '<tr><td>Tie die</td><td>2</td><td>3</td></tr>';
$output .= '<tr><td>Juliana</td><td>2</td><td>3</td></tr>';
$output .= '<tr><td>Howard B</td><td>2</td><td>3</td></tr>';
$output .= '<tr><td>Doug P</td><td>2</td><td>3</td></tr>';
$output .= '<tr><td>Erika</td><td>2</td><td>3</td></tr>';
$output .= '<tr><td>Howard L</td><td>2</td><td>3</td></tr>';
$output .= '<tr><td>Chris K</td><td>2</td><td>3</td></tr>';
$output .= '<tr><td>Dana</td><td>2</td><td>3</td></tr>';
$output .= '<tr><td>Mike Jones</td><td>2</td><td>3</td></tr>';
$output .= '<tr><td>Travis</td><td>2</td><td>3</td></tr>';




$output .= '<tr><td>Tina</td><td>3</td><td>4</td></tr>';
$output .= '<tr><td>Charlie</td><td>3</td><td>4</td></tr>';
$output .= '<tr><td>Mel F</td><td>3</td><td>4</td></tr>';
$output .= '<tr><td>Oscar</td><td>3</td><td>4</td></tr>';
$output .= '<tr><td>Jimmy C</td><td>3</td><td>4</td></tr>';
$output .= '<tr><td>Rick</td><td>3</td><td>4</td></tr>';
$output .= '<tr><td>Tom</td><td>3</td><td>4</td></tr>';
$output .= '<tr><td>Jessica</td><td>3</td><td>4</td></tr>';

$output .= '<tr><td>Tammy V</td><td>3</td><td>5</td></tr>';
$output .= '<tr><td>Donna L</td><td>3</td><td>5</td></tr>';
$output .= '<tr><td>Heather</td><td>3</td><td>5</td></tr>';


	
		
	$output .= "</tbody></table></div>";
	return $output;
}

	
?>