<?php
# Powered By Invalid Team


$n = 20;


$inviter = $_GET['inviter'];
$accessKey = $_GET['authKey'];
$codefrom = $_GET['codefrom'];

$invitecode = bin2hex($inviter).bin2hex($codefrom).bin2hex(randString($n));


$filename = "invite.log";
$time = date('Y年m月d日G时i分', time());
$msg = "[" . $time . "] inviter: " . $inviter . " codefrom: " . $codefrom . " invitecode: " . $invitecode . "\n";
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
    
    $sql = $conn->prepare('SELECT * FROM `client_users` WHERE user = (?)');
    
    $sql->bind_param('s', $inviter);
    $sql->execute();
    $result = $sql->get_result();
    
     if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user = $row["user"];
            $isAdmin = $row["ADMIN"];
            $authKey = $row["authKey"];
            
        }
    } else {
        //Invalid User Name.
        echo "INVALID_USER";
        file_put_contents($filename, "Invalid User.\n", FILE_APPEND);
        return;
    }

    if($isAdmin == 1 && $accessKey == $authKey && $user == $inviter) {
        $authKey_sql = "INSERT INTO `register_db` (`invite`, `inviter`, `codefrom`, `VALID`) VALUES ('" . $invitecode . "', '" .$inviter."', '".$codefrom."', '1')";
        
        $conn->query($authKey_sql);
                    
        file_put_contents($filename, "success to generate invite code.\n", FILE_APPEND);
        echo $invitecode;
    } else {
        // Not Admin.
        echo "NO_ACCESS";
    }
    
    function randString($length) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
       
    

    $result = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $result .= $chars[rand(0, $max)];
    }
    return $result;
}
    

?>