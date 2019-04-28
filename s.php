<?php
if(isset($_POST['submit']))
{
    try{
    
        $pdoConnect = new PDO ("mysql:host=127.0.0.1;dbname=camagru","root","tiger");
        //
            $firstname = $_POST['firstname'];
            $lastname =  $_POST['lastname'];
            $birthdate = $_POST['birthdate'];
            $gender = $_POST['gender'];
            $email =  $_POST['email'];
            $username =  $_POST['username'];
            $password1 =  $_POST['password1'];
            $password2 =  $_POST['password2'];
        //
        $pdoQuery = $sql = "INSERT INTO `utils`(`id`, `firstname`, `lastname`, `birthdate`, `gender`, `email`, `username`, `password`) VALUES (:firstname ,:lastname, :birthdate, :gender, :email, :username, :password)";
        $pdoResult = $pdoConnect->prepare($pdoQuery);
        $pdoExec = $pdoResult->execute(array(":firstname"=>$firstname, ":lastname"=>$lastname, ":birthdate"=>$birthdate, ":gender"=>$gender, ":email"=>$email, ":username"=>$username,  ":password"=>$password1));
        if($pdoExec){
            echo "data insert";
        }
        
    
    }
    catch(PDOException $e){
        echo "error".$e->getMessage();
    }
}
?>