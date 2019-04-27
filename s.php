<?php

try{
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=camagru", "root", "tiger");
    echo "connection successful";
}catch(PDOException $e){
    echo "connection failed" . $e->getMessage();
}

$status = "";

    $firstname = $_POST['firstname'];
    $lastname =  $_POST['lastname'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $email =  $_POST['email'];
    $username =  $_POST['username'];
    $password =  $_POST['password'];
    //$password2 =  $_POST['password2'];

    $sql ="INSERT INTO `users`(`id`, `firstname`, `lastname`, `birthdate`, `gender`, `email`, `username`, `password`) VALUES (:$firstname ,:$lastname, :$birthdate, :$gender, :$email, :$username, :$password)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute(array(":firstname" => $firstname, ":lastname" => $lastname, ":birthdate" => $birthdate, ":gender" => $gender, ":email" => $email, ":username" => $username,  ":password" => $password));
     if($test)
        echo "khdama";
?>