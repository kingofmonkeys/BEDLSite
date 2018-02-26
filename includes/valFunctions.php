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


?>