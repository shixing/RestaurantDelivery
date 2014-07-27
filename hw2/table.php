<?php 
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

<div class = "panel p-wider">
  <?php include 'welcome_row.php'; ?>
  <?php
  $operation = $_POST['operation'];
  $table = $_POST['table'];
  //validate this two;
  if
  ?>
</div>



<?php
include 'post.php';
?>

