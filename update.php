<<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>找回密码</title>
<style type="text/css">
.error {color: #FF0000;}
.table1 {margin:0 auto;}
</style>
</head>
<body> 
<?php
// 定义变量并设置为空值
$nameErr =$password1Err =$password2Err=$emailErr=$message="";
$name =$password1=$password2=$email = "";

$problem=false;//没有问题

//表单格式验证
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
   if (empty($_POST["name"])) 
   {
     $nameErr ="姓名是必填的!";
	 $problem=true;
   } 
   else 
   {
     $name = test_input($_POST["name"]);
     // 检查姓名是否包含字母和空白字符
     if (!preg_match("/^[a-zA-Z ]*$/",$name)) 
	 {
       $nameErr = "只允许字母和空格！"; 
	   $problem=true;
     }
   }
   
   if (empty($_POST["email"])) 
   {
     $emailErr = "请输入注册时的邮箱!";
	 $problem=true;
   } 
   else 
   {
     $email = test_input($_POST["email"]);
     // 检查电子邮件地址语法是否有效
     if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) 
	 {
       $emailErr = "无效的email格式！"; 
	   $problem=true;
     }
   }
     
   if (empty($_POST["password1"])||empty($_POST["password2"])) 
   {
     $password1Err ="请输入新密码！";
	 $password2Err="确认输入的密码！";
	 $problem=true;
   } 
   else 
   {
	   $password1=$_POST["password1"];
	   $password2=$_POST["password2"];
	  if($password1!=$password2)
		{
		$password2Err="两次输入的密码不一致！";
		$problem=true;
	    }
		else
		{
		$password1 = test_input($password1);
		$password2 = test_input($password2);
		}
     // 检查 密码是否有效（密码只能是字母和数字）
     if (!preg_match("/^[a-z\d]{6,15}$/i",$password1)) 
	 {
       $password1Err = "密码是字母和数字的组合且长度>6！"; 
	   $problem=true;
     }
   }
   if(!$problem)
   {    //连接数据库
		include('conn.php');
		//定义查询并运行查询。用户名和邮箱是否一致！！
	$check_query = mysql_query("select name from s_userinfo where name='$name'and email='$email'");
	
	if ($row = mysql_fetch_array($check_query)) 
	{ 
	//  执行查询。
	    
		if($password1==$password2)
		{
			$update=mysql_query("update s_userinfo set password='$password2' where name='$name'");
			if(mysql_affected_rows())//验证是否修改成功成功
			{
				$message= '<p>密码修改成功请牢记！<br>点击此处重新 <a href="login.html">登录!</a> </p>';
	        }
			else
			{
				$message= '<p>密码修改失败！请重新尝试！ </p>';
			}
		}
		else
		{
			$message= '<p style="color: red;">两次输入的密码不一致！</p>';
		}
	} 
	else 
	{ // 信息不匹配
		$message= '<p style="color: red;">用户名和用户邮箱不匹配！</p>';
	}	
   } 
}
@mysql_close();//关闭数据库连接

function test_input($data) 
{//剔除输入的一些空格等
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>
<div class="table1">
<table border="0" align="center">
<tr><td></td>
	<th>找回密码</th>
</tr>
<tr><td></td>
	<td></td>
	<td><span class="error">* 必需的字段</span></td>
</tr>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" > 
   <tr>	<td align="right">用户名：</td>
		<td><input type="text" name="name"></td>
		<td><span class="error">* <?php echo $nameErr;?></span></td>
   </tr>
   <tr> <td align="right">电子邮件：</td>
		 <td><input type="text" name="email"></td>
		 <td><span class="error">* <?php echo $emailErr;?></span></td>
	</tr>

   <tr> <td align="right">输入新密码：</td>
		<td><input type="password" name="password1"></td>
		<td><span class="error">* <?php echo $password1Err;?></span></td>
	</tr> 

   <tr> <td align="right">确认新密码：</td>
		<td><input type="password" name="password2" /></td>
		<td><span class="error">* <?php echo $password2Err;?></span></td>
	</tr>

    

   <tr>
		<td></td>
		<td align="center">
		<input type="submit" name="submit" value="提交">
		</td> 
   </tr>
</form>
</table>
</div>
<b align="center"><?php echo $message;?></b>
</body>
</html>