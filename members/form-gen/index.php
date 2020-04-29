<?php
	include("../header.php");
	try{
		$conn = new PDO('mysql:dbname='.$formDB.';host='.$servername.';charset=utf8', $username, $password);

		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $e){
		$message = $e->getMessage()  ;
	    header('Location:pages/error.php?error='.$e->getMessage());
	    die();
	}
	$form_location="../../public/Generated Forms/";
	$display_prompts=array(
						'regno' => 'Registration Number' ,
						'dept' => 'Department',
						'year' => 'Year',
						'college' => 'College Name',
						'github' => 'Github Profile link',
						'email' => 'E-mail Address',
						'phoneno' => 'Contact number',
						'linkedin' => 'Linkedin Profile URL'
					);
	$year_dropdown = '
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			</select>';

	$department_dropdown = '	<option value="Automobile Engineering">Automobile Engineering</option>
						<option value="Biotechnology">Biotechnology</option>
						<option value="Chemical Engineering">Chemical Engineering</option>
						<option value="Computer Science and Engineering">Computer Science and Engineering</option>
						<option value="Civil Engineering">Civil Engineering</option>
						<option value="Electronics and Communication Engineering">Electronics and Communication Engineering</option>
						<option value="Electrical and Electronics Engineering">Electrical and Electronics Engineering</option>
						<option value="Information technology">Information Technology</option>
						<option value="Marine Engineering">Marine Engineering</option>
						<option value="Mechanical Engineering">Machanical Engineering</option>
						</select>'
?>

<html>

<head id="head_tag">
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
     <title>From Generator:<?php echo " ".$OrgName; ?></title>
     <link rel="icon" type="image/png" sizes="600x600" href="../../assets/img/Logo_White.png">
     <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
     <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <link rel="stylesheet" href="../../assets/fonts/fontawesome5-overrides.min.css">
     <link rel="stylesheet" href="../../assets/css/custom.css">
</head>

