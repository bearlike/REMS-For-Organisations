<?php
/*
* Username and Password Validator
* For CMS For Organisations
*/
session_start();
if ((isset($_COOKIE["username"])) && (isset($_COOKIE["password"]))) {
	$_POST['uname'] = $_COOKIE['username'];
	$_POST['password'] = $_COOKIE['password'];
	$_POST['remember'] = 1;
	$_POST['ipadd'] = "Last Used Browser";
}
if (!empty($_SESSION)) {
	if ($_SESSION['remember'] == 0) {
		session_destroy();
		//echo "no remember";
		setcookie("username", "", time() - 3600);	// forget cookie
		setcookie("password", "", time() - 3600);	// forget cookie
		header('Location: member-login.php');
	}
	$_POST['uname'] = $_SESSION['uname'];
	$_POST['password'] = $_SESSION['password'];
	$_POST['remember'] = $_SESSION['remember'];
}
if (!empty($_POST)) {
	include("secrets_.php"); // Include Secret Keys such as APIs and override default database credentials
	try {
		$conn = new PDO('mysql:dbname=' . $MainDB . ';host=' . $servername . ';charset=utf8', $username, $password);

		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		$message = $e->getMessage();
		echo $message;
		//header('Location:../public/error.html');
		die();
	}

	//$check_sql = 'CALL CheckUser("' . $_POST['uname'] . '","' . $_POST['password'] . '");';	// Call Procedures
	$check_sql = $conn->prepare('CALL CheckUser(:uname,:password)');
	$check_sql->bindValue(":uname", $_POST['uname']);
	$check_sql->bindValue(":password", $_POST['password']);

	$check_sql->execute();
	$result = $check_sql->fetchAll(PDO::FETCH_ASSOC);
	$check_sql->closeCursor();

	foreach ($result as $row) {
		if ($row["code"] > 0)
			$flag = true;
		else
			$flag = false;
	}

	if ($flag == false) {
		$conn = null;
		//echo "Unsuccessful";
		session_destroy();
		setcookie("username", "", time() - 3600);	// forget cookie
		setcookie("password", "", time() - 3600);	// forget cookie
		header('Location: member-login.php');
	} else {
		$_SESSION['uname'] = $_POST['uname'];
		$_SESSION['password'] = $_POST['password'];
		if (empty(($_POST['remember']))) {
			$_POST['remember'] = 0;
		}
		if ($_POST['remember'] == 1) {
			$_SESSION['remember'] = 1;
			setcookie("username", $_POST['uname'], time() + 30 * 24 * 60 * 60);	// start cookie, expire in 30 days
			setcookie("password", $_POST['password'], time() + 30 * 24 * 60 * 60);	// start cookie, expire in 30 days
			//echo "remembered";
		} else {
			$_SESSION['remember'] = 0;
			//echo "not remembered";
		}
		date_default_timezone_set('Asia/Kolkata');
		$_SESSION['loginTime'] = date("Y-m-d H:i:s", time());

		$conn = new PDO('mysql:dbname=' . $MainDB . ';host=' . $servername . ';charset=utf8', $username, $password);
		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$Log = "Logged in Successfully from " . $_POST['ipadd'];
		$LogSQL = $conn->prepare('CALL enterLog(:uname,:log);');
		$LogSQL->bindValue(":uname", $_POST['uname']);
		$LogSQL->bindValue(":log", $Log);

		if ($LogSQL->execute()) {
			//echo "New record created successfully";
		} else {
			//echo "Error: " . $conn->error;
			header('Location: member-login.php');
		}
		header('Location: dashboard.php');
	}
} else {
	header('Location: member-login.php');
}
