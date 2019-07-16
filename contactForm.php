<?php
include "common.php";

$output ="";

$image = new Securimage();

$output .= draw_head("Baltimore English Dart League", "Baltimore English Dart League Contacts");

if(isset($_POST['Submit'])){
	$errors = array();	
	
	if(isset($_POST['message'])){
		$contains_cyrillic = (bool) preg_match('/[А-Яа-яЁё]/u', $_POST['message']);
		if ($contains_cyrillic) {
			$ipfile=fopen("contactip.txt","a");
			$ipoutput ="\n";
			$ipoutput .=date ("F jS, Y");
			$ipoutput .=", " . $_POST['first_name'] . " " . $_POST['last_name'];
			$ipoutput .=", " . $_SERVER['REMOTE_ADDR'];
			$ipoutput .=", message text contains Cyrillic, assuming bot e-mail not sent";
			fwrite($ipfile,$ipoutput);
			fclose($ipfile);
			$errors = setError("general","There was an error in your request.",$errors);		
		}else if ((strpos($_POST['message'], 'http://') !== FALSE) || (strpos($_POST['message'], 'https://') !== FALSE) || (strpos($_POST['message'], 'www.') !== FALSE) || (strpos($_POST['message'], '.com') !== FALSE))
		{
			$errors = setError("message","Links are not allowed.",$errors);				
		}	

	}		
	
	if(isset($_POST['rating'])&&""!=$_POST['rating']){
		$ipfile=fopen("contactip.txt","a");
		$ipoutput ="\n";
		$ipoutput .=date ("F jS, Y");
		$ipoutput .=", " . $_POST['first_name'] . " " . $_POST['last_name'];
		$ipoutput .=", " . $_SERVER['REMOTE_ADDR'];
		$ipoutput .=", rating field was set, assuming bot e-mail not sent";
		fwrite($ipfile,$ipoutput);
		fclose($ipfile);
		$errors = setError("general","There was an error in your request.",$errors);		
	}

	if(!isset($_POST['first_name'])||$_POST['first_name']==null){
		$errors = setError("first_name","First name field is required.",$errors);
	}
	if(!isset($_POST['email'])||$_POST['email']==null){
		$errors = setError("email","E-mail field is required",$errors);
	}else{
		if (!check_email_address($_POST['email'])) {
			$errors = setError("email","E-mail does not appear to be valid",$errors);
		}
	}
		

	if ($image->check($_POST['captcha']) == false) {
		$ipfile=fopen("contactip.txt","a");
		$ipoutput ="\n";
		$ipoutput .=date ("F jS, Y");
		$ipoutput .=", " . $_POST['first_name'] . " " . $_POST['last_name']. ", " .  $_POST['email'];
		$ipoutput .=", " . $_SERVER['REMOTE_ADDR'];
		$ipoutput .=", captcha field was wrong, assuming bot e-mail not sent";
		fwrite($ipfile,$ipoutput);
		fclose($ipfile);		
		
		$errors = setError("captcha","The CAPTCHA was wrong",$errors);
	}
	$bannedfilename="bannedips.txt";
	$bannedfile=fopen($bannedfilename,"r");
	$isbanned = "false";

	$banned_raw = fread($bannedfile, filesize($bannedfilename));
	fclose($bannedfile);
	$banned = explode("\n", $banned_raw);
	$num_banned = count($banned);
	for ($i = 0; $i < $num_banned; $i++) {
		if($banned[$i]==$_SERVER['REMOTE_ADDR']){
	 		$isbanned="true";
		}
	}

	if($isbanned=="true"){
		$ipfile=fopen("contactip.txt","a");
		$ipoutput ="\n";
		$ipoutput .=date ("F jS, Y");
		$ipoutput .=", " . $_POST['first_name'] . " " . $_POST['last_name']. ", " .  $_POST['email'];
		$ipoutput .=", " . $_SERVER['REMOTE_ADDR'];
		$ipoutput .=", IP BANNED e-mail not sent";
		fwrite($ipfile,$ipoutput);
		fclose($ipfile);
		$errors = setError("general","Your are banned from posting this form. Contact the site admin to get your ban removed.",$errors);
	}

	if(count($errors)>0){
		$output .= displayErrors($errors);
		$output .= drawContactForm($errors);
	}else{
		$ipfile=fopen("contactip.txt","a");
		$ipoutput ="\n";
		$ipoutput .=date ("F jS, Y");
		$ipoutput .=", " . $_POST['first_name']." ".$_POST['last_name'];
		$ipoutput .=", " . $_SERVER['REMOTE_ADDR'];
		$output .= '<table cellpadding="0" cellspacing="0" width="100%"><tr><td class="contentarea">';

		//$to = "jimmy@kingofmonkeys.com, bedlsublist@gmail.com";
		$to = "jimmy@kingofmonkeys.com";
		$subject = "Someone sent a message to the Baltimore English Dart League";
		$body = "Subject: ".$_POST['subject'];
		$body.= "\nName: ".$_POST['first_name']." ".$_POST['last_name'];
		$body.= "\nE-mail: ".$_POST['email'];
		$body.= "\nPhone Number: ".$_POST['phone'];
		$body.= "\n\nWant To Join: ";
		if(isset($_POST['wanttojoin'])){
			$body.="Yes";
		}else{
			$body.="No";
		}
		$body.= "\nEmail Me League Updates: ";
		if(isset($_POST['emailupdates'])){
			$body.="Yes";
		}else{
			$body.="No";
		}
		$body.= "\n\nMessage: ".$_POST['message'];
		$body.="\n\nIP: ".$_SERVER['REMOTE_ADDR'];

		if(sendEmail($to, $subject, $body)=="true"){
			$output .= 'Your message has been sent. <a href="./index.php">Click here</a> to go back to the home page. ';
		}else{
			$output .= 'There was an error sending your message.  Please try again later or contact us using the information on the <a href="./contact.php">contact us</a> page. <a href="./index.php">Click here</a> to go back to the home page. ';
		}
		$output .= '</td></tr></table>';
	}

}else{
	$errors = array();
	$output .= drawContactForm($errors);
}


