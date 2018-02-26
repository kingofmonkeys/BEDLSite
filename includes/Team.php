<?php


class Team {
    // property declaration
    var $teamId;
	var $teamName;
	var $division;

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


    function getTeamId() {
	        return $this->{"teamId"};
	   }
	function getTeamName() {
		        return $this->{"teamName"};
    }
    function getDivision() {
			 return $this->{"division"};
    }


}
?>