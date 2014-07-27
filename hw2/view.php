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
     
     ?>
</div>

<?php
include 'post.php';
?>
