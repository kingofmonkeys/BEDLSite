<?php

function sendEmail($to, $subject, $body){
	$log = new KLogger ( "./logs/log.txt" , KLogger::DEBUG );
	if (mail($to, $subject, $body, "From: Baltimore English Dart League"."\r\n")) {
   		return TRUE;
  	} else {
  		$log->LogError("An email did not send: subject=".$subject." Body: ".$body);
  		return FALSE;
  	}

}

function isScoresSubmitted($log,$requestedMatch){
		$conn = getDBConnection($log);

		$result = mysql_query("select * from teamstats, schedule where teamstats.teamid=schedule.hometeamid and teamstats.week=schedule.week and schedule.ID='".$requestedMatch."';");

		$row = mysql_fetch_array($result,MYSQL_BOTH);

		if($row['teamid']==null){
			return FALSE;
	  	}else{
			return TRUE;
		}


}
//not sure  where to put this method, i think it is used by the admin page only. maybethis is was the ajax function calls?
function getSchedule($log,$week,$errors){
	$output ="";
	$matchValue="";
	if(isset($_POST['match'])){
		$matchValue=$_POST['match'];
	}


	$matchFieldHTML = '<label for="match">Match: <br></label><select name="match" id="match" onchange="updateScoreSheet()"><option value="--">--</option>'."\r\n";



	$conn = getDBConnection($log);
  	$result = mysql_query("select ID,hometeamid, (select teams.teamname from teams where schedule.week=".$week." and schedule.hometeamid = teams.teamid) as hometeamname, visitingteamid, (select teams.teamname from teams where schedule.week=".$week." and schedule.visitingteamid = teams.teamid) as visitingteamname from schedule where week=".$week.";");

  	while($row = mysql_fetch_array($result))
  	{
		$matchFieldHTML = $matchFieldHTML.'<option value="'.$row['ID'].'"';

		if($row['ID']==$matchValue){
	 	    $matchFieldHTML = $matchFieldHTML.' selected';
		}
	    $matchFieldHTML = $matchFieldHTML.'>'.$row['hometeamname'].' - '.$row['visitingteamname'].'</option>'."\r\n";
   	}
  	$matchFieldHTML = $matchFieldHTML.'</select>';
  	$output .= displayField($matchFieldHTML,"match",$errors);
	return $output;
}

?>