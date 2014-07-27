<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Add_cart_h {
	
  public function __construct(){
    $this->CI =& get_instance();
    $this->CI->load->model('cart_model');
  }


function transfer_cart(){
  //add to db;
  $uid = $this->CI->session->userdata('uid');
  foreach ($this->CI->session->userdata('cart') as $pid => $quantity){
    $this->add_to_cart_db($uid,$pid,$quantity);
  }
  //remove seesion's cart info
  $this->CI->session->set_userdata('cart',Array()); 
}

function add_to_cart_db($uid,$pid,$quantity){
  $data = $this->CI->cart_model->init_from_uid_pid($uid,$pid);
  if (count($data) == 0){
    $this->CI->cart_model->insert_db($uid,$pid,$quantity);
  } else {
    $quantity += $data['quantity'];
    $this->CI->cart_model->update_db($uid,$pid,$quantity);
  }
}

function add_to_cart_session($pid, $quantity) {
  $cart =& $this->CI->session->userdata('cart');
  $old_quantity = 0;
  if (isset($cart[$pid])){
    $old_quantity = $cart[$pid];
  }

  if ($old_quantity == 0){
    $cart[$pid] = $quantity;
  } else {
    $cart[$pid] += $quantity;
  }
  $this->CI->session->set_userdata('cart',$cart);
}


function update_cart_db($uid,$pid,$quantity){
  if ($quantity == '0'){
    $this->CI->cart_model->delete_db($uid,$pid);
  } else {
    $this->CI->cart_model->update_db($uid,$pid,$quantity);
  }
}

function update_cart_session($pid,$quantity){
  $cart =& $this->CI->session->userdata('cart');
  if ($quantity == '0'){
    //remove
    unset($cart[$pid]);
  } else {
    $cart[$pid] = $quantity;
  }
  $this->CI->session->set_userdata('cart',$cart);
}

function empty_cart_db($uid){
  $this->CI->cart_model->delete_db_uid($uid);
}

function empty_cart_session(){
  $cart =& $this->CI->session->userdata('cart');
  $cart = Array();
  $this->CI->session->set_userdata('cart',$cart);
}

function show_cart(){
  $pid_quan = Array();
  if ($this->CI->session_h->is_login()) {
    // from db
    $uid = $uid = $this->CI->session->userdata('uid');
    $data = $this->CI->cart_model->init_from_uid($uid);
    foreach ($data as $row){
      $pid =$row['pid'];
      $quantity = $row['quantity'];
      $pid_quan[$pid] = $quantity;
    }
  } else {
    // from session
    $pid_quan = $this->CI->session->userdata('cart');
  }
  // show 
  $html = '';
  foreach ($pid_quan as $pid => $quantity) {
    $data = $this->CI->product_model->init_all_from_pid($pid);
    $data['product']['quantity'] = $quantity;
    $product_html = $this->CI->display_h->to_product_html_cart_update($data);
    $html .= $product_html;
  }
  return $html;
}
}
?>
