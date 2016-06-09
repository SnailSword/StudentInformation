<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Create to MySQL</title>
</head>
<body>
<?php // 脚本创建数据库与表
//注意root已获得最高权限如数据未加密则不用修改！！！
if ($dbc = mysql_connect('localhost', 'root','')) //如数据未加密则不用修改
{
 print '<p>Successfully connected to MySQL!</p>';
	// 如数据库不存在则尝试创建数据库同时默认的字符集是utf8,字符排序类型为utf8_general_ci
	//如果qsecondgroup数据库已经存在则不新建并返回true
	$query='CREATE DATABASE IF NOT EXISTS qsecondgroup DEFAULT CHARSET utf8 COLLATE utf8_general_ci';
	 if (@mysql_query($query, $dbc)) 
	 {
	 print '<p>The database has been created!</p>';
	
	 } 
	 else 
	 { //  无法创建数据库。
	 print '<p style="color: red;">Could not create the database because:<br/>' .
	 mysql_error($dbc) . '.</p>';
	 }
   if(mysql_select_db('qsecondgroup',$dbc))
    {
		
	   //html表单、php脚本、mysql字符编码要一致！！！！！！
      mysql_query("set names utf8;") or die("字符集设置错误");
	}
    else 
	{
      print '<p style="color: red;">Could not select the database because:<br/>' .
      mysql_error($dbc) . '.</p>';
	}
}
 else 
 {
 print '<p style="color: red;">Could not connect to MySQL.</p>';
 } 

 if ($dbc) 
 {
//创建班级信息表
$query1='CREATE TABLE IF NOT EXISTS classinfo(
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	major    CHAR(20) NOT NULL,
	grade    CHAR(20) NOT NULL,
	allstud  INT    NOT NULL,
	numboys  INT    NOT NULL,
	numgirls INT    NOT NULL,
	btutor   CHAR(20) ,
	counsellor CHAR(20) ,
	image    MediumBlob ,
	introduce TEXT(1000),
	weburl   CHAR(50),
	remark   CHAR(50))';
//创建学生信息表
$query2='CREATE TABLE IF NOT EXISTS studentinfo(
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	sex      CHAR(10) NOT NULL,
	birtnday CHAR(20),
	pronvice CHAR(10) NOT NULL,
	city     CHAR(10) NOT NULL,
	county   CHAR(10) ,
	major    CHAR(20) NOT NULL,
	grade    CHAR(20) NOT NULL,
	class    CHAR(20) NOT NULL,
	source   CHAR(20) NOT NULL,
	sourceclass CHAR(20) NOT NULL,
	perimage MediumBlob ,
	perweb   CHAR(20) ,
	remark   CHAR(50))';
	
//学生活动信息表	
$query3='CREATE TABLE IF NOT EXISTS  activeinfo(
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	project CHAR(10) NOT NULL,
	class   CHAR(20) NOT NULL,
	member  CHAR(10) NOT NULL,
	date    CHAR(10) NOT NULL,
	adviser CHAR(10) ,
	diploma MediumBlob,
	rank    CHAR(20),
	place   CHAR(20),
	remark  CHAR(50))';
	
//毕业去向信息表
$query4='CREATE TABLE IF NOT EXISTS graduatinfo(
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	go_work    CHAR(30) NOT NULL,
	unit       CHAR(20) NOT NULL,
	jop_class  CHAR(10),
	jop_intr   Text(600),
	remark     CHAR(50))';
	
//个人轨迹信息表
$query5='CREATE TABLE IF NOT EXISTS personinfo(
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	positioninfo CHAR(30) NOT NULL,
	imagesinfo CHAR(20) NOT NULL,
	image  MediumBlob ,
	textdescrip   Text(600))';
	//如表不存在则尝试新建表，如存在则不新建并返回true
	if (mysql_query($query1, $dbc)&mysql_query($query2, $dbc)&mysql_query($query3, $dbc)&mysql_query($query4, $dbc)&mysql_query($query5, $dbc)) 
	{
		print '<p>The All table has been created!</p>';
	} 
	 else 
	{
		print '<p style="color: red;"> Could not create the table because:<br/>' .
		mysql_error($dbc) .'</p>';
	}
 }
 ?>