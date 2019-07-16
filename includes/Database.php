<?php
function getDBiConnection($log){
	$dbhost = 'bedldartstats.db.6902892.hostedresource.com';
	$dbuser = 'bedldartstats';
	$dbpass = 'Ocelot3#3';
	$dbname = 'bedldartstats';
	try{
  		$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
	}catch(Exception $e){
  		$log->LogError("Unable to connect to the database with values: Host=".$dbhost." User=".$dbuser." password=".$dbpass);
  		header("Location: errorPage.php");
  	}
 	try{
 		mysqli_select_db($conn,$dbname);
 	}catch(Exception $e){
	  	$log->LogError("Unable to select database: ".$dbname);
	  	header("Location: errorPage.php");
  	}
  	return $conn;
}

function getDBConnection($log){
	$dbhost = 'bedldartstats.db.6902892.hostedresource.com';
	$dbuser = 'bedldartstats';
	$dbpass = 'Ocelot3#3';
	$dbname = 'bedldartstats';
	try{
  		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
  	}catch(Exception $e){
  		$log->LogError("Unable to connect to the database with values: Host=".$dbhost." User=".$dbuser." password=".$dbpass);
  		header("Location: errorPage.php");
  	}
  	try{
  		mysql_select_db($dbname, $conn);
  	}catch(Exception $e){
	  	$log->LogError("Unable to select database: ".$dbname);
	  	header("Location: errorPage.php");
  	}
  	return $conn;
}


function closeDB($conn){
	mysql_close($conn);
}

?>