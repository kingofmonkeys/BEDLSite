<?php


class FormError {
    // property declaration
    var $fieldname;
	var $fielderror;

function FormError(){
}

    // method declaration
    function setfieldname($fieldnameset) {
        $this->{"fieldname"}=$fieldnameset;
    }
    function setfielderror($fielderrorset) {
	        $this->{"fielderror"}=$fielderrorset;
    }
    function getFieldName() {
	        return $this->{"fieldname"};
	   }
	function getFieldError() {
		        return $this->{"fielderror"};
    }


}
?>