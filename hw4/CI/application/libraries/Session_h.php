<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Session_h {

  public function __construct(){
    $this->CI =& get_instance();
  }
  
  public function init_s(){
    if (! $this->CI->session->userdata('init')){
      $this->CI->session->set_userdata('init',true);
      // for login
      $this->CI->session->set_userdata('time', time());
      $this->CI->session->set_userdata('login',false);
      $this->CI->session->set_userdata('user','');
      // for navigate
      $this->CI->session->set_userdata('next','');
      // for shopping cart
      $this->CI->session->set_userdata('cart',Array());
    }
  }

  public function refresh_s(){
    $this->CI->session->set_userdata('time', time());
  }

  public function login_s($user,$uid,$fn,$ln){
    $this->refresh_s();
    $this->CI->session->set_userdata('login',true);
    $this->CI->session->set_userdata('user',$user);
    $this->CI->session->set_userdata('uid',$uid);
    $this->CI->session->set_userdata('fn',$fn);
    $this->CI->session->set_userdata('ln',$ln);
  }

  function logout_s(){
    $this->CI->session->set_userdata('login',false);
    $this->CI->session->set_userdata('user','');
    $this->CI->session->set_userdata('uid','');
  }

  function is_login(){
    return $this->CI->session->userdata('login');
  }

  function check_timeout()
  {
    $log_time = $this->CI->session->userdata('time');
    if (strlen($log_time)>0){
      $current = time();
      if ($this->CI->session->userdata('login')){
	if ($current - $log_time > 300){
	  redirect('user/signout');
	} else {
	  $this->refresh_s();
	}
      } 
    }
  }

	
}

?>
