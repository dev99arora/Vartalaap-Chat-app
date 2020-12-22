<?php
    require_once "pdo.php";

        $user = "selct *from users";

        $run_user = $pdo->prepare($user);
        $run_user->execute();
        while($row_user=$run_user->fetch(PDO::FETCH_ASSOC)){
            $user_id = $row_user['user_id'];
            $user_name = $row_user['user_name'];
            $user_profile = $row_user['user_profile'];
            $login = $row_user['login'];


        }
 ?>
