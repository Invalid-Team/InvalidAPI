<?php
# Powered By Invalid Team

# invalid user name -> INVALID_USER
# error pwd -> ERROR_PASSWORD
# hwid err -> INVALID_HWID
# success login -> LOGIN_SUCCESS
# sub outdate -> OUTDATE_SUB
# got banned -> BANNED_USER



$user = $_GET['user'];
$pass = $_GET['pass'];
$hwid = $_GET['hwid'];
$SafeKey = $_GET['authKey'];



$filename = "login.log";
$time = date('Y年m月d日G时i分', time());
$msg = "[" . $time . "] User: " . $user . " Password: " . $pass . " HWID: " . $hwid . "\n";
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
    $sql->bind_param('s', $user);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $pass_hash = $row["pass"];
            $hwid_data = $row["hwid"];
            $expire = $row["SUBTIME"];
            $suspend = $row["BANNED"];
            $authKey = $row["authKey"];
            
        }
    } else {

        echo "INVALID_USER";
        file_put_contents($filename, "Invalid User.\n", FILE_APPEND);
        return;
    }

    if ($pass == $pass_hash) {

        if ($suspend == 0) {

            if ($expire < time()) {
                
                
                if($authKey == null & !$SafeKey == null) {
                    $authKey_sql = "UPDATE `client_users` SET `authKey` = '" . $SafeKey . "' WHERE `client_users`.`user` = '" . $user . "';";
                    $conn->query($authKey_sql);
                    
                    file_put_contents($filename, "upload authKey.\n", FILE_APPEND);
                } 
                
                if ($hwid_data == null) {

                    $hwid_sql = "UPDATE `client_users` SET `hwid` = '" . $hwid . "' WHERE `client_users`.`user` = '" . $user . "';";
                    $conn->query($hwid_sql);
                   
                    echo 'LOGIN_SUCCESS';
                    file_put_contents($filename, "Login Success.\n", FILE_APPEND);
                    return;
                } else {

                    if ($hwid === $hwid_data) {
                        echo 'LOGIN_SUCCESS';
                        file_put_contents($filename, "Login Success\n", FILE_APPEND);
                        return;
                    }

                    else {
                        echo 'INVALID_HWID';
                        file_put_contents($filename, "HWID Error\n", FILE_APPEND);
                        return;
                    }
                }
            }

            else {
                echo "OUTDATE_SUB";
                file_put_contents($filename, "Subscription Outdate\n", FILE_APPEND);
                return;
            }
        }

        else {
            echo "BANNED_USER";
            file_put_contents($filename, "Banned User.\n", FILE_APPEND);
            return;
        }
    }

    else {
        echo 'ERROR_PASSWORD';
        file_put_contents($filename, "Pwd Error\n", FILE_APPEND);
        return;
    }


    $conn->close();

?>
