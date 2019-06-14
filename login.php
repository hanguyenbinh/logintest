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
$validUser = isset($_SESSION["login"]) && $_SESSION["login"] == true;
$userCreated = false;
if(isset($_POST["sub"])) {
  include('PasswordManager.php');
  $pm = new PasswordManager();
  $validUser = false;
  $userName = isset($_POST["username"]) ? $_POST["username"]:"";
  $userPassword = isset($_POST["password"]) ? $_POST["password"]:"";
  if ($userName !== "" && $userPassword !== ""){
      $loginStatus = $pm->login($userName, $userPassword);
      if ($loginStatus){
          $validUser = true;
          $_SESSION["login"] = true;
      }
      else{
          echo "User Name or Password not correct!";
      }
  }
}
else if(isset($_POST["register"])) {
    include('PasswordManager.php');
    $pm = new PasswordManager();
    $validUser = false;
    $userName = isset($_POST["username"]) ? $_POST["username"]:"";
    $userPassword = isset($_POST["password"]) ? $_POST["password"]:"";
    if ($userName !== "" && $userPassword !== ""){
        $createStatus = $pm->createNewUser($userName, $userPassword);
        if ($createStatus == ""){
            $userCreated = true;
        }
        else{
            $errorMsg = $createStatus;
        }
    }
}
if($validUser) {
   echo "Login Success!";
   echo "<a href='./logout.php'>Logout</a>";
   echo "<a href='./changePassword.php'>Change Password</a>";
}
else if ($userCreated){
    echo "New user has been created succesfull!";
    echo "Click on the link below to return to login page!";
    echo "<a href='./login.php'>Login page</a>";
}
else{
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
        <div class="error"><?= $errorMsg ?></div>
        <input type="submit" value="Login" name="sub" />
        <input type="submit" value="Register" name="register" />
    </form>
    </body>
    </html>

    <?php
}
?>