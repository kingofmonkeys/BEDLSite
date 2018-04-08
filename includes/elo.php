<?php


function getNewRatings($playerA, $playerB, $winner, $debug, $log){
	$k = 32;
	$scoreA = 0;
	$scoreB = 0;
	
	
	
	if($playerA->getPlayerId()==$winner){
		$scoreA = 1;
	}else{
		$scoreB = 1;
	}
	if($debug==True){
		$log->LogDebug("Before rating update");
		$log->LogDebug("Winner=".$winner);
		$log->LogDebug("Player ". $playerA->getPlayerId() ." ". $playerA->getFirstName() . " ".$playerA->getLastName() . " Rating: ".$playerA->getRating(). " Expected: " . getExpectedScore($playerA->getRating(), $playerB->getRating()));
		$log->LogDebug("Player ". $playerB->getPlayerId() ." ". $playerB->getFirstName() . " ".$playerB->getLastName() . " Rating: ".$playerB->getRating(). " Expected: " . getExpectedScore($playerB->getRating(), $playerA->getRating()));
	}
    $newRatingA = $playerA->getRating() + ($k * ($scoreA - getExpectedScore($playerA->getRating(), $playerB->getRating())));

    $newRatingB = $playerB->getRating() + ($k * ($scoreB - getExpectedScore($playerB->getRating(), $playerA->getRating())));

	$pointsExchanged = abs($playerA->getRating()-round($newRatingA));
	
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