$output .= draw_foot();


echo $output;



function drawContactForm($errors){
	$output = "";
	$firstname ="";
	$lastname ="";
	$email ="";
	$phone ="";
	$subject="";
	$message ="";
	$rating ="";

	if(isset($_POST['first_name'])){
		$firstname =$_POST['first_name'];
	}
	if(isset($_POST['last_name'])){
		$lastname =$_POST['last_name'];
	}
	if(isset($_POST['email'])){
		$email =$_POST['email'];
	}
	if(isset($_POST['phone'])){
		$phone =$_POST['phone'];
	}
	if(isset($_POST['subject'])){
		$subject =$_POST['subject'];
	}
	if(isset($_POST['message'])){
		$message =$_POST['message'];
	}
	if(isset($_POST['rating'])){
		$rating =$_POST['rating'];
	}
	
	$heading = 'Contact the league with questions/comments or to ask about joining:';

	$body .= '<form method="post" action="'.$_SERVER['PHP_SELF'].'">';
	$body .= '<div id="ratingTextBox">If you see this leave it empty: <input type="textbox" name="rating" value="'.$rating.'" /></div>';
	
	$body .= '<table cellspacing="0" cellpadding="0" width="100%">';
	$body .= '<tr><td width="15%" class="contentarea" >';
	$body .= displayField('First Name:</td><td class="contentarea"> <input type="text" name="first_name" value="'.$firstname.'"/></td></tr>',"first_name",$errors);
	$body .= '<tr><td class="contentarea">';
	$body .= displayField('Last Name: </td><td class="contentarea"><input type="text" name="last_name" value="'.$lastname.'"/></td></tr>','last_name',$errors);
	$body .= '<tr><td class="contentarea">';
	$body .= displayField('E-Mail: </td><td class="contentarea"><input type="text" name="email" value="'.$email.'"/></td></tr>',"email",$errors);
	$body .= '<tr><td class="contentarea">';
	$body .= displayField('Phone Number: </td><td class="contentarea"><input type="text" name="phone" value="'.$phone.'"/></td></tr>',"phone",$errors);
	$body .= '<tr><td class="contentarea">';
	$body .= displayField('Subject: </td><td class="contentarea"><input type="text" name="subject" value="'.$subject.'"/></td></tr>',"subject",$errors);
	$body .= '<tr><td class="contentarea">';
	$body .= displayField('Message: </td><td class="contentarea"><textarea name="message" width="500" height="100">'.$message.'</textarea></td></tr>',"message",$errors);

	if(isset($_POST['wanttojoin'])){
		$body .= '<tr><td colspan="2" class="contentarea"><input type="checkbox" name="wanttojoin" checked="true"/> I am interested in joining the league<br>';
	}else{
		$body .= '<tr><td colspan="2" class="contentarea"><input type="checkbox" name="wanttojoin" /> I am interested in joining the league<br>';
	}
	if(isset($_POST['emailupdates'])){
		$body .= '<input type="checkbox" name="emailupdates" checked="true"/> Please E-mail me league updates</td></tr>';
	}else{
		$body .= '<input type="checkbox" name="emailupdates"/> Please E-mail me league updates</td></tr>';
	}

	$body .= '<tr><td colspan="2" class="contentarea"></td></tr>';

	$body .= '<tr><td colspan="2" class="contentarea"><img id="captcha" src="./secureimage/securimage_show.php" alt="CAPTCHA Image" /><br>';
	$body .= '<a href="#" onclick="document.getElementById(\'captcha\').src = \'./secureimage/securimage_show.php?\' + Math.random(); return false">Get another image</a>';
	$body .= '<br>';
	$body .= displayField('Type the letters above: <input type="text" name="captcha"/>',"captcha",$errors);
	$body .= '<br><input type="submit" value="Submit" name="Submit"/>&nbsp&nbsp<a href="./index.php">Cancel</a></td></tr></table>';
	$body .= '</form>';

	$output = drawContainer($heading,$body)."\r\n";		
	
	return $output;
}



?>


