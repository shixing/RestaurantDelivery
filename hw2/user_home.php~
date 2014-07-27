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

<div class = "panel p-wider">
  <?php include 'welcome_row.php'; ?>
  <?php
  $operation = $_POST['operation'];
  $table = $_POST['table'];
  $table_request = $_POST['table_request'];
  if (strlen($table_request)>0){ // table request
    if (vl_operation_table($operation,$table)){
      echo operate_table($operation,$table);
    } else {
      echo '<h4 class="warning">Please select operation and table! </h4>';
      echo guide_generate($type);
    }
  } else {
    echo guide_generate($type);
  }
  ?>
</div>





<?php
include 'post.php';
?>
