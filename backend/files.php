<?php
/*
 Created by PhpStorm.
 * User: HP
 * Date: 2015/7/18
 * Time: 19:02
 */
session_start();
var_dump($_FILES);


if ((($_FILES["file"]["type"] == "image/gif")
        || ($_FILES["file"]["type"] == "image/jpeg")
        || ($_FILES["file"]["type"] == "image/pjpeg")
        ||($_FILES["file"]["type"] == "application/msword")
        ||($_FILES["file"]["type"] == "text/plain")
        ||($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"))
    && ($_FILES["file"]["size"] < 20000))
{
    if ($_FILES["file"]["error"] > 0)
    {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
    else
    {
        echo "<br/>";
        echo "Upload: " . $_FILES["file"]["name"] . "<br />";
        echo "Type: " . $_FILES["file"]["type"] . "<br />";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

        if (file_exists( $destination. $_FILES["file"]["name"]))
        {
            echo $_FILES["file"]["name"] . " already exists. ";
        }
        else
        {
            //$destination ='/media/sf_mima/happya/file/';
            $filename=$_FILES["file"]["name"];
            $destination="/media/sf_mima/happya/file/". $filename;

            mt_srand(1402929682);
            for ($i=1; $i < 25; $i++) {
                $salt = mt_rand();
                PHP_EOL;
            }
            //$password=$_SESSION['password'];
            $key = openssl_random_pseudo_bytes(8,$salt);
            $key=based64_encode($key_);
                //openssl_pbkdf2($password,$salt,8);
            //生成对称密钥


           //$handle=fopen()

            $data   =file_get_contents($destination);

            if(!function_exists("hex2bin")) { // PHP 5.4起引入的hex2bin
                function hex2bin($data) {
                    return pack("H*", $data);
                }
            }

            $td = mcrypt_module_open('tripledes', '', 'cbc', '');
            $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);

            mcrypt_generic_init($td, $key, $iv);
            $encrypted_data1 = mcrypt_generic($td, $data);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
            print_r(bin2hex($iv)."\n");
            print_r(bin2hex($encrypted_data1)."\n");


            move_uploaded_file($_FILES["file"]["tmp_name"], $destination . $encrypted_data1);
            echo "Stored in: "  .   $destination . $encrypted_data1;
            echo "<br/>";
            echo "$encrypted_data1";
            //string hash_file ( string $algo , string $filename [, bool $raw_output = false ] )
            //$file_path=$destination . $_FILES["file"]["name"];
            echo hash_file('sha256',$destination . $_FILES["file"]["name"]);
        }
    }
}
else{echo "<script>alert('文件格式不正确！');location='files.html';</script>";}





//


$filenam_download=$_FILES["downfile"]["name"];

function decryfile($filename)
{
    if(!function_exists("hex2bin")) { // PHP 5.4起引入的hex2bin
    function hex2bin($data) {
        return pack("H*", $data);
    }
}
    $iv ="SELECT iv from filestable where ori_name='$filename'";
    $key="SELECT enckey from filestable where ori_name='$filename'";
    $td = mcrypt_module_open('tripledes', '', 'cbc', '');
    mcrypt_generic_init($td, $key, $iv);
    $decrypted_data = mdecrypt_generic($td, $filename);
    file_put_contents($filename,$decrypted_data);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    print_r($decrypted_data."\n");
}

//对称解密


//文件下载

function downfile($filename)
{  //$filename=realpath(""); //

    Header( "Content-type:  application/octet-stream ");
    Header( "Accept-Ranges:  bytes ");
    Header( "Accept-Length: " .filesize($filename));
    header( 'Content-Disposition:  attachment;  filename= "$filename"');
    echo file_get_contents($filename);
    readfile($filename);  }

downfile($filenam_download);
//


// 数字签名认证

$public_key= "SELECT pub_key from filesname where ori_name='$filenam_download'";
$signer="SELECT sign from filesname where ori_name='$filenam_download'";
//$result = openssl_public_decrypt(hex2bin($cipher_text_hex), $message, $priv_key);


//var_dump($result);
//var_dump($message);

$result = openssl_private_decrypt(file_get_contents("$destination.filenam_download"),  $signer, $public_key);
var_dump($result);

if($result==TRUE)
{ echo"<script>alert('认证成功，开始下载文件！')";
    downfile($filenam_download);
    decryfile($filename_download);}
else
{echo"<script>alert('认证失败！不能下载！');location='files.html';</script>";}

//