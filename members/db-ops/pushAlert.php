<?php
     include("../header.php");
     try {
          $conn = new PDO('mysql:dbname=' . $MainDB . ';host=' . $servername . ';charset=utf8', $username, $password);
     } catch (PDOException $e) {
          $message = $e->getMessage();
          header('Location: ../pages/error.php?error=Cannot connect to the server/database');
          die();
     }
     $alertSQL = 'INSERT INTO `notification` (`user`, `message`, `type`, `clickURL`) VALUES (:user, :message, :type, :clickURL)';
     // echo $logSQL; // For Debugging
     $alertPrep = $conn->prepare($alertSQL);
     if ($alertPrep == TRUE) {
          $alertPrep->bindValue(":user", $_SESSION['uname']);
          $alertPrep->bindValue(":message", $_POST["alertMessage"]);
          $alertPrep->bindValue(":type", $_POST["type"]);
          $alertPrep->bindValue(":clickURL", "#");
          $alertPrep->execute();
          header('Location: ../dashboard.php?alPush=Success');
     } else {
          header('Location: ../member-login.php');
     }
?>