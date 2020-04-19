<?php
include("header.php");
if(!empty($_POST)){
     $noPost=0;
     $OrignalURL=$_POST["OrignalURL"];
     $curl = curl_init();
     /* $shortcm_domain, $shortcm_authorization are to be initialised in secrets.php */
     if(!empty($_POST["URLPath"])) {
          $URLPath=$_POST["URLPath"];
          curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.short.cm/links",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode(array(
               'originalURL' => $OrignalURL,
               'domain' => $shortcm_domain,
               'path' => $URLPath
          )) ,
          CURLOPT_HTTPHEADER => array(
               "authorization: ".$shortcm_authorization,
               "content-type: application/json"
          ) ,
          ));
     }
     else {
          $URLPath=" ";
          curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.short.cm/links",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode(array(
               'originalURL' => $OrignalURL,
               'domain' => $shortcm_domain
          )) ,
          CURLOPT_HTTPHEADER => array(
               "authorization: ".$shortcm_authorization,
               "content-type: application/json"
          ) ,
          ));
     }
     $response = curl_exec($curl);
     $err = curl_error($curl);
     curl_close($curl);
     if ($err) {
          echo "cURL Error #:" . $err;
          $response="ERROR";
     }
     else {
          $obj=json_decode($response, true);
          $shortURL=$obj['shortURL'];
          logActivity($_SESSION['uname'], 'In Link-Short, [' . $OrignalURL . '] -> [' . $shortURL . '] shortend');
     }
}
else{
     $noPost=1;
}
?>
<head id="head_tag">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Link Shortener: SVCE-ACM CMS</title>
    <link rel="icon" type="image/png" sizes="600x600" href="../assets/img/Logo_White.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>

<body id="page-top">
    <div id="wrapper">
    <?php include("navigation.php"); ?>
    <div class="container-fluid">
			<div class="card shadow">
                    <div class="card-header py-3">
                        <p class="text-primary m-0 font-weight-bold">Link Shortner</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
					    <table class="table dataTable my-0" id="dataTable">
							<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" style="width: 60%;margin-left: 20%;margin-right: 20%" onSubmit="return validate_form();">
								<tr>
									<th>Enter the Original Link</th>
									<td><input class="form-control" type="url" name="OrignalURL" id="url" placeholder="https://example.com" pattern="https://.*" required/></td>
									<td></td>
								</tr>
                                        <tr>
									<th>Enter the Link Slug/Path (optional)</th>
									<td><input class="form-control" type="text" name="URLPath" placeholder="Examples: fipBM, Gd7Xk"/></td>
									<td></td>
								</tr>
                                        <tr>
                                              <td></td>
                                             <td>
                                                  <div class="link_copy">
                                                       <input class="form-control" style="background-color: #f8f9fc; float: left; width:90%;" type="text" value="<?php if($noPost==0){ echo $shortURL;} else { echo " "; }   ?>" id="shortURL_" readonly>
                                                       <a class="" onclick="copyToClipboard()"><i class="far fa-copy"></i></a>
                                                  </div>
                                             </td>
                                             <td></td>
                                        </tr>
                                        <tr>
									<td>
										<input type="submit" style="margin-top: 1em;" name="submit" class="btn btn-primary mb-2">
									</td>
									<td></td>
									<td></td>
								</tr>
							</form>
						</table>
					</div>
                    </div>
			</div>
		</div>
     </div>
     <div id="mail_shortened">

     </div>

     <footer class="bg-white sticky-footer">
          <div class="container my-auto">
               <div class="text-center my-auto copyright"><span>SVCE ACM Student Chapter</span></div>
          </div>
     </footer>
    <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
    <script src="../assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="../assets/js/theme.js"></script>
    <script>
          function copyToClipboard() {
               var copyText = document.getElementById("shortURL_");
               copyText.select();
               copyText.setSelectionRange(0, 99999); /*For mobile devices*/
               document.execCommand("copy");
               document.getElementById("mail_shortened").innerHTML=`<div class="alert alert-success" role="alert">
                         Link has been copied to your clipboard
                       </div>`;
          }
    </script>
</body>

</html>
