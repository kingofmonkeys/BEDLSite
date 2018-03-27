<?php
function displayField($html,$field,$errors){
	$output = "";
	if(hasFieldError($field,$errors)){
		$output .= '<font color="red">* </font>';
	}
	$output .= $html;
	return $output;
}

function displayErrors($errors){
	$output = "";
	for($i=0;$i<count($errors);$i++)
	{
		$output .= '<font color="red">* - '.$errors[$i]->getfielderror().'</font><br>';
	}
	return $output;
}


function LoadSideBar($file)
{
	$output ="";
	if ($fp = fopen($file,"r")) {
	    $server_raw = fread($fp, filesize($file));
	    fclose($fp);
	    $server = explode("#TOPIC#", $server_raw);
	    $num_server = count($server);

		for ($i = 1; $i < $num_server; $i++) {
			list($heading, $content) = explode("#CONTENT#",$server[$i],2);
			$output .= drawContainer($heading,$content);
		}
	}else{
		$output .= "Error Loading Sidebar";
	}
	return $output;
}


function drawContainer($header,$body){
	$output ='';
	$output .=  "<div class='container'>"."\r\n";
	$output .=  "<div class='hd shadow gradient'>"."\r\n";
	$output .= $header."\r\n";
	$output .= "</div>"."\r\n";
	$output .= "<div class='bd'>"."\r\n";
	$output .= $body."\r\n";
	$output .= "</div>"."\r\n";
	$output .= "</div>"."\r\n";		
	return $output;
}



function LoadInfo($file)
{
	$output ="";
	if ($fp = fopen($file,"r")) {
	    $info_raw = fread($fp, filesize($file));
    	fclose($fp);
    	$info = explode("#TOPIC#", $info_raw);
    	$num_info = count($info);
		for ($i = 1; $i < $num_info; $i++) {
			list($heading,$content) = explode("#BREAK#",$info[$i]);
			$output .= drawContainer($heading,$content);
		}
	}else{
		$output .= "Error Loading Info";
	}
	return $output;

}

function LoadNews($file)
{
$output ="";
	if ($fp = fopen($file,"r")) {
	    $news_raw = fread($fp, filesize($file));
    	fclose($fp);
    	$news = explode("#TOPIC#", $news_raw);
    	$num_news = count($news);

		
		$output .= "<div class='headerBreak shadow gradient'>News</div>";

		for ($i = 1; $i < $num_news; $i++) {
			list($heading,$date,$content) = explode("#BREAK#",$news[$i]);
		$output .= drawContainer($heading,$content);
		}
		
	}else{
		$output .= "Error Loading News";
	}
	return $output;
}





function LoadEvents($file)
{
	$output ="";
	if ($fp = fopen($file,"r")) {
		$events_raw = fread($fp, filesize($file));
		fclose($fp);
		//split the string at #RECURRING#
    	$temp = explode("#RECURRING#", $events_raw);
		//strip out the #ONETIME# tag since its only used to make the txt file human readable
		if(substr_count($temp[0],"#EVENT#")!=0){
		    $events = explode("#EVENT#",str_replace("#ONETIME#","",$temp[0]));
    		$num_events = count($events);
			
			
			$output .= "<div class='headerBreak shadow gradient'>Upcoming Events</div>";
			
			for ($i = 1; $i < $num_events; $i++) {
				list($heading,$content) = explode("#INFO#",$events[$i]);				
				$output .= drawContainer($heading,$content)."\r\n";				
			}
		}

		$events = explode("#EVENT#",$temp[1]);

		$num_events = count($events);
		if($num_events!=0){
			$output .= "<div class='headerBreak shadow gradient'>Recurring Events</div>";			

			for ($i = 1; $i < $num_events; $i++) {
				list($heading,$content) = explode("#INFO#",$events[$i]);
				
				$output .= drawContainer($heading,$content)."\r\n";					
			}
		}
	}else{
		$output .= "Error Loading Events";
	}
	return $output;
}

function LoadContacts($file)
{
	$output = "";
	if ($fp = fopen($file,"r")) {
		$contacts_raw = fread($fp, filesize($file));
		fclose($fp);
    	$temp = explode("#OTHERLEAGUES#", $contacts_raw);

    	$contacts = explode("#CONTACTS#",$temp[0]);

    	$num_contacts = count($contacts);

		$output .= '<table width = "100%" cellspacing="0" cellpadding="0"><tr><td width ="100%"  class="heading" >';
		$output .= '<center>Baltimore English Dart League Contacts</center></td></tr></table>';

		for ($i = 1; $i < $num_contacts; $i++) {
			list($name,$content) = explode("#INFO#",$contacts[$i]);
			$output .= '<table width = "100%" cellspacing="0" cellpadding="0"><tr><td class="newsheading" >';
			$output .= $name;
			$output .= '</td>';
			$output .= '</tr><tr><td width = "100%" colspan="2" class="contentarea">';
			$output .= $content.'</td></tr></table><br>';
		}

 		$contacts = explode("#CONTACTS#",$temp[1]);

    	$num_contacts = count($contacts);


		$output .= '<table width = "100%" cellspacing="0" cellpadding="0"><tr><td width ="100%"  class="heading" >';
		$output .= '<center>Other League Contacts</center></td></tr></table>';

		for ($i = 1; $i < $num_contacts; $i++) {
			list($name,$content) = explode("#INFO#",$contacts[$i]);
			$output .= '<table width = "100%" cellspacing="0" cellpadding="0"><tr><td class="newsheading" >';
			$output .= $name;
			$output .= '</td>';
			$output .= '</tr><tr><td width = "100%" colspan="2" class="contentarea">';
			$output .= $content.'</td></tr></table><br>';

		}

	}else{
		$output .= "Error Loading Sidebar";
	}
	return $output;
}


