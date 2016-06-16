<?php

/**
 * @author blog.anchen8.net
 * @copyright 2016
 */

//连接数据库
$conn=@mysql_connect("localhost","root","") or die("数据库连接失败：".mysql_errno());
//选择指定的数据库，设置字符集
@mysql_select_db("qsecondgroup",$conn) or die("数据库连接失败：".mysql_error());

//html表单、php脚本、mysql字符编码要一致！！！！！！
mysql_query("set names utf8;") or die("字符集设置错误");

?>