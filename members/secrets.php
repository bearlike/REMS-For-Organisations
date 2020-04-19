
<!-- Fill in all these details to get your system up and running. Rename this file to secrets.php -->

<?php
     /* Sample Values Filled */
     $servername = 'localhost'; //Name of server
     $username = 'root'; // User name for the database
     $password = ''; //Password for the database
     $dbname = 'svcehost_cms'; //Name of the main database where all CMS information is stored (login, certificates, event information)
     $formDB="svcehost_forms"; //Name of the database for recording generated form details
     $startPath='/cms'; //Leave it blank if the files are in the root location of your web server else fill in with the location
     /* API Keys */
     $shortcm_authorization = "ABCDEFABCDEFABCDEFABCDEF"; //Visit https://short.io/features/api for more details
?>