function drawWinsInputForMatch($log,$requestedMatch,$errors){

	$output = "";
	if(isScoresSubmitted($log,$requestedMatch)){
		$output .= '<input type="hidden" id="scoresSubmitted" name="scoresSubmitted" value="true"/>';
		$output .= '<font color="red">Scores have already been submitted for this match, however you can still submit additional notes.</font>';
		return $output;
	}

	$homeTeamId="";
	$visitingTeamId="";
	$homeTeamName="";
	$visitingTeamName="";

	$homeTeamWinsValue = "";
	$visitingTeamWinsValue = "";


	if(isset($_POST['homeTeamWins']) &&$_POST['homeTeamWins']!=null){
		$homeTeamWinsValue=$_POST['homeTeamWins'];
	}

	if(isset($_POST['visitingTeamWins']) &&$_POST['visitingTeamWins']!=null){
		$visitingTeamWinsValue=$_POST['visitingTeamWins'];
	}


	$conn = getDBConnection($log);
	$result = mysql_query("select hometeamid, visitingteamid, (select teamname from teams where teams.teamid=hometeamid) as hometeamname, (select teamname from teams where teams.teamid=visitingteamid) as visitingteamname from schedule where ID=".$requestedMatch.";");

	$row = mysql_fetch_array($result);
  	$homeTeamId=$row['hometeamid'];
	$visitingTeamId=$row['visitingteamid'];

	$homeTeamName=$row['hometeamname'];
	$visitingTeamName=$row['visitingteamname'];

	$output .= '   <div class="halfWidth">';
	$output .= '     <div class="textbox dl-input">';

	$output .= '<input type="hidden" name="homeTeam" value="'.$homeTeamId.'"/>';
	$output .= '<h3>Home Team:</h3>';
	$output .= $homeTeamName;


	$output .= '      </div>';
	$output .= '    </div>';
	$output .= '    <div class="halfWidth">';
	$output .= '      <div class="textbox dl-input">';

	$output .= '<input type="hidden" name="visitingTeam" value="'.$visitingTeamId.'"/>';
	$output .= '<h3>Visiting Team:</h3>';
	$output .=  $visitingTeamName;

	$output .= '     </div>';
	$output .= '    </div>';
	$output .= '    <div class="halfWidth">';
	$output .= '      <div class="textbox dl-input">';

	$output .= displayField('<label for="homeTeamWins">Games Won</label><input type="text" name="homeTeamWins" id="homeTeamWins" size="2" maxlength="2" value="'.$homeTeamWinsValue.'"/>',"homeTeamWins",$errors);

	$output .= '      </div>';
	$output .= '    </div>';
	$output .= '    <div class="halfWidth">';
	$output .= '     <div class="textbox dl-input">';
	$output .= displayField('<label for="visitingTeamWins">Games Won</label><input type="text" name="visitingTeamWins" id="visitingTeamWins" size="2" maxlength="2" value="'.$visitingTeamWinsValue.'"/>',"visitingTeamWins",$errors);

	$output .= '      </div>';
	$output .= '    </div>';

	return $output;
}


function drawPlayersForMatch($log,$requestedMatch,$errors){
	$output = "";

	$homeTeamId="";
	$visitingTeamId="";
	$additionalNotesValue="";
	if(isset($_POST['additionalNotes']) &&$_POST['additionalNotes']!=null){
		$additionalNotesValue=$_POST['additionalNotes'];
	}
	
	
	$conn = getDBConnection($log);
	$result = mysql_query("select * from schedule where ID=".$requestedMatch.";");

	$row = mysql_fetch_array($result);
  	$homeTeamId=$row['hometeamid'];
	$visitingTeamId=$row['visitingteamid'];
	$homeplayers = getPlayersForTeam($log, $homeTeamId);
	$visitplayers = getPlayersForTeam($log, $visitingTeamId);

	if(!isScoresSubmitted($log,$requestedMatch)){		

		$output .= '  <div class="playerInfo">';
		$output .= '    <h3>Player Points</h3>';
		$output .= '    <p class="instructions">Select a player from the list and enter the number of points that player received this week.</p>';
		$output .= '    <div id="homePlayers" class="halfWidth">';
		$output .= '      <h4>Home Team</h4>';

		
		$output .= '<table><tr><td width="150" class="playerTableHeader">Player</td><td class="playerTableHeader">Points</td><td class="playerTableHeader">Games Played</td></tr>';
		foreach ($homeplayers as $i => $player) {
			$fieldValue = "";
			if(isset($_POST['player'.$player->getPlayerId().'points']) &&$_POST['player'.$player->getPlayerId().'points']!=null){
				$fieldValue=$_POST['player'.$player->getPlayerId().'points'];
			}
			$playerGamesValue ="";
			if(isset($_POST['player'.$player->getPlayerId().'games']) &&$_POST['player'.$player->getPlayerId().'games']!=null){
				$playerGamesValue=$_POST['player'.$player->getPlayerId().'games'];
			}
			$output .= '<tr><td width="150">';
			$output .= displayField($player->getFirstName()." ".$player->getLastName().'</td><td><input type="text" name="player'.$player->getPlayerId().'points" id="player'.$player->getPlayerId().'points" size="3" maxlength="3" value="'.$fieldValue.'"/>'."\r\n","player".$player->getPlayerId()."points",$errors);
			$output .= '</td><td>';
			$output .= displayField('<input type="text" name="player'.$player->getPlayerId().'games" id="player'.$player->getPlayerId().'games" size="3" maxlength="3" value="'.$playerGamesValue.'"/>'."\r\n","player".$player->getPlayerId()."games",$errors);			
			$output .= '</td></tr>';
		}
		$output .= '</table>';
		$output .= '    </div>';
		$output .= '    <div class="halfWidth">';
		$output .= '      <h4>Visiting Team</h4>';
		
		$output .= '<table><tr><td width="150" class="playerTableHeader">Player</td><td class="playerTableHeader">Points</td><td class="playerTableHeader">Games Played</td></tr>';
		foreach ($visitplayers as $i => $player) {
			$fieldValue = "";
			if(isset($_POST['player'.$player->getPlayerId().'points']) &&$_POST['player'.$player->getPlayerId().'points']!=null){
				$fieldValue=$_POST['player'.$player->getPlayerId().'points'];
			}
			$playerGamesValue ="";
			if(isset($_POST['player'.$player->getPlayerId().'games']) &&$_POST['player'.$player->getPlayerId().'games']!=null){
				$playerGamesValue=$_POST['player'.$player->getPlayerId().'games'];
			}
			$output .= '<tr><td width="150">';
			$output .= displayField($player->getFirstName()." ".$player->getLastName().'</td><td><input type="text" name="player'.$player->getPlayerId().'points" id="player'.$player->getPlayerId().'points" size="3" maxlength="3" value="'.$fieldValue.'"/>'."\r\n","player".$player->getPlayerId()."points",$errors);
			$output .= '</td><td>';
			$output .= displayField('<input type="text" name="player'.$player->getPlayerId().'games" id="player'.$player->getPlayerId().'games" size="3" maxlength="3" value="'.$playerGamesValue.'"/>'."\r\n","player".$player->getPlayerId()."games",$errors);			
			$output .= '</td></tr>';
		}
		$output .= '</table>';
		$output .= '  </div>';
	

		$output .=	'</div>'."\r\n";
	}
	//special shot stuff.
	
	$output .= '<div class="bedl-specialShots">';
	$output .= '<h3>Player Shots</h3>';
	$output .=  '<div class="bedl-inlineInputs">'."\r\n";
		
	$output .=  '<div class="bedl-repeatingFields bedl-inlineInputs" data-bedl-maxfieldsets="10">'."\r\n";
	
	$allplayers = array_merge($visitplayers,$homeplayers);
	
	$output .= generateShotFieldSet($log,$allplayers,"1",$errors);
	
	for($i = 2;$i<10;$i += 1){
		if(isset($_POST['specialShotPlayerName'.$i])){
		$output .= generateShotFieldSet($log,$allplayers,$i,$errors);
		
			$str = "Special Shot PlayerName".$i." found: ".$_POST['specialShotPlayerName'.$i].", Shot Type: ".$_POST['specialShotType'.$i].", Shot Value: ".$_POST['specialShotValue'.$i]."\r\n";
		
		}
	}
	
	
	$output .= '</div>'."\r\n";
	$output .= '</div>'."\r\n";
	$output .= '</div>'."\r\n";	
	
	//end special shot stuff
	
	
	$output .= '  <div class="additionalNotes dl-input">';

	$output .= displayField('<label for="additionalNotes">Additional Notes</label><textarea name="additionalNotes" id="additionalNotes" cols="60" rows="10">'.$additionalNotesValue.'</textarea>',"additionalNotes",$errors);

	$output .= '  </div>';

	return $output;
}

