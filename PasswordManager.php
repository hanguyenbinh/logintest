<?php
class  PasswordManager{
    public $name = "";
    public $password = "";
    private $passwordFile;
    private $passwordFileName = "password.txt";
    function __construct() {
    }
    protected function encrypt($str):string{
        return md5($str);
    }
    protected function verifyPassword($str):bool{
        $vPass = md5($str);
        return strcmp($vPass, $this->password) == 0;
    }

    protected function saveToFile($name, $password):bool{
        try{
            $this->passwordFile = fopen($this->passwordFileName, "a");
            $jsonObj = (object)[];
            $jsonObj->name = $name;
            $jsonObj->password = $password;
            $content = json_encode($jsonObj);
            fwrite($this->passwordFile, $content.PHP_EOL);
            fclose($this->passwordFile);
            return true;
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    protected function findFromFile($name, $password):bool{
        $result = false;
        try{
            $this->passwordFile = fopen($this->passwordFileName, "c+");                     
            while(! feof($this->passwordFile))  {
                $content = fgets($this->passwordFile);
                if (strlen($content)>0){
                    $jsonObj = json_decode($content);
                    if (strcmp($name, $jsonObj->name) == 0 && strcmp($password, $jsonObj->password) == 0){
                        $result = true;
                        break;
                    }
                }
            }
            fclose($this->passwordFile);
            
        }
        catch(Exception $e){
            echo $e->getMessage();          
        }
        return $result;
    }
    protected function getUserList(){
        $result = [];
        try{
            $this->passwordFile = fopen($this->passwordFileName, "c+");                     
            while(! feof($this->passwordFile))  {
                $content = fgets($this->passwordFile);
                if (strlen($content) > 5){ //the line may be containt end line or new line characters, but the valid line length always > 5
                    $jsonObj = json_decode($content);
                    array_push($result, $jsonObj);
                }                
            }
            fclose($this->passwordFile);
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }
    protected function updateUserList($userList):bool{
        $result = false;
        try{
            $this->passwordFile = fopen($this->passwordFileName, "w");
            $content = '';
            foreach ($userList as $key => $value) {
                //fwrite($this->passwordFile, json_encode($value).PHP_EOL);
                $content.=json_encode($value)."\r\n";
            }
            fwrite($this->passwordFile, $content);
            fclose($this->passwordFile);
            $result = true;
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }
    //public
    public function createNewUser($name, $password):string{
        $result = "";
        if ($this->findFromFile($name, md5($password))) $result = "User Name is already exist!";
        else {
            $result = $this->validatePassword($password);
            if ($result == ""){
                $this->name = $name;
                $this->password = md5($password);
                $this->saveToFile($name, md5($password));
            }
        }        
        return $result;
    }
    public function login($name, $password):bool{
        $result = false;
        if ($this->findFromFile($name, md5($password))) {
            $result = true;
            $this->name = $name;
            $this->password = md5($password);
        }
        return $result;
    }

    public function setNewPassword($name, $password, $newPassword):bool{        
        $result = false;
        //echo $this->validatePassword($newPassword);
        if ($this->validatePassword($newPassword) == ""){         
            $userList = $this->getUserList();
            for($i = 0; $i < count($userList); $i++){
                if (strcmp($userList[$i]->name,$name) == 0 && strcmp($userList[$i]->password, md5($password)) == 0){
                    $userList[$i]->name = $name;
                    $userList[$i]->password = md5($newPassword);
                    $result = $this->updateUserList($userList);
                    break;
                }
            }            
        }
        return $result;
    }
    public function setInfo($name, $password):string{
        $result = $this->validatePassword($password);
        if ($result == ""){
            $this->name = $name;
            $this->password = md5($password);
            return "";
        }
        else return $result;

    }
    public function validatePassword($str):string{

        if (strlen($str)<6){
            return "password must be at least 6 characters";
        }
        else if (preg_match('/\s/', $str)){
            return "password must not contain any whitespace";
        }
        else if (preg_match("/[A-Z]/", $str) == 0){
            return "password must contain at least one uppercase letter";
        }
        else if (preg_match("/[a-z]/", $str)==0){
            return "password must contain at least one lowercase letter";
        }
        else if (preg_match("/[0-9]/", $str)==0){
            return "password must contain at least one one digit";
        }
        else if (preg_match('/^[A-Za-z0-9]/', $str)==0){
            return "password must contain at least one symbol";
        }
        return "";
    }
}
?>