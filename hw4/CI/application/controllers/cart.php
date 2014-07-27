<?php
class Cart extends CI_Controller{
  
  public function __construct()
  {
    parent::__construct();
    $this->session_h->init_s();
    $this->load->model('product_model');
    $this->load->library('add_cart_h');
  }

  public function display_cart(){
    $this->session_h->check_timeout();
    $this->load->view('include/pre');
    $suc = true;
    $html = '<h1>Your Shopping Cart</h1>';
    $pid = $this->input->post('pid');
    $quantity = $this->input->post('quantity');
    $update = $this->input->post('update');
    $remove = $this->input->post('remove_all');
    if ($remove == '1') {
      // remove
      if ($this->session_h->is_login()){
	$uid = $this->session->userdata('uid');
	$this->add_cart_h->empty_cart_db($uid);
      } else {
	$this->add_cart_h->empty_cart_session();
      }
      if ($suc){
	$html .= $this->generate_h->fail_page("Successfully empty your cart!");
      }
      else{
	$html .= $this->generate_h->fail_page("Error Happens ...");
      }
    } else {
      if ($update == '1') {
	// update cart
	if ($this->session_h->is_login()){
	  $uid = $this->session->userdata('uid');
	  $this->add_cart_h->update_cart_db($uid,$pid,$quantity);
	} else {
	  $this->add_cart_h->update_cart_session($pid,$quantity);
	}
	// show message
	if (!$suc){
	  $html .= $this->generate_h->fail_page('Error Happens ...');
	}
      }
      // show cart;
      $html .= $this->add_cart_h->show_cart();
      $html .= $this->generate_h->remove_check("/hw4/CI/index.php/cart/display_cart","Empty Cart",'/hw4/CI/index.php/checkout/check_out',"Check Out");
    }
    
    $this->load->view('include/p_wider',Array('html'=>$html));
    $this->load->view('include/post');
  }



  public function add_cart(){
    $this->session_h->check_timeout();

    $this->load->library('form_validation');
    $this->load->library('add_cart_h');
    $this->form_validation->set_rules('quantity','Quantity','required|xxs_clean|integer|larger_than[-1]');

    $suc = true;
    $err_msg = "";
    $pid = '';
    $quantity = '';
    if ($this->form_validation->run() === FALSE){
      $suc = false;
      $err_msg = validation_errors();
    } else {
      $pid = $this->input->post('pid');
      $quantity = $this->input->post('quantity');

      if ($this->session_h->is_login()){
	// add to db
	$uid = $this->session->userdata('uid');
	$this->add_cart_h->add_to_cart_db($uid,$pid,$quantity);
      } else {
	// add to session
	$this->add_cart_h->add_to_cart_session($pid,$quantity);
      }
    }
    $this->load->view('include/pre');
    if ($suc){
      $html = $this->generate_h->success_page2("Successfully add to your shopping cart!",'/hw4/CI/index.php/cart/display_cart','Check Out',$_SERVER["HTTP_REFERER"],'Continue Shopping');
      $data = $this->product_model->init_all_from_pid($pid);
      $data['product']['quantity'] = $quantity;
      $product_html = $this->display_h->to_product_html_cart($data);
      $html .= $product_html;
      $this->load->view('include/p_wider',Array('html'=>$html));

      // for the similar products
      $html = '<h2>People Also buy the following:</h2>';
      $config = Array('show_button'=>'add', 'action'=>'/hw4/CI/index.php/cart/add_cart');
      $datas = $this->product_model->get_recommand_product($pid);
      foreach($datas as $data){
	$html .= $this->display_h->to_product_html($data,$config);
      }
      if (count($datas)>0){
	$this->load->view('include/p_wider',Array('html'=>$html));
      }


    } else {
      $html = $this->generate_h->success_page("Error Happens ...".$err_msg, $_SERVER["HTTP_REFERER"],'Go Back');
      $this->load->view('include/p_normal',Array('html'=>$html));
    }
    $this->load->view('include/post');
  }

}

?>
