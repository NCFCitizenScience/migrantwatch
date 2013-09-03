<?php
include('db.php');
if(isset($_POST['content']))
{
$content=$_POST['content'];
mysql_query("insert into dummy_messages(msg) values ('$content')");
$sql_in= mysql_query("SELECT msg,msg_id FROM dummy_messages order by msg_id desc");
$r=mysql_fetch_array($sql_in);
}
?>
<b><?php echo $r['msg']; ?></b>

