<?php
# Powered By Invalid Team



$invitecode = $_GET['invitecode'];
$user = $_GET['user'];
$pass = $_GET['pass'];




$filename = "register.log";
$time = date('Y年m月d日G时i分', time());
$msg = "[" . $time . "] user: " . $user . " password: " . $pass . " invitecode: " . $invitecode . "\n";
file_put_contents($filename, $msg , FILE_APPEND);


  $servername = "";
  $username = "";
  $password = "";
  $dbname = "";
  
   $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        file_put_contents($filename, "Failed to Connect DataBase Server , Reason:" . $conn->connect_error . "\n", FILE_APPEND);
    }
    
    $sql = $conn->prepare('SELECT * FROM `register_db` WHERE invite = (?)');
    
    $sql->bind_param('s', $invitecode);
    $sql->execute();
    $result = $sql->get_result();
    
     if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $invite = $row["invite"];
            $inviter = $row["inviter"];
            $Isvalid = $row["VALID"];
            
        }
    } else {
        echo "INVALID_INVITECODE";
        file_put_contents($filename, "Invalid InviteCode.\n", FILE_APPEND);
        return;
    }
    
    $sql_auth = $conn->prepare('SELECT * FROM `client_users` WHERE invite = (?)');
    
    $sql_auth->bind_param('s', $invitecode);
    $sql_auth->execute();
    $result_auth = $sql_auth->get_result();
    
    if($result_auth-> num_rows > 0) {
         while ($row = $result->fetch_assoc()) {
            $user_autht = $row["user"];
            $invite_autht = $row["invite"];
            
            
        }
    }
    
    if($user != null && $pass != null && $invitecode != null && $invitecode == $invite && $Isvalid == 1 && $user != $user_autht && $invitecode != $invite_autht) {
        $reg_sql = "INSERT INTO `client_users` (`user`, `pass`, `hwid`, `authKey`, `invite`, `inviter`, `SUBTIME`, `ADMIN`, `BANNED`) VALUES ('" . $user ."', '".$pass."', '', '', '" .$invitecode. "', '".$inviter."', '2077-12-12 08:15:40.000000', '0', '0')";
        
        $conn->query($reg_sql);
        
        $update_sql = "UPDATE `register_db` SET `VALID`='0' WHERE invite = '". $invitecode ."'";
        
        $conn->query($update_sql);
        
        
                    
        file_put_contents($filename, "New Member Register.\n", FILE_APPEND);
        echo "SUCCESS_REGISTER";
        
        
    } else {
        echo "ERROR_REGISTER";
    }
    
    
    

?>