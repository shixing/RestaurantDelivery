<?php session_start(); ?>
<html>		       
  <?php require 'ad_head.html'; ?>
  <body>			
    <?php require 'ad_header.html'; ?>

    <div class="main-frame">
      <div class="panel p-wide">
      <h3>Log out successfully!</h3>
      </div>
      <?php	
	   session_unset();
	   session_destroy();
      ?>

    </div>
  </body>
</html>
