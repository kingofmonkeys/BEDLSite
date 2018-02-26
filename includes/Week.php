<?php


class Week {
    // property declaration
    var $weekNumber;
	var $weekDate;

function Week(){
}

    // method declaration
    function setWeekNumber($weekNumberset) {
        $this->{"weekNumber"}=$weekNumberset;
    }
    function setWeekDate($weekDateset) {
	        $this->{"weekDate"}=$weekDateset;
    }
    function getWeekNumber() {
	        return $this->{"weekNumber"};
	   }
	function getWeekDate() {
		        return $this->{"weekDate"};
    }


}
?>