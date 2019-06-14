<?php
$action = isset($_GET["action"]) ? $_GET["action"]:"";
switch ($action){
    case "addnew":
    {
        include('PasswordManager.php');
        $pm = new PasswordManager();
        $validUser = false;
        $userName = isset($_POST["username"]) ? $_POST["username"]:"";
        $userPassword = isset($_POST["password"]) ? $_POST["password"]:"";
        if ($userName !== "" && $userPassword !== ""){
            $createStatus = $pm->createNewUser($userName, $userPassword);
            if ($createStatus == ""){
                echo "User created";
            }
            else{
                echo $createStatus;
            }
        }
    }break;
    case "validate":{
        include('PasswordManager.php');
        $pm = new PasswordManager();
        $userPassword = isset($_POST["password"]) ? $_POST["password"]:"";
        if ($userPassword !== ""){
            $message = $pm->validatePassword($userPassword);
            if ($message == ""){
                echo "password is correct!";                
            }
            else{
                echo $message;
            }
        }
        
    }break;
    case "login":{
        include('PasswordManager.php');
        $pm = new PasswordManager();
        $validUser = false;
        $userName = isset($_POST["username"]) ? $_POST["username"]:"";
        $userPassword = isset($_POST["password"]) ? $_POST["password"]:"";
        if ($userName !== "" && $userPassword !== ""){
            $loginStatus = $pm->login($userName, $userPassword);
            if ($loginStatus){
                echo "login successfull";
            }
            else{
                echo "login failure!";
            }
        }
    }break;

    case "changePassword":{
        include('PasswordManager.php');
        $pm = new PasswordManager();
        $validUser = false;
        $userName = isset($_POST["username"]) ? $_POST["username"]:"";
        $userPassword = isset($_POST["password"]) ? $_POST["password"]:"";
        $newUserPassword = isset($_POST["newPassword"]) ? $_POST["newPassword"]:"";
        if ($userName !== "" && $userPassword !== "" && $newUserPassword !== ""){
            $changeStatus = $pm->setNewPassword($userName, $userPassword, $newUserPassword);
            if ($changeStatus){                
                echo "Password has changed successfull!";                
            }
            else{
                echo "Error occurs, can not change password!";
            }
        }
    }break;
}
?>
<div>
            Create new user test:
            <form name="input" action="./testCase.php?action=addnew" method="post">
                <label for="username">Username:</label><input type="text" id="username" name="username" />
                <label for="password">Password:</label><input type="password" id="password" name="password" />                        
                <input type="submit" value="Create new User" name="sub" />                
            </form>
        </div>
        <div>
        Validate password test:
            <form name="input" action="./testCase.php?action=validate" method="post">                
                <label for="password">Password:</label><input type="password" id="password" name="password" />                        
                <input type="submit" value="Check password" name="sub" />                
            </form>
        </div>
        <div>
        Login test:
            <form name="input" action="./testCase.php?action=login" method="post">
                <label for="username">Username:</label><input type="text" id="username" name="username" />
                <label for="password">Password:</label><input type="password" id="password" name="password" />                        
                <input type="submit" value="test login" name="sub" />                
            </form>
            
        </div>
        <div>
        Change password test:
            <form name="input" action="./testCase.php?action=changePassword" method="post">
                <label for="username">Username:</label><input type="text" id="username" name="username" />
                <label for="password">Password:</label><input type="password" id="password" name="password" />                        
                <label for="newPassword">Password:</label><input type="password" id="newPassword" name="newPassword" />        
                <input type="submit" value="change password test" name="sub" />                
            </form>
        </div>