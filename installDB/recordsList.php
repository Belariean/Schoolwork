<?php

/*
\author     Keelan Hyde
\group      Y3S Group
\date       2022-02-26
\file       recordsList.php
\brief      Returns array of records to pre-load for LCM database
*/

/**
 * Returns an array of records
 * /--------
 * | 0 -> UsrAuth Table
 * | 1 -> Languages Table
 * | 2 -> States Table
 * | 3 -> Theme Table
 * | 4 -> pCategory Table
 * | 5 -> sCategory Table
 * \--------
 * @param int|string $tbl Takes an int that relates to the table needed or the table name 
 * @return array Returns the records to be inserted for selected table
 */
function rtnRecordList($tbl){

	/*----/UsrAuth Table Default Records/----*/
	$auth_a = "INSERT INTO UsrAuth (Auth_Name, Auth_Level, Auth_Desc) VALUES ('System Admin','0','Maintains systems and services')";
	$auth_b = "INSERT INTO UsrAuth (Auth_Name, Auth_Level, Auth_Desc) VALUES ('Teacher','1','Digital Communications & Media faculty')";
	$auth_c = "INSERT INTO UsrAuth (Auth_Name, Auth_Level, Auth_Desc) VALUES ('Moderator','2','Moderates comments and users')";
	$auth_d = "INSERT INTO UsrAuth (Auth_Name, Auth_Level, Auth_Desc) VALUES ('Student','3','Current Digital Communications & Media student')";
	$auth_e = "INSERT INTO UsrAuth (Auth_Name, Auth_Level, Auth_Desc) VALUES ('Contributor','4','Former Digital Communications & Media student')";
	$auth_f = "INSERT INTO UsrAuth (Auth_Name, Auth_Level, Auth_Desc) VALUES ('Client','5','Advertises with Lethbridge Campus Media')";
	$auth_g = "INSERT INTO UsrAuth (Auth_Name, Auth_Level, Auth_Desc) VALUES ('User','6','A registered Lethbridge Campus Media viewer')";
	$auth_h = "INSERT INTO UsrAuth (Auth_Name, Auth_Level, Auth_Desc) VALUES ('Guest','7','A unregistered Lethbridge Campus Media viewer')";
	
	$auth = [$auth_a,$auth_b,$auth_c,$auth_d,$auth_e,$auth_f,$auth_g,$auth_h];	//Pre-load for User Authorization


	/*----/Languages Table Default Records/----*/
	$lang_1 = "INSERT INTO Languages (Lang_Code, Lang_Name) VALUES ('en','English')";
	$lang_2 = "INSERT INTO Languages (Lang_Code, Lang_Name) VALUES ('fr','French')";
	$lang_3 = "INSERT INTO Languages (Lang_Code, Lang_Name) VALUES ('es','Spanish')";

	$lang = [$lang_1,$lang_2,$lang_3]; //Pre-load for content languages


	/*----/States Table Default Records/----*/
	$state_1 = "INSERT INTO States (States_Name) VALUES ('Published')";
	$state_2 = "INSERT INTO States (States_Name) VALUES ('Approved')";
	$state_3 = "INSERT INTO States (States_Name) VALUES ('Pending Approval')";
	$state_4 = "INSERT INTO States (States_Name) VALUES ('Draft - Edit')";
	$state_5 = "INSERT INTO States (States_Name) VALUES ('Draft - Final')";
	$state_6 = "INSERT INTO States (States_Name) VALUES ('Draft - In-Progress')";
	$state_7 = "INSERT INTO States (States_Name) VALUES ('Draft - Rough')";

	$state= [$state_1,$state_2,$state_3,$state_4,$state_5,$state_6,$state_7];	//Pre-load for content publishing state


	/*----/Theme Table Default Records/----*/
	$theme_1 = "INSERT INTO Theme (Theme_Name, CSS_File) VALUES ('LCM-Default','lcmdefault.css')";

	$theme= [$theme_1]; //Pre-load for default theme


	/*----/pCategory Table Default Records/----*/
	$pCat_1 = "INSERT INTO pCategory (Cat_Name) VALUES ('No Category')";
	$pCat_2 = "INSERT INTO pCategory (Cat_Name) VALUES ('News')";
	$pCat_3 = "INSERT INTO pCategory (Cat_Name) VALUES ('Entertainment')";
	$pCat_4 = "INSERT INTO pCategory (Cat_Name) VALUES ('Life')";
	$pCat_5 = "INSERT INTO pCategory (Cat_Name) VALUES ('Sports')";
	$pCat_6 = "INSERT INTO pCategory (Cat_Name) VALUES ('Bios')";
	$pCat_7 = "INSERT INTO pCategory (Cat_Name) VALUES ('Blog')";
	$pCat_8 = "INSERT INTO pCategory (Cat_Name) VALUES ('Columns')";
	$pCat_9 = "INSERT INTO pCategory (Cat_Name) VALUES ('Opinion')";

	$pCat = [$pCat_1,$pCat_2,$pCat_3,$pCat_4,$pCat_5,$pCat_6,$pCat_7,$pCat_8,$pCat_9];	//Pre-load for Categories


	/*----/sCategory Table Default Records/----*/

	/*/.../News Category/.../*/
	$sCat_1 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('2','No Sub-Category')";

	/*/.../Entertainment Category/.../*/
	$sCat_2 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('3','No Sub-Category')";
	$sCat_3 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('3','Art')";
	$sCat_4 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('3','Contests')";
	$sCat_5 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('3','Gaming')";
	$sCat_6 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('3','Movies')";
	$sCat_7 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('3','Music')";
	$sCat_8 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('3','Television')";

	/*/.../Life Category/.../*/
	$sCat_9 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('4','No Sub-Category')";
	$sCat_10 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('4','Health')";
	$sCat_11 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('4','Pets')";
	$sCat_12 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('4','Technology')";

	/*/.../Sports Category/.../*/
	$sCat_13 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('5','No Sub-Category')";
	$sCat_14 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('5','BasketBall')";
	$sCat_15 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('5','Curling')";
	$sCat_16 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('5','Hockey')";
	$sCat_17 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('5','Kodiaks')";
	$sCat_18 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('5','Recorded Games')";
	$sCat_19 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('5','Soccer')";
	$sCat_20 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('5','Track & Field')";
	$sCat_21 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('5','Volleyball')";

	/*/.../Bios Category/.../*/
	$sCat_22 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('6','No Sub-Category')";

	/*/.../Blog Category/.../*/
	$sCat_23 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('7','No Sub-Category')";

	/*/.../Columns Category/.../*/
	$sCat_24 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('8','No Sub-Category')";

	/*/.../Opinion Category/.../*/
	$sCat_25 = "INSERT INTO sCategory (Cat_ID, SubCat_Name) VALUES ('9','No Sub-Category')";
	

	$sCat = [$sCat_1,$sCat_2,$sCat_3,$sCat_4,$sCat_5,
			 $sCat_6,$sCat_7,$sCat_8,$sCat_9,$sCat_10,
			 $sCat_11,$sCat_12,$sCat_13,$sCat_14,$sCat_15,
			 $sCat_16,$sCat_17,$sCat_18,$sCat_19,$sCat_20,
			 $sCat_21,$sCat_22,$sCat_23,$sCat_24,$sCat_25];	//Pre-load for Sub-Categories


	if($tbl == 0 || $tbl == 'UsrAuth'){
		return $auth;
	}else if($tbl == 1 || $tbl == 'Languages'){
		return $lang;
	}else if($tbl == 2 || $tbl == 'States'){
		return $state;
	}else if($tbl == 3 || $tbl == 'Theme'){
		return $theme;
	}else if($tbl == 4 || $tbl == 'pCategory'){
		return $pCat;
	}else if($tbl == 5 || $tbl == 'sCategory'){
		return $sCat;
	}else{/*RETURN ERROR HERE*/}
}

?>