<?php 
include 'utils.php';
$sql = stripslashes($_POST['sql']);
echo format_sql($sql);
?>
