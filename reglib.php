<?php
/*/-
| Author:   Keelan Hyde
| Group-:   Y3S Group
| Date--:   2022-02-19
| File--:   reglib.php
| Brief-:   Library that contains functions for login, registration, and user info updates
| Notes-:   NONE
| 
| ToDo--:   [ADD] - Send activation email to user (createUser function)
|           [CON'T] - Finish JavaDoc comments
/*/


/**
* Creates new user
* @param string $fname Takes first name of the user that is registering
* @param string $lname Takes last name of the user that is registering
* @param string $email Takes the email of the user that is registering
* @param string $pass  Takes the password of the user that is registering
* return        Returns rtnData as an array of data:
*                   Key: success, Data Type: Bool, Usage: TRUE=> Acct. created & FALSE=> Acct. not created
*                   Key: guid, Data Type: String, Usage: Passes md5 random hash or NULL
*                   Key: emailExists, Data Type: Bool, Usage: TRUE=> Has Account & FALSE=> May create acct.
*/
function createUser($fname, $lname, $email, $pass){
    include 'dbConn.php';
    include_once 'loginlib.php';

    $rtnData = ['success'=>NULL, 'guid'=>NULL, 'emailExists'=>NULL];

    //Account creation variables
    $auth = NULL;   //<!--IMPORTANT--->REPLACE WITH HARDCODED VALUE BEFORE DEPLOYMENT
    $theme = NULL;  //<!--IMPORTANT--->REPLACE WITH HARDCODED VALUE BEFORE DEPLOYMENT
    $hash = passHash($pass);    //Hashes password
    $ban = 0; //0 = FALSE
    $acctActive = 0; //0 = FALSE
    $acctGUID = md5(rand(0,1000));      //Creates GUID for account activation
    $regDate = date('Y-m-d H:i:s');     //Current server's time at user registration
    $expGUID = date('Y-m-d H:i:s', strtotime('+1 day'));

    $sql = "SELECT Email FROM Users WHERE Email='$email'"; //SQL to check for duplicate
    $results = $conn->query($sql);  //Queries the sql statement
    $row = $results->fetch_row();   //If NULL then account can be created, otherwise email exists in system.

    

    //Checks to see if there is already an account with the email that the user is signing up with
    if (!$row == NULL){
        /**
         * NOTE: Error coding for "This account already exists" may be expaned here if need be.
         */


        //If email exists sets "emailExists" to TRUE. All other array keys remain NULL.
        $rtnData['emailExists']=TRUE;
        $rtnData['success']=FALSE;
        return $rtnData;

    }else{
        
        //SQL to create user's account if the email address does not yet exist in the system
        //TODO - ADD AUTH AND THEME FIELDS AND VALUES
        $sql = "INSERT INTO Users (Fname, Lname, Email, PassHash, Blocked_Usr, Acct_Active, Last_Active,RegisterDate, Acct_GUID, GUID_Expire)
                VALUES ('$fname', '$lname', '$email', '$hash', '$ban', '$acctActive', '$regDate', '$regDate', '$acctGUID', '$expGUID') ";

        //Creates the user in the Users table.
        if ($result = $conn->query($sql)){
            /**
             * ADD: Email account activation link to new user here
             */


            $rtnData['success']=TRUE;       //Account created successfully
            $rtnData['emailExists']=FALSE;  //Email does not already exist
            $rtnData['guid']="$acctGUID";   //Sends GUID back to create account activation link
            return $rtnData;

        }else{
            /**
             * NOTE: Error coding for "Account creation failed" may be expaned here if need be.
             */
            

            $rtnData['success']=FALSE;      //Account creation failed
            $rtnData['emailExists']=FALSE;  //Email does not already exist
            return $rtnData;
        }
    }
}


/**
* Activates new users
* @param string $id    Takes guid from user activation link
* @param string $email Takes user email address from user activation link
* return        Returns rtnData as an array of data:
*                   Key: expired, Data Type: Bool, Usage: Default FALSE=> GUID is still valid, TRUE=> GUID has expired
*                   Key: activated, Data Type: Bool, Usage: Default NULL, TRUE=> Acct Active, FALSE=> Activation Failed
*                   Key: badLink, Data Type: Bool, Usage: Default FALSE=> Link is good, TRUE=> Link doesn't match records
*/
function activateUser($id, $email){
    include 'dbConn.php';

    $rtnData = ['expired'=>FALSE, 'activated'=>NULL, 'badLink'=>FALSE];

    //Gets necessary data to activate account
    $sql = "SELECT Email, Acct_Active, Acct_GUID, GUID_Expire FROM Users WHERE Acct_GUID='$id' AND Email='$email'";
    $result = $conn->query($sql);
    $userRecord = $result->fetch_array();
    
    //Checks if the email and GUID from the activation link match the stored information
    if($id == $userRecord['Acct_GUID'] && $email == $userRecord['Email']){

        //Checks to make sure that the GUID is still valid/active
        if(date('Y-m-d H:i:s') < $userRecord['GUID_Expire']){

            //Activates account and sets GUID and GUID_expire Date to NULL to prevent an attack vector
            $sql = "UPDATE Users SET Acct_Active = '1', GUID_Expire = NULL, Acct_GUID = NULL WHERE Acct_GUID='$id' AND Email='$email'";
            
            //Returns account activation status
            if($results = $conn->query($sql)){
                return $rtnData['activated'] = TRUE;  //Returns 'activated' as TRUE if account has been activated
            }else{
                return $rtnData['activated'] = FALSE;  //Returns 'activated' as FALSE if account has not been activated
            }

        }else{
            return $rtnData['expired'] = TRUE;  //Returns 'expired' as TRUE if guid has expired
        }
    }else{
        return $rtnData['badLink'] = TRUE;  //Returns 'badLink' as TRUE if link id & email do not match stored values
    }
}


?>