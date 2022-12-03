<?php
/*/
|author     Keelan Hyde
|group      Y3S Group
|date       2022-02-17
|file       loginLib.php
|brief      Library that contains functions for login, registration, and user info updates
|notes      NONE
|
|ToDo		[ADD] - Account Status Reasons for error reporting.
|			[ADD] - Error & Error logging logic
/*/



/**
 * Hashes the user's Password
 * @param string $pass Takes the user entered password in plaintext
 * @return string Returns the hashed password
 */
function passHash($pass) {
    return password_hash($pass, PASSWORD_DEFAULT);
}


/**
 * Verifies the user's password against their stored hash
 * @param string $hash Takes the stored password hash of the user
 * @param string $pass Takes the plaintext password that the user entered
 * @return bool Returns TRUE if hash and plaintext match.
 */
 function passVerify($hash, $pass){
    return password_verify($pass, $hash);
}

/**
 * Gets the user's record
 * @param string $email Takes the users email address
 * @return array Returns user record with category preferences appended
 */
function getUserRecord($email){
    include 'dbConn.php';
	
	$recordArr = NULL;	//Holds User Record
	
	//Gets user record and stores it in $recordArr
    $sql = "SELECT * FROM Users WHERE Users.Email='$email'";
    if ($results = $conn->query($sql)){
		$recordArr = $results->fetch_assoc();
	}else{
		/*
		 * <!--ADD ERROR LOGGING LOGIC HERE--->
		 */
	}

	$conn->close();
    return $recordArr;	//Returns appended user record.
}


/**
 * Verifies the login and registration page forms
 * @param string|null $email Takes the user's email address
 * @param string|null $pass Takes the user's plaintext password
 * @param string|null $fname Takes the user's first name
 * @param string|null $lname Takes the user's last name
 * @param string|null $curPass Takes the plaintext of the user's current password
 * @return (null|true)[]|(null|false)[]|null[]|(null|bool)[] Returns an array that
 *  contains the verification status. NULL - Not used, TRUE - Passed, FALSE - Failed
 */
function usrCredVerification($email, $pass, $fname, $lname, $curPass){
	$validField = ["email"=>NULL, "pass"=>NULL, "fname"=>NULL, "lname"=>NULL, "curPass"=>NULL];

	//Checks for valid email if an email was provided
	if ($email == NULL){
		//Do nothing [keeps 'email' key as NULL]
	} else {
		if (filter_var($email,FILTER_VALIDATE_EMAIL)){
			$validField['email'] = TRUE;
		}else{
			$validField['email'] = FALSE;
		}
	}

	//Checks for valid email if an password was provided
	if ($pass == NULL){
		//Do nothing [keeps 'pass' key as NULL]
	}else{
		
		$uppercase = preg_match('@[A-Z]@', $pass);	//Checks that the password contains A-Z
		$lowercase = preg_match('@[a-z]@', $pass);	//Checks that the password contains a-z
		$number = preg_match('@[0-9]@', $pass);		//Checks that the password contains 0-9
		$specialChar = preg_match('@[^\w]@', $pass); //Checks that the password contains a special character

		//If all of these conditions are true it has met validation conditions
		if ($uppercase && $lowercase && $number && $specialChar && strlen($pass) > 8){
			$validField['pass'] = TRUE;
		}else{
			$validField['pass'] = FALSE;
		}
	}

	//Makes sure the user's name is not longer than the database field's max length
	if ($fname == NULL){
		//Do nothing [keeps 'fname' key as NULL]
	}else{
		if (strlen($fname) < 255){
			$validField['fname'] = TRUE;
		}else{
			$validField['fname'] = FALSE;
		}
		
		//[TODO] - ADD EXCEEDS LIMIT ERROR LOGIC
	}

	//Makes sure the user's name is not longer than the database field's max length
	if ($lname == NULL){
		//Do nothing [keeps 'lname' key as NULL]
	}else{
		if (strlen($lname) < 255){
			$validField['lname'] = TRUE;
		}else{
			$validField['lname'] = FALSE;
		}
		
		//[TODO] - ADD EXCEEDS LIMIT ERROR LOGIC
	}

	//Verifies that the user password is correct
	if ($curPass == NULL){
		//Do nothing [keeps 'curpass' key as NULL]
	}else{
		if (passVerify($_SESSION['storedHash'], $curPass)){
			$validField['curPass'] = TRUE;
		}else{
			$validField['curpass'] = FALSE;
		}
	}


	return $validField;
}

 /**
  * Gets the user's account status
  * @param string|bool $banned Takes the Blocked_Usr status from the user record returned from the getUserRecord function
  * @param string|bool $active Takes the Acct_Active status from the user record returned from the getUserRecord function
  * @return mixed|bool Returns status of account. Currently TRUE indicates active & in good standing and FALSE idicates either account is banned or not activated
  */
