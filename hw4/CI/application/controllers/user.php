<?php
class User extends CI_Controller{
  
  public function __construct()
  {
    parent::__construct();
    $this->session_h->init_s();
    $this->load->model('user_model');
    $this->load->model('order_model');
  }

  public function signin(){
    $this->session_h->check_timeout();
    if ($this->session_h->is_login()){
      if (strlen($this->session->userdata('next')) >0 ){
	$next = $this->session->userdata('next');
	$this->session->set_userdata('next','');
	redirect($next,'location');
      } else {
	redirect('display/home','location');
      }
    } else {
      $this->load->helper('form');
      $this->load->library('form_validation');
    
      $this->form_validation->set_rules('username','Username','required|valid_email|xxs_clean');
      $this->form_validation->set_rules('password','Password','required|min_length[4]|xxs_clean|callback_check_database');
      
      if ($this->form_validation->run() === FALSE){
	$this->load->view('include/pre.php');
	$this->load->view('user/user_login_form');
	$this->load->view('include/post.php');
      } else {
	$this->load->library('add_cart_h');
	$this->add_cart_h->transfer_cart();
	if (strlen($this->session->userdata('next')) >0 ){
	  $next = $this->session->userdata('next');
	  $this->session->set_userdata('next','');
	  redirect($next,'location');
	} else {
	  redirect('display/home','location');
	}
      }
    }
  }

  public function edit_profile(){
    
    $this->session_h->check_timeout();

    $this->load->view('include/pre.php');    

    $this->load->helper('form');
    $this->load->library('form_validation');

    $uid = $this->session->userdata('uid');
    if ($this->input->post('action') == 'update'){
      //update
      //validate

      $this->form_validation->set_rules('email','Email','required|valid_email|xxs_clean');
      $this->form_validation->set_rules('fn','First Name','required|xxs_clean');
      $this->form_validation->set_rules('ln','Last Name','required|xxs_clean');
      $this->form_validation->set_rules('street','Address','required|xxs_clean');
      $this->form_validation->set_rules('city','City','required|xxs_clean');
      $this->form_validation->set_rules('us_state','State','required|xxs_clean');
      $this->form_validation->set_rules('zipcode','Zipcode','required|xxs_clean|integer');
      $this->form_validation->set_rules('card_number','Credit Card','required|integer|exact_length[16]|xxs_clean');
      $this->form_validation->set_rules('name','Name on Card','required|xxs_clean');
      $this->form_validation->set_rules('expire_month','Expire Month','required|integer|exact_length[2]|greater_than[0]|less_than[13]|xxs_clean');
      $this->form_validation->set_rules('expire_year','Expire Year','required|exact_length[2]|integer|xxs_clean');


      if ($this->form_validation->run() === FALSE){
	$content = $this->user_model->init_data_from_post();
	$content = $this->user_model->init_data_update($content);
	$data = Array('data'=>$content);
	$this->load->view('user/user_signup_form',$data);
      } else {
	$data = $this->user_model->init_data_from_post();
	$suc = $this->user_model->update_three_table($data);
	$html = '';
	if ($suc){
	  $html .= $this->generate_h->fail_page("Successfully update your profile!");
	} else {
	  $html .= $this->generate_h->fail_page("Error Happens!");	  
	}
	$this->load->view('include/p_wider',Array('html'=>$html));
      }
    } else {
      //show form
      $content = $this->user_model->init_data_from_db($uid);
      $content = $this->user_model->init_data_update($content);
      $data = Array('data'=>$content);
      $this->load->view('user/user_signup_form',$data);
    }
    $this->load->view('include/post');
  }
  
  public function change_password(){
    $this->session_h->check_timeout();
    
    $this->load->helper('form');
    $this->load->library('form_validation');

    $this->load->view('include/pre');
    $action = $this->input->post('action');
    if ($action == 'change_password'){
      // validate
      $this->form_validation->set_rules('old_password','Old Password','required|min_length[4]|xxs_clean|callback_check_password');
      $this->form_validation->set_rules('new_password_1','New Password','required|min_length[4]|xxs_clean|matches[new_password]');
      $this->form_validation->set_rules('new_password','New Password','required|min_length[4]|xxs_clean');

      if ($this->form_validation->run() === FALSE){
	$this->load->view('user/change_password_form');
      } else {
	$uid = $this->session->userdata('uid');
	$new_password = $this->input->post('new_password');
	$suc = $this->user_model->change_password($uid,$new_password);
	$html = $this->generate_h->fail_page("Successfully change your password!");
	$this->load->view('include/p_wider',Array('html'=>$html));
      }
    } else {
      $this->load->view('user/change_password_form');
    }
    $this->load->view('include/post');
  }

