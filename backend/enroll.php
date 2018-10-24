<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>

<?php
header("content-Type:text/html;charset=utf-8");
var_dump($_POST);

    $username = $_POST['user'];
    $password = $_POST['pass'];
    $repassword = $_POST['repass'];

if ($password != $repassword) {
        echo "<script>alert('两次输入的密码不一致，请重新输入！');location='enroll.html';</script>";
    }else {

        $con = mysql_connect("localhost:3306", "root", "cuccs2015");
        mysql_set_charset("utf8".$con);
        if (!$con) {
            die('连接失败 ' . mysql_error());
        } else {
            mysql_select_db("happy-data", $con);
            echo("链接数据库成功");


            $sql = "select * from uesrs where usr_name = '" . $username . "'";
            mysql_query($sql);
            $num = mysql_affected_rows();
            echo("$num");
            if ($num > 0) {
                echo "<script>alert('此用户名已经被注册，请重新输入！');location='enroll.html';</script>";
            } else {
                //检测用户名合法性
                function check_name($playerName) {
                    $temp_len = (strlen ( $playerName ) + mb_strlen ( $playerName, 'utf-8' )) / 2;
                    if ($temp_len < 1 || $temp_len > 36) {
                        return 'error';
                            echo "<script>alert('长度不符，输入长度应在1～36');location='enroll.html';</script>";
                    } else {
                        $reg = '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/u';
                        if (preg_match ( $reg, $playerName )) {
                            return 'match';
                        } else {
                            return 'error_match';
                            echo "<script>alert('用户名应为中文、数字、英文字母的组合');location='enroll.html';</script>";

                        }
                    }
                }

                 check_name($username);

                $reg1 = '/^[a-zA-Z]+$/u';
                $reg2 ='/^[0-9]+$/u';
                if (preg_match ( $reg1, $password )==1) {
                    echo "<script>alert('口令不能为纯英文字母！');location='enroll.html';</script>";
                }
                if (preg_match ( $reg2, $password )==1) {
                    echo "<script>alert('口令不能为纯数字！');location='enroll.html';</script>";
                }

                $hash = password_hash("$password", PASSWORD_BCRYPT);
                echo $hash;  echo "<br>"; echo $username;  echo "<br>";   echo $password;
               // $insert = "INSERT INTO uesrs (usr_name,pasaword)VALUES ('$username','$hash')";
                //生成公私钥：
                $config = array(
                    "digest_alg" => "sha512",
                    "private_key_bits" => 1024,
                    "private_key_type" => OPENSSL_KEYTYPE_RSA,
                );

// Create the private and public key
                $res = openssl_pkey_new($config);

// Extract the private key from $res to $privKey
                openssl_pkey_export($res, $privKey);
                echo ($privKey);
// Extract the public key from $res to $pubKey
                $pubKey = openssl_pkey_get_details($res);
                $pubKey = $pubKey["key"];
                echo ($privKey);
                $data = 'plaintext data goes here';

// Encrypt the data to $encrypted using the public key
//openssl_public_encrypt($data, $encrypted, $pubKey);

// Decrypt the data using the private key and store the results in $decrypted
//openssl_private_decrypt($encrypted, $decrypted, $privKey);

//echo $decrypted;

                //把用户名，密码的哈希，公私钥存储到数据库：
                $insert = "INSERT INTO uesrs (usr_name,pasaword,pub_key,private_key)VALUES ('$username','$hash','$pubKey','$privKey')";
                mysql_query($insert);
                $num = mysql_affected_rows();
                if ($num > 0)
                {
                    echo "<script>alert('用户注册成功！');location='index.html';</script>";
                }
                else
                {
                    echo "<script>alert('用户注册失败！');location='enroll.html';</script>";
                }
            }//用户可用
        }//链接数据库成功
    }//两次密码相同
?>