function generateShotFieldSet($log,$players,$index,$errors){
$output ="";
$output .=  '<fieldset class="bedl-repeatingFieldsSet">'."\r\n";
	$output .=  '<div class="bedl-input" data-bedl-basename="specialShotPlayerName">'."\r\n";	
$inputString ="";
$inputString .= ' <label for="specialShotPlayerName'.$index.'">Player Name</label>';
$inputString .= '<select size="1" id="specialShotPlayerName'.$index.'" name="specialShotPlayerName'.$index.'">';
$inputString .= '<option value="NONE">Select Player</option>'."\r\n";
	 
	foreach ($players as $i => $player) {
	 $selected ="";
	 if(isset($_POST['specialShotPlayerName'.$index]) && $_POST['specialShotPlayerName'.$index]==$player->getPlayerId()){
		$selected ="selected";
	}
	 $inputString .=   '<option value="'.$player->getPlayerId().'" '.$selected.'>'.$player->getFirstName()." ".$player->getLastName().'</option>'."\r\n";
	 }
     $inputString .= '</select>';

	 $output .= displayField($inputString,"specialShotPlayerName".$index,$errors);
	 
	 $output .= '</div>'."\r\n";
		$output .= '<div class="bedl-input" data-bedl-basename="specialShotType" data-bedl-addnewset="true">'."\r\n";
		
		$inputString ="";
     $inputString .=' <label for="specialShotType'.$index.'">Shot Type</label>';
	 $inputString .= '<select size="1" id="specialShotType'.$index.'" name="specialShotType'.$index.'" value="'.$_POST['specialShotType'.$index].'">';
	 $inputString .=   '<option value="NONE">Select Shot</option>'."\r\n";
	 $query = "select * from shots;";
	 $shots  = mysql_query($query);
	
	while($shot = mysql_fetch_array($shots))
	{
	$selected ="";
	 if(isset($_POST['specialShotType'.$index]) && $_POST['specialShotType'.$index]==$shot['ID']){
		$selected ="selected";
	 }
  
   $inputString .='<option value="'.$shot['ID'].'" '.$selected.'>'.$shot['shotname'].'</option>'."\r\n"; 
    }	  
    $inputString .='</select> ';
	$output .= displayField($inputString,"specialShotType".$index,$errors);

	$output .='	</div>';
    $output .='<div class="bedl-input" data-bedl-basename="specialShotValue" data-bedl-defaultvalue="">';
    
	$inputString ="";
	
	$inputString .='<label for="specialShotValue'.$index.'">Value</label>';
    $inputString .='<input type="text" name="specialShotValue'.$index.'" id="specialShotValue'.$index.'" size="3" maxlength="3" value="'.$_POST['specialShotValue'.$index].'"/>';
    
	$output .= displayField($inputString,"specialShotValue".$index,$errors);
	
	$output .='</div>';
	$output .= '</fieldset>'."\r\n";
	
	
	return $output;
}



