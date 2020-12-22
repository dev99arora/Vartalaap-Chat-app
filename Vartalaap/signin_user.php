<?php
session_start();
require_once "Include/pdo.php";


    if(isset($_POST['sign_in'])){
        $email = htmlentities($_POST['sign_in']);
        $pass = htmlentities($_POST['pass']);

        $select_user = "select * from users where user_email='$email' AND user_pass='$pass'";

        $query = $pdo->query($select_user);
        $check_user = $query->rowCount();

        if($check_user == 1){
            $_SESSION['user_email']=$email;
            $update_msg = $pdo->query("UPDATE users SET login = 'Online' WHERE user_email='$email'");
            $user = $_SESSION['user_email'];
            $get_user = $pdo->prepare("SELECT * From users WHERE user_email='$user'");
            $get_user->execute();
            $row = $get_user->fetch(PDO::FETCH_ASSOC);

            $user_name = $row['user_name'];
            echo "<script>window.open('home.php?user_name=urlencode($user_name)', '_self')</script>";
        }
        else{
            echo"

            <div class='alert alert-danger'>
                <strong>Check your email and password.</strong>
            </div>
            ";
        }
    }
?>
