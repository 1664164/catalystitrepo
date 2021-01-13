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

if(isset($_POST['but_import'])){
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["importfile"]["name"]);

	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

	$uploadOk = 1;
	if($imageFileType != "csv"){
		$uploadOk = 0;
	}

	if($uploadOk != 0){
		if (move_uploaded_file($_FILES["importfile"]["tmp_name"], $target_dir.'users.csv')) {
			
			//Checking file exists or not
			$target_file = $target_dir . 'users.csv'
			$fileexists = 0;
			if(file_exists($target_file)){
				$fileexists = 1;
			}
			if($fileexists == 1){
				
				//Open the file.
				$file = fopen("users.csv", "r");
				$i = 0;
				
				$importData_arr = array();
				
				//Loop through the CSV rows.
				while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
					$num = count($data);
					
					for($c=0; $c < $num; $c++){
						$importData_arr[$i][] = mysqli_real_escape_string($con, $data[$c]);
					}
					$i++
				}
				//Close the file
				fclose($file);
			
				$skip = 0;
				//insert import data
				foreach($importData_arr as $data){
					if($skip != 0){
						$name = $data[0];
						$surname = $data[1];
						$email = data[2];
						
						//Checking duplicate entry
						$sql = "select count(*) as allcount from user where name='" . $name . "' and  surname='" . $surname . "' and email='" . $email . "' ";
						
						$retrieve_data = mysqli_query($con,$sql);
						$row = mysqli_fetch_array($retrieve_data);
						$count = $row['allcount'];
						
						if($count == 0){
							//Insert record
							$insert_query = "insert into user(name,surname,email) values('".$name."','".$surname."','".$email."')";
							mysqli_query($con,$insert_query);
						}
					}
					$skip ++;
				}
				$newtargetfile = $target_file;
				if(file_exists($newtargetfile)){
					unlink($newtargetfile);
				}
			}
		}
	}
}
?>









