<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<title>学生信息查询</title>
</head>
<body>
<form name="studinfo" method="post"  action="stud_query.php" enctype="multipart/form-data">
*学号：<input type="text" name="number">
*姓名：<input type="text" name="name">
<input type="submit"  name="submit" value="查询"  />
</form>
<br>
</body>
</html>
<?php
header("Content-Type: text/html; charset=utf-8");//免得出现中文乱码.
@include('conn.php');
$messageErr=$message='';
$nowyear=gmdate('Y');//获取当前年份
$id=$stuname=$sex=$birth=$prov=$major=$perimg=$name='';
$grade=$class=$source=$soclass=$perimg=$lifimg=$perweb=$remark='';
$county=$city='暂无';
$submit =$submit2=$submit3='';
if($_SERVER['REQUEST_METHOD']=="POST")
{
	$num=$_POST['number'];//学号 必填
	$name=$_POST['name'];//姓名
	
	if(!empty($num)&!empty($name)&is_numeric($num))
	{
		$s_query="SELECT *FROM studentinfo WHERE id=$num and stuname='$name' ";
		$s_R=mysql_query($s_query);
		if($result=@mysql_fetch_array($s_R))
		{
			///以下为学生基本信息页
			$id =$result['id'];//学号
			$stuname  =$result['stuname'];//姓名
			$sex   =$result['sex'];//性别
			$birth =$result['birtnday'];//生日
			$prov  =$result['pronvice'];//省
			$city     =$result['city'];//市
			$county   =$result['county'];//县
			$major    =$result['major'];//专业
			$grade    =$result['grade'];//年级
			$class    =$result['class'];//班级
			$source   =$result['source'];//生源地
			$soclass =$result['sourceclass'];//生源类型
			$perimg =$result['perimage'];//个人图片
			$lifimg =$result['lifimage'];//生活照片
			$perweb   =$result['perweb'];//个人网站
			$s_mark   =$result['remark'];//备注
			
			print '<br>在基础信息页打印html页面标签显示以上信息';
			
			///以下为学生毕业信息页
			if($nowyear-$grade<4)//小于四年则还没有毕业
			{
				$g_message='该学生未毕业！暂无毕业信息！';
				print  $g_message;//该提示信息位于学生的毕业信息页
			}
			else
			{
				$g_query="SELECT *FROM graduatinfo WHERE id=$num";
				$g_R=mysql_query($g_query);
		       if($result2=@mysql_fetch_array($g_R))
			   {
				   $go_work=$result2['go_work'];//工作去向
				   $unit   =$result2['unit'];//单位
				   $jop_class=$result2['jop_class'];//工作类别
				   $jop_intr=$result2['jop_intr'];//工作介绍
				   $g_mark =$result2['remark'];//备注
				   
				     print '<br>在就业去向信息页打印html页面标签显示以上信息';
			   }
			}
			
			////以下为学生活动信息页
			$act="member like '%".$name."%'";//模糊查询 同名怎么办？
			$a_query="SELECT *FROM activeinfo WHERE $act ";
		    $a_R=mysql_query($a_query);
			if($result3=@mysql_fetch_array($a_R))
			{
				$project=$result3['project'];//项目名
				$proclass=$result3['class'];//活动类别
				$member=$result3['member'];//成员
				$date=$result3['date'];//参加日期
				$adviser=$result3['adviser'];//指导老师
				$diploma=$result3['diploma'];//证书照片
				$rank=$result3['rank'];//等级
				$place=$result3['place'];//参加地点
				$a_mark=$result3['remark'];//备注
				
				
				print '<br>在活动信息页打印html页面标签显示学生所参加的活动的信息！';
			}
			
			else
			{
				$a_message='<br>该学生暂时没有参加过任何活动！或暂无其信息！';
				print $a_message;
			}
		}
		
		else
		{
			$messageErr="<br>不存在学号为:$num的学生信息！<br/>请检查学号是否正确！";
			
			print '<br>打印页面提示示$messageErr信息';
		}
	}
}
?>
