<?php


function getNewRatings($playerA, $playerB, $winner, $k, $rankOffset, $debug, $log){
	//$k = 32;
	$scoreA = 0;
	$scoreB = 0;
	
	$playerARating = $playerA->getRating();
	$playerBRating = $playerB->getRating();
	$playerAAdjustedRating = $playerARating;
	$playerBAdjustedRating = $playerBRating;
	if($rankOffset!=0){
		if($playerA->getRank()>$playerB->getRank()){
			//player a is a lower rank than b so they get a handicap we should raise their ranking for this match.
			$adjust = ($playerA->getRank()-$playerB->getRank())*$rankOffset;
			$playerAAdjustedRating = $playerARating+$adjust;		
		}else if($playerB->getRank()>$playerA->getRank()){
			//player b is a lower rank than player a so they get a handicap we should raise their ranking for this match.
			$adjust = ($playerB->getRank()-$playerA->getRank())*$rankOffset;
			$playerBAdjustedRating = $playerBRating+$adjust;		
		}	
	}
	
	if($playerA->getPlayerId()==$winner){
		$scoreA = 1;
	}else{
		$scoreB = 1;
	}	
	
	if($debug==True){
		$log->LogDebug("Before rating update");
		$log->LogDebug("Winner=".$winner);
		$log->LogDebug("Player ". $playerA->getPlayerId() ." ". $playerA->getFirstName() . " ".$playerA->getLastName() . " Player Rank: ". $playerA->getRank() ." Rating: ".$playerARating. " Expected: " . getExpectedScore($playerARating, $playerBRating));
		$log->LogDebug("Player ". $playerB->getPlayerId() ." ". $playerB->getFirstName() . " ".$playerB->getLastName() . " Player Rank: ". $playerB->getRank() ." Rating: ".$playerBRating. " Expected: " . getExpectedScore($playerBRating, $playerARating));
	}
    $newRatingA = $playerARating + ($k * ($scoreA - getExpectedScore($playerAAdjustedRating, $playerBAdjustedRating)));

    $newRatingB = $playerBRating + ($k * ($scoreB - getExpectedScore($playerBAdjustedRating, $playerAAdjustedRating)));

	$pointsExchanged = abs($playerARating-round($newRatingA));
	
	if($debug==True){
		$log->LogDebug("After rating update");
		$log->LogDebug("Points exchanged: ". $pointsExchanged);
	}
	
    $playerA->setRating(round($newRatingA));
    $playerB->setRating(round($newRatingB));
	
	if($debug==True){
		$log->LogDebug("Player ". $playerA->getPlayerId() ." ". $playerA->getFirstName() . " ".$playerA->getLastName() . " Rating: ".$playerA->getRating());
		$log->LogDebug("Player ". $playerB->getPlayerId() ." ". $playerB->getFirstName() . " ".$playerB->getLastName() . " Rating: ".$playerB->getRating());
	}
	
	$results = array($playerA->getPlayerId()=>$playerA, $playerB->getPlayerId()=>$playerB,'pointsExchanged'=>$pointsExchanged);
	
	return $results;
}
 
// This gets the expected score for the ratingA player
function getExpectedScore($ratingA, $ratingB) {
    $expectedScores;
    
	$expectedScores = 1 / (1 + (pow(10, ($ratingB - $ratingA) / 400)));

    return $expectedScores;

 }

?>