function usrAcctStatus($banned, $active){
	//[TODO] ADD ARRAY TO ALLOW SPECIFIC INFO TO BE RETURNED (eg: "Your account has been banned" or "Please activate your account")

	if ($banned && ($active || !$active)){
		/*
		 * <!--ADD LOGGING LOGIC HERE--->
		 */
		return FALSE;
	}else if(!$banned && !$active){
		/*
		 * <!--ADD LOGGING LOGIC HERE--->
		 */
		return FALSE;
	}else if(!$banned && $active){
		/*
		 * <!--ADD LOGGING LOGIC HERE--->
		 */
		return TRUE;
	}
}

 /**
  *Validates user status to allow login
  *@param array $usrRecord Takes the user record array from the getUserRecord function
  *@param bool $acctStatus Takes the user account status from the usrAcctStatus function
  *@return bool Returns TRUE if loggin is approved or FALSE if login is denied
  */
function login($usrRecord, $acctStatus){
	include 'dbConn.php';
	if ($acctStatus){
		$email = $usrRecord['email'];
		$date = date('Y-m-d H:i:s');
		$sql = "UPDATE Users SET Last_Active='$date' WHERE Email='$email'";	//Updates user's last active time
		if($conn->query($sql)){
			//Do Nothing
		}else{
			/*
		 	 * <!--ADD ERROR LOGGING LOGIC HERE--->
			 */
		}

		//Starts session and populates session data
		session_start();
		$_SESSION['loggedIn'] = TRUE;
		$_SESSION['usrID'] = $usrRecord['Usr_ID'];
		$_SESSION['authID'] = $usrRecord['Auth_ID'];
		$_SESSION['themeID'] = $usrRecord['Theme_ID'];
		$_SESSION['fname'] = $usrRecord['Fname'];
		$_SESSION['lname'] = $usrRecord['Lname'];
		$_SESSION['email'] = $usrRecord['Email'];
		$_SESSION['storedHash'] = $usrRecord['PassHash'];
		$_SESSION['banned'] = $usrRecord['Blocked_Usr'];
		$_SESSION['active'] = $usrRecord['Acct_Active'];
		$_SESSION['lstActive'] = $usrRecord['Last_Active'];
		$_SESSION['regDate'] = $usrRecord['RegisterDate'];

		/*
		 * <!--ADD LOGGING LOGIC HERE--->
		 */

		$conn->close();
		return TRUE;	//Indicates authorized login
	}else{
		/*
		 * <!--ADD LOGGING LOGIC HERE--->
		 */
		$conn->close();
		return FALSE;	//Denies Login
	}
}

/**
 * Logs out a user
 * @return bool Returns TRUE for a successful logout and FALSE for unsuccessful
 */
function logout(){

	session_unset();
	session_destroy();

	if($_SESSION){
		/*
		 * <!--ADD LOGGING LOGIC HERE--->
		 */
		return FALSE;
	}else{
		/*
		 * <!--ADD LOGGING LOGIC HERE--->
		 */
		return TRUE;
	}
}


/**
 * Sends user a password reset email
 * @param string $email Takes user's email address
 * @return (null|true)[]|(null|bool)[]|(null|false)[]
 * Returns a status array with two keys (exists, guid). Null - Unknown, TRUE - record exists/guid added to record, FALSE - No record/guid not added to record
 */
function forgotPass($email){
	include 'dbConn.php';

	$rtnData = ['exists'=>NULL, 'guid'=>NULL];

	//Makes sure account exists
	$sql = "SELECT Email FROM Users WHERE Email='$email'";
    $results = $conn->query($sql);
    $row = $results->fetch_row();   //If NULL no account with that email, otherwise email exists in system.

	if(!$row == NULL){
		$rtnData['exists']=TRUE;	//Account exists

		$acctGUID = md5(rand(0,1000));	//Creates GUID for account activation
    	$expGUID = date('Y-m-d H:i:s', strtotime('+1 day'));	//Sets GUID expiry date

		//SQL statement that adds GUID and expiry date to the user account attached to the entered email
		$sql = "UPDATE Users SET GUID_Expire = '$expGUID', Acct_GUID = '$acctGUID' WHERE Email='$email'";

		//Performs query and checks if it succeeded
		if($results = $conn->query($sql)){
			$rtnData['guid']=TRUE;		//Added GUID info to user record
			/*
			 * <!--ADD PASS RESET EMAIL LOGIC HERE--->
			 */
			$conn->close();
			return $rtnData;
		}else{
			$rtnData['guid']=FALSE;		//Failed to add GUID info to user record
			/*
			 * <!--ADD ERROR LOGGING LOGIC HERE--->
			 */
			$conn->close();
			return $rtnData;
		}

	}else{
		$rtnData['exists']=FALSE;		//Account does not exist
		/*
		 * <!--ADD ERROR LOGGING LOGIC HERE--->
		 */
		$conn->close();
		return $rtnData;
	}

}

?>
