<?php
/*
\author     Chris Gfrerer
\           Keelan Hyde
\group      Y3S Group
\date       2022-02-18
\file       userLib.php
\brief      Library that contains functions
\notes      NONE
*/

/****************************************************************************
 *function      compareUpdateInfo
 *param         $updateFname - The updated or original first name for the user
 *param         $updateLname - The updated or original last name for the user
 *param         $updateEmail - The updated or original email for the user
 *param         $UpdatePass  - The updated password for the user
 *return        Returns array that indicates successful or failed updates
 *brief         Updates user info and saves to user's database record
 ****************************************************************************/
function compareUpdateInfo($updateFname, $updateLname, $updateEmail, $updatePass, $curPass){
    include 'dbConn.php';
    include_once 'loginlib.php';

    /**
     * $rtnData States
     *  0 means that, that key was not updated
     *  1 means that, that key was updated
     * -1 means that, that key failed to update
     */
    $rtnData = ['email'=>0, 'pass'=>0, 'fname'=>0, 'lname'=>0];

	//Updates user email if condition is false
    if($_SESSION['email']==$updateEmail){
        //Do nothing
    }else{
        $sql = "UPDATE Users SET Email='$updateEmail' WHERE Email='{$_SESSION['email']}'";

		//Updates email and provides feedback
        if($results = $conn->query($sql)){
            $_SESSION["email"] = $updateEmail;	//Updates session data
            $rtnData['email'] = 1;
        }else{
            $rtnData['email'] = -1;
        }

    }

	//Updates user fname if condition is false
    if($_SESSION['fname']==$updateFname){
        //Do nothing
    }else{
        $sql = "UPDATE Users SET Fname='$updateFname' WHERE Email='{$_SESSION['email']}'";

		//Updates fname and provides feedback
        if($results = $conn->query($sql) or die($conn->error)){
            $_SESSION["fname"] = $updateFname;	//Updates session data
            $rtnData['fname'] = 1;
        }else{
            $rtnData['fname'] = -1;
        }

    }

	//Updates user lname if condition is false
    if($_SESSION['lname']==$updateLname){
        //Do nothing
    }else{
        $sql = "UPDATE Users SET Lname='$updateLname' WHERE Email='{$_SESSION['email']}'";

		//Updates lname and provides feedback
        if($results = $conn->query($sql) or die($conn->error)){
            $_SESSION["lname"] = $updateLname;	//Updates session data
            $rtnData['lname'] = 1;
        }else{
            $rtnData['lname'] = -1;
        }

    }

	//Updates user password if condition is false
    if(passVerify($_SESSION['storedHash'], $updatePass) || NULL || ""){
        //Do nothing
    }else{

		//Verifies user's current password
        if(passVerify($_SESSION['storedHash'], $curPass)){
            $newPass = passHash($updatePass);

            $sql = "UPDATE Users SET PassHash='$newPass' WHERE Email='{$_SESSION['email']}'";

			//Updates password and provides feedback
            if($results = $conn->query($sql)){
                $_SESSION["storedHash"] = $newPass;	//Updates session data
                $rtnData['pass'] = 1;
            }else{
                $rtnData['pass'] = -1;
            }
        }
    }

	return $rtnData;
}


/****************************************************************************
 *function      updateUserTheme
 *param         $id - The new theme ID that the user would like to use
 *return        Returns TRUE if theme was updated or FALSE if it was not updated
 *brief         Updates user theme info and saves to user's database record
 ****************************************************************************/
function updateUserTheme($id){
	include 'dbConn.php';

	if($_SESSION['themeID']==$id){
		//Do nothing
	}else{
		$sql = "UPDATE Users SET Theme_ID='$id' WHERE Email='{$_SESSION['email']}'";
		if($results = $conn->query($sql)){
            $_SESSION['themeID']=$id;
			return TRUE;
		}else{
			return FALSE;
		}
	}
}

/****************************************************************************
 *function      updateUserAuth
 *param         $id - The new theme ID that the user would like to use
 *return        Returns TRUE if authorization was updated or FALSE if it was not updated
 *brief         Updates user authorization info and saves to user's database record
 ****************************************************************************/
function updateUserAuth($id){
	include 'dbConn.php';

	if($_SESSION['authID']==$id){
		//Do nothing
	}else{
		$sql = "UPDATE Users SET Auth_ID='$id' WHERE Email='{$_SESSION['email']}'";
		if($results = $conn->query($sql)){
            $_SESSION['authID']=$id;
			return TRUE;
		}else{
			return FALSE;
		}
	}
}

/****************************************************************************
 *function      addUserCategory
 *param         $catID - Selected category
 *param         $subCatID - selected subcategories of the selected category
 *param         $userID - the id of the user account
 *return        NONE - No Returns at this time
 *brief         Sets user's prefered news category
 ****************************************************************************/
function addUserCategory($catID, $subCatID, $userID){
	include 'dbConn.php';

	$sql = "INSERT INTO CatPref (SubCat_ID, Cat_ID, Usr_ID) VALUES ($subCatID, $catID, $userID)";

	if($results = $conn->query($sql)){
		return TRUE;
	}else{
		return FALSE;
	}
}

/****************************************************************************
 *function      removeUserCategory
 *param         $catID - Selected category
 *param         $subCatID - selected subcategories of the selected category
 *param         $userID - the id of the user account
 *return        Returns TRUE if recorded was deleted, otherwise FALSE if deletion failed
 *brief         Deletes a user's prefered news category
 ****************************************************************************/
function removeUserCategory($catID, $subCatID, $userID){
	include 'dbConn.php';

	$sql = "DELETE FROM CatPref WHERE SubCat_ID='$subCatID' AND Cat_ID='$catID' AND Usr_ID='$userID'";

	if($results = $conn->query($sql)){
		return TRUE;
	}else{
		return FALSE;
	}
}