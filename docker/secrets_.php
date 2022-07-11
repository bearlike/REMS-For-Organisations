<?php
/* Secrets and enviroinment file for Docker instances. */

/* Sample Values Filled. You must alter to your preference, otherwise you 
    might have limited or no functionality */

$OrgName = 'SVCE-ACM';                  // Name of the organisation
$servername = getenv('MYSQL_HOST');     // (Not recommended to change) Name of server.
$username = getenv('MYSQL_USER');       // (Not recommended to change) User name for the database.
$password = getenv('MYSQL_PASSWORD');   // (Not recommended to change) Password for the database.
$MainDB = 'db_cms';                     // (Not recommended to change) Name of the main database where all CMS information is stored.
$formDB = "db_forms";                   // (Not recommended to change) Name of the database for recording generated form details.
$mailerDB = "db_mailer";                // (Not recommended to change) Name of the mailer database.
$startPath = '';                        // (Not recommended to change) Root of the platform.

/* Mailer Details */
$mailerHostname = "sample.host.name";           // SMTP Hostname
$mailerUname = "sample_mail@sample.host.name";  // SMTP email username
$mailerPassword = "sample_mail_password";       // SMTP email password

/* Default mail template details */
$buttonLabel = "Click here";                // (Recommended) Label that is present in button
$buttonURL = "https://sample.org/";         // URL that button directs too
$logoURL = "https://sample.org/logo.png";   // URL for organisation logo in mail
$coverURL = "https://sample.org/cover.jpg"; // URL for organisation cover page in mail

/* Forgot Password section details */
$hostName = "localhost/domain/";                        // Host name for local testing
// $hostName = "sample.host.name/";                     // Host name for deployment
$forgotPwdExtension = "members/change-password.php";    //Path to change password page

/* Forgot Password Mail Template details */
$reachEmail = 'sample@sample.host.name';    // contact mail-id present in the forgot password email
$darkLogo = 'https://sample.logo/path';     // Path for organisation logo in forgot password mail
$logoHREF = "https://sample.org";           // Website that clicking on organisation logo leads to

/* API Keys */
$shortcm_authorization = "ABCDEFABCDEFABCDEFABCDEF";    // Visit https://short.io/features/api for more details
$shortcm_domain = "sample.domain";                      // Domain for generating the shortened URL
