<?php
session_start();
$time = $_SERVER['REQUEST_TIME'];

$timeout_duration = 300;

if (isset($_SESSION['LAST_ACTIVITY']) && 
   ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: ./login.php");
    die();
}
$_SESSION['LAST_ACTIVITY'] = $time;
$errorMsg = "";
if(isset($_POST["sub"])) {
  include('PasswordManager.php');
  $pm = new PasswordManager();
  $validUser = false;
  $userName = isset($_POST["username"]) ? $_POST["username"]:"";
  $userPassword = isset($_POST["password"]) ? $_POST["password"]:"";
  $newUserPassword = isset($_POST["newPassword"]) ? $_POST["newPassword"]:"";
  if ($userName !== "" && $userPassword !== "" && $newUserPassword !== ""){
      $changeStatus = $pm->setNewPassword($userName, $userPassword, $newUserPassword);
      if ($changeStatus){
        session_unset();
        session_destroy();
        echo "Password has changed, click the link below to go to the login page!";
        echo "<a href='./login.php'>Login page</a>";
        die();
      }
      else{
          echo "Error occurs, can not change password!";
      }
  }
}
else if(isset($_POST["cancel"])) {
    header("Location: ./login.php");
    die();
}

?>
    <!DOCTYPE html>
    <html>
    <head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title>Login</title>
    </head>
    <body>
    <form name="input" action="" method="post">
        <label for="username">Username:</label><input type="text" id="username" name="username" />
        <label for="password">Password:</label><input type="password" id="password" name="password" />        
        <label for="newPassword">Password:</label><input type="password" id="newPassword" name="newPassword" />        
        <div class="error"><?= $errorMsg ?></div>
        <input type="submit" value="Change" name="sub" />
        <input type="submit" value="Cancel" name="cancel" />
        
    </form>
    </body>
    </html>

    <?php
?>