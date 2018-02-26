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



/*
function DisplayImage($title, $file){
	$output ="";
	$output .= '<table width = "100%" cellspacing="0" cellpadding="0"><tr><td width ="100%" class="heading" >';
	$output .= '<center>'.$title.'</center></td></tr></table>';
	$output .= '<table width = "100%" cellspacing="0" cellpadding="0"><tr><td class="contentarea">';
	$output .= '<center><img src="'.$file.'"/><br>';
	$output .= '<a href="./gallery.php">Back to Gallery</a>';
	$output .= '</center>';
	$output .= '</td></tr></table>';
	return $output;
}

*/
?>