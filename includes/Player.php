<?php


class Player {
    // property declaration
    var $playerId;
	var $firstName;
	var $lastName;
	var $teamId;


function Player(){
}

    // method declaration
    function setPlayerId($playerIdset) {
        $this->{"playerId"}=$playerIdset;
    }
    function setFirstName($firstNameset) {
	        $this->{"firstName"}=$firstNameset;
    }
    function setLastName($lastNameset) {
		        $this->{"lastName"}=$lastNameset;
    }
      function setTeamId($teamIdset) {
	        $this->{"teamId"}=$teamIdset;
    }

    function getPlayerId() {
	        return $this->{"playerId"};
	   }
	function getFirstName() {
		        return $this->{"firstName"};
    }

function getLastName() {
		        return $this->{"lastName"};
    }

function getTeamId() {
	        return $this->{"teamId"};
	   }

}
?>