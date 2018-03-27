<?php

function hasFieldError($field,$errors)
{
	for($i=0;$i<count($errors);$i++)
	{
		if($errors[$i]->getfieldname()==$field)
		{
			return true;
		}
	}
	return false;
}

function setError($field,$error,$errors){
	$errorobj =new FormError();
	$errorobj->setfielderror($error);
	$errorobj->setfieldname($field);
	$errors[] = $errorobj;
	return $errors;
}

function check_email_address($temp_email) {
        function valid_dot_pos($email) {
            $str_len = strlen($email);
            for($i=0; $i<$str_len; $i++) {
                $current_element = $email[$i];
                if($current_element == "." && ($email[$i+1] == ".")) {
                    return false;
                    break;
                }
                else {

                }
            }
            return true;
        }
        function valid_local_part($local_part) {
            if(preg_match("/[^a-zA-Z0-9-_@.!#$%&'*\/+=?^`{\|}~]/", $local_part)) {
                return false;
            }
            else {
                return true;
            }
        }
        function valid_domain_part($domain_part) {
            if(preg_match("/[^a-zA-Z0-9@#\[\].]/", $domain_part)) {
                return false;
            }
            elseif(preg_match("/[@]/", $domain_part) && preg_match("/[#]/", $domain_part)) {
                return false;
            }
            elseif(preg_match("/[\[]/", $domain_part) || preg_match("/[\]]/", $domain_part)) {
                $dot_pos = strrpos($domain_part, ".");
                if(($dot_pos < strrpos($domain_part, "]")) || (strrpos($domain_part, "]") < strrpos($domain_part, "["))) {
                    return true;
                }
                elseif(preg_match("/[^0-9.]/", $domain_part)) {
                    return false;
                }
                else {
                    return false;
                }
            }
            else {
                return true;
            }
        }
        $str_trimmed = trim($temp_email);
        $at_pos = strrpos($str_trimmed, "@");
        $dot_pos = strrpos($str_trimmed, ".");
        $local_part = substr($str_trimmed, 0, $at_pos);
        $domain_part = substr($str_trimmed, $at_pos);
        if(!isset($str_trimmed) || is_null($str_trimmed) || empty($str_trimmed) || $str_trimmed == "") {
            return false;
        }
        elseif(!valid_local_part($local_part)) {
            return false;
        }
        elseif(!valid_domain_part($domain_part)) {
           return false;
        }
        elseif($at_pos > $dot_pos) {
            return false;
        }
        elseif(!valid_local_part($local_part)) {
            return false;
        }
        elseif(($str_trimmed[$at_pos + 1]) == ".") {
            return false;
        }
        elseif(!preg_match("/[(@)]/", $str_trimmed) || !preg_match("/[(.)]/", $str_trimmed)) {
            return false;
        }
        else {
           return true;
        }
}

