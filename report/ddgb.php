<?php

// Dagon Design Guestbook Script v2.45
// http://www.dagondesign.com
// Visit site for information, help, and new versions



// Load settings

require ('config.php');





// Check for GD support 

function check_gd_support() {

	if (extension_loaded("gd") && (function_exists("imagegif") || function_exists("imagepng") || function_exists("imagejpeg"))) {
		return TRUE;
	} else {
		return FALSE;
	}

}



// Start of main script processing



// Method for CSS inclusion depends on stand-alone setting

if ($standalone) {
	include ('header.php');
} else {
	// Edited by Hans Nordhaug 19.11.2007:
	// illegal placement. style element must be in the head section
	/*
	echo "<style type=\"text/css\">\n<!-- ";
	include ($css_file);
	echo "-->\n</style>";
	*/
}


// check for GD support
// if not supported, give error if not disabled

if (!check_gd_support()) {
	if (!$disable_verification) {
		echo "<p><b>" . _INVALID_GD . "</b></p>";
	}
}


// initialize variables

$the_line = null;
$editing = false;



// Show smiles js

function show_smile_js() {

	// Edited by Hans Nordhaug 19.11.2007:
	// Type must be set. Using document.getElementById to find the element.
	return '
	<script type="text/javascript">
	function smiley(smile) {
		var message = document.getElementById(\'fm_message\');
		if (message) {
			message.value = message.value + " " + smile;
		}
	}
	</script>';

}




// Show smiles preview

function show_smiles_preview() {

	// Edited by Hans Nordhaug 19.11.2007: 
        // - Added alt text
        // - Added "return false;" to onclick to avoid jumping up when clicking the link.
	echo '

  <tr>
    <td width="35%" align="right" valign="top">&nbsp;</td>
    <td align="left" valign="top">
		<div class="ddgb_smiles">
			<table>
			<tr>
			<td width="90"><a class=\'emotionlink\' href=\'#\' onclick="smiley(\':)\'); return false;"><img src="smiles/smile.gif" alt=":)" title="Smile" /></a> :) </td>
			<td width="90"><a class=\'emotionlink\' href=\'#\' onclick="smiley(\':D\'); return false;"><img src="smiles/bigsmile.gif" alt=":D" title="Big smile" /></a> :D </td>
			<td width="90"><a class=\'emotionlink\' href=\'#\' onclick="smiley(\':(\'); return false;"><img src="smiles/sad.gif" alt=":(" title="Sad" /></a> :( </td>
			</tr>
			<tr>
			<td width="90"><a class=\'emotionlink\' href=\'#\' onclick="smiley(\':O\'); return false;"><img src="smiles/suprised.gif" alt=":O" title="Suprised" /></a> :O </td>
			<td width="90"><a class=\'emotionlink\' href=\'#\' onclick="smiley(\':x\'); return false;"><img src="smiles/mad.gif" alt=":x" title="Mad" /></a> :x </td> 
			<td width="90"><a class=\'emotionlink\' href=\'#\' onclick="smiley(\';)\'); return false;"><img src="smiles/wink.gif" alt=";)" title="Wink" /></a> ;) </td>
			</tr>
			<tr>
			<td width="90"><a class=\'emotionlink\' href=\'#\' onclick="smiley(\':lol:\'); return false;"><img src="smiles/laughing.gif" alt=":lol:" title="Laughing" /></a> :lol: </td> 
			<td width="90"><a class=\'emotionlink\' href=\'#\' onclick="smiley(\':eek:\'); return false;"><img src="smiles/shocked.gif" alt=":eek:" title="Shocked" /></a>	:eek: </td>
			<td width="90"><a class=\'emotionlink\' href=\'#\' onclick="smiley(\':oops:\'); return false;"><img src="smiles/embarrassed.gif" alt=":oops:" title="Embarrassed" /></a>	:oops: </td>
			</tr>
			</table>
		</div>
	</td>
  </tr>

	';

}


// Process smiles

function process_smiles($text) {

	// Edited by Hans Nordhaug 19.11.2007: Added alt text
	$text = str_replace(':)', 		'<img src="smiles/smile.gif" alt=":)" title="Smile" />', $text);
	$text = str_replace(':D', 		'<img src="smiles/bigsmile.gif" alt=":D" title="Big smile" />', $text);
	$text = str_replace(':(', 		'<img src="smiles/sad.gif" alt=":(" title="Sad" />', $text);
	$text = str_replace(':O', 		'<img src="smiles/suprised.gif" alt=":O" title="Suprised" />', $text);
	$text = str_replace(':x', 		'<img src="smiles/mad.gif" alt=":x" title="Mad" />', $text);
	$text = str_replace(';)', 		'<img src="smiles/wink.gif" alt=";)" title="Wink" />', $text);
	$text = str_replace(':lol:', 	'<img src="smiles/laughing.gif" alt=":lol:" title="Laughing" />', $text);
	$text = str_replace(':eek:', 	'<img src="smiles/shocked.gif" alt=":eek:" title="Shocked" />', $text);
	$text = str_replace(':oops:', 	'<img src="smiles/embarrassed.gif" alt=":ooops:" title="Embarrassed" />', $text);

	return $text;

}



// For those who did not update the config when this option was added

if (!isset($enable_file_locking )) {
	$enable_file_locking = FALSE;
}	


// Handle file locking

function ddfm_flock($handle, $param) {

	global $enable_file_locking;

	if ($enable_file_locking == TRUE) {
		return flock($handle, $param);	
	} else {
		return TRUE;
	}

}





// check session for login status

$logged_in = false;
if (isset($_SESSION["pass"])) {
	if ($_SESSION["pass"] == $admin_password) {
		$logged_in = true;
	}
}

// get current action
$action = "";
if (isset($_GET['action'])) {
	switch ($_GET['action']) {
	case 'entry':
	   $action = "entry";
	   break;
	case 'admin':
	   $action = "admin";
	   break;
	case 'logout':
	   $action = "logout";
	   break;
	case 'edit':
	   $action = "edit";
	   break;
	case 'delete':
	   $action = "delete";
	   break;	
	case 'approve':
	   $action = "approve";
	   break;	
	case 'ban':
	   $action = "ban";
	   break;
	}
}

// get current page
$page = 1;
if (isset($_GET['page'])) {
	if (is_numeric($_GET['page'])) {
		$page = $_GET['page'];
	}
}

?>

<div align="center">
<div class="ddgb_wrapper">

<?php

if ($show_intro_text) {
	echo "<p>" . _INTRO . "</p>";
}




if ($action == "logout") {
	$_SESSION["pass"] = null;
	unset($_SESSION);
	$logged_in = false;
	$action = "";
}


if (isset($_POST["fm_admin_submit"])) {
// admin login form was submitted

	$fm_password = trim($_POST['fm_password']);
	$fm_verify = trim($_POST['fm_verify']);

	// if magic quotes are on, strip slashes
	if (get_magic_quotes_gpc()) {
		$fm_password = stripslashes($fm_password);
		$fm_verify = stripslashes($fm_verify);
	}

	// prepare error list
	unset($errors);

	if ($fm_password != $admin_password) {
		$errors[] = _INVALID_PASS;
	}


	if (!$disable_verification) {

		if ($fm_verify == "") {
			$errors[] = _ENTER_CODE;
		} else if ($_SESSION["ddgbcode"] == "") {
			$errors[] = _NO_CODE;
		} else if ($_SESSION["ddgbcode"] != strtoupper($fm_verify)) {
			$errors[] = _INVALID_CODE;
		}
	}


	if (empty($errors)) {
		// logged in

		// setup session

		$_SESSION["pass"] = $admin_password;

		$logged_in = true;


	} else {
		// not logged in

		$logged_in = false;

		?>
		<div class="ddgb_entrybox">
		<table width="100%" border="0" cellspacing="8" cellpadding="0">
		<tr>
	    <td width="42%" align="right" valign="top"></td>
		<td align="left" valign="top">

		<?php

	    echo "<h2>" . _ERROR . "</h2><ul>";
		foreach ($errors as $f) {
			echo "<li>" . $f . "</li>";
		}
		echo "</ul>";

		?>
		</td>
		</tr>
		</table>
		</div>
		<?php

		// show admin login again
		$action = "admin";
	}

}



if ($action == "approve") {
// approve post


	if ($logged_in) {
	// make sure user is logged in

		// get time of selected entry
		if (isset($_GET['item'])) {
			if (is_numeric($_GET['item'])) {
				$item = $_GET['item'];
			}
		}

		$entries = file_get_contents($data_file);
		$entries = (array)explode('###', $entries);

		// recreate file
		$handle = fopen($data_file, "w");

		if (ddfm_flock($handle, LOCK_EX)) { // do an exclusive lock

			foreach ($entries as $entry) {
					
				$data_t = explode("\r\n", trim($entry));

				if (trim($data_t[0]) != "") {

					if ($data_t[0] != $item) {
						foreach ($data_t as $d) {
								fwrite($handle, $d . "\r\n");
						}
						fwrite($handle, "###\r\n");
					} else {

						$data = array();
						foreach ($data_t as $dt) {		
						if (strpos($dt, '=') != FALSE) {		
								$k = substr($dt, 0, strpos($dt, '='));
								$v = substr($dt, strpos($dt, '=') + 1, strlen($dt) - strpos($dt, '='));	
								$data[$k] = $v;
							}
						}
						$data['timestamp'] = $data_t[0];
						$data['approved'] = $data_t[1];

						$the_string = "";
						$the_string .= $data['timestamp'] . "\r\n";
						$the_string .= "TRUE\r\n";
						$the_string .= "ip=" . $data['ip'] . "\r\n";
						$the_string .= "name=" . $data['name'] . "\r\n";
						$the_string .= "website=" . $data['website'] . "\r\n";
						$the_string .= "email=" . $data['email'] . "\r\n";
						$the_string .= "location=" . $data['location'] . "\r\n";
						$the_string .= "message=" . $data['message'] . "\r\n";
						$the_string .= "###\r\n";
	
						fwrite($handle, $the_string);

					}

				}
			}	

			ddfm_flock($handle, LOCK_UN); // release the lock

			echo '<p>' . _UPDATED . '</p>';
			echo '<p><a href="' . basename($_SERVER["PHP_SELF"]) . '">' . _BACK . '</a></p>';
			$action = "nothing";

		} else {
		   echo _ERROR_NO_LOCK;
		}

		fclose($handle);

		unset($_SESSION['item']);

	} else {
	
		echo '<p>' . _NO_EDIT_NO_LOGGED . '</p>';
		echo '<p><a href="' . basename($_SERVER["PHP_SELF"]) . '">' . _BACK . '</a></p>';

	}

}




if (isset($_POST["fm_submit"])) {
// form was submitted

	$editing = false;
	if (isset($_SESSION['item'])) {
		$editing = true;
		$item = $_SESSION['item'];
	}


	if (!$disable_verification) {
		$fm_verify = trim($_POST['fm_verify']);
	}
	$fm_verify2 = trim($_POST['fm_verify2']);
	$fm_name = trim($_POST['fm_name']);
	$fm_website = trim($_POST['fm_website']);
	$fm_email = trim($_POST['fm_email']);
	$fm_location = trim($_POST['fm_location']);
	$fm_message = trim($_POST['fm_message']);

	// if magic quotes are on, strip slashes
	if (get_magic_quotes_gpc()) {
		$fm_name = stripslashes($fm_name);
		$fm_website = stripslashes($fm_website);
		$fm_email = stripslashes($fm_email);
		$fm_location = stripslashes($fm_location);
		if (!$disable_verification) {
			$fm_verify = stripslashes($fm_verify);
		}
		$fm_message = stripslashes($fm_message);
	}

	// prepare error list
	unset($errors);

	// check for errors
	if ($fm_name == "") {
		$errors[] = _ERROR_NAME;
	}
	if (strlen($fm_name) > 40) {
		$errors[] = _ERROR_MAX_40;
	}
	if ($fm_message == "") {
		$errors[] = _ERROR_MESSAGE;
	}


	if (!$logged_in) {

		if (!$disable_verification) {

			if ($fm_verify == "") {
				$errors[] = _ENTER_CODE;
			} else if ($_SESSION["ddgbcode"] == "") {
				$errors[] = _NO_CODE;
			} else if ($_SESSION["ddgbcode"] != strtoupper($fm_verify)) {
				$errors[] = _INVALID_CODE;
			}
		}
	}
	// Added by Hans Nordhaug 19.11.2007:
        if ($fm_verify2 != "") {
		$errors[] = _LEAVE_EMPTY;
        }

	if ($fm_email != "" && !preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $fm_email)) {
		$errors[] = _INVALID_EMAIL;
	}


	// check for banned IP
	$ban_data = file_get_contents($ban_file);
	if (false !== strpos($ban_data, (string)$_SERVER["REMOTE_ADDR"])) {
	    $errors[] = _BANNED;
	}

	// if no errors, add entry
	if (empty($errors)) {

		// replace newlines with breaks
		$fm_message = str_replace("\r\n", "<br />", trim($fm_message));

		if ($editing) {
			// editing entry

			// get file contents
			$entries = file_get_contents($data_file);
			$entries = (array)explode('###', $entries);

			// recreate file
			$handle = fopen($data_file, "w");

			if (ddfm_flock($handle, LOCK_EX)) { // do an exclusive lock

				foreach ($entries as $entry) {
					
					$data_t = explode("\r\n", trim($entry));

			if (trim($data_t[0]) != "") {

					if ($data_t[0] != $item) {
						foreach ($data_t as $d) {
							fwrite($handle, $d . "\r\n");
						}
						fwrite($handle, "###\r\n");
					} else {

						$data = array();
						foreach ($data_t as $dt) {		
							if (strpos($dt, '=') != FALSE) {		
								$k = substr($dt, 0, strpos($dt, '='));
								$v = substr($dt, strpos($dt, '=') + 1, strlen($dt) - strpos($dt, '='));	
								$data[$k] = $v;
							}
						}
						$data['timestamp'] = $data_t[0];
						$data['approved'] = $data_t[1];

						$the_string = "";
						$the_string .= $data['timestamp'] . "\r\n";
						$the_string .= "TRUE\r\n";
						$the_string .= "ip=" . $data['ip'] . "\r\n";
						$the_string .= "name=" . $fm_name . "\r\n";
						$the_string .= "website=" . $fm_website . "\r\n";
						$the_string .= "email=" . $fm_email . "\r\n";
						$the_string .= "location=" . $fm_location . "\r\n";
						$the_string .= "message=" . $fm_message . "\r\n";
						$the_string .= "###\r\n";

						fwrite($handle, $the_string);

					}

				}
			}	

				ddfm_flock($handle, LOCK_UN); // release the lock

				echo '<p>' . _UPDATED . '</p>';
				echo '<p><a href="' . basename($_SERVER["PHP_SELF"]) . '">' . _BACK . '</a></p>';
				$action = "nothing";

			} else {
			   echo _ERROR_NO_LOCK;
			}

			fclose($handle);

			unset($_SESSION['item']);

		} else {
			// new entry

			// get file contents
			$old_entries = file_get_contents($data_file);

			// recreate file
			$handle = fopen($data_file, "w");

			if (ddfm_flock($handle, LOCK_EX)) { // do an exclusive lock

				// add in new entry

				$the_string = "";
				$the_string .= time() . "\r\n";
				if ($require_approval && !$logged_in) {
					$the_string .= "FALSE\r\n";
				} else {
					$the_string .= "TRUE\r\n";
				}
				$the_string .= "ip=" . $_SERVER["REMOTE_ADDR"] . "\r\n";
				$the_string .= "name=" . $fm_name . "\r\n";
				$the_string .= "website=" . $fm_website . "\r\n";
				$the_string .= "email=" . $fm_email . "\r\n";
				$the_string .= "location=" . $fm_location . "\r\n";
				$the_string .= "message=" . $fm_message . "\r\n";
				$the_string .= "###\r\n";

				fwrite($handle, $the_string);

				// add rest of entries back in

				fwrite($handle, $old_entries);

				ddfm_flock($handle, LOCK_UN); // release the lock


				echo '<p>' . _ADDED_THX . '</p>';
				echo '<p><a href="' . basename($_SERVER["PHP_SELF"]) . '">' . _BACK . '</a></p>';
				// Added by Hans Nordhaug 19.11.2007 - admin e-mail and notifications.
				if ($send_notifications) {
					mail($admin_email, 'New Guestbook Entry', 'A New Guestbook Entry was added by ' . $fm_name);
				}
				$action = "nothing";

			} else {
			   echo _ERROR_NO_LOCK;
			}

			fclose($handle);

			unset($_SESSION['item']);

		}

	} else { // there are errors

		$action = "reentry"; // show form again

		?>
		<div class="ddgb_entrybox">
		<table width="100%" border="0" cellspacing="8" cellpadding="0">
		<tr>
	    <td width="35%" align="right" valign="top"></td>
		<td align="left" valign="top">

		<?php

	    echo "<h2>" . _ERROR . "</h2><ul>";
		foreach ($errors as $f) {
			echo "<li>" . $f . "</li>";
		}
		echo "</ul>";

		?>
		</td>
		</tr>
		</table>
		</div>
		<?php

	}

}

