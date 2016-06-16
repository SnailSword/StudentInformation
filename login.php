
<?php
header("Content-Type: text/html; charset=utf-8");//避免中文乱码.

//注销登录
if(@$_GET['action'] == "logout"){
    /* unset($_SESSION['userid']);
    unset($_SESSION['username']);  */
    echo '注销登录成功！点击此处 <a href="login.html">登录</a>';
    exit;
}
//登录
if(!isset($_POST['submit'])){
    exit('非法访问!');
}
$username = ($_POST['username']);//htmlspecialchars
$password = ($_POST['password']);//MD5

//包含数据库连接文件
include('conn.php');
//检测用户名及密码是否正确
$check_query = mysql_query("select id,name from userinfo where name='$username'and password='$password'limit 1");
if($result = mysql_fetch_array($check_query)){//返回true
    //登录成功
    session_start();
    $_SESSION['username'] =$result['name'];	
    $_SESSION['userid'] = $result['id'];
    
	//系统主页
    echo $username.' 欢迎你！进入交大测绘系学生信息系统！<script>window.location="my.html"</script>
	<br />';
	
	//预放置主页页面顶端用户中心与注销登录
	echo '点击此处进入 <a href="my.php">用户中心</a> <br />';//用户信息html页有待完善
    echo '点击此处 <a href="login.php?action=logout">注销</a> 登录！<br />';
    exit;
} 
else 
{
    exit('密码或用户名错误！点击此处 <a href="javascript:history.back(-1);">返回</a> 重试');
}
?>