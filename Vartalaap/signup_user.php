<?php
session_start();
require_once "Include/pdo.php";

    if(isset($_POST['sign_up'])){
        $name = htmlentities($_POST['user_name']);
        $pass = htmlentities($_POST['user_pass']);
        $email = htmlentities($_POST['user_email']);
        $rand = rand(1,2); //Dont know about this right now.

        //Checking if data entries are sufficient.
        if(strlen($name)<1){
            echo"<script>alert('We can not verify your name')</script>";
        }
        if(strlen($pass)<8){
            echo"<script>alert('Password should be minimum 8 characters!')</script>";
            exit();   //Terminate the script after printing the message.
        }
        //Now checking whether the account already exists.
        $check_email = "select*from users where email='$email'";
        $run_email = $pdo->query($check_email);
        $value = $run_email->rowCount();
        if($value == 1){
            echo"<script>alert('Email already exists, Please try again!')</script>";
            echo"<script>window.open('signup.php', '_self')</script>";
            exit();
        }
            //??Assign a default random profile pic here. (Maybe)

            $insert = "insert into users (username, email, password) values('$name','$email','$pass')"; //??Add a hash value for a password.
            $quer = $pdo->query($insert);

            if($quer){
                echo"<script>alert('Congratulations $name, your account has been created successfully')</script>";
                echo"<script>window.open('signin.php', '_self')</script>";
            }
            else{
                echo"<script>alert('Registration failed, try again!')</script>";
                echo"<script>window.open('signup.php', '_self')</script>";
            }
        }

 ?>
