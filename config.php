<?php

$servername = "localhost";
$username = "username";
$password = "";
$dbname = "catalystit";

//Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
//Check connection
if($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}

//sql create table
$sql = "CREATE TABLE users (
name VARCHAR(100) NOT NULL,
surname VARCHAR(100) NOT NULL,
email VARCHAR(250) NOT NULL UNIQUE,
)";

if($conn->query($sql) === TRUE){
  echo "Table users created successfully";
} else {
  echo "Error creating table: " . $conn->error;
}

$conn->close();