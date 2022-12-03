<?php

/*
\author     Keelan Hyde
\group      Y3S Group
\date       2022-02-16
\file       tableList.php
\brief      Returns array of tables to install for LCM database
\notes      Place create commands that have foreign keys after all the foreign tables they require
\           or you will get Error 1005.
*/

function rtnTblList() {
        
                $a=" CREATE TABLE Article (
                    Article_ID INT AUTO_INCREMENT,
                    State_ID INT,
                    Lang_ID INT,
                    Likes INT NOT NULL,
                    Featured BOOLEAN NOT NULL DEFAULT TRUE,
                    Created_Date DATETIME NOT NULL,
                    Usr_ID INT,
                    Meta_Keywrd VARCHAR(255) NOT NULL,
                    Meta_Desc VARCHAR(255) NOT NULL,
                    Headline VARCHAR(255) NOT NULL,
                    HTML_Title VARCHAR(255) NOT NULL,
                    Intro_txt VARCHAR(255) NOT NULL,
                    HTML_Txt_File VARCHAR(120) NOT NULL,
                    PRIMARY KEY (Article_ID),
                    FOREIGN KEY (State_ID) REFERENCES States (State_ID),
                    FOREIGN KEY (Lang_ID) REFERENCES Languages (Lang_ID),
                    FOREIGN KEY (Usr_ID) REFERENCES Users (Usr_ID)
                    );";
                    
                $b="CREATE TABLE Transaction (
                    Trans_ID INT AUTO_INCREMENT,
                    Article_ID INT,
                    Usr_ID INT,
                    Chkd_Out BOOLEAN NOT NULL,
                    Chkd_Out_Date DATETIME NOT NULL,
                    Modified_Date DATETIME,
                    PRIMARY KEY (Trans_ID),
                    FOREIGN KEY (Article_ID) REFERENCES Article (Article_ID),
                    FOREIGN KEY (Usr_ID) REFERENCES Users (Usr_ID)
                    );";

                $c="CREATE TABLE States (
                    State_ID INT AUTO_INCREMENT,
                    States_Name VARCHAR(75) NOT NULL,
                    PRIMARY KEY (State_ID)
                    );";

                $d="CREATE TABLE Languages (
                    Lang_ID INT AUTO_INCREMENT,
                    Lang_Code VARCHAR(10) NOT NULL,
                    Lang_Name VARCHAR(40) NOT NULL,
                    PRIMARY KEY (Lang_ID)
                    );";

                $e="CREATE TABLE ArticleCategory (
                    Cat_ID INT,
                    SubCat_ID INT,
                    Article_ID INT,
                    PRIMARY KEY (Cat_ID, SubCat_ID, Article_ID),
                    FOREIGN KEY (Article_ID) REFERENCES Article (Article_ID),
                    FOREIGN KEY (Cat_ID) REFERENCES pCategory (Cat_ID),
                    FOREIGN KEY (SubCat_ID) REFERENCES sCategory (SubCat_ID)
                    );";

                $f="CREATE TABLE Theme (
                    Theme_ID INT AUTO_INCREMENT,
                    Theme_Name VARCHAR(120) NOT NULL,
                    CSS_File VARCHAR(120) NOT NULL,
                    PRIMARY KEY (Theme_ID)
                    );";

                $g="CREATE TABLE sCategory (
                    SubCat_ID INT AUTO_INCREMENT,
                    Cat_ID INT,
                    SubCat_Name VARCHAR(120) NOT NULL,
                    PRIMARY KEY (SubCat_ID),
                    FOREIGN KEY (Cat_ID) REFERENCES pCategory (Cat_ID)
                    );";

                $h="CREATE TABLE pCategory (
                    Cat_ID INT AUTO_INCREMENT,
                    Cat_Name VARCHAR(120) NOT NULL,
                    PRIMARY KEY (Cat_ID)
                    );";

                $i="CREATE TABLE Users (
                    Usr_ID INT AUTO_INCREMENT,
                    Auth_ID INT,
                    Theme_ID INT,
                    Fname VARCHAR(40) NOT NULL,
                    Lname VARCHAR(40) NOT NULL,
                    Email VARCHAR(255) NOT NULL,
                    PassHash VARCHAR(255) NOT NULL,
                    Blocked_Usr BOOLEAN NOT NULL DEFAULT FALSE,
                    Acct_Active BOOLEAN NOT NULL DEFAULT FALSE,
                    Last_Active DATETIME NOT NULL,
                    RegisterDate DATETIME NOT NULL,
                    Acct_GUID VARCHAR(255),
                    GUID_Expire DATETIME,
                    PRIMARY KEY (Usr_ID),
                    FOREIGN KEY (Auth_ID) REFERENCES UsrAuth (Auth_ID),
                    FOREIGN KEY (Theme_ID) REFERENCES Theme (Theme_ID)
                    );";

                $j="CREATE TABLE CatPref (
                    SubCat_ID INT,
                    Cat_ID INT,
                    Usr_ID INT,
                    PRIMARY KEY (Cat_ID, SubCat_ID, Usr_ID),
                    FOREIGN KEY (Usr_ID) REFERENCES Users (Usr_ID),
                    FOREIGN KEY (Cat_ID) REFERENCES pCategory (Cat_ID),
                    FOREIGN KEY (SubCat_ID) REFERENCES sCategory (SubCat_ID)
                    );";

                $k="CREATE TABLE UsrAuth (
                    Auth_ID INT AUTO_INCREMENT,
                    Auth_Name VARCHAR(120) NOT NULL,
                    Auth_Level INT NOT NULL,
                    Auth_Desc VARCHAR(255) NOT NULL,
                    PRIMARY KEY (Auth_ID)
                    );";

                $l="CREATE TABLE Views (
                    View_ID INT AUTO_INCREMENT,
                    Article_ID INT,
                    Endeav_ID INT,
                    View_Date DATE NOT NULL,
                    Hits INT(5) NOT NULL,
                    PRIMARY KEY (View_ID),
                    FOREIGN KEY (Article_ID) REFERENCES Article (Article_ID),
                    FOREIGN KEY (Endeav_ID) REFERENCES Endeavour (Endeav_ID)
                    );";

                $m="CREATE TABLE Endeavour (
                    Endeav_ID INT AUTO_INCREMENT,
                    Usr_ID INT,
                    Volume_Num INT(4) NOT NULL,
                    Issue_Num INT(4) NOT NULL,
                    Publication_Date DATETIME NOT NULL,
                    Created_Date DATETIME NOT NULL,
                    PDF_File VARCHAR(120) NOT NULL,
                    Meta_Keywrd VARCHAR(255) NOT NULL,
                    Meta_Desc VARCHAR(255) NOT NULL,
                    Headline VARCHAR(255) NOT NULL,
                    HTML_Title VARCHAR(255) NOT NULL,
                    PRIMARY KEY (Endeav_ID),
                    FOREIGN KEY (Usr_ID) REFERENCES Users (Usr_ID)
                    );";

                $n="CREATE TABLE AdCat (
                    SubCat_ID INT,
                    Cat_ID INT,
                    Advert_ID INT,
                    PRIMARY KEY (Cat_ID, SubCat_ID, Advert_ID),
                    FOREIGN KEY (Advert_ID) REFERENCES Advert (Advert_ID),
                    FOREIGN KEY (Cat_ID) REFERENCES pCategory (Cat_ID),
                    FOREIGN KEY (SubCat_ID) REFERENCES sCategory (SubCat_ID)
                    );";

                $o="CREATE TABLE Advert (
                    Advert_ID INT AUTO_INCREMENT,
                    Client_ID INT,
                    Media_typ VARCHAR(10) NOT NULL,
                    Ad_startDate DATE NOT NULL,
                    Ad_endDate DATE NOT NULL,
                    Frequency INT(2),
                    Contract_File VARCHAR(120),
                    Ad_Statue BOOLEAN NOT NULL DEFAULT FALSE,
                    Ad_File VARCHAR(255) NOT NULL,
                    Apply_Date DATE NOT NULL,
                    Approval_Date DATE,
                    PRIMARY KEY (Advert_ID),
                    FOREIGN KEY (Client_ID) REFERENCES Client (Client_ID)
                    );";
                    
                $p="CREATE TABLE Client (
                    Client_ID INT AUTO_INCREMENT,
                    Usr_ID INT,
                    Comp_Name VARCHAR(255),
                    Comp_Phone VARCHAR(20),
                    Comp_Email VARCHAR(255),
                    Contact_Name VARCHAR(255) NOT NULL,
                    Contact_Email VARCHAR(255) NOT NULL,
                    Contact_Phone VARCHAR(20) NOT NULL,
                    City VARCHAR(85) NOT NULL,
                    St_Addr VARCHAR(255) NOT NULL,
                    Province VARCHAR(50) NOT NULL,
                    Post_Code VARCHAR(12) NOT NULL,
                    NonProfit BOOLEAN NOT NULL,
                    PRIMARY KEY (Client_ID),
                    FOREIGN KEY(Usr_ID) REFERENCES Users(Usr_ID)
                    );";

    //If your table has a foreign key place it on the right end of the array.
    //If your table does not have foreign keys place it on the left end of the array.
    $tableList = [$c,$d,$f,$h,$g,$k,$i,$j,$m,$p,$o,$n,$a,$b,$e,$l];

    return $tableList;
}

?>