  public function view_orders($oid=''){
    $this->session_h->check_timeout();

    $this->load->view('include/pre');
    
    if ($oid == ''){
      $html = '<h1>Orders</h1>';
      $uid = $this->session->userdata('uid');
      $datas = $this->order_model->init_all_from_uid($uid);
      $html .= $this->display_h->to_orders_html($datas);
      $this->load->view('include/p_wider',Array('html'=>$html));
    } else {
      $html = '<h1>Order Detail</h1>';
      $data = $this->order_model->init_all_from_oid($oid);
      $html .= $this->display_h->to_orderdetail_html_date($data);
      $this->load->view('include/p_wider',Array('html'=>$html));
    }
    
    $this->load->view('include/post');
  }



  public function home(){
    $this->session_h->check_timeout();
    $uid = $this->session->userdata('uid');
    $data = $this->user_model->init_from_uid($uid);
    $fn = $data['fn'];
    $ln = $data['ln'];
    $html = "<h1>Hello, {$fn} {$ln}</h1>";
    $url_text = Array( '/hw4/CI/index.php/user/edit_profile' => 'Edit Profile',
     	       	 	'/hw4/CI/index.php/user/change_password' => 'Chage Password',
			'/hw4/CI/index.php/user/view_orders' => 'View Orders'
			);
    $html .= $this->generate_h->buttons_row($url_text);
    $this->load->view('include/pre');
    $this->load->view('include/p_wider',Array('html'=>$html));
    $this->load->view('include/post');
  }

  public function check_password($password){
    $uid = $this->session->userdata('uid');
    $suc = $this->user_model->check_password($uid,$password);
    if ($suc) {
      return TRUE;
    } else {
      $this->form_validation->set_message('check_password','Invalid username or password');
      return FALSE;
    }
    
  }

  public function check_database($password){
    $username = $this->input->post('username');
    $suc = $this->user_model->login($username,$password);
    if ($suc) {
      return TRUE;
    } else {
      $this->form_validation->set_message('check_database','Invalid username or password');
      return FALSE;
    }
  }

  public function test(){
    $pid = 1;
    $this->load->model('product_model');
    $data = $this->product_model->init_all_from_sid(5);
    echo var_dump($data);
  }

  public function signout(){
    $this->session->sess_destroy();
    $this->load->view('include/pre.php');
    $this->load->view('user/user_logout');
    $this->load->view('include/post.php');
  }

  public function signup(){
    if (! $this->input->post('action')){
      //signup new users
      $this->load->helper('form');
      $this->load->library('form_validation');

      $this->load->view('include/pre.php');
      $content = $this->user_model->init_data();
      $data = Array('data'=>$content);
      $this->load->view('user/user_signup_form',$data);
      $this->load->view('include/post.php');
      
    } else if ($this->input->post('action') == 'create') {
      //validate
      $this->load->helper('form');
      $this->load->library('form_validation');

      $this->form_validation->set_rules('email','Email','required|valid_email|xxs_clean');
      $this->form_validation->set_rules('password','Password','required|min_length[4]|xxs_clean');
      $this->form_validation->set_rules('fn','First Name','required|xxs_clean');
      $this->form_validation->set_rules('ln','Last Name','required|xxs_clean');
      $this->form_validation->set_rules('street','Address','required|xxs_clean');
      $this->form_validation->set_rules('city','City','required|xxs_clean');
      $this->form_validation->set_rules('us_state','State','required|xxs_clean');
      $this->form_validation->set_rules('zipcode','Zipcode','required|xxs_clean|integer');
      $this->form_validation->set_rules('card_number','Credit Card','required|integer|exact_length[16]|xxs_clean');
      $this->form_validation->set_rules('name','Name on Card','required|xxs_clean');
      $this->form_validation->set_rules('expire_month','Expire Month','required|integer|exact_length[2]|greater_than[0]|less_than[13]|xxs_clean');
      $this->form_validation->set_rules('expire_year','Expire Year','required|exact_length[2]|integer|xxs_clean');


      if ($this->form_validation->run() === FALSE){
	$this->load->view('include/pre.php');
	$content = $this->user_model->init_data_from_post();
	$data = Array('data'=>$content);
	$this->load->view('user/user_signup_form',$data);
	$this->load->view('include/post.php');
      } else {
	$data = $this->user_model->init_data_from_post();
	$suc = $this->user_model->insert_three_table($data);
	if ($suc) {
	  $this->load->view('include/pre.php');
	  $html = $this->generate_h->success_page('Successfully Sign Up','/hw4/CI/index.php/user/signin','Sign In');
	  $this->load->view('include/p_normal.php',Array('html'=> $html));
	  $this->load->view('include/post.php');
	} else {
	  $this->load->view('include/pre.php');
	  $data = Array('data'=>$data,'err_msg'=>$this->generate_h->fail_page("Error Happens when sign up!"));
	  $this->load->view('user/user_signup_form',$data);

	}
      }
      
      
    }
    
  }

  


}

?>
