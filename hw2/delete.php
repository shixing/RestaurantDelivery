<?php 
session_start();
include 'utils.php';
check_timeout($_SESSION);
$_SESSION['time'] = time();
?>
<?php
include 'pre.php';
$type = $_SESSION['type'];
$name = $_SESSION['fn'] . ' ' . $_SESSION['ln'];
$username = $_SESSION['username'];
?>

<div class = "panel p-normal">
     <?php include 'welcome_row.php'; ?>
     <?php 
     $res=delete_table($_POST);
     $suc = $res[0];
     $err_msg = $res[1];
     if (!$suc) {
       echo fail_page($err_msg);
       echo operate_table('Delete',$_POST['table']);
     } else {
       echo success_page($err_msg,'user_home.php','OK');
     }	
     ?>
</div>

<?php
include 'post.php';
?>
