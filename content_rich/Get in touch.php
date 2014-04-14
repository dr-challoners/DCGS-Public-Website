<h1>Get in touch</h1>

<p>We would love to hear from you.</p>
<p>By keeping in touch with DCGS, former students are able to reconnect and maintain links with their peers, receive news of the school and upcoming events and hear about how our Campaigns will benefit future generations of students.</p>

<?php

	function fRequired($needed) {
		$error = 0;
		$i = 0;
		foreach ($needed as $value) {
			if((($_REQUEST["$needed[$i]"]) == "" || !isset($_REQUEST["$needed[$i]"]))) {
				if ($needed[$i] == 'birthdate') {
          $error = "<p class=\"error\">Sorry, your date of birth cannot be left blank.<br />Please insert it in the form DD/MM/YYYY.</p>";
					break;
					}
				elseif ($needed[$i] == 'email') {
					$error = "<p class=\"error\">Sorry, your e-mail address cannot be left blank.</p>";
					break;
					}
				else {
					$error = "<p class=\"error\">Sorry, your $needed[$i] cannot be left blank.</p>";
					break;
					}
				}
			$i++;
			}
		return $error;
		}

	$redirect = "./";
	$insert = array('forename', 'surname', 'birthdate', 'email', 'phone', 'address', 'attendance', 'activities');
	$required = array('forename', 'surname', 'birthdate', 'email');
		
	for ($i = 0; $i < count($insert); $i++) {
    if (isset($_REQUEST[$insert[$i]])) {
		  ${$insert[$i]} = $_REQUEST[$insert[$i]];
      }
		}

	//processing
	if (isset($_REQUEST['submit'])) {
		$error = fRequired($required);  //all required variables.. or its set to delete
		if ($error != '0') {
			echo $error;
			}
		else {
			$to = "simonburn.san@gmail.com"; //Only instance of e-mail address on this page
			$subject = "Message from an Old Challoner:".$forename." ".$surname;
			$message = "<p>Name: ".$forename." ".$surname."</p>";
			$message .= "\n<p>Date of birth: ".$birthdate."</p>";
			$message .= "\n<p>Contact e-mail: ".$email."</p>";
			if ($phone != '') {
				$message .= "\n<p>Contact phone number: ".$phone."</p>";
				}
			if ($address != '') {
				$message .= "\n<p>Postal address: ".$address."</p>";
				}
			if ($attendance != '') {
				$message .= "\n<p>Dates of attendance at DCGS: ".$attendance."</p>";
				}
			if ($activities != '') {
				$message .= "\n<p>Activities since leaving DCGS: ".$activities."</p>";
				}
								
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: <'.$email.'>' . "\r\n";
			
			$query = mail($to,$subject,$message,$headers);
			if ($query != 1) {
				echo "<p class=\"error\">Sorry, an error occurred. Please try sending your message again.</h3>";
				}
			else {
				echo "<h3>Thank you for contacting DCGS</h3>";
				echo "<p>This page will refresh in three seconds. Click <a href=\"$redirect\">here</a> if you don't want to wait.</p></div>";
				echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"4;URL=$redirect\">";
				
				echo "</div>";
				include('footer.php');
				
				exit();
				}
			}
		} ?>

<form name="contact" action="/rich/Information/Alumni/Get in touch" method="post">
	<p>Forename:</p>
  <input type="text" name="forename" value="<?php if (isset($forename)) { echo stripslashes(trim($forename)); } ?>" />
	<p class="require">Required</p>
	<p>Surname:</p>
  <input type="text" name="surname" value="<?php if (isset($surname)) { echo stripslashes(trim($surname)); } ?>" />
	<p class="require">Required</p>
	<p>Date of birth (DD/MM/YYYY):</p>
  <input type="text" name="birthdate" value="<?php if (isset($birthdate)) { echo stripslashes(trim($birthdate)); } ?>" />
	<p class="require">Required</p>
	<p>E-mail address:</p>
  <input type="text" name="email" value="<?php if (isset($email)) { echo stripslashes(trim($email)); } ?>" />
	<p class="require">Required</p>
	<p>Telephone number:</p>
  <input type="text" name="phone" value="<?php if (isset($phone)) { echo stripslashes(trim($phone)); } ?>" />
	<p class="require">&nbsp;</p>
	<p>Postal address:</p>
  <textarea name="address"><?php if (isset($address)) { echo stripslashes(trim($address)); } ?></textarea>
	<p class="require">&nbsp;</p>
	<p>Dates of attendance at DCGS:</p>
  <input type="text" name="attendance" value="<?php if (isset($attendance)) { echo stripslashes(trim($attendance)); } ?>" />
	<p class="require">&nbsp;</p>
	<p>Activities since leaving DCGS:</p>
  <textarea name="activities"><?php if (isset($activities)) { echo stripslashes(trim($activities)); } ?></textarea>
	<p class="require">&nbsp;</p>
	
	<input class="submit" type="submit" name="submit" value="Send message">
</form>