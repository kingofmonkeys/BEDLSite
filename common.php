<?php
date_default_timezone_set('America/New_York');

if(isBanned()){

	header("Location: http://www.youtube.com/watch?v=VtSLh3Yq0Lw");

}

session_start();

include "./secureimage/securimage.php";

include "./includes/functions.php";

include "./includes/getFunctions.php";

include "./includes/displayFunctions.php";

include "./includes/valFunctions.php";

include "./includes/FormError.php";

include "./includes/Week.php";

include "./includes/Team.php";

include "./includes/Player.php";

include "./includes/Match.php";

include "./includes/KLogger.php";

include "./includes/Database.php";

$log = new KLogger ( "./logs/log.txt" , KLogger::DEBUG );



function draw_head($page, $header_text) {

$output = "<!doctype html>";

$output .= '<html>'."\r\n";

$output .= '<head>'."\r\n";

$output .= '<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">'."\r\n";

$output .= '<script type="text/javascript" src="./includes/jquery-1.7.1.min.js"></script>'."\r\n";
$output .= '<script type="text/javascript" src="./includes/functions2.js"></script>'."\r\n";
$output .= '<script type="text/javascript" src="./includes/md5.js"></script>'."\r\n";
$output .= '<script type="text/javascript" src="./includes/yahoo-dom-event.js"></script>'."\r\n";
$output .= '<script type="text/javascript" src="./includes/repeatingFields.js"></script>'."\r\n";
$output .= '<script type="text/javascript" src="./includes/listManager.js"></script>'."\r\n";

$output .= '<title>'. $page.': '.$header_text.'</title>'."\r\n";

$output .= '<style>'."\r\n";

$output .= '@import url(./import.css);'."\r\n";

$output .= '</style>'."\r\n";

$output .= '</head>'."\r\n";

$output .= '<body>'."\r\n";

$output .= '<div id="page">';
$output .= '<div id="header" class="shadow gradient">';
$output .= '<img id="left_logo"  class="logo" src="./images/BEDL_logo.png"/>';
$output .= '<div id="title">Baltimore English Dart League</div>';
$output .= '<img id="right_logo" class="logo" src="./images/BEDL_logo.png"/>';
$output .= '</div>'."\r\n";

$output .= '<div id="tabbar">';
$output .= '<div class="tab"><a href="index.php">Home</a>'."\r\n";
$output .= '</div>';
$output .= '<div class="tab"><a href="#" id="season">Season Info</a>'."\r\n";
$output .= '</div>';
$output .= '<div id="seasonDropdown" class="dropdown">'."\r\n";
$output .= '<a href="schedule.php">Schedule</a><a href="roster.php">Roster</a>';
$output .= '<a href="dynastats.php">Weekly Stats</a>'.'<a href="locations.php">Shooting Locations</a>'."\r\n";
$output .= '</div>';
$output .= '<div class="tab"><a href="#" id="documents">Documents</a>'."\r\n";
$output .= '</div>';
$output .= '<div id="documentsDropdown" class="dropdown">'."\r\n";
$output .= '<a href="./documents/BEDL_ByLaws.pdf" target="_blank">By-laws</a><a href="./documents/BEDL_Rules.pdf" target="_blank">League Rules</a>';
$output .= '<a href="./documents/handicaps.pdf" target="_blank">Handicap Rules</a>';
$output .= '<a href="./documents/BEDL_Score_Sheets_2017.pdf" target="_blank">Score Sheet</a>'."\r\n";

$output .= '</div>';
$output .= '<div class="tab"><a href="lucks.php">Tourneys/Lucks</a>'."\r\n";
$output .= '</div>';
$output .= '<div class="tab"><a href="gallery.php">Gallery</a>'."\r\n";
$output .= '</div>';
$output .= '<div class="tab"><a href="login.php">Login</a>'."\r\n";
$output .= '</div>';

$output .= '</div> ';



$output .= '<div id="content">';

return $output;

}



function draw_foot() {

	$output ="";	
	
	$output .='</div></div>';

	$output .= '</body>'."\r\n";

	$output .= '</html>'."\r\n";

	return $output;

}



function isBanned(){

	$bannedfilename="bannedips.txt";

	$bannedfile=fopen($bannedfilename,"r");

	$isbanned = false;

	$banned_raw = fread($bannedfile, filesize($bannedfilename));

	fclose($bannedfile);

	$banned = explode("\n", $banned_raw);

	$num_banned = count($banned);

	for ($i = 0; $i < $num_banned; $i++) {

		if($banned[$i]==$_SERVER['REMOTE_ADDR']){

			$isbanned=true;

  		}

  	}

	return $isbanned;

}





?>