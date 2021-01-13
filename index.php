<?php
include "config.php";

if(isset($_POST['but_import'])){
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["importfile"]["name"]);

	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

	$uploadOk = 1;
	if($imageFileType != "csv"){
		$uploadOk = 0;
	}

	if($uploadOk != 0){
		if (move_uploaded_file($_FILES["importfile"]["tmp_name"], $target_dir.'importfile.csv')) {
			
			//Checking file exists or not
			$target_file = $target_dir . 'importfile.csv'
			$fileexists = 0;
			if(file_exists($target_file)){
				$fileexists = 1;
			}
			if($fileexists == 1){
				
				//Open the file.
				$file = fopen($target_file, "r");
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

<!-- Import form (start) -->
<div class="popup_import">
 <form method="post" action="" enctype="multipart/form-data" id="import_form">
  <table width="100%">

   <tr>
    <td colspan="2">
     <input type='file' name="importfile" id="importfile">
    </td>
   </tr>
   <tr>
    <td colspan="2" ><input type="submit" id="but_import" name="but_import" value="Import"></td>
   </tr>
   <tr>
    <td colspan="2" align="center"><span class="format">Name, Surname, Email</span> </td>
   </tr>
   <tr>
    <td colspan="2" align="center"><a href="import_example.csv" target="_blank">Download Sample</a></td>
   </tr>

   <tr>
    <td colspan="2"><b>Instruction : </b><br/>
     <ul>
      <li>Enclose text field in quotes (' , " ) if text contains comma (,) is used.</li>
      <li>Enclose text field in single quotes (') if text contains double quotes (")</li>
      <li>Enclose text field in double quotes (") if text contains single quotes (')</li>
     </ul>
    </td>
   </tr>
  </table>
 </form>
</div>
<!-- Import form (end) -->

<!-- Displaying imported users -->
<table border="1" id="user table">
	<tr>
		<td>S.no</td>
		<td>name</td>
		<td>surname</td>
		<td>email</td>
	</tr>
	<?php
		$sql = "select * from user order by id desc limit 12";
		$sno = 1;
		$retrieve_data = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($retrieve_data)){
			$id = $row['id'];
			$name = $row['name'];
			$surname = $row['surname'];
			$email = $row['email'];
		
			echo "<tr>
				<td>".$sno."</td>
				<td>".$name."</td>
				<td>".$surname."</td>
				<td>".$email."</td>
			
			</tr>";
			$sno++;
		}
	?>
</table>