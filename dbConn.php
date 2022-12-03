<?php

//Database Connection Variables
$servername = "localhost";
$username = "school";
$password = ",5@pY~+VzMMe<kJ";
$database = "LCM2022";

//Create connection
$conn = new mysqli($servername, $username, $password, $database);

//Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error); //Kill connection and display error
}

?>