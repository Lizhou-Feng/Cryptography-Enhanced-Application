<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>

<?php
session_start();
var_dump($_POST);



$username = $_POST['usrname'];
$password = $_POST['pass'];


$con = mysql_connect("localhost:3306", "root", "cuccs2015");
if (!$con) {
    die('连接失败 ' . mysql_error());
} else
{
    mysql_select_db("happy-data", $con);
    echo("链接数据库成功");



    $sql= "SELECT * from uesrs WHERE usr_name='$username'";
    var_dump($sql);
    $result=mysql_query($sql);

    $row=mysql_fetch_array($result, MYSQL_ASSOC);
    echo($row);echo "<br/>";
    if(!empty($row)) {
        echo("用户名存在 ");
        $hash = $row['pasaword'];

        if(password_verify($password,$hash)) {
            echo '验证成功!';
            $_SESSION['username']=$username;
            $_SESSION['password']=$password;
            echo "<script>alert('用户登录成功！');location='files.html';</script>";
        }else echo "<script>alert('密码错误，重新输入！');location='index.html';</script>";
    }echo "<script>alert('用户名不存在，重新输入！');location='index.html';</script>";
