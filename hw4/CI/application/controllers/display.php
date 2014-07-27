<?php

class Display extends CI_Controller{
  
  public function __construct(){
    parent::__construct();
    $this->session_h->init_s();
    $this->load->model('product_model');
  }

  public function home($cid = ''){
    $this->session_h->check_timeout();
    $this->load->library('display_h');
    if ($cid == ''){
      //show all the special sales;
      $sids = $this->sale_model->get_sid_list();
      $datas = Array();
      foreach ($sids as $sid){
	$data = $this->product_model->init_all_from_sid($sid);
	$datas[] = $data;
      }
      $config = Array('show_button'=>'add', 'action'=>'/hw4/CI/index.php/cart/add_cart');
      $this->load->view('include/pre');
      $this->load->view('display/home',Array('datas'=>$datas, 'config'=>$config));
      $this->load->view('include/post');
    } else {
      //show certain catigory
      $datas = $this->product_model->get_product_of_category($cid);
      $config = Array('show_button'=>'add', 'action'=>'/hw4/CI/index.php/cart/add_cart');
      $this->load->view('include/pre');
      $this->load->view('display/home',Array('datas'=>$datas, 'config'=>$config));
      $this->load->view('include/post');
    }
  }
  
}
  
?>
