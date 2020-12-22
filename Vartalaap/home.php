<?php
    session_start();
    require_once "Include/pdo.php";
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Vartalaap-Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container main-section">
        <div class="row">
            <div class = "col-md-3 col-sm-3 col-xs-12 left-sidebar">
                <div class="input-group searchbox">
                    <div class="input-group-btn">
                        <center><a href="include/find_friends.php">
                            <button class = "btn btn-default searc-icon" name="search_user" type="submit">Add new User</button></a></center>
                        </div>
                    </div>
                    <div class="left-chat">
                        <ul>
                            <?php include("include/get_users_dta.php"); ?>
                        </ul>
                    </div>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-12 right-sidebar">
                    <div class="row">
                        <!-- getting the user information who is logged in -->
                        <?php
                            $user = $_SESSION['user_email'];
                            $get_user = "select* from users where user_email='$user'";
                            $run_user = $pdo->prepare($get_user);
                            $run_user->execute();
                            $row = $run_user->fetch(PDO::FETCH_ASSOC);

                            $user_id = $row['user_id'];
                            $user_name = $row['user_name'];
                        ?>

                        <!-- getting user data on which user clicks -->
                        <?php
                            if(isset($GET['user_name'])){

                                $get_username = $_GET['user_name'];
                                $get_user = "select * from users where user_name='$get_username'";

                                $run_user = $pdo->prepare($get_user);
                                $run_user->execute();
                                $row_user = $run_user->fetch(PDO::FETCH_ASSOC);
                                $username = $row_user['user_name'];
                                $user_profile_image = $row_user['user_profile'];
                            }

                            $total_messages = "select * from users_chats where (sender_username='$user_name' AND reciever_username='$username') OR (reciever_username='$user_name'
                            AND sender_username='$username')";
                            $run_messages = $pdo->query($total_messages);
                            $total = $run_messages->rowCount();
                            ?>

                            <div class="col-md-12 right-header">
                                <div class="right-header-img">
                                    <img src="<?php echo"$user_profile_image"; ?>">
                                </div>
                                <div class="right-header-detail">
                                    <form method="post">
                                        <p><?php echo "$username"; ?></p>
                                        <span><?php echo $total; ?> messages</span>&nbsp &nbsp
                                        <button name="logout" class="btn btn-danger">Logout</button>
                                    </form>
                                    <?php
                                        if(isset($_POST['logout'])){
                                            $update_msg = $pdo->query("UPDATE users SET log_in='Offline' WHERE user_name='$user_name'");
                                            header("Location:logout.php");
                                            exit();
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class = "row">
                            <div id="scrolling_to_bottom" class="col-md-12 right-header-contentChat">
                                <?php

                                    $update_msg = $pdo->query("UPDATE users_chats SET msg_status='read'
                                         WHERE sender_username='$username' AND reciever_username='$user_name'");

                                     $sel_msg = "select * from users_chats where (sender_username='$user_name'
                                     AND reciever_username='$username')OR (reciever_username='$user_name' AND sender_username='$username')
                                     ORDER by 1 ASC";
                                     $run_msg = $pdo->prepare($sel_msg);
                                     $run_msg->execute();

                                     while($row = $run_msg->fetch(PDO::FETCH_ASSOC)) {
                                         $sender_username = $row['sender_username'];
                                         $reciever_username = $row['reciever_username'];
                                         $msg_content = $row['msg_content'];
                                         $msg_date = $row['msg_date'];

                                 ?>
                                 <ul>
                                     <?php
                                        if($user_name == $sender_username AND $username == $reciever_username){
                                            echo"
                                                <li>
                                                    <div class='rightside-chat'>
                                                        <span> $username <small>$msg_date</small></span>
                                                        <p>$msg_date</p>
                                                    </div>
                                                </li>
                                            ";
                                        }
                                        else if($user_name == $reciever_username AND $username == $sender_username){
                                            echo"
                                                <li>
                                                    <div class='rightside-chat'>
                                                        <span> $username <small>$msg_date</small></span>
                                                        <p>$msg_date</p>
                                                    </div>
                                                </li>
                                            ";
                                        }
                                      ?>
                                  </ul>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 right-chat-textbox">
                            <form method="post">
                                <input autocomplete="off" type="text" name="msg_content" placeholder="Type your message">
                                <button class="btn" name="submit"><i class="fa fa-telegram" aria-hidden="true"></i></button>
                            </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php

        if(isset($_POST['submit'])){
            $msg = htmlentities($_POST['msg_content']);
            if($msg == ""){
                echo"
                    <div class='alert alert-danger'>
                        <strong><center>Message was unable to send</center></strong>
                    </div>
                    ";
            }
            else if(strlen($msg) >100){
                echo"
                <div class='alert alert-danger'>
                    <strong><center>Message is too long. Use only 100 characters</center></strong>
                </div>
                ";
            }
            else{
                $insert = "insert into users_chats(sender_username, reciever_username, msg_content, msg_status, msg_date) values('$user_name','$username','$msg','unread',NOW())";
                $run_insert = $pdo->prepare($insert);
                $run_insert->execute();

            }
        }
     ?>
</body>
</html>