?>

<?php

if ($action == "edit") {
// edit post

	if ($logged_in) {
	// make sure user is logged in


		$editing = true;
		$action = "entry";

		// get time of selected entry
		if (isset($_GET['item'])) {
			if (is_numeric($_GET['item'])) {
				$item = $_GET['item'];
			}
		}

		$_SESSION['item'] = $item;

	} else {

		echo '<p>' . _NO_EDIT_NO_LOGGED . '</p>';
		echo '<p><a href="' . basename($_SERVER["PHP_SELF"]) . '">' . _BACK . '</a></p>';

	}

}


if ($action == "entry") {

	// if editing an existing entry

	if ($editing) {
		if ($action != "reentry")  {

			$entries = file_get_contents($data_file);
			$entries = (array)explode('###', $entries);

			foreach ($entries as $entry) {
				$data_t = explode("\r\n", trim($entry));

				if ($data_t[0] == $_SESSION['item']) {

					$data = array();
					foreach ($data_t as $dt) {		
						if (strpos($dt, '=') != FALSE) {		
							$k = substr($dt, 0, strpos($dt, '='));
							$v = substr($dt, strpos($dt, '=') + 1, strlen($dt) - strpos($dt, '='));	
							$data[$k] = $v;
						}
					}
					$data['timestamp'] = $data_t[0];
					$data['approved'] = $data_t[1];

					$fm_name = $data['name'];
					$fm_website = $data['website'];
					$fm_email = $data['email'];
					$fm_location = $data['location'];
					$fm_message = $data['message'];

				}
			}


		} 
	}


}