function getAverages($log,$requestedMatch,$errors){
	
		$conn = getDBConnection($log);
	
	
		$getMatchSQL = "select * from schedule where ID='".$requestedMatch."'";
		
		$matchInfoList = mysql_query($getMatchSQL) or die("Failed Query of " . $getMatchSQL);  //do the query
		$matchInfo = mysql_fetch_array($matchInfoList);
		//have to convert requested match to team ids
		$homeTeamId =$matchInfo['hometeamid'];
		$visitingTeamId=$matchInfo['visitingteamid'];
	
		$getHomeTeamPP = "select players.player_id, players.first_name, players.last_name, 
		ROUND(AVG(player_stats.personal_points)) as personal_points from player_stats, players where player_stats.player_id=players.player_id 
		and players.player_id in (select player_id from players where team_id ='".$homeTeamId."') 
		and week_number in (select week from schedule where (hometeamid ='".$homeTeamId."' and visitingteamid in (select teamid from teams where division=(select division from teams where teamid='".$visitingTeamId."'))) 
		or (hometeamid in (select teamid from teams where division=(select division from teams where teamid='".$visitingTeamId."')) 
		and visitingteamid ='".$homeTeamId."')) group by player_id";
		
		$getVisitingTeamPP = "select players.player_id, players.first_name, players.last_name, 
		ROUND(AVG(player_stats.personal_points)) as personal_points from player_stats, players where player_stats.player_id=players.player_id 
		and players.player_id in (select player_id from players where team_id ='".$visitingTeamId."') 
		and week_number in (select week from schedule where (hometeamid ='".$visitingTeamId."' and visitingteamid in (select teamid from teams where division=(select division from teams where teamid='".$homeTeamId."'))) 
		or (hometeamid in (select teamid from teams where division=(select division from teams where teamid='".$homeTeamId."')) 
		and visitingteamid ='".$visitingTeamId."')) group by player_id";
		
		
		$getHomeTeamWins = " select teamname, round(avg(wins)) as wins from teamstats, teams where teamstats.teamid = teams.teamid and teamstats.teamid='".$homeTeamId."' and week in (select week from schedule where (hometeamid ='".$homeTeamId."' and visitingteamid in (select teamid from teams where division=(select division from teams where teamid='".$visitingTeamId."'))) 
		or (hometeamid in (select teamid from teams where division=(select division from teams where teamid='".$visitingTeamId."')) and visitingteamid ='".$homeTeamId."'))";
 
		$getVisitingTeamWins = " select teamname, round(avg(wins)) as wins from teamstats, teams where teamstats.teamid = teams.teamid and teamstats.teamid='".$visitingTeamId."' and week in (select week from schedule where (hometeamid ='".$visitingTeamId."' and visitingteamid in (select teamid from teams where division=(select division from teams where teamid='".$homeTeamId."'))) 
		or (hometeamid in (select teamid from teams where division=(select division from teams where teamid='".$homeTeamId."')) and visitingteamid ='".$visitingTeamId."'))";
		
		$result1 = mysql_query($getHomeTeamWins) or die("Failed Query of " . $getHomeTeamWins);  //do the query
			
			$output .='<div class="teamInfo">';
		while($thisrow=mysql_fetch_array($result1))
		{          
			$output .="<div class='halfWidth'>"; 
			$output .="<div>".$thisrow["teamname"]."</div>";
			$output .="<div>".$thisrow["wins"]."</div>";			
			$output .="</div>"; 
		}				
		
		$result2 = mysql_query($getVisitingTeamWins ) or die("Failed Query of " . $getVisitingTeamWins );  //do the query
	
		while($thisrow=mysql_fetch_array($result2))
		{          
			$output .="<div class='halfWidth'>"; 
			$output .="<div>".$thisrow["teamname"]."</div>";
			$output .="<div>".$thisrow["wins"]."</div>";			
			$output .="</div>"; 
		}		
		$output .='</div>';
		
		
		
		
		
		
		
		$result3 = mysql_query($getHomeTeamPP) or die("Failed Query of " . $getHomeTeamPP);  //do the query
		$output .="<div class='halfWidth'>"; 
		$output .="<table class='stattable'>";	
	while($thisrow=mysql_fetch_array($result3))
	{          
$output .="<tr>";
//$output .="<td>".$thisrow["player_id"]."</td>";
$output .="<td><div style='display:none'>".$thisrow["player_id"]."</div>".$thisrow["first_name"]." ";
$output .=$thisrow["last_name"]."</td>";
$output .="<td>".$thisrow["personal_points"]."</td>";
$output .="</tr>";
	}		
		$output .="</table></div>";
		
		$result4 = mysql_query($getVisitingTeamPP ) or die("Failed Query of " . $getVisitingTeamPP );  //do the query
		$output .="<div class='halfWidth'>"; 
		$output .="<table class='stattable'>";	
	while($thisrow=mysql_fetch_array($result4))
	{          
$output .="<tr>";
//$output .="<td><div style='display:none'>".$thisrow["player_id"]."</div>".$thisrow["player_id"]."</td>";
$output .="<td><div style='display:none'>".$thisrow["player_id"]."</div>".$thisrow["first_name"]." ";
$output .=$thisrow["last_name"]."</td>";
$output .="<td>".$thisrow["personal_points"]."</td>";
$output .="</tr>";
	}		
		$output .="</table></div>";
		
		return $output;
	
	
}

