<?php
include "common.php";

$output = "";
if(!(isset($_SESSION['logged_in']))){
	header("Location: login.php");
}

$output .= draw_head("Baltimore English Dart League", "Members Page");

$log->LogDebug($_SESSION['username']." is in the members.php page");
$roles =getUsersRoles($log,$_SESSION['username']);


$heading =$_SESSION['username'].', Welcome to the members section';

$body = '<h3>Available tasks:</h3>';
$body.= "<ul>";
if(in_array("ADMIN",$roles)){
	$body .= '<li><a href="./adminscoresheet.php">Enter weekly stats (Admin)</a></li>';
	$body .= '<li><a href="./average.php">Determine make-up week averages</a></li>';
}

if(in_array("CAPT",$roles)){
	$body .= '<li><a href="./scoresheet.php">Enter weekly stats for your team.</a></li>';
}

$body .= "</ul>";

$body .= '<br><a href="./logout.php">Logout</a><br/>';

$output .= drawContainer($heading,$body);
$output .= draw_foot();

echo $output;

?>