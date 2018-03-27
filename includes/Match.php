<?php
class Match {
    // property declaration
    var $ID;
	var $week;
	var $hometeamid;
	var $visitingteamid;
	var $hometeamname;
	var $visitingteamname;
	var $scoresentered;
	
	function Match(){
	}

    // method declaration
    function setID($IDset) {
        $this->{"ID"}=$IDset;
    }
    function setWeek($weekset) {
	    $this->{"week"}=$weekset;
    }
	function setHomeTeamId($hometeamidset) {
        $this->{"hometeamid"}=$hometeamidset;
    }
    function setVisitingTeamId($visitingteamidset) {
	    $this->{"visitingteamid"}=$visitingteamidset;
    }
	
	function setHomeTeamName($hometeamnameset) {
        $this->{"hometeamname"}=$hometeamnameset;
    }
    function setVisitingTeamName($visitingteamnameset) {
		$this->{"visitingteamname"}=$visitingteamnameset;
    }
	
	function setScoresEntered($scoresenteredset) {
	    $this->{"scoresentered"}=$scoresenteredset;
    }
	
    function getID() {
	    return $this->{"ID"};
	}
	function getWeek() {
	    return $this->{"week"};
    }
	function getHomeTeamId() {
	    return $this->{"hometeamid"};
	}
	function getVisitingTeamId() {
		return $this->{"visitingteamid"};
    }
	function getHomeTeamName() {
	    return $this->{"hometeamname"};
	}
	function getVisitingTeamName() {
	    return $this->{"visitingteamname"};
    }
	function getScoresEntered() {
	    return $this->{"scoresentered"};
    }
}
?>