function drawScoreSheetForm($log,$errors,$homeTeam,$visitingTeam){	
	
	$single011HomePlayer = getFieldValue("single011HomePlayer");
	$single011HomeWins = getFieldValue("single011HomeWins");
	$single011VisitPlayer = getFieldValue("single011VisitPlayer");
	$single011VisitWins = getFieldValue("single011VisitWins");
		
	$single012HomePlayer =getFieldValue("single012HomePlayer");
	$single012HomeWins =getFieldValue("single012HomeWins");
	$single012VisitPlayer =getFieldValue("single012VisitPlayer");
	$single012VisitWins =getFieldValue("single012VisitWins");	

	$single013HomePlayer =getFieldValue("single013HomePlayer");
	$single013HomeWins =getFieldValue("single013HomeWins");
	$single013VisitPlayer =getFieldValue("single013VisitPlayer");
	$single013VisitWins =getFieldValue("single013VisitWins");	
	
	$single014HomePlayer =getFieldValue("single014HomePlayer");
	$single014HomeWins =getFieldValue("single014HomeWins");
	$single014VisitPlayer =getFieldValue("single014VisitPlayer");
	$single014VisitWins =getFieldValue("single014VisitWins");	
	
	
	$singleCricket1HomePlayer = getFieldValue("singleCricket1HomePlayer");
	$singleCricket1HomeWins = getFieldValue("singleCricket1HomeWins");
	$singleCricket1VisitPlayer = getFieldValue("singleCricket1VisitPlayer");
	$singleCricket1VisitWins = getFieldValue("singleCricket1VisitWins");
		
	$singleCricket2HomePlayer =getFieldValue("singleCricket2HomePlayer");
	$singleCricket2HomeWins =getFieldValue("singleCricket2HomeWins");
	$singleCricket2VisitPlayer =getFieldValue("singleCricket2VisitPlayer");
	$singleCricket2VisitWins =getFieldValue("singleCricket2VisitWins");	

	$singleCricket3HomePlayer =getFieldValue("singleCricket3HomePlayer");
	$singleCricket3HomeWins =getFieldValue("singleCricket3HomeWins");
	$singleCricket3VisitPlayer =getFieldValue("singleCricket3VisitPlayer");
	$singleCricket3VisitWins =getFieldValue("singleCricket3VisitWins");	
	
	$singleCricket4HomePlayer =getFieldValue("singleCricket4HomePlayer");
	$singleCricket4HomeWins =getFieldValue("singleCricket4HomeWins");
	$singleCricket4VisitPlayer =getFieldValue("singleCricket4VisitPlayer");
	$singleCricket4VisitWins =getFieldValue("singleCricket4VisitWins");	
	
	$doubles011HomePlayer1 = getFieldValue("doubles011HomePlayer1");
	$doubles011HomePlayer2 = getFieldValue("doubles011HomePlayer2");
	$doubles011HomeWins = getFieldValue("doubles011HomeWins");
	$doubles011VisitPlayer1 = getFieldValue("doubles011VisitPlayer1");
	$doubles011VisitPlayer2 = getFieldValue("doubles011VisitPlayer2");
	$doubles011VisitWins = getFieldValue("doubles011VisitWins");
		
	$doubles012HomePlayer1 =getFieldValue("doubles012HomePlayer1");
	$doubles012HomePlayer2 =getFieldValue("doubles012HomePlayer2");
	$doubles012HomeWins =getFieldValue("doubles012HomeWins");
	$doubles012VisitPlayer1 =getFieldValue("doubles012VisitPlayer1");
	$doubles012VisitPlayer2 =getFieldValue("doubles012VisitPlayer2");
	$doubles012VisitWins =getFieldValue("doubles012VisitWins");	
	
	$doublesCricket1HomePlayer1 = getFieldValue("doublesCricket1HomePlayer1");
	$doublesCricket1HomePlayer2 = getFieldValue("doublesCricket1HomePlayer2");
	$doublesCricket1HomeWins = getFieldValue("doublesCricket1HomeWins");
	$doublesCricket1VisitPlayer1 = getFieldValue("doublesCricket1VisitPlayer1");
	$doublesCricket1VisitPlayer2 = getFieldValue("doublesCricket1VisitPlayer2");
	$doublesCricket1VisitWins = getFieldValue("doublesCricket1VisitWins");
		
	$doublesCricket2HomePlayer1 =getFieldValue("doublesCricket2HomePlayer1");
	$doublesCricket2HomePlayer2 =getFieldValue("doublesCricket2HomePlayer2");
	$doublesCricket2HomeWins =getFieldValue("doublesCricket2HomeWins");
	$doublesCricket2VisitPlayer1 =getFieldValue("doublesCricket2VisitPlayer1");
	$doublesCricket2VisitPlayer2 =getFieldValue("doublesCricket2VisitPlayer2");
	$doublesCricket2VisitWins =getFieldValue("doublesCricket2VisitWins");	
	
	$additionalNotesValue = getFieldValue("additionalNotes");
	
	$homeplayers = getPlayersForTeam($log, $homeTeam->getTeamId());
	$visitplayers = getPlayersForTeam($log, $visitingTeam->getTeamId());	
	
	$body .= '   <div class="halfWidth">';
	$body .= '     <div class="textbox dl-input">';

	$body .= '<input type="hidden" name="homeTeam" value="'.$homeTeam->getTeamId().'"/>';
	$body .= '<h3>Home Team: ';
	$body .= $homeTeam->getTeamName();
	$body .='</h3>';

	$body .= '      </div>';
	$body .= '    </div>';
	$body .= '    <div class="halfWidthRight">';
	$body .= '      <div class="textbox dl-input">';

	$body .= '<input type="hidden" name="visitingTeam" value="'.$visitingTeam->getTeamId().'"/>';
	$body .= '<h3>Visiting Team: ';
	$body .=  $visitingTeam->getTeamName();
	$body .='</h3>';
	$body .= '     </div>';
	$body .= '    </div>';
	
	$body .= '<div class="gameInfo">';
	//singles 01
	$body .= '	<div class="bedl-setContainer">';
	$body .= '		<div class="bedl-gameTitle">Singles 01</div>';	
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight">Home Team Player</div>';
	$body .= '				<div class="bedl-wins">Wins</div>';
	$body .= '				<div class="bedl-vs"></div>';
	$body .= '				<div class="bedl-wins">Wins</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft">Visiting Team Player</div>';
	$body .= '			</div>';
	$body .= '		</div>';
	//singles 01 1
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight bedl-standardInput">';	
	$body .= displayField(getPlayerDropdown($homeplayers,"single011HomePlayer",$single011HomePlayer),"single011HomePlayer",$errors);	
	$body .= '				</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';		
	$body .= displayField(getGamesWonDropdown("single011HomeWins",$single011HomeWins),"single011HomeWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-vs">VS</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';
	$body .= displayField(getGamesWonDropdown("single011VisitWins",$single011VisitWins),"single011VisitWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft bedl-standardInput">';
	$body .= displayField(getPlayerDropdown($visitplayers,"single011VisitPlayer",$single011VisitPlayer),"single011VisitPlayer",$errors);
	$body .= '				</div>';						
	$body .= '			</div>';
	$body .= '		</div>';	
	//end singles 01 1
	//singles 01 2	
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight bedl-standardInput">';	
	$body .= displayField(getPlayerDropdown($homeplayers,"single012HomePlayer",$single012HomePlayer),"single012HomePlayer",$errors);	
	$body .= '				</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';		
	$body .= displayField(getGamesWonDropdown("single012HomeWins",$single012HomeWins),"single012HomeWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-vs">VS</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';
	$body .= displayField(getGamesWonDropdown("single012VisitWins",$single012VisitWins),"single012VisitWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft bedl-standardInput">';
	$body .= displayField(getPlayerDropdown($visitplayers,"single012VisitPlayer",$single012VisitPlayer),"single012VisitPlayer",$errors);
	$body .= '				</div>';						
	$body .= '			</div>';
	$body .= '		</div>';
	//end singles 01 2
	//singles 01 3	
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight bedl-standardInput">';	
	$body .= displayField(getPlayerDropdown($homeplayers,"single013HomePlayer",$single013HomePlayer),"single013HomePlayer",$errors);	
	$body .= '				</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';		
	$body .= displayField(getGamesWonDropdown("single013HomeWins",$single013HomeWins),"single013HomeWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-vs">VS</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';
	$body .= displayField(getGamesWonDropdown("single013VisitWins",$single013VisitWins),"single013VisitWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft bedl-standardInput">';
	$body .= displayField(getPlayerDropdown($visitplayers,"single013VisitPlayer",$single013VisitPlayer),"single013VisitPlayer",$errors);
	$body .= '				</div>';						
	$body .= '			</div>';
	$body .= '		</div>';	
	//end singles 01 3
	//singles 01 4
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight bedl-standardInput">';	
	$body .= displayField(getPlayerDropdown($homeplayers,"single014HomePlayer",$single014HomePlayer),"single014HomePlayer",$errors);	
	$body .= '				</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';		
	$body .= displayField(getGamesWonDropdown("single014HomeWins",$single014HomeWins),"single014HomeWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-vs">VS</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';
	$body .= displayField(getGamesWonDropdown("single014VisitWins",$single014VisitWins),"single014VisitWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft bedl-standardInput">';
	$body .= displayField(getPlayerDropdown($visitplayers,"single014VisitPlayer",$single014VisitPlayer),"single014VisitPlayer",$errors);
	$body .= '				</div>';						
	$body .= '			</div>';
	$body .= '		</div>';
	$body .= '	</div>';	
	//end singles 01 4
	//end singles 01
	
	//singles cricket
	$body .= '	<div class="bedl-setContainer">';
	$body .= '		<div class="bedl-gameTitle">Singles Cricket</div>';	
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight">Home Team Player</div>';
	$body .= '				<div class="bedl-wins">Wins</div>';
	$body .= '				<div class="bedl-vs"></div>';
	$body .= '				<div class="bedl-wins">Wins</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft">Visiting Team Player</div>';
	$body .= '			</div>';
	$body .= '		</div>';
	//singles cricket 1
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight bedl-standardInput">';	
	$body .= displayField(getPlayerDropdown($homeplayers,"singleCricket1HomePlayer",$singleCricket1HomePlayer),"singleCricket1HomePlayer",$errors);	
	$body .= '				</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';		
	$body .= displayField(getGamesWonDropdown("singleCricket1HomeWins",$singleCricket1HomeWins),"singleCricket1HomeWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-vs">VS</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';
	$body .= displayField(getGamesWonDropdown("singleCricket1VisitWins",$singleCricket1VisitWins),"singleCricket1VisitWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft bedl-standardInput">';
	$body .= displayField(getPlayerDropdown($visitplayers,"singleCricket1VisitPlayer",$singleCricket1VisitPlayer),"singleCricket1VisitPlayer",$errors);
	$body .= '				</div>';						
	$body .= '			</div>';
	$body .= '		</div>';	
	//end singles cricket 1
	//singles cricket 2	
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight bedl-standardInput">';	
	$body .= displayField(getPlayerDropdown($homeplayers,"singleCricket2HomePlayer",$singleCricket2HomePlayer),"singleCricket2HomePlayer",$errors);	
	$body .= '				</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';		
	$body .= displayField(getGamesWonDropdown("singleCricket2HomeWins",$singleCricket2HomeWins),"singleCricket2HomeWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-vs">VS</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';
	$body .= displayField(getGamesWonDropdown("singleCricket2VisitWins",$singleCricket2VisitWins),"singleCricket2VisitWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft bedl-standardInput">';
	$body .= displayField(getPlayerDropdown($visitplayers,"singleCricket2VisitPlayer",$singleCricket2VisitPlayer),"singleCricket2VisitPlayer",$errors);
	$body .= '				</div>';						
	$body .= '			</div>';
	$body .= '		</div>';
	//end singles cricket 2
	//singles cricket 3	
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight bedl-standardInput">';	
	$body .= displayField(getPlayerDropdown($homeplayers,"singleCricket3HomePlayer",$singleCricket3HomePlayer),"singleCricket3HomePlayer",$errors);	
	$body .= '				</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';		
	$body .= displayField(getGamesWonDropdown("singleCricket3HomeWins",$singleCricket3HomeWins),"singleCricket3HomeWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-vs">VS</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';
	$body .= displayField(getGamesWonDropdown("singleCricket3VisitWins",$singleCricket3VisitWins),"singleCricket3VisitWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft bedl-standardInput">';
	$body .= displayField(getPlayerDropdown($visitplayers,"singleCricket3VisitPlayer",$singleCricket3VisitPlayer),"singleCricket3VisitPlayer",$errors);
	$body .= '				</div>';						
	$body .= '			</div>';
	$body .= '		</div>';	
	//end singles cricket 3
	//singles cricket 4
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight bedl-standardInput">';	
	$body .= displayField(getPlayerDropdown($homeplayers,"singleCricket4HomePlayer",$singleCricket4HomePlayer),"singleCricket4HomePlayer",$errors);	
	$body .= '				</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';		
	$body .= displayField(getGamesWonDropdown("singleCricket4HomeWins",$singleCricket4HomeWins),"singleCricket4HomeWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-vs">VS</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';
	$body .= displayField(getGamesWonDropdown("singleCricket4VisitWins",$singleCricket4VisitWins),"singleCricket4VisitWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft bedl-standardInput">';
	$body .= displayField(getPlayerDropdown($visitplayers,"singleCricket4VisitPlayer",$singleCricket4VisitPlayer),"singleCricket4VisitPlayer",$errors);
	$body .= '				</div>';						
	$body .= '			</div>';
	$body .= '		</div>';
	$body .= '	</div>';	
	//end singles cricket 4
	//end singles cricket
	
	
	
	//doubles 01
	$body .= '	<div class="bedl-setContainer">';
	$body .= '		<div class="bedl-gameTitle">Doubles 01</div>';	
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight">Home Team Players</div>';
	$body .= '				<div class="bedl-wins">Wins</div>';
	$body .= '				<div class="bedl-vs"></div>';
	$body .= '				<div class="bedl-wins">Wins</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft">Visiting Team Players</div>';
	$body .= '			</div>';
	$body .= '		</div>';
	//doubles 01 1
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight bedl-standardInput">';	
	$body .= displayField(getPlayerDropdown($homeplayers,"doubles011HomePlayer1",$doubles011HomePlayer1),"doubles011HomePlayer1",$errors);	
	$body .= '<br/>';
	$body .= displayField(getPlayerDropdown($homeplayers,"doubles011HomePlayer2",$doubles011HomePlayer2),"doubles011HomePlayer2",$errors);	
	$body .= '				</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';		
	$body .= displayField(getGamesWonDropdown("doubles011HomeWins",$doubles011HomeWins),"doubles011HomeWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-vs">VS</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';
	$body .= displayField(getGamesWonDropdown("doubles011VisitWins",$doubles011VisitWins),"doubles011VisitWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft bedl-standardInput">';
	$body .= displayField(getPlayerDropdown($visitplayers,"doubles011VisitPlayer1",$doubles011VisitPlayer1),"doubles011VisitPlayer1",$errors);
	$body .= '<br/>';
	$body .= displayField(getPlayerDropdown($visitplayers,"doubles011VisitPlayer2",$doubles011VisitPlayer2),"doubles011VisitPlayer2",$errors);
	$body .= '				</div>';						
	$body .= '			</div>';
	$body .= '		</div>';	
	//end doubles 01 1
	//doubles 01 2	
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight bedl-standardInput">';	
	$body .= displayField(getPlayerDropdown($homeplayers,"doubles012HomePlayer1",$doubles012HomePlayer1),"doubles012HomePlayer1",$errors);	
	$body .= '<br/>';
	$body .= displayField(getPlayerDropdown($homeplayers,"doubles012HomePlayer2",$doubles012HomePlayer2),"doubles012HomePlayer2",$errors);	
	$body .= '				</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';		
	$body .= displayField(getGamesWonDropdown("doubles012HomeWins",$doubles012HomeWins),"doubles012HomeWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-vs">VS</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';
	$body .= displayField(getGamesWonDropdown("doubles012VisitWins",$doubles012VisitWins),"doubles012VisitWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft bedl-standardInput">';
	$body .= displayField(getPlayerDropdown($visitplayers,"doubles012VisitPlayer1",$doubles012VisitPlayer1),"doubles012VisitPlayer1",$errors);
	$body .= '<br/>';
	$body .= displayField(getPlayerDropdown($visitplayers,"doubles012VisitPlayer2",$doubles012VisitPlayer2),"doubles012VisitPlayer2",$errors);
	$body .= '				</div>';						
	$body .= '			</div>';
	$body .= '		</div>';
	$body .= '	</div>';	
	//end doubles 01 2	
	//end doubles 01
	
	//doubles Cricket
	$body .= '	<div class="bedl-setContainer">';
	$body .= '		<div class="bedl-gameTitle">Doubles Cricket</div>';	
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight">Home Team Players</div>';
	$body .= '				<div class="bedl-wins">Wins</div>';
	$body .= '				<div class="bedl-vs"></div>';
	$body .= '				<div class="bedl-wins">Wins</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft">Visiting Team Players</div>';
	$body .= '			</div>';
	$body .= '		</div>';
	//doubles Cricket 1
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight bedl-standardInput">';	
	$body .= displayField(getPlayerDropdown($homeplayers,"doublesCricket1HomePlayer1",$doublesCricket1HomePlayer1),"doublesCricket1HomePlayer1",$errors);	
	$body .= '<br/>';
	$body .= displayField(getPlayerDropdown($homeplayers,"doublesCricket1HomePlayer2",$doublesCricket1HomePlayer2),"doublesCricket1HomePlayer2",$errors);	
	$body .= '				</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';		
	$body .= displayField(getGamesWonDropdown("doublesCricket1HomeWins",$doublesCricket1HomeWins),"doublesCricket1HomeWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-vs">VS</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';
	$body .= displayField(getGamesWonDropdown("doublesCricket1VisitWins",$doublesCricket1VisitWins),"doublesCricket1VisitWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft bedl-standardInput">';
	$body .= displayField(getPlayerDropdown($visitplayers,"doublesCricket1VisitPlayer1",$doublesCricket1VisitPlayer1),"doublesCricket1VisitPlayer1",$errors);
	$body .= '<br/>';
	$body .= displayField(getPlayerDropdown($visitplayers,"doublesCricket1VisitPlayer2",$doublesCricket1VisitPlayer2),"doublesCricket1VisitPlayer2",$errors);
	$body .= '				</div>';						
	$body .= '			</div>';
	$body .= '		</div>';	
	//end doubles Cricket 1
	//doubles Cricket 2	
	$body .= '		<div class="bedl-match">';
	$body .= '			<div class="bedl-centerDivs">';
	$body .= '				<div class="bedl-playerNameColumnRight bedl-standardInput">';	
	$body .= displayField(getPlayerDropdown($homeplayers,"doublesCricket2HomePlayer1",$doublesCricket2HomePlayer1),"doublesCricket2HomePlayer1",$errors);	
	$body .= '<br/>';
	$body .= displayField(getPlayerDropdown($homeplayers,"doublesCricket2HomePlayer2",$doublesCricket2HomePlayer2),"doublesCricket2HomePlayer2",$errors);	
	$body .= '				</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';		
	$body .= displayField(getGamesWonDropdown("doublesCricket2HomeWins",$doublesCricket2HomeWins),"doublesCricket2HomeWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-vs">VS</div>';
	$body .= '				<div class="bedl-wins bedl-standardInput">';
	$body .= displayField(getGamesWonDropdown("doublesCricket2VisitWins",$doublesCricket2VisitWins),"doublesCricket2VisitWins",$errors);
	$body .= '				</div>';
	$body .= '				<div class="bedl-playerNameColumnLeft bedl-standardInput">';
	$body .= displayField(getPlayerDropdown($visitplayers,"doublesCricket2VisitPlayer1",$doublesCricket2VisitPlayer1),"doublesCricket2VisitPlayer1",$errors);
	$body .= '<br/>';
	$body .= displayField(getPlayerDropdown($visitplayers,"doublesCricket2VisitPlayer2",$doublesCricket2VisitPlayer2),"doublesCricket2VisitPlayer2",$errors);
	$body .= '				</div>';						
	$body .= '			</div>';
	$body .= '		</div>';
	$body .= '	</div>';	
	//end doubles Cricket 2	
	//end doubles Cricket
	
	$body .= '</div>';	
	
	$body .= '<div class="gameInfo">';	
	$body .= '  <div class="playerInfo">';
	$body .= '    <h3>Player Points</h3>';
	$body .= '    <p class="instructions">Select a player from the list and enter the number of points that player received this week.</p>';
	$body .= '    <div id="homePlayers" class="halfWidth">';
	$body .= '      <h4>Home Team</h4>';

	
	$body .= '<table><tr><td width="150px" class="playerTableHeader">Player</td><td class="playerTableHeader" width="70px">Singles 01</td><td class="playerTableHeader" width="70px">Singles Cricket</td><td class="playerTableHeader" width="70px">Doubles 01</td><td class="playerTableHeader" width="70px">Doubles Cricket</td></tr>';
	foreach ($homeplayers as $i => $player) {
			$singles01Points = getFieldValue('player'.$player->getPlayerId().'Singles01Points');
			$singlesCricketPoints = getFieldValue('player'.$player->getPlayerId().'SinglesCricketPoints');
			$doubles01Points = getFieldValue('player'.$player->getPlayerId().'Doubles01Points');
			$doublesCricketPoints = getFieldValue('player'.$player->getPlayerId().'DoublesCricketPoints');
				
			$body .= '<tr><td width="150">'.$player->getFirstName()." ".$player->getLastName().'</td><td>';
			$body .= displayField('<input type="text" name="player'.$player->getPlayerId().'Singles01Points" id="player'.$player->getPlayerId().'Singles01Points" size="3" maxlength="3" value="'.$singles01Points.'"/>'."\r\n","player".$player->getPlayerId()."Singles01Points",$errors);
			$body .= '</td><td>';
			$body .= displayField('<input type="text" name="player'.$player->getPlayerId().'SinglesCricketPoints" id="player'.$player->getPlayerId().'SinglesCricketPoints" size="3" maxlength="3" value="'.$singlesCricketPoints.'"/>'."\r\n","player".$player->getPlayerId()."SinglesCricketPoints",$errors);			
			$body .= '</td><td>';
			$body .= displayField('<input type="text" name="player'.$player->getPlayerId().'Doubles01Points" id="player'.$player->getPlayerId().'Doubles01Points" size="3" maxlength="3" value="'.$doubles01Points.'"/>'."\r\n","player".$player->getPlayerId()."Doubles01Points",$errors);			
			$body .= '</td><td>';
			$body .= displayField('<input type="text" name="player'.$player->getPlayerId().'DoublesCricketPoints" id="player'.$player->getPlayerId().'DoublesCricketPoints" size="3" maxlength="3" value="'.$doublesCricketPoints.'"/>'."\r\n","player".$player->getPlayerId()."DoublesCricketPoints",$errors);			
			$body .= '</td></tr>';
		}
	$body .= '</table>';
	$body .= '    </div>';
	$body .= '    <div class="halfWidth">';
	$body .= '      <h4>Visiting Team</h4>';
	
	$body .= '<table><tr><td width="150px" class="playerTableHeader">Player</td><td class="playerTableHeader" width="70px">Singles 01</td><td class="playerTableHeader" width="70px">Singles Cricket</td><td class="playerTableHeader" width="70px">Doubles 01</td><td class="playerTableHeader" width="70px">Doubles Cricket</td></tr>';
	foreach ($visitplayers as $i => $player) {
			$singles01Points = getFieldValue('player'.$player->getPlayerId().'Singles01Points');
			$singlesCricketPoints = getFieldValue('player'.$player->getPlayerId().'SinglesCricketPoints');
			$doubles01Points = getFieldValue('player'.$player->getPlayerId().'Doubles01Points');
			$doublesCricketPoints = getFieldValue('player'.$player->getPlayerId().'DoublesCricketPoints');
				
			$body .= '<tr><td width="150">'.$player->getFirstName()." ".$player->getLastName().'</td><td>';
			$body .= displayField('<input type="text" name="player'.$player->getPlayerId().'Singles01Points" id="player'.$player->getPlayerId().'Singles01Points" size="3" maxlength="3" value="'.$singles01Points.'"/>'."\r\n","player".$player->getPlayerId()."Singles01Points",$errors);
			$body .= '</td><td>';
			$body .= displayField('<input type="text" name="player'.$player->getPlayerId().'SinglesCricketPoints" id="player'.$player->getPlayerId().'SinglesCricketPoints" size="3" maxlength="3" value="'.$singlesCricketPoints.'"/>'."\r\n","player".$player->getPlayerId()."SinglesCricketPoints",$errors);			
			$body .= '</td><td>';
			$body .= displayField('<input type="text" name="player'.$player->getPlayerId().'Doubles01Points" id="player'.$player->getPlayerId().'Doubles01Points" size="3" maxlength="3" value="'.$doubles01Points.'"/>'."\r\n","player".$player->getPlayerId()."Doubles01Points",$errors);			
			$body .= '</td><td>';
			$body .= displayField('<input type="text" name="player'.$player->getPlayerId().'DoublesCricketPoints" id="player'.$player->getPlayerId().'DoublesCricketPoints" size="3" maxlength="3" value="'.$doublesCricketPoints.'"/>'."\r\n","player".$player->getPlayerId()."DoublesCricketPoints",$errors);			
			$body .= '</td></tr>';
		}
	$body .= '</table>';
	$body .= '  </div>';
	$body .= '</div>';
	
	
	$body .= '<div class="bedl-specialShots">';
	$body .= '<h3>Player Shots</h3>';
	$body .=  '<div class="bedl-inlineInputs">'."\r\n";
	
	$body .=  '<div class="bedl-repeatingFields bedl-inlineInputs" data-bedl-maxfieldsets="10">'."\r\n";
	
	$allplayers = array_merge($visitplayers,$homeplayers);
	
	$body .= generateShotFieldSet($log,$allplayers,"1",$errors);
	
	for($i = 2;$i<10;$i += 1){
		if(isset($_POST['specialShotPlayerName'.$i])){
		$body .= generateShotFieldSet($log,$allplayers,$i,$errors);
		
			$str = "Special Shot PlayerName".$i." found: ".$_POST['specialShotPlayerName'.$i].", Shot Type: ".$_POST['specialShotType'.$i].", Shot Value: ".$_POST['specialShotValue'.$i]."\r\n";
		
		}
	}
	
	
	$body .= '</div>'."\r\n";
	$body .= '</div>'."\r\n";
	$body .= '</div>'."\r\n";	
	
	
	$body .= '  <div class="additionalNotes dl-input">';
	$body .= '    <label for="additionalNotes">Additional Notes</label>';
	$body .= '    <textarea name="additionalNotes" id="additionalNotes" cols="60" rows="10" value="'.$additionalNotesValue.'">'.$additionalNotesValue.'</textarea>';
	$body .= '  </div>';	
	$body .= '</div>';
	
	return $body;
}

function drawOutOfSeason($log){
	$seasonName = getSeasonName($log);
	$header = $seasonName.' Season';	
	$body = "";		
	$body .=  '<form action="./members.php" method="post">';
	$body .=  '<div><br/>';
	$body .=  "I'm sorry but there are currently no stats due.<br/><br/>";
	$body .=  '<input type="submit" name="BTN_BACK" value="Back"/>';
	$body .=  '</div>';
	$body .=  '</form>';	
	$output .= drawContainer($header,$body);
	return $output;
}


function displaySuccessPage($log){	
	$seasonName = getSeasonName($log);
	$header = $seasonName.' Season';	
	$body = "";	
	$body .= '<form action="./members.php" method="post">';
	$body .= '<div><br/>';
	$body .= 'Success!  Weekly stats submitted.<br/><br/>';
	$body .= '<input type="submit" name="BTN_BACK" value="Back"/>';
	$body .= '</div>';
	$body .= '</form>';	
	$output .= drawContainer($header,$body);
	return $output;
}

?>