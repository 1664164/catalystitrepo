<?php
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
?>