if (($action == "entry") || ($action == "reentry")) {

?>






<?php
		// Generate verification code
		srand((double)microtime()*1000000); 
		$ddgbcode = substr(strtoupper(md5(rand(0, 999999999))), 2, 5); 
		$ddgbcode = str_replace("O", "A", $ddgbcode);
		$ddgbcode = str_replace("0", "B", $ddgbcode);
		$_SESSION["ddgbcode"] = $ddgbcode;

?>



<?php echo show_smile_js(); ?>

<form method="post" name="entrybox" action="<?php echo basename($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
<div class="ddgb_entrybox">
<table width="100%" border="0" cellspacing="8" cellpadding="0">
  <tr>
    <td width="35%" align="right" valign="top"></td>
    <td align="left" valign="top">

	<?php
		if ($editing) {
			echo '<h2>' . _EDIT . '</h2>';
		} else {
			echo '<h2>' . _ADD . '</h2>';

			
			if (!$logged_in && $require_approval) {

				echo '<p>' . _MODNOTICE . '</p>';

			}

		}
	?>

</td>
  </tr>
  <tr>
    <td width="35%" align="right" valign="top"> <?php echo _NAME ?></td>
    <td align="left" valign="top"><input name="fm_name" type="text" size="37" value="<?php if (isset($fm_name)) echo htmlspecialchars($fm_name); ?>"/></td>
  </tr>
  <tr>
    <td width="35%" align="right" valign="top"><?php echo _WEBSITE ?></td>
    <td align="left" valign="top"><input name="fm_website" type="text" size="37" value="<?php if (isset($fm_website)) echo htmlspecialchars($fm_website); ?>"/></td>
  </tr>
  <tr>
    <td width="35%" align="right" valign="top"><?php echo _EMAIL ?></td>
    <td align="left" valign="top"><input name="fm_email" type="text" size="37" value="<?php if (isset($fm_email)) echo htmlspecialchars($fm_email); ?>"/></td>
  </tr>
  <tr>
    <td width="35%" align="right" valign="top"><?php echo _LOCATION ?></td>
    <td align="left" valign="top"><input name="fm_location" type="text" size="37" value="<?php if (isset($fm_location)) echo htmlspecialchars($fm_location); ?>"/></td>
  </tr>

	<?php if (!$logged_in) { ?>

	<?php if (!$disable_verification) { ?>


	  <tr>
	    <td width="35%" align="right" valign="top"><?php echo _VERIFY ?></td>
	    <td align="left" valign="top"><input style="width: 60px;" type="text" name="fm_verify" /> &nbsp; 
			<img style="vertical-align:bottom; border: 1px solid #005ABE;" src="<?php echo $verify_path; ?>" alt="" width="60" height="20" />
		</td>
	  </tr>

	<?php } ?>

	<?php } ?>

  <!-- Added by Hans Nordhaug 19.11.2007 -->
  <tr style="display: none">
    <td width="35%" align="right" valign="top"><?php echo _LEAVE_EMPTY ?></td>
    <td align="left" valign="top"><input name="fm_verify2" type="text" size="37" value=""/></td>
  </tr>
  <tr>
    <td width="35%" align="right" valign="top"><?php echo _COMMENT ?></td>
    <td align="left" valign="top"><textarea id="fm_message" name="fm_message" cols="28" rows="6"><?php if (isset($fm_message)) echo htmlspecialchars($fm_message); ?></textarea></td>
  </tr>

	<?php
	if ($enable_smiles) {
		show_smiles_preview();
	}
	?>

  <tr>
    <td width="35%" align="right" valign="top">&nbsp;</td>
    <td align="left" valign="top"><input type="submit" name="fm_submit" value="<?php echo _SEND ?>" /></td>
  </tr>
</table>
</div>
</form>



<p><a href="<?php echo basename($_SERVER["PHP_SELF"]); ?>"><?php echo _BACK ?></a></p>

<?php

} elseif ($action == "admin") { // admin panel



?>



<?
		// Generate verification code
		srand((double)microtime()*1000000); 
		$ddgbcode = substr(strtoupper(md5(rand(0, 999999999))), 2, 5); 
		$ddgbcode = str_replace("O", "A", $ddgbcode);
		$ddgbcode = str_replace("0", "B", $ddgbcode);
		$_SESSION["ddgbcode"] = $ddgbcode;

?>

<?php echo show_smile_js(); ?>

<form method="post" name="entrybox" action="<?php echo basename($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
<div class="ddgb_entrybox">
<table width="100%" border="0" cellspacing="8" cellpadding="0">
  <tr>
    <td width="42%" align="right" valign="top"></td>
    <td align="left" valign="top"><h1><?php echo _ADMIN_LOGIN ?></h1></td>
  </tr>
  <tr>
    <td width="42%" align="right" valign="top"> <?php echo _PASS ?></td>
    <td align="left" valign="top"><input name="fm_password" type="password" size="20" /></td>
  </tr>

	<?php if (!$disable_verification) { ?>

	  <tr>
	    <td width="42%" align="right" valign="top"><?php echo _VERIFY ?></td>
	    <td align="left" valign="top"><input style="width: 60px;" type="text" name="fm_verify" /> &nbsp; <img style="vertical-align:bottom; border: 1px solid #005ABE;" src="<?php echo $verify_path; ?>" width="60" height="20" /></td>
	  </tr>
	
	<?php } ?>

  <tr>
    <td width="42%" align="right" valign="top">&nbsp;</td>
    <td align="left" valign="top"><input type="submit" name="fm_admin_submit" value="<?php echo _LOGIN ?>" /></td>
  </tr>
</table>
</div>
</form>

<p><a href="<?php echo basename($_SERVER["PHP_SELF"]); ?>"><?php echo _BACK ?></a></p>

<?php



} elseif ($action == "delete") { // delete post


	if ($logged_in) {
	// make sure user is logged in


		// get time of selected entry
		if (isset($_GET['item'])) {
			if (is_numeric($_GET['item'])) {
				$item = $_GET['item'];
			}
		}


		// get file contents
		$entries = file_get_contents($data_file);
		$entries = (array)explode('###', $entries);

		// recreate file
		$handle = fopen($data_file, "w");

		if (ddfm_flock($handle, LOCK_EX)) { // do an exclusive lock

			foreach ($entries as $entry) {
					
				$data_t = explode("\r\n", trim($entry));

		if (trim($data_t[0]) != "") {

				if ($data_t[0] != $item) {
					foreach ($data_t as $d) {
						fwrite($handle, $d . "\r\n");
					}
					fwrite($handle, "###\r\n");
				} else {

					// skip item to be deleted

				}

			}
		}	

			ddfm_flock($handle, LOCK_UN); // release the lock

			echo '<p>' . _DELETED . '</p>';
			echo '<p><a href="' . basename($_SERVER["PHP_SELF"]) . '">' . _BACK . '</a></p>';
			$action = "nothing";

		} else {
		   echo _ERROR_NO_LOCK;
		}

		fclose($handle);

		unset($_SESSION['item']);



	} else {

		echo '<p>' . _NO_DEL_NO_LOGGED . '</p>';
		echo '<p><a href="' . basename($_SERVER["PHP_SELF"]) . '">' . _BACK . '</a></p>';

	}

} elseif ($action == "ban") { // ban IP and delete the posts that go with it


	if ($logged_in) {
	// make sure user is logged in


		// get time of selected entry
		if (isset($_GET['item'])) {
			if (is_numeric($_GET['item'])) {
				$item = $_GET['item'];
			}
		}



		// Get IP of this poster

		$the_ip = "";
		$entries = file_get_contents($data_file);
		$entries = (array)explode('###', $entries);
		foreach ($entries as $entry) {
			$data_t = explode("\r\n", trim($entry));
			if (trim($data_t[0]) == $item) {
				foreach ($data_t as $d) {
					if (strpos($d, 'ip=') === 0)  {
						$the_ip = substr($d, 3, strlen($d) - 1);
					}
				}
			}
		}	
	

		// add the IP to the ban list
		$handle = fopen($ban_file, "a");
		if (ddfm_flock($handle, LOCK_EX)) { // do an exclusive lock
			fwrite($handle, (string)$the_ip . "\r\n");
			ddfm_flock($handle, LOCK_UN); // release the lock
		} else {
		   echo _ERROR_NO_LOCK;
		}
		fclose($handle);



		// remove entries from IP 
		$entries = file_get_contents($data_file);
		$entries = (array)explode('###', $entries);

		// recreate file
		$handle = fopen($data_file, "w");

		if (ddfm_flock($handle, LOCK_EX)) { // do an exclusive lock

			foreach ($entries as $entry) {
					
				$data_t = explode("\r\n", trim($entry));

				if (trim($data_t[0]) != "") { // if valid item

					foreach ($data_t as $d) {
						if (strpos($d, 'ip=') === 0)  {
							$test_ip = substr($d, 3, strlen($d) - 1);
						}
					}

					if ($test_ip != $the_ip) { // put back
 
						foreach ($data_t as $d) {
							fwrite($handle, $d . "\r\n");
						}
						fwrite($handle, "###\r\n");

					} else {
						// skip items from this IP
					}

				}
			}	
	
			ddfm_flock($handle, LOCK_UN); // release the lock

			echo '<p>' . _IP_BANNED_DELETED. '</p>';
			echo '<p><a href="' . basename($_SERVER["PHP_SELF"]) . '">' . _BACK . '</a></p>';
			$action = "nothing";

		} else {
		   echo _ERROR_NO_LOCK;
		}

		fclose($handle);
	
		unset($_SESSION['item']);

	} else {

		echo '<p>' . _NO_BAN_NO_LOGGED . '</p>';
		echo '<p><a href="' . basename($_SERVER["PHP_SELF"]) . '">' . _BACK . '</a></p>';

	}


} elseif ($action != "nothing") { // display posts

?>



<p align="right"><a href="<?php echo basename($_SERVER["PHP_SELF"]) . "?action=entry"; ?>"><?php echo _ADD ?></a></p>



<?php






	// display entries

	$lines = trim(file_get_contents($data_file));


	// get items (depending on admin being logged in)

	$total_items = 0;

	if (strlen($lines) > 0) {
		$lines = (array)explode('###', $lines);

		if ($logged_in) { 

			$total_items = count($lines);

			if (trim($lines[sizeof($lines)]) == "") {
				$total_items--;			
			}

		} else {

			foreach ($lines as $l) {

				if (trim($l) != "") {

					$t = (array)split("\r\n", trim($l));

					if ($t[1] == 'TRUE') {
						$total_items++;
					}

				}
			}
			
			/*
			if (trim($lines[sizeof($lines)]) == "") {
				$total_items--;			
			}
			*/
			
		}
	}





	if ($total_items < 1) {
		echo '<div class="ddgb_entry"><p>' . _NO_ENTRIES . '</p></div>';
	}

	$total_pages = ceil($total_items / $items_per_page);





	$si = ($page - 1) * $items_per_page;

	$ei = $si + $items_per_page;

	if ($ei > $total_items) {
		$ei = $total_items;
	}

	if ($si < 0) {
		$si = 0;
	}

	



	// if not logged in, array should only contain approved entries

	if ($total_items > 0) {
		if (!$logged_in) {
			$new_lines = array();
			foreach ($lines as $line) {
				$data_t = explode("\r\n", trim($line));
				if ($data_t[1] == 'TRUE') {
					$new_lines[] = $line;
				}
			}
			$lines = $new_lines;
		}
	}



	for ($i = $si; $i < $ei; $i++) {


		$data_t = explode("\r\n", trim($lines[$i]));



		// make entry array elements into indexed array

		$data = array();
		foreach ($data_t as $dt) {		
			if (strpos($dt, '=') != FALSE) {		
				$k = substr($dt, 0, strpos($dt, '='));
				$v = substr($dt, strpos($dt, '=') + 1, strlen($dt) - strpos($dt, '='));	
				$data[$k] = $v;
			}
		}
		$data['timestamp'] = $data_t[0];




		// if magic quotes are on, strip slashes
		if (get_magic_quotes_gpc()) {
			$data['name'] = stripslashes($data['name']);
			$data['message'] = stripslashes($data['message']);
			$data['location'] = stripslashes($data['location']);
		}


		// remove any html from name, email, location, and website (should never be there)
		$data['name'] = htmlspecialchars($data['name']);
		$data['email'] = htmlspecialchars($data['email']);
		$data['location'] = htmlspecialchars($data['location']);
		$data['website'] = htmlspecialchars($data['website']);

		// allow html in message?
		if (!$allow_html) {
			$data['message'] = str_replace("<br />", "*BR*", $data['message']);
			$data['message'] = htmlspecialchars($data['message']);
			$data['message'] = str_replace("*BR*", "<br />", $data['message']);
		}



		echo '<div class="ddgb_entry">';
		echo '<div class="ddgb_info">';

		if ($data['location']) {
			echo ' <span class="ddgb_h">' . _LOCATION . '</span>: ' . $data['location'] . ' - ';
		}

		echo ' <span class="ddgb_h">' . _WRITTEN_ON . "</span> " . date(_FORMAT_DATE, $data['timestamp']) . " " . _WRITTEN_ON_TIME . '<br />' . "\n";

		if (($data['website'] != "") || ($data['email'] != "")) {

			if ($data['website']) {

				// check website validity
				if (strpos($data['website'], "//") === FALSE) {
					$data['website'] = "http://" . $data['website'];
				}

				echo '<span class="ddgb_h"><a href="' . $data['website'] . '" title="' . $data['website'] . '" rel="nofollow">' . _WEBSITE . '</a></span>';
			}

			if ($data['website'] && $data['email']) {
				echo ' - ';
			}

			if ($data['email']) {

				if ($protect_email) {
					
					$email = $data['email'];	
					$email = str_replace('@', ' [at] ', $email);
					$email = str_replace('.', ' [dot] ', $email);

					echo '<span class="ddgb_h">' . _EMAIL . '</span>: ' . $email;


				} else {

					echo '<span class="ddgb_h"><a href="mailto:' . $data['email'] . '" title="mailto:' . $data['email'] . '">' . _EMAIL . '</a></span>';
				}

			}



		}

		echo '</div>';

		echo '<h2>' . $data['name'] . '</h2>';

		echo '<p>' . process_smiles($data['message']) . '</p>';


		// if logged in
		if ($logged_in) {

			echo '<div class="ddgb_admin">';

			if ($data_t[1] == 'FALSE') {
				echo '<div class="mod"><a href="' . basename($_SERVER["PHP_SELF"]) . "?action=approve&item=" . $data['timestamp'] . '" onclick="javascript:return confirm(\'' . _CONFIRM_APPROVE . '\');">' . _APPROVE . '</a></div>';
			} 

			echo _IP . $data['ip'] . ' - ';
			echo '<a href="' . basename($_SERVER["PHP_SELF"]) . "?action=edit&item=" . $data['timestamp'] . '">' . _EDIT . '</a> - ';
			echo '<a href="' . basename($_SERVER["PHP_SELF"]) . "?action=delete&item=" . $data['timestamp'] . '" onclick="javascript:return confirm(\'' . _CONFIRM_DELETE . '\');">' . _DELETE . '</a> - ';
			echo '<a href="' . basename($_SERVER["PHP_SELF"]) . "?action=ban&item=" . $data['timestamp'] . '" onclick="javascript:return confirm(\'' . _SURE_BAN . '\');">' . _BAN_IP . '</a>';
			echo '</div>';
		}

		echo '</div>';


	}





	echo '<div class="ddgb_nav">';

	if ($total_pages > 1) {

	        // Added by Hans Nordhaug 19.11.2007 - "of" wasn't translated.
		// Show page navigation
                $pagetext = str_replace('%page%',$page,_PAGES_OF_TOTAL);
                $pagetext = str_replace('%total_pages%',$total_pages,$pagetext);
		echo '<p>' . $pagetext . '&nbsp;&nbsp;&nbsp;';

		if ($page > 1) {
			echo '<a href="' . basename($_SERVER["PHP_SELF"]) . '?page=' . ($page - 1) . '">&laquo;' .  _PREVIOUS . '</a>&nbsp;&nbsp;&nbsp;';
		}
		if ($page < $total_pages) {
			echo '<a href="' . basename($_SERVER["PHP_SELF"]) . '?page=' . ($page + 1) . '">' . _NEXT . ' &raquo;</a>&nbsp;&nbsp;&nbsp;';
		}

		echo '</p>';

	}

	// Show admin login

	echo '<p>[ ';

	if ($logged_in) {
		echo '<a href="' . basename($_SERVER["PHP_SELF"]) . '?action=logout">' . _LOGOUT . '</a>';
	} else {
		echo '<a href="' . basename($_SERVER["PHP_SELF"]) . '?action=admin">' . _LOGIN . '</a>';
	}

	echo ' ]</p>';

	echo '</div>';

	echo '<p align="right">Script by <a href="http://www.dagondesign.com" title="Dagon Design">Dagon Design</a></p>';





} // end of posts

?>

</div>
</div>


<?php

// Show footer if running stand-alone
if ($standalone) {
	echo "</body>\n</html>";
}

?>
