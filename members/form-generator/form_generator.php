<?php
$form_location="../../public/Generated Forms/";
include("../header.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>From Generator</title>
	<link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
	<h1 align="center">Form generator</h1>
	<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" style="width: 60%;margin-left: 20%;margin-right: 20%">
		<div class="form-group">
			<label>Event name:</label>
			<input type="text" class="form-control" name="event_name">
		</div>
		<label>Select event type:</label>
		<div class="form-check">
		  <input class="form-check-input" type="radio" name="event_type" value="individual" checked>
		  <label class="form-check-label">
		    Individual event
		  </label>
		</div>
		<div class="form-check">
		  <input class="form-check-input" type="radio" name="event_type" value="team">
		  <label class="form-check-label">
		    Team event
		  </label>
		</div>

		
		<div class="form-group">
	    <label>Select number of members in the team:</label>
	    <select class="form-control" name ="number_participants">
	      <option value = "2">2</option>
           <option value = "3">3</option>
           <option value = "4">4</option>
           <option value = "5">5</option>
	    </select>
	  	</div>

         <label>Select all the fields needed:</label>
         <div class="form-check">
		  <input class="form-check-input" type="checkbox" value="regno" name="fields[]">
		  <label class="form-check-label">
		    Registration Number
		  </label>
		 </div>

		 <div class="form-check">
		  <input class="form-check-input" type="checkbox" name="fields[]" value="dept">
		  <label class="form-check-label">
		    Department
		  </label>
		 </div>

		 <div class="form-check">
		  <input class="form-check-input" type="checkbox" name="fields[]" value="year">
		  <label class="form-check-label">
		    Year
		  </label>
		 </div>

		 <div class="form-check">
		  <input class="form-check-input" type="checkbox" name="fields[]" value="college">
		  <label class="form-check-label">
		    College
		  </label>
		 </div>

		 <div class="form-check">
		  <input class="form-check-input" type="checkbox" name="fields[]" value="github">
		  <label class="form-check-label">
		    GitHub profile link
		  </label>
		 </div>

         <input type="submit" name="submit" class="btn btn-primary mb-2">

        <?php
        	//Establish connection with database to create table once form is created
   //      	$servername = "localhost";
			// $username = "root";
			// $password = "";
			// $dbname = "test";
			//Connecting to the database
			$conn = new mysqli($servername, $username, $password, $dbname);
		    if ($conn->connect_error) {
		        die("Connection failed: " . $conn->connect_error); // IF-Fail to Connect
		    }


        	if (isset($_POST["submit"])){
        		$event_name = $_POST["event_name"];
        		$event_type=$_POST["event_type"];
        		$number_participants=$_POST["number_participants"];
        		$fields=$_POST["fields"];
        		$form_file = $form_location.$event_name."-form.html";
        		$file = fopen($form_file,"w");
        		$table_columns = "";
        		
        		//Initial details of the HTML page
        		$html_file = "<!DOCTYPE html>
						<html>
						<head>
							<title>".$event_name."</title>
							<link rel=\"stylesheet\" href=\"../../assets/bootstrap/css/bootstrap.min.css\">
						</head>
						<body>";
				//Form section starts here
				$html_file = $html_file."<form action=\"../entry.php\" method=\"post\" style=\"width: 40%;margin-left: 30%;margin-right: 30%\">";
				$html_file = $html_file."<input type=\"hidden\" name=\"event_name\" value=\"".$event_name."\">";
				$html_file = $html_file."<h1>".$event_name."</h1>";
				if($event_type=="individual"){
					$html_file = $html_file."<div class=\"form-group\">
					<label>Participant name:</label>
					<input type=\"text\" name=\"participant_name\" class=\"form-control\">
					</div>";
					$table_columns = $table_columns."participant_name VARCHAR(255),";
					foreach($fields as $selected){
						$html_file = $html_file."<div class=\"form-group\">
						<label>".$selected."</label> 
						<input type=\"text\" name=\"".$selected."\"class=\"form-control\">
						</div>";
						$table_columns = $table_columns.$selected." VARCHAR(255),";
					}
				}else{
					$number_participants = (int)$number_participants;
					for ($i=0;$i<$number_participants;$i++){
						$participant_number = $i+1;
						$html_file = $html_file."<h5 align=\"center\">Participant - ".$participant_number."</h5>";
						$html_file = $html_file."<div class=\"form-group\">
						<label>Participant name:</label>
						<input type=\"text\" name=\"participant_name".$participant_number."\" class=\"form-control\">
						</div>";
						$table_columns = $table_columns."participant_name".$participant_number." VARCHAR(255),";
						foreach($fields as $selected){
						$html_file = $html_file." <div class=\"form-group\">
						<label>".$selected."</label>
						<input type=\"text\" name=\"".$selected.$participant_number."\" class=\"form-control\">
						</div>";
						$table_columns = $table_columns.$selected.$participant_number." VARCHAR(255),";
						}
					}
				}

				//table columns for the new table generated and query to create also generated
				$table_columns=substr($table_columns, 0, -1);
				$creation_query = "CREATE TABLE IF NOT EXISTS ".$event_name." (".$table_columns.");";
				$submit_stmt = $conn->prepare($creation_query);
			    if (!$submit_stmt) {
			        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error . "<br>";
			    }
			    $submit_stmt->execute();
			    echo ("<br>Table created successfully for the new form<br>");

				//Closing section
				$html_file = $html_file."<input type=\"submit\" class=\"btn btn-primary mb-2\">
				</form>
				<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\" integrity=\"sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa\" crossorigin=\"anonymous\"></script>
				</body>
				</html>";

				fwrite($file, $html_file);
				fclose($file);
				echo ("<br>Form created successfully<br>");
				echo "<br><a href='".$form_file."'>Click here</a>";
        	}
        ?>
	</form>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>