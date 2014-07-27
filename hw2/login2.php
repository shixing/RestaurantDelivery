<?php session_start(); 
include 'utils.php';
check_timeout($_SESSION);
?>
<?php
// validate
$username = $_POST['username'];
$password = $_POST['password'];
$err_msg = "";

if (isset($_SESSION['time'])){
  // login page
  header('Location: user_home.php');
  exit;
}else if (strlen($username) == 0 && strlen($password) == 0 ) {
  require 'pre.php';
  require 'ad_login_form.html';  
  require 'post.php';
} elseif (strlen($username) == 0 || strlen($password) == 0){
  $err_msg = "No Username or Password!";
} else {
  $con = mysql_connect('localhost:5000','root','');
  if (!$con){
    $err_msg = "Cannot connect to DB!";	  
  } else {
    mysql_select_db('company',$con);
    $sql = "select type,fn,ln,username from employee where username='$username' and password = password('$password');";
    $res = mysql_query($sql);
    $row = mysql_fetch_array($res);
    if (!$row){
      $err_msg = "Username and password doesn't match!";
    }
    else
    {
      $type = $row[0];
      //set session
      $_SESSION['time'] = time();
      $_SESSION['type'] = $type;
      $_SESSION['fn'] = $row[1];
      $_SESSION['ln'] = $row[2];
      $_SESSION['username'] = $row[3];

      header('Location: user_home.php');
      
    }
  }
}
if (strlen($err_msg)!=0){
  require 'pre.php';
  require 'ad_login_error.php';
  require 'ad_login_form.html';  
  require 'post.php';
}

?>


      
      
