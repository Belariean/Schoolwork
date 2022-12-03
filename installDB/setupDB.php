<?php

/*/
|author     Keelan Hyde
|group      Y3S Group
|date       2022-02-15
|file       setupDB.php
|brief      Installs Database, Tables, and default Records
|notes      NONE
|
|ToDo       [ADD] - Default records installation logic
/*/

include 'tableList.php';
include 'recordsList.php';

//Database Connection Variables
$servername = "localhost";
$username = "school";
$password = ",5@pY~+VzMMe<kJ";
$database = "LCM2022";

//Create connection
$conn = new mysqli($servername, $username, $password);

//Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error); //Kill connection and display error
}

//Setup button disable variables
$disableDB="";
$disableTBL="";

//Database status variables
$exist_DB=false;
$exist_TBL=false;
$status_DB="";
$status_TBL="";
$txtColour_Green="#00FF00"; //Exists|Installed
$txtColour_Red="#FF0000"; //Exists|Installed
$colour_DB=$txtColour_Red;
$colour_TBL=$txtColour_Red;

//Database querying variable
$sql = "";

//Database query results
$result = "";

//Database error message handler
$errorStatus = "";

//Holds results for show tables
$tables;
$tableCount=0;
$tblNames = ["Article", "Transaction", "States",
             "Languages", "ArticleCategory",
             "Theme", "sCategory", "pCategory",
             "Users", "CatPref", "UsrAuth",
             "Views", "Endeavour", "AdCat",
             "Advert", "Client"];


//Checks for the existance of the database and provides a install/not installed status based on results
if ($result = $conn->select_db($database)){

    $exist_DB = TRUE;
    $disableDB = "disabled";
    $status_DB="Installed";
    $colour_DB=$txtColour_Green;
    $tables = $conn->query("SHOW TABLES");  //Requests tables
    $matchingTables=0;  //Trackes matched tables

    //Compares table names and updates matchingTables for matches
        for ($i = 0; $i < count($tblNames); $i++){
            if ($result = $conn->query("SHOW TABLES LIKE'".$tblNames[$i]."'")){
                if ($result->num_rows == 1){
                    $matchingTables++;
                }
            }
        }

    /*Checks matchingTables against expected tblNames.
     *If both are equal then installed is displayed and button is disabled
     *If matchingTables < expected tblNames button is disabled and error is shown
     *If matchingTables is 0 (zero) button is enabled and not installed is displayed
     */
    if ($matchingTables == count($tblNames)){
        $exist_TBL = TRUE;
        $disableTBL = "disabled";
        $status_TBL="Installed";
        $colour_TBL=$txtColour_Green;
    } else if ($matchingTables < count($tblNames) && $matchingTables != 0) {
        $disableTBL = "disabled";
        $status_TBL="ERROR: Missing Tables. Please contact your system administrator";
        $colour_TBL=$txtColour_Red;
    } else if ($matchingTables == 0){
        $disableTBL = "";
        $status_TBL="Not Installed";
        $colour_TBL=$txtColour_Red;
    }
    
}else{
    
    $disableDB = "";
    $disableTBL = "";
    $status_DB="Not Installed";
    $status_TBL="Not Installed";
    $colour_TBL=$txtColour_Red;
    $colour_DB=$txtColour_Red;
    
}

if ($_POST['setupDB']=="Install Database" && !$exist_DB){
    $sql ="CREATE DATABASE $database";
    if ($conn->query($sql)===TRUE){
        $exist_DB=TRUE;
        $errorStatus = "[Status] - SUCCESS: The database was created successfully!";
        $_POST['setupDB']="";
        header("Refresh:2");
    }else{
        $errorStatus = "[Status] - ERROR: An error was encountered when creating the database: " . $conn->error . " Please contact your systems administrator.";
    }
}

if ($_POST['setupTBL']=="Install Tables" && !$exist_TBL){
    $createTables = rtnTblList();
    $goodTBL = 0;
    $badTBL = 0;


    for ($i = 0; $i < count($createTables); $i++){
        $sql = $createTables[$i];

        if ($conn->query($sql)===TRUE){
            $goodTBL++;
        }else{
            $errorStatus = "[Status] - ERROR: An error was encountered when creating the tables: " . $conn->error . " Please contact your systems administrator.";
            $badTBL++;
        }
    }

    if ($goodTBL == count($tblNames)){
        $exist_TBL = TRUE;
        $errorStatus = "[Status] - SUCCESS: The tables were created successfully!";
        $_POST['setupTBL']="";
        header("Refresh:2");
    }
}


//Displays setup options and information
echo "
    <form action='' method='POST'>
        <table>
            <tr>
                <!--Database Status: Does it exist-->
                <td>Database: </td>
                <td><p style='color: $colour_DB'>$status_DB</p></td>
            </tr>
            <tr>
                <!--Table Status: Do they exist-->
                <td>Tables: </td>
                <td><p style='color: $colour_TBL'>$status_TBL</p></td>
            </tr>
            <tr>
                <!--Database and Table setup buttons-->
                <td> <input type='submit' name='setupDB' value='Install Database' $disableDB /> </td>
                <td> <input type='submit' name='setupTBL' value='Install Tables' $disableTBL /> </td>
            </tr>
            <tr>
                <td>Page refreashes</td>
                <td>after each installation is complete.</td>
            </tr>
        </table>        
    </form>
    <p style='margin-top: 100px'>$errorStatus</p>
";

$conn->close();
?>