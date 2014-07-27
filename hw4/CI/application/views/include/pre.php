<html>
  <?php $this->load->view('include/ad_head'); ?>
  <body>
    <?php
    if ($this->mobile_detect->isMobile()){
      $this->load->view('include/ad_header_mobile');
    } else {
      $this->load->view('include/ad_header'); 
    }
    ?>

    <div class="main-frame">

