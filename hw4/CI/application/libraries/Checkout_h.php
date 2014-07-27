<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Checkout_h {
  public function __construct(){
    $this->CI =& get_instance();
    $this->CI->load->model('creditcard_model');
    $this->CI->load->model('address_model');
    $this->CI->load->model('product_model');
  }

  function show_address($uid){
  $row = $this->CI->address_model->init_from_uid($uid);
  $street = $row['street'];
  $city = $row['city'];
  $state = $row['us_state'];
  $zipcode = $row['zipcode'];
  $ship_row = $this->CI->generate_h->row_generate($this->CI->generate_h->column_generate("col-8 bold","Shipping to:"));
  $street_row = $this->CI->generate_h->row_generate($this->CI->generate_h->column_generate("col-8",$street));
  $city_row = $this->CI->generate_h->row_generate($this->CI->generate_h->column_generate("col-8",$city.'&nbsp;&nbsp;'.$state.'&nbsp; &nbsp;'.$zipcode));
  $rows_html = $ship_row.$street_row.$city_row;
  $html = <<<EOF
  <div class="container">
  $rows_html
  </div>
EOF;
  return $html;
}

function show_creditcard($uid){
  $row = $this->CI->creditcard_model->init_from_uid($uid);
  $card_number = $row['card_number'];
  $name = $row['name'];
  $expire_month = $row['expire_month'];
  $expire_year = $row['expire_year'];
  $card_row = $this->CI->generate_h->row_generate($this->CI->generate_h->column_generate("col-8 bold","Credit Card"));
  $number_row = $this->CI->generate_h->row_generate($this->CI->generate_h->column_generate("col-8",$card_number));
  $misc_row = $this->CI->generate_h->row_generate($this->CI->generate_h->column_generate("col-2",$name) . $this->CI->generate_h->column_generate("col-1",$expire_month.'/'.$expire_year));
  $rows_html = $card_row.$number_row.$misc_row;
  $html = <<<EOF
  <div class="container">
  $rows_html
  </div>
EOF;
  return $html;
}

function show_cart_summary($uid){
  $pid_quan = Array();
  $uid = $uid = $this->CI->session->userdata('uid');
  $data = $this->CI->cart_model->init_from_uid($uid);
  foreach ($data as $row){
    $pid =$row['pid'];
    $quantity = $row['quantity'];
    $pid_quan[$pid] = $quantity;
  }
  $html = $this->CI->generate_h->row_generate($this->CI->generate_h->column_generate("col-8 bold","Order Summary"));
  $rows_html = '';
  $total = 0;
  foreach ($pid_quan as $pid => $quantity) {
    $data = $this->CI->product_model->init_all_from_pid($pid);
    $data['product']['quantity'] = $quantity;
    $product_html = $this->CI->display_h->to_product_row($data);
    $rows_html .= $product_html;
    $total += $data['product']['final_price'] * $quantity;
  }
  $thead = $this->CI->generate_h->table_row_generate(Array("Product","Price","Quantity","Total"));
  $tfoot = $this->CI->generate_h->table_row_generate(Array("","","","$". $total));
  $html .= $this->CI->generate_h->table_generate2($thead,$rows_html,$tfoot);
  $html = '<div class="container">' . $html . '</div>';
  return $html;
}




}

?>
