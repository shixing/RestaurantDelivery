<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Ad_header_h {
	
  public function __construct(){
    $this->CI =& get_instance();
  }
  
  // Utils function for ad_header.php
  
  function get_cate_options(){
    $this->CI->load->model('category_model');
    $cates = $this->CI->category_model->get_entries();
    $html = '';
    foreach ($cates as $cate_item){
      $option = <<<EOF
    <option value="{$cate_item['cid']}">{$cate_item['name']}</option>
EOF;
      $html .= $option;
    }  
    return $html;
  } 

  function get_cart_item_num(){
    if ($this->CI->session_h->is_login()){
      $this->CI->load->model('cart_model');
      return $this->CI->cart_model->get_entries_num($this->CI->session->userdata['uid']);
    } else {
      if (! $this->CI->session->userdata('cart')){
        return 0;
      } else {
        return count($this->CI->session->userdata('cart'));
      }
    }
  }


}

/* End of file Someclass.php */