function validateScoreSheet($log){
$errors = array();

//check here to see if scores already submitted for this match.

//you likely need to add something about one person playing more than one match per set
//singles 01 1
//check if home team player is set
if($_POST['single011HomePlayer']==''){
	$errors = setError("single011HomePlayer","Home Team Player field is required.",$errors);
}
//check if visit  team player is set
if($_POST['single011VisitPlayer']==''){
	$errors = setError("single011VisitPlayer","Visiting Team Player field is required.",$errors);
}
//check if wins are between 2 and 3 combined
if($_POST['single011HomeWins']+$_POST['single011VisitWins']<2){
	$errors = setError("single011HomeWins","Combined match wins must be 2 or greater",$errors);	
}
if($_POST['single011HomeWins']+$_POST['single011VisitWins']>3){
	$errors = setError("single011HomeWins","Combined match wins must be 3 or less",$errors);
}
if($_POST['single011HomeWins']+$_POST['single011VisitWins']==2 && $_POST['single011HomeWins']==1){
	$errors = setError("single011HomeWins","One player MUST win 2 games to complete the match",$errors);	
}


//singles 01 2
//check if home team player is set
if($_POST['single012HomePlayer']==''){
	$errors = setError("single012HomePlayer","Home Team Player field is required.",$errors);
}
//check if visit  team player is set
if($_POST['single012VisitPlayer']==''){
	$errors = setError("single012VisitPlayer","Visiting Team Player field is required.",$errors);
}
//check if wins are between 2 and 3 combined
if($_POST['single012HomeWins']+$_POST['single012VisitWins']<2){
	$errors = setError("single012HomeWins","Combined match wins must be 2 or greater",$errors);	
}
if($_POST['single012HomeWins']+$_POST['single012VisitWins']>3){
	$errors = setError("single012HomeWins","Combined match wins must be 3 or less",$errors);
}
if($_POST['single012HomeWins']+$_POST['single012VisitWins']==2 && $_POST['single012HomeWins']==1){
	$errors = setError("single012HomeWins","One player MUST win 2 games to complete the match",$errors);	
}

//singles 01 3
//check if home team player is set
if($_POST['single013HomePlayer']==''){
	$errors = setError("single013HomePlayer","Home Team Player field is required.",$errors);
}
//check if visit  team player is set
if($_POST['single013VisitPlayer']==''){
	$errors = setError("single013VisitPlayer","Visiting Team Player field is required.",$errors);
}
//check if wins are between 2 and 3 combined
if($_POST['single013HomeWins']+$_POST['single013VisitWins']<2){
	$errors = setError("single013HomeWins","Combined match wins must be 2 or greater",$errors);	
}
if($_POST['single013HomeWins']+$_POST['single013VisitWins']>3){
	$errors = setError("single013HomeWins","Combined match wins must be 3 or less",$errors);
}
if($_POST['single013HomeWins']+$_POST['single013VisitWins']==2 && $_POST['single013HomeWins']==1){
	$errors = setError("single013HomeWins","One player MUST win 2 games to complete the match",$errors);	
}

//singles 01 4
//check if home team player is set
if($_POST['single014HomePlayer']==''){
	$errors = setError("single014HomePlayer","Home Team Player field is required.",$errors);
}
//check if visit  team player is set
if($_POST['single014VisitPlayer']==''){
	$errors = setError("single014VisitPlayer","Visiting Team Player field is required.",$errors);
}
//check if wins are between 2 and 3 combined
if($_POST['single014HomeWins']+$_POST['single014VisitWins']<2){
	$errors = setError("single014HomeWins","Combined match wins must be 2 or greater",$errors);	
}
if($_POST['single014HomeWins']+$_POST['single014VisitWins']>3){
	$errors = setError("single014HomeWins","Combined match wins must be 3 or less",$errors);
}
if($_POST['single014HomeWins']+$_POST['single014VisitWins']==2 && $_POST['single014HomeWins']==1){
	$errors = setError("single014HomeWins","One player MUST win 2 games to complete the match",$errors);	
}


//you likely need to add something about one person playing more than one match per set
//singles Cricket 1
//check if home team player is set
if($_POST['singleCricket1HomePlayer']==''){
	$errors = setError("singleCricket1HomePlayer","Home Team Player field is required.",$errors);
}
//check if visit  team player is set
if($_POST['singleCricket1VisitPlayer']==''){
	$errors = setError("singleCricket1VisitPlayer","Visiting Team Player field is required.",$errors);
}
//check if wins are between 2 and 3 combined
if($_POST['singleCricket1HomeWins']+$_POST['singleCricket1VisitWins']<2){
	$errors = setError("singleCricket1HomeWins","Combined match wins must be 2 or greater",$errors);	
}
if($_POST['singleCricket1HomeWins']+$_POST['singleCricket1VisitWins']>3){
	$errors = setError("singleCricket1HomeWins","Combined match wins must be 3 or less",$errors);
}
if($_POST['singleCricket1HomeWins']+$_POST['singleCricket1VisitWins']==2 && $_POST['singleCricket1HomeWins']==1){
	$errors = setError("singleCricket1HomeWins","One player MUST win 2 games to complete the match",$errors);	
}

//singles Cricket 2
//check if home team player is set
if($_POST['singleCricket2HomePlayer']==''){
	$errors = setError("singleCricket2HomePlayer","Home Team Player field is required.",$errors);
}
//check if visit  team player is set
if($_POST['singleCricket2VisitPlayer']==''){
	$errors = setError("singleCricket2VisitPlayer","Visiting Team Player field is required.",$errors);
}
//check if wins are between 2 and 3 combined
if($_POST['singleCricket2HomeWins']+$_POST['singleCricket2VisitWins']<2){
	$errors = setError("singleCricket2HomeWins","Combined match wins must be 2 or greater",$errors);	
}
if($_POST['singleCricket2HomeWins']+$_POST['singleCricket2VisitWins']>3){
	$errors = setError("singleCricket2HomeWins","Combined match wins must be 3 or less",$errors);
}
if($_POST['singleCricket2HomeWins']+$_POST['singleCricket2VisitWins']==2 && $_POST['singleCricket2HomeWins']==1){
	$errors = setError("singleCricket2HomeWins","One player MUST win 2 games to complete the match",$errors);	
}

//singles Cricket 3
//check if home team player is set
if($_POST['singleCricket3HomePlayer']==''){
	$errors = setError("singleCricket3HomePlayer","Home Team Player field is required.",$errors);
}
//check if visit  team player is set
if($_POST['singleCricket3VisitPlayer']==''){
	$errors = setError("singleCricket3VisitPlayer","Visiting Team Player field is required.",$errors);
}
//check if wins are between 2 and 3 combined
if($_POST['singleCricket3HomeWins']+$_POST['singleCricket3VisitWins']<2){
	$errors = setError("singleCricket3HomeWins","Combined match wins must be 2 or greater",$errors);	
}
if($_POST['singleCricket3HomeWins']+$_POST['singleCricket3VisitWins']>3){
	$errors = setError("single013HomeWins","Combined match wins must be 3 or less",$errors);
}
if($_POST['singleCricket3HomeWins']+$_POST['singleCricket3VisitWins']==2 && $_POST['singleCricket3HomeWins']==1){
	$errors = setError("singleCricket3HomeWins","One player MUST win 2 games to complete the match",$errors);	
}

//singles Cricket 4
//check if home team player is set
if($_POST['singleCricket4HomePlayer']==''){
	$errors = setError("singleCricket4HomePlayer","Home Team Player field is required.",$errors);
}
//check if visit  team player is set
if($_POST['singleCricket4VisitPlayer']==''){
	$errors = setError("singleCricket4VisitPlayer","Visiting Team Player field is required.",$errors);
}
//check if wins are between 2 and 3 combined
if($_POST['singleCricket4HomeWins']+$_POST['singleCricket4VisitWins']<2){
	$errors = setError("singleCricket4HomeWins","Combined match wins must be 2 or greater",$errors);	
}
if($_POST['singleCricket4HomeWins']+$_POST['singleCricket4VisitWins']>3){
	$errors = setError("singleCricket4HomeWins","Combined match wins must be 3 or less",$errors);
}
if($_POST['singleCricket4HomeWins']+$_POST['singleCricket4VisitWins']==2 && $_POST['singleCricket1HomeWins']==1){
	$errors = setError("singleCricket4HomeWins","One player MUST win 2 games to complete the match",$errors);	
}


//doubles 01 1
//check if home team player is set
if($_POST['doubles011HomePlayer1']==''){
	$errors = setError("doubles011HomePlayer1","Home Team Player 1 field is required.",$errors);
}
if($_POST['doubles011HomePlayer2']==''){
	$errors = setError("doubles011HomePlayer2","Home Team Player 2 field is required.",$errors);
}
//check if visit  team player is set
if($_POST['doubles011VisitPlayer1']==''){
	$errors = setError("doubles011VisitPlayer1","Visiting Team Player 1 field is required.",$errors);
}
if($_POST['doubles011VisitPlayer2']==''){
	$errors = setError("doubles011VisitPlayer2","Visiting Team Player 2 field is required.",$errors);
}

//check if wins are between 2 and 3 combined
if($_POST['doubles011HomeWins']+$_POST['doubles011VisitWins']<2){
	$errors = setError("doubles011HomeWins","Combined match wins must be 2 or greater",$errors);	
}
if($_POST['doubles011HomeWins']+$_POST['doubles011VisitWins']>3){
	$errors = setError("doubles011HomeWins","Combined match wins must be 3 or less",$errors);
}
if($_POST['doubles011HomeWins']+$_POST['doubles011VisitWins']==2 && $_POST['doubles011HomeWins']==1){
	$errors = setError("doubles011HomeWins","One team MUST win 2 games to complete the match",$errors);	
}

//doubles 01 2
//check if home team player is set
if($_POST['doubles012HomePlayer1']==''){
	$errors = setError("doubles012HomePlayer1","Home Team Player 1 field is required.",$errors);
}
if($_POST['doubles012HomePlayer2']==''){
	$errors = setError("doubles012HomePlayer2","Home Team Player 2 field is required.",$errors);
}
//check if visit  team player is set
if($_POST['doubles012VisitPlayer1']==''){
	$errors = setError("doubles012VisitPlayer1","Visiting Team Player 1 field is required.",$errors);
}
if($_POST['doubles012VisitPlayer2']==''){
	$errors = setError("doubles012VisitPlayer2","Visiting Team Player 2 field is required.",$errors);
}

//check if wins are between 2 and 3 combined
if($_POST['doubles012HomeWins']+$_POST['doubles012VisitWins']<2){
	$errors = setError("doubles011HomeWins","Combined match wins must be 2 or greater",$errors);	
}
if($_POST['doubles012HomeWins']+$_POST['doubles012VisitWins']>3){
	$errors = setError("doubles012HomeWins","Combined match wins must be 3 or less",$errors);
}
if($_POST['doubles012HomeWins']+$_POST['doubles012VisitWins']==2 && $_POST['doubles012HomeWins']==1){
	$errors = setError("doubles012HomeWins","One team MUST win 2 games to complete the match",$errors);	
}


//doubles Cricket 1
//check if home team player is set
if($_POST['doublesCricket1HomePlayer1']==''){
	$errors = setError("doublesCricket1HomePlayer1","Home Team Player 1 field is required.",$errors);
}
if($_POST['doublesCricket1HomePlayer2']==''){
	$errors = setError("doublesCricket1HomePlayer2","Home Team Player 2 field is required.",$errors);
}
//check if visit  team player is set
if($_POST['doublesCricket1VisitPlayer1']==''){
	$errors = setError("doublesCricket1VisitPlayer1","Visiting Team Player 1 field is required.",$errors);
}
if($_POST['doublesCricket1VisitPlayer2']==''){
	$errors = setError("doublesCricket1VisitPlayer2","Visiting Team Player 2 field is required.",$errors);
}

//check if wins are between 2 and 3 combined
if($_POST['doublesCricket1HomeWins']+$_POST['doublesCricket1VisitWins']<2){
	$errors = setError("doublesCricket1HomeWins","Combined match wins must be 2 or greater",$errors);	
}
if($_POST['doublesCricket1HomeWins']+$_POST['doublesCricket1VisitWins']>3){
	$errors = setError("doublesCricket1HomeWins","Combined match wins must be 3 or less",$errors);
}
if($_POST['doublesCricket1HomeWins']+$_POST['doublesCricket1VisitWins']==2 && $_POST['doublesCricket1HomeWins']==1){
	$errors = setError("doublesCricket1HomeWins","One team MUST win 2 games to complete the match",$errors);	
}

//doubles Cricket 2
//check if home team player is set
if($_POST['doublesCricket2HomePlayer1']==''){
	$errors = setError("doublesCricket2HomePlayer1","Home Team Player 1 field is required.",$errors);
}
if($_POST['doublesCricket2HomePlayer2']==''){
	$errors = setError("doublesCricket2HomePlayer2","Home Team Player 2 field is required.",$errors);
}
//check if visit  team player is set
if($_POST['doublesCricket2VisitPlayer1']==''){
	$errors = setError("doublesCricket2VisitPlayer1","Visiting Team Player 1 field is required.",$errors);
}
if($_POST['doublesCricket2VisitPlayer2']==''){
	$errors = setError("doublesCricket2VisitPlayer2","Visiting Team Player 2 field is required.",$errors);
}

//check if wins are between 2 and 3 combined
if($_POST['doublesCricket2HomeWins']+$_POST['doublesCricket2VisitWins']<2){
	$errors = setError("doublesCricket2HomeWins","Combined match wins must be 2 or greater",$errors);	
}
if($_POST['doublesCricket2HomeWins']+$_POST['doublesCricket2VisitWins']>3){
	$errors = setError("doublesCricket2HomeWins","Combined match wins must be 3 or less",$errors);
}
if($_POST['doublesCricket2HomeWins']+$_POST['doublesCricket2VisitWins']==2 && $_POST['doublesCricket2HomeWins']==1){
	$errors = setError("doublesCricket2HomeWins","One team MUST win 2 games to complete the match",$errors);	
}

$homeTeamPlayers = getPlayersForTeam($log, $_POST["homeTeam"]);
$visitingTeamPlayers = getPlayersForTeam($log, $_POST["visitingTeam"]);

	foreach ($homeTeamPlayers as $i => $player) {
		$playerId=$player->getPlayerId();
		$singles01Points = getFieldValue('player'.$player->getPlayerId().'Singles01Points');
		$singlesCricketPoints = getFieldValue('player'.$player->getPlayerId().'SinglesCricketPoints');
		$doubles01Points = getFieldValue('player'.$player->getPlayerId().'Doubles01Points');
		$doublesCricketPoints = getFieldValue('player'.$player->getPlayerId().'DoublesCricketPoints');			
		
		if($singles01Points!=""){
			//this means the players point are set.
			if(!is_numeric($singles01Points)){
				$errors = setError('player'.$player->getPlayerId().'Singles01Points',"Personal points must be a number.",$errors);
			}
		}
		
		if($singlesCricketPoints!=""){
			//this means the players point are set.
			if(!is_numeric($singlesCricketPoints)){
				$errors = setError('player'.$player->getPlayerId().'SinglesCricketPoints',"Personal points must be a number.",$errors);
			}
		}
		
		if($doubles01Points!=""){
			//this means the players point are set.
			if(!is_numeric($doubles01Points)){
				$errors = setError('player'.$player->getPlayerId().'Doubles01Points',"Personal points must be a number.",$errors);
			}
		}
		
		if($doublesCricketPoints!=""){
			//this means the players point are set.
			if(!is_numeric($doublesCricketPoints)){
				$errors = setError('player'.$player->getPlayerId().'DoublesCricketPoints',"Personal points must be a number.",$errors);
			}
		}
		
	
	}

	foreach ($visitingTeamPlayers as $i => $player) {
		$playerId=$player->getPlayerId();
		$singles01Points = getFieldValue('player'.$player->getPlayerId().'Singles01Points');
		$singlesCricketPoints = getFieldValue('player'.$player->getPlayerId().'SinglesCricketPoints');
		$doubles01Points = getFieldValue('player'.$player->getPlayerId().'Doubles01Points');
		$doublesCricketPoints = getFieldValue('player'.$player->getPlayerId().'DoublesCricketPoints');			
		
		if($singles01Points!=""){
			//this means the players point are set.
			if(!is_numeric($singles01Points)){
				$errors = setError('player'.$player->getPlayerId().'Singles01Points',"Personal points must be a number.",$errors);
			}
		}
		
		if($singlesCricketPoints!=""){
			//this means the players point are set.
			if(!is_numeric($singlesCricketPoints)){
				$errors = setError('player'.$player->getPlayerId().'SinglesCricketPoints',"Personal points must be a number.",$errors);
			}
		}
		
		if($doubles01Points!=""){
			//this means the players point are set.
			if(!is_numeric($doubles01Points)){
				$errors = setError('player'.$player->getPlayerId().'Doubles01Points',"Personal points must be a number.",$errors);
			}
		}
		
		if($doublesCricketPoints!=""){
			//this means the players point are set.
			if(!is_numeric($doublesCricketPoints)){
				$errors = setError('player'.$player->getPlayerId().'DoublesCricketPoints',"Personal points must be a number.",$errors);
			}
		}
	}

	
return $errors;
}

?>