<body id="page-top">
     <div id="wrapper">
          <?php include("../navigation.php"); ?>
          <div class="container-fluid">
               <div class="card shadow">
                    <div class="card-header py-3">
                         <p class="text-primary m-0 font-weight-bold">Form Generator</p>
                    </div>
                    <div class="card-body">
                         <div class="table-responsive table mt-2" id="dataTable" role="grid"
                              aria-describedby="dataTable_info">
                              <table class="table dataTable my-0" id="dataTable">
                                   <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post"
                                        style="width: 60%;margin-left: 20%;margin-right: 20%"
                                        onSubmit="return validate_form();">
                                        <tr id="alert_name">
                                             <th>Event name</th>
                                             <td onclick="reverse_red('name')"><input type="text" class="form-control"
                                                       name="event_name" id="input_name" /></td>
                                             <td></td>
                                        </tr>
                                        <tr id="alert_name">
                                             <th>Event description
                                                  <div style="margin-top: 10px;"><button type="button"
                                                            class="btn btn-sm btn-primary"
                                                            onclick="preview_description()">Preview</button></div>
                                             </th>
                                             <td><textarea class="form-control" name="event_description"
                                                       id="event_desc"></textarea></td>
                                             <td></td>
                                        </tr>
                                        <script type="text/javascript">
                                        function preview_description() {
                                             var myWindow = window.open("", "_blank");
                                             var markdown_text = document.getElementById('event_desc').value.replace(
                                                  /\r?\n|\r/g, "\\n");
                                             var generated_html =
                                                  '<html><head><title>Preview page</title><body><div id="content"></div><script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"><\/script><script>document.getElementById("content").innerHTML =marked("' +
                                                  markdown_text + '");<\/script></body></html>';
                                             myWindow.document.write(generated_html);
                                        }
                                        </script>
                                        <tr>
											<?php $event_type_array = ['individual','team']; ?>
                                             <th>Choose Type</th>
                                             <td>
                                                  <div class="form-check">
                                                       <input class="form-check-input" type="radio" name="event_type"
                                                            value="individual" onclick="disable_dropdown()" checked>
                                                       <label class="form-check-label">
                                                            Individual
                                                       </label>
                                                  </div>
                                                  <div class="form-check">
                                                       <input class="form-check-input" type="radio" name="event_type"
                                                            value="team" onclick="enable_dropdown()">
                                                       <label class="form-check-label">
                                                            Team
                                                       </label>
                                                  </div>
                                             </td>
                                             <td></td>
                                        </tr>
                                        <script type="text/javascript">
                                        function disable_dropdown() {
                                             document.getElementById("number_participants").disabled = true;
                                        }

                                        function enable_dropdown() {
                                             document.getElementById("number_participants").disabled = false;
                                        }
                                        </script>
                                        <tr>
											<?php $number_participants_array = [NULL,"1","2","3","4"]; ?>
                                             <th>Choose Number of Members (If Team)</th>
                                             <td>
                                                  <select class="form-control" name="number_participants"
                                                       id="number_participants" disabled>
                                                       <option value="1">1</option>
                                                       <option value="2">2</option>
                                                       <option value="3">3</option>
                                                       <option value="4">4</option>
                                                       <option value="5">5</option>
                                                  </select>
                                             </td>
                                             <td></td>
                                        </tr>

                                        <tr id="alert_fields">
                                             <th>Choose the Fields Needed</th>
											 <?php $fields_array = ["regno","dept","year","email","phoneno","college","github","linkedin"]; ?>
                                             <td onclick="reverse_red('fields')">
                                                  <div class="form-check">
                                                       <input class="form-check-input" type="checkbox" value="regno"
                                                            name="fields[]">
                                                       <label class="form-check-label">
                                                            Registration Number
                                                       </label>
                                                  </div>

                                                  <div class="form-check">
                                                       <input class="form-check-input" type="checkbox" name="fields[]"
                                                            value="dept">
                                                       <label class="form-check-label">
                                                            Department
                                                       </label>
                                                  </div>

                                                  <div class="form-check">
                                                       <input class="form-check-input" type="checkbox" name="fields[]"
                                                            value="year">
                                                       <label class="form-check-label">
                                                            Year
                                                       </label>
                                                  </div>

                                                  <div class="form-check">
                                                       <input class="form-check-input" type="checkbox" name="fields[]"
                                                            value="email">
                                                       <label class="form-check-label">
                                                            E-mail address
                                                       </label>
                                                  </div>

                                                  <div class="form-check">
                                                       <input class="form-check-input" type="checkbox" name="fields[]"
                                                            value="phoneno">
                                                       <label class="form-check-label">
                                                            Contact Number
                                                       </label>
                                                  </div>

                                                  <div class="form-check">
                                                       <input class="form-check-input" type="checkbox" name="fields[]"
                                                            value="college">
                                                       <label class="form-check-label">
                                                            College
                                                       </label>
                                                  </div>

                                                  <div class="form-check">
                                                       <input class="form-check-input" type="checkbox" name="fields[]"
                                                            value="github">
                                                       <label class="form-check-label">
                                                            GitHub Profile link
                                                       </label>
                                                  </div>

                                                  <div class="form-check">
                                                       <input class="form-check-input" type="checkbox" name="fields[]"
                                                            value="linkedin">
                                                       <label class="form-check-label">
                                                            LinkedIn Profile Link
                                                       </label>
                                                  </div>
                                             </td>
                                             <td></td>
                                        </tr>
                                        <tr>
                                             <td>
                                                  <input type="submit" style="margin-top: 1em;" name="submit"
                                                       class="btn btn-primary mb-2">
                                             </td>
                                             <td></td>
                                             <td></td>
                                        </tr>
                                   </form>
                              </table>
                              <script type="text/javascript">
                              function validate_form() {
                                   var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                                   var checkedOne = Array.prototype.slice.call(checkboxes).some(x => x.checked);

                                   var event_length = document.getElementById("input_name").value.replace(" ", "")
                                        .length;
                                   var name_row = document.getElementById("alert_name");
                                   if (!event_length) {
                                        name_row.style.backgroundColor = "rgba(255,0,0,0.1)";
                                   }
                                   var fields_row = document.getElementById("alert_fields");
                                   if (!checkedOne) {
                                        fields_row.style.backgroundColor = "rgba(255,0,0,0.1)";
                                   }

                                   return checkedOne;
                              }

                              function reverse_red(x) {
                                   var row = document.getElementById("alert_" + x);
                                   row.style.backgroundColor = "rgba(255,255,255,0)";
                              }
                              </script>
                         </div>
                    </div>
               </div>
               <br><br>
               <div class="card shadow">
                    <div class="card-header py-3">
                         <p class="text-primary m-0 font-weight-bold">Form Generation Log</p>
                    </div>
                    <div class="card-body">
                         <div class="table-responsive table mt-2" id="dataTable" role="grid"
                              aria-describedby="dataTable_info">
                              <table class="table dataTable my-0" id="dataTable">
                                   <tbody>
                                    <?php
									// Connecting to the database
									if (isset($_POST["submit"])){
										$event_name = $_POST["event_name"];
										$event_type=$_POST["event_type"];
										$fields=$_POST["fields"];

										$verify_name = preg_match ('/[^a-zA-Z-, ]/' , $event_name);
										if(!isset($_POST["number_participants"])){
											$number_participants=1;
										}else{
											$number_participants=$_POST["number_participants"];
										}

										$verify_fields =true;

										foreach($fields as $field){
											if(! in_array($field,$fields_array)){
												$verify_fields=false;
												break;
											}
										}
										//Values verification to prevent SQL injection
										if(in_array($event_type,$event_type_array) && $verify_fields && in_array($number_participants,$number_participants_array) && !$verify_name) {
											$form_file = $form_location.$event_name."-form.html";
											$file = fopen($form_file,"w");
											logActivity($_SESSION['uname'], 'Generated a form for ['. $event_name.']');
											$table_columns = "id int NOT NULL AUTO_INCREMENT PRIMARY KEY, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,";

											//Initial details of the HTML page
											$html_file = '<!DOCTYPE html>
														<html>
															<head>
																<title>'.ucwords($event_name).'</title>
																<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
																<link rel="icon" type="image/png" sizes="600x600" href="../../assets/img/Logo_White.png">
																<link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
																<link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
																<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
																<link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
																<link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">
																<link href="css/main.css" rel="stylesheet" media="all">
																<link href="css/custom.css" rel="stylesheet" media="all">
															</head>
														<body>
															<div class="page-wrapper bg-blue p-t-100 p-b-100 font-robo">
																<div class="wrapper wrapper--w680">
																	<div class="card card-1">
																		<div class="card-heading"></div>
																		<div class="card-body">
																			<h2 class="title">Registration for '.ucwords($event_name).'</h2>
																			<div id="event_description" class="desc" style="line-height: 1.6em;"></div><br>';
												//Form section starts here
												$html_file = $html_file.'<form action="../entry.php" method="post" onSubmit="return verify()" id="entry_form">';
												$html_file = $html_file.'<input type="hidden" name="event_name" value="'.$event_name.'">';
												if($event_type=="individual"){
													$html_file = $html_file.'<p id="participant_name" style="color:red;font-size:12px;" ></p>
													<div class="input-group">
													<input type="text" placeholder="Participant name" name="participant_name" class="input--style-1">
													</div>';
													$table_columns = $table_columns."participant_name VARCHAR(255),";
													foreach($fields as $selected){
														if($selected == "year"){
															$html_file = $html_file.'<p id="'.$selected.'" style="color:red;font-size:12px;" ></p>
															<div class="input-group"><div class="rs-select2 js-select-simple select--no-search"><select name="'.$selected.'" class="'.$selected.'"><option disabled="disabled" selected="selected" value="">'.ucwords($display_prompts[$selected]).'</option>'.$year_dropdown.'<div class="select-dropdown"></div></div></div>';
														}else if($selected == "dept"){
															$html_file=$html_file.'<p id="'.$selected.'" style="color:red;font-size:12px;" ></p><div class="input-group"><div class="rs-select2 js-select-simple select--no-search"><select name="'.$selected.'" class="'.$selected.'">
								<option disabled="disabled" selected="selected" value="">'.ucwords($display_prompts[$selected]).'</option>	'.$department_dropdown.'<div class="select-dropdown"></div></div></div>';
														}else{
															$html_file = $html_file.'<p id="'.$selected.'" style="color:red;font-size:12px;" ></p><div class="input-group">
														<input type="text" placeholder="'.ucwords($display_prompts[$selected]).'" name="'.$selected.'" class="input--style-1 '.$selected.'">
														</div>';
														}
														$table_columns = $table_columns.$selected." VARCHAR(255),";
													}
												}
												else{
													$number_participants = (int)$number_participants;
													for ($i=0;$i<$number_participants;$i++){
														$participant_number = $i+1;
														$html_file = $html_file.'<h3 class="title">Details for Participant - '.$participant_number.'</h3>';
														$html_file = $html_file.'
														<p id="participant_name'.$participant_number.'" style="color:red;font-size:12px;" ></p>
														<div class="input-group">
														<input type="text" placeholder="Name of Member '.$participant_number.'" name="participant_name'.$participant_number.'" class="input--style-1">
														</div>';
														$table_columns = $table_columns."participant_name".$participant_number." VARCHAR(255),";
														foreach($fields as $selected){
															if($selected=="year"){
																$html_file = $html_file.'<p id="'.$selected.$participant_number.'" style="color:red;font-size:12px;" ></p><div class="input-group"><div class="rs-select2 js-select-simple select--no-search"><select name="'.$selected.$participant_number.'"  class="'.$selected.'"><option disabled="disabled" selected="selected" value="">'.ucwords($display_prompts[$selected]).' of Member '.$participant_number. '</option>'.$year_dropdown.'<div class="select-dropdown"></div></div></div>';
															}else if($selected=='dept'){
																$html_file = $html_file.'<p id="'.$selected.$participant_number.'" style="color:red;font-size:12px;" ></p><div class="input-group"><div class="rs-select2 js-select-simple select--no-search"><select name="'.$selected.$participant_number.'"  class="'.$selected.'"><option disabled="disabled" selected="selected" value="">'.ucwords($display_prompts[$selected]).' of Member '.$participant_number. '</option>'.$department_dropdown.'<div class="select-dropdown"></div></div></div>';
															}
															else{$html_file = $html_file.' <p id="'.$selected.$participant_number.'" style="color:red;font-size:12px;" ></p><div class="input-group">
															<input type="text" placeholder="'.ucwords($display_prompts[$selected]).' of Member '.$participant_number.'" name="'.$selected.$participant_number.'" class="input--style-1 '.$selected.'">
															</div><br>';
															}
															$table_columns = $table_columns.$selected.$participant_number." VARCHAR(255),";
														}
														/*if(i==0){
															$html_file = $html_file.'</div></div>';
														}*/
													}
												}

												//table columns for the new table generated and query to create also generated
												$table_columns=substr($table_columns, 0, -1);
												$creation_query = "CREATE TABLE IF NOT EXISTS event_".str_replace(" ","_",$event_name)." (".$table_columns.");";
												$submit_stmt = $conn->prepare($creation_query);
											if (!$submit_stmt) {
												echo "Prepare failed: (" . $conn->errno . ") " . $conn->error . "<br>";
											}
											$submit_stmt->execute();
											echo ("<tr><td>Successfully created table in database for the new form</td></tr>");
												$markdown_text = preg_replace('/\r?\n|\r/', "<br>",$_POST["event_description"]);
												//Closing section
												$html_file = $html_file.'		<div class="p-t-20">
																				<input type="submit" class="btn btn--radius btn--green">
																			</div>
																		</form>
																	</div>
																</div>
															</div>
														</div>
														    <!-- Jquery JS-->
														    <script src="vendor/jquery/jquery.min.js"></script>
														    <!-- Vendor JS-->
														    <script src="vendor/select2/select2.min.js"></script>
														    <script src="vendor/datepicker/moment.min.js"></script>
														    <script src="vendor/datepicker/daterangepicker.js"></script>
														    <script src="js/validation.js"></script>
														    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
														    <script>
																document.getElementById("event_description").innerHTML =marked("'.$markdown_text.'");
															</script>

														    <!-- Main JS-->
														    <script src="js/global.js"></script>
													</body>
												</html>';

												fwrite($file, $html_file);
												fclose($file);
												echo "<tr><td>Successfully Form Created</td></tr>";
												echo "<tr><td><a target=\"_blank\" href='".$form_file."'>Click here to visit the form</a></td></tr>";

										}else{
											echo('<div class="alert alert-danger" role="alert" style="width:70%;margin-left:15%;margin-right:15%">
											Dangerous request to the server! Please try again!
											</div>');
										}
									}
								?>

                                   </tbody>
                              </table>
                         </div>
                    </div>
               </div>
               <br><br>
          </div>
     </div>
     <footer class="bg-white sticky-footer">
          <div class="container my-auto">
               <div class="text-center my-auto copyright"><span>SVCE ACM Student Chapter</span></div>
          </div>
     </footer>
     </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a></div>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
     <script src="../../assets/js/bs-init.js"></script>
     <script src="../../assets/js/theme.js"></script>
</body>

</html>
