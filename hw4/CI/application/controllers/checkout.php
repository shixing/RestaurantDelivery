<?php
class Checkout extends CI_Controller{
  
  public function __construct()
  {
    parent::__construct();
    $this->session_h->init_s();
    $this->load->library('checkout_h');
    $this->load->model('order_model');
  }

  public function check_out(){
    $this->session_h->check_timeout();
    if ($this->session_h->is_login()){
      $this->load->view('include/pre');
      
      $html = '';
      if ($this->ad_header_h->get_cart_item_num() > 0){
	$uid = $this->session->userdata('uid');
	$html .= '<h1>Check Out</h1>';
	$html .= $this->checkout_h->show_address($uid);
	$html .= $this->checkout_h->show_creditcard($uid);
	$html .= $this->checkout_h->show_cart_summary($uid);
	$html .= $this->generate_h->success_page2("","/hw4/CI/index.php/cart/display_cart","Edit Cart","/hw4/CI/index.php/checkout/place_order","Place Order");
      }
      $this->load->view('include/p_wider',Array('html'=>$html));
      $this->load->view('include/post');
    } else {
      $this->session->set_userdata('next','checkout/checkout');
      redirect('user/signin','location');
    }
  }

  public function place_order(){
    $this->session_h->check_timeout();
    $suc = true;
    $this->load->view('include/pre');
    $html = '';
    if ($this->session_h->is_login()){
      // place the order
      $uid = $this->session->userdata('uid');
      $suc = $this->order_model->place_order($uid);
      if ($suc){
	$html .= $this->generate_h->fail_page("Successfully Placed Your Order! Thank you!");
      } else {
	$html .= $this->generate_h->fail_page("We have a problem to place your order!");
      } 
      // message page
      
    } else {
      $html .= $this->generate_h->success_page("Please Sign In",'/hw4/CI/index.php/user/signin',"Sign In");
    }
    $this->load->view('include/p_wider',Array('html'=>$html));
    $this->load->view('include/post');
  }
}
?>
