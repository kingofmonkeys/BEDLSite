<?php
include "common.php";

$output = "";
$errors = array();
$output .= draw_head("Baltimore English Dart League", "User login");


if(isset($_SESSION['logged_in'])){
	header("Location: members.php");
}else{
	if(isset($_POST['Submit'])){
		$conn = getDBConnection($log);
		$log->LogDebug("Login page submitted with values username: ".$_POST['username']." Password: ".$_POST['password']);
		if($_POST['username']==null){
			$errors = setError("username","Username field is required.",$errors);
		}
		if($_POST['password']==null){
			$errors = setError("password","Password field is required.",$errors);
		}

		if(count($errors)>0){
			$log->LogDebug("Login page submit failed because something was null");

			$output .= displayErrors($errors);
			$output .= drawLoginForm($errors);

		}else{
			$result = mysql_query("SELECT * FROM users where username='".$_POST['username']."'");
			if(!$result){
				$log->LogError("Login for username ".$_POST['username']." failed because of something wrong with the database");
				header("Location: errorPage.php");
			}else{
				$row = mysql_fetch_array($result,MYSQL_BOTH);
  				if($row['password']==$_POST['password']){
					$log->LogDebug("Login for username ".$_POST['username']." successful");

  					//we need to set stuff in session here.  forward to a landing page.
  					$_SESSION['logged_in']=true;
  					$_SESSION['username']=$row['username'];
  					//need to get the user info here
  					$_SESSION['firstname']=$row['firstname'];
  					$_SESSION['lastname']=$row['lastname'];
  					$_SESSION['userid']=$row['ID'];

 					header("Location: members.php");
 				}else{
 					$log->LogDebug("Login for username ".$_POST['username']." failed to authenticate. ip: ".$_SERVER['REMOTE_ADDR']);
  					$errors = setError("username","Username/Password not correct.  Please try again.",$errors);
  					$output .= displayErrors($errors);
  					$output .= drawLoginForm($errors);

  				}
  			}
		}

	}else{
		$log->LogDebug("Login page accessed");
	 	$output .= drawLoginForm($errors);
	}
}



$output .= draw_foot();


echo $output;



function drawLoginForm($errors){
	$output ="";
	$username = "";

	if(isset($_POST['username'])){
		$username=$_POST['username'];
	}
	
	$heading = "User Login:"."\r\n";
		
	$body .= '<table cellspacing="0" cellpadding="0" width="100%"><form method="post" action="'.$_SERVER['PHP_SELF'].'" onsubmit="md5LoginPassword()">'."\r\n";
	$body .= '<tr><td width="15%" >';
	$body .= displayField('Username:</td><td > <input type="text" name="username" value="'.$username.'"/></td></tr>'."\r\n","username",$errors);
	$body .= '<tr><td>';
	$body .= displayField('Password: </td><td ><input type="password" name="password" id="password" value=""/></td></tr>'."\r\n","password",$errors);
	$body .= '<tr><td colspan="2" class="whitebg"><br><input type="submit" value="Submit" name="Submit"/> &nbsp&nbsp<a href="./index.php">Cancel</a></td></tr></table>'."\r\n";
	$body .= '</form>';

	$output .= drawContainer($heading,$body);
	
	return $output;
}
?>
