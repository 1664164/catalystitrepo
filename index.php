<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "catalystit";

//Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
//Check connection
if($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}

//sql create table
$sql = "CREATE TABLE users (
name VARCHAR(50) NOT NULL,
surname VARCHAR(50) NOT NULL,
email VARCHAR(50) NOT NULL UNIQUE,
)";


//Open the file.
$fileHandle = fopen("users.csv", "r");
 
//Loop through the CSV rows.
while (($row = fgetcsv($fileHandle, 0, ",")) !== FALSE) {
    //Dump out the row for the sake of clarity.
    echo $row[0] . '<br>';
	echo $row[1] . '<br>';
	echo $row[2] . '<br>';
	echo '<br>';
}
//Close the file
fclose($fileHandle);

?>