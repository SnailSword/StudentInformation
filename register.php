<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>注册</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/three.min.js"></script>
</head>
<body>
<?php
// 定义变量并设置为空值
$nameErr =$password1Err =$password2Err=$emailErr = $genderErr = $websiteErr =$message="";
$name =$password1=$password2=$email = $gender = $comment = $website = "";

$problem=false;//没有问题

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
            $nameErr = "只允许字母字符！";
            $problem=true;
        }
    }

    if (empty($_POST["email"]))
    {
        $emailErr = "用于找回密码！";
        $problem=true;
    }
    else
    {
        $email = test_input($_POST["email"]);
        // 检查电子邮件地址语法是否有效
        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
        {
            $emailErr = "无效的email格式";
            $problem=true;
        }
    }

    if (empty($_POST["password1"])||empty($_POST["password2"]))
    {
        $password1Err ="登陆密码必填！";
        $password2Err="确认登陆密码！";
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

    if (empty($_POST["gender"]))
    {
        $genderErr = "性别是必选的！";
        $problem=true;
    }
    else
    {
        $gender = test_input($_POST["gender"]);
    }
    if(!$problem)
    {
        //包含数据库连接文件
        include('conn.php');
        //检查用户名是否存在
        $check_name = mysql_query("select name from s_userinfo where name='$name' limit 1");

        if($result1 = mysql_fetch_array($check_name))
        {
            $nameErr = "该用户名已被注册！";
        }
        else
        {
            $sql="insert into s_userinfo(id,name,password,email,sex) value(null,'$name','$password2','$email','$gender')";
            $query=mysql_query($sql);
            if(mysql_affected_rows())//验证是否插入成功
            {
                $message= '恭喜注册成功!'.
                    '<br/>用户名：'.$name.
                    '<br/>登陆密码：'.$password2.
                    '<br/>点击此处 <a href="login.html">登录!</a> ';
            }
            else
            {
                $message='注册失败！请刷新后重新尝试！';
            }
        }
    }
}
@mysql_close();//关闭数据库连接
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<!-- multistep form -->
<form id="msform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <h2>交大测绘学子信息地图系统</h2>
    <!-- progressbar -->
    <ul id="progressbar">
        <li class="active">用户协议</li>
        <li>账号信息</li>
        <li>个人信息</li>
    </ul>
    <!-- fieldsets -->
    <fieldset>
        <h2 class="fs-title">用户注册协议</h2>
        <h3 class="fs-subtitle">本系统涉及数据包含班级和学生个人信息，仅供内部学习交流，不得以任何形式传播。</h3>
        <h3 class="fs-subtitle">点击“下一步”按钮代表您同意本协议。</h3>
        <input type="button" name="next" class="next action-button" value="下一步" />
    </fieldset>
    <fieldset>
        <h2 class="fs-title">账号信息</h2>
        <h3 class="fs-subtitle">设置登录账号与密码</h3>
        <input type="text" name="name" placeholder="用户名" />
        <input type="password" name="password1" placeholder="密码" />
        <input type="password" name="password2" placeholder="确认密码" />
        <input type="button" name="previous" class="previous action-button" value="上一步" />
        <input type="button" name="next" class="next action-button" value="下一步" />

    </fieldset>
    <fieldset>
        <h2 class="fs-title">个人信息</h2>
        <h3 class="fs-subtitle">设置个人资料</h3>
        <input type="text" name="email" placeholder="电子邮件"/>
        <input type="text" name="gender" placeholder="性别" />
<!--        <input type="text" name="school" placeholder="学校" />-->
        <input type="button" name="previous" class="previous action-button" value="上一步" />
        <input type="submit" name="submit" class="submit action-button" value="提交" />
    </fieldset>
</form>
<div></div>
<div class="wrapper"></div>
<script src="js/background.js"></script>

    <script src="js/person.js"></script>
</body>
</html>