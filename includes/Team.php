<?php


class Team {
    // property declaration
    var $teamId;
	var $teamName;
	var $division;
	var $oname;
	var $shortName;

	function Team(){
	}

    // method declaration
    function setTeamId($teamIdset) {
        $this->{"teamId"}=$teamIdset;
    }
    function setTeamName($teamNameset) {
	    $this->{"teamName"}=$teamNameset;
    }
    function setDivision($divisionset) {
	    $this->{"division"}=$divisionset;
    }
	function setoname($onameset) {
	    $this->{"oname"}=$onameset;
    }
	function setShortName($shortNameset) {
	    $this->{"shortName"}=$shortNameset;
    }

    function getTeamId() {
	    return $this->{"teamId"};
	}
	function getTeamName() {
	    return $this->{"teamName"};
    }
    function getDivision() {
		return $this->{"division"};
    }
	function getOname() {
		return $this->{"oname"};
    }
	function getShortName() {
		return $this->{"shortName"};
    }


}
?>