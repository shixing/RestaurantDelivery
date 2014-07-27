<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Display_h {
	
  public function __construct(){
    $this->CI =& get_instance();
  }

  public function to_orderdetail_html_date($data){
    $order_date = $data['order_date'];
    $html = "<h4>Date:$order_date</h4>";
    $html .= $this->to_orderdetail_html($data);
    return $html;
  }
  
  public function to_orderdetail_html($data){
    $html = '';
    $rows_html = '';
    $total = $data['total'];
    foreach($data['orderdetails'] as $od){
      $product_data = $this->CI->product_model->init_from_pid($od['pid']);
      $product_data['final_price'] = $od['price'];
      $product_data['quantity'] = $od['quantity'];
      $product_html = $this->to_product_row(Array('product'=>$product_data));
      $rows_html .= $product_html;
    }
    $thead = $this->CI->generate_h->table_row_generate(Array("Product","Price","Quantity","Total"));
    $tfoot = $this->CI->generate_h->table_row_generate(Array("","","","$". $total));
    $html .= $this->CI->generate_h->table_generate2($thead,$rows_html,$tfoot);
    $html = '<div class="container">' . $html . '</div>';
    return $html;
  
  }
  
  public function to_order_row($data){
    $order_date = $data['order_date'];
    $total = $data['total'];
    $oid = $data['oid'];
    $button = <<<EOF
    <form method="POST" action="/hw4/CI/index.php/user/view_orders/$oid">
    <input type="hidden" name="oid" value="{$oid}"/>
    <input type="submit" class="form_button green" value="View"/>
    </form>
EOF;
    $trow = $this->CI->generate_h->table_row_generate(Array($order_date,"$". $total, $button));
    return $trow;
  }

public function to_orders_html($datas){
  $rows_html = '';
  foreach($datas as $data){
    $row_html = $this->to_order_row($data);
    $rows_html .= $row_html;
  }
  $thead = $this->CI->generate_h->table_row_generate(Array("Order Date","Total Cost","View"));
  $html = $this->CI->generate_h->table_generate2($thead,$rows_html,"");
  return $html;
  }

  public function to_product_html_cart($data){
    $config['show_button'] = 'null';
    $config['action'] = '';
    return $this->to_product_html($data,$config);
  }

  public function to_product_html_cart_update($data){
    $config['show_button'] = 'update';
    $config['action'] = '/hw4/CI/index.php/cart/display_cart';
    return $this->to_product_html($data,$config);
  }
  
  public function to_product_row($data){
    $total_price = $data['product']['final_price'] * $data['product']['quantity'];
    $contents = Array($data['product']['product_name'],'$'.$data['product']['final_price'],$data['product']['quantity'],'$'.$total_price);
    return $this->CI->generate_h->table_row_generate($contents);
  }

  public function to_sale_html($sale){
    if ($this->CI->mobile_detect->isMobile()){
    $off_percent = 100 - $sale['percent'];
    $html = <<<EOF
    <div class="row sale">
      <div class="col-2"><span class="highlight_sale">SALE:</span><span class="highlight_percent">$off_percent</span>% OFF</div>
      <div class="col-2 float-right"><span class="highlight_date">{$sale['end_date']}</span>~<span class="highlight_date">{$sale['start_date']}</span></div>
    </div>
EOF;
    return $html;
    } else {
    $off_percent = 100 - $sale['percent'];
    $html = <<<EOF
    <div class="row sale">
      <div class="col-1"><span class="highlight_sale">SALE</span></div>
      <div class="col-1"><span class="highlight_percent">$off_percent</span>% OFF</div>
      <div class="col-2 float-right"><span class="highlight_date">{$sale['end_date']}</span></div>
      <div class="col-1 float-right"><span>to</span></div>
      <div class="col-2 float-right"><span class="highlight_date">{$sale['start_date']}</span></div>
    </div>
EOF;
    return $html;
    }
  }

  public function to_product_html($data,$config){
    $show_button = $config['show_button']; //null,update
    $action = $config['action'];

    $sale = Array();

    if (isset($data['sale'])) {
      $sale = $data['sale'];
    }

    $product = $data['product'];
    $category = $data['category'];
    
    $price = $product['price'];
    $pid = $product['pid'];
    $quantity = $product['quantity'];
    $sid = '';
    if ($sale){
      $sid = $sale['sid'];
    }
    


    $price_html= <<<EOF
<div class="col-1">$<span class="price">$price</span></div>
EOF;
    $sale_html="";
    $button_html = <<<EOF
    <div class="row">
	  <div class="col-3"><input type="submit" class="form-button green font-big" value="Add to Cart"/></div>
	</div>
EOF;


    if ($show_button == 'null'){
      $button_html = "";
    } else if ($show_button == "update"){
      $button_html = <<<EOF
    <input type="hidden" name="update" value="1"/>
    <div class="row">
	  <div class="col-3"><input type="submit" class="form-button green font-big" value="Update"/></div>
	</div>
EOF;
    } 

    if ($sale){ 
      $sale_html = $this->to_sale_html($sale);
      $new_price = $price * 1.0 / 100 * $sale['percent'];
      $price_html= <<<EOF
<div class="col-1">$<span class="d_price">$price</span></div>
<div class="col-1">$<span class="price">$new_price</span></div>
EOF;
    }
    

    $html = <<<EOF
  <div class = "product">
    $sale_html
    <div class="row">
      <div class="col-3 product_img_div"><img class="product_img" src="/hw2/{$product['product_img']}" height="270" width="90%"/></div>
      <div class="col-5 product_content">
	<div class="row">
	  <div class="col-6"><h2 style="margin:1px;">{$product['product_name']}</h2></div>
	</div>
	<div class="row">
	  <div class="col-2"><span class="cate">{$category['name']}</span></div>
	</div>
	<div class="row">
	  <div class="col-1"><span class="price_label">Price</span></div>
          $price_html
	</div>
	<div class="row">
	  <div class="col-8"><span class="price_label">Description</span></div>
	</div>
	<div class="row">
	  <div class="col-8">{$product['product_description']}</div>
	</div>
        <form method="POST" action="$action">
	<div class="row">
	  <div class="col-1"><span class="price_label">Quantity</span></div>
	  <div class="col-1">
             <input type="text" class="numeric input-text-small" style="max-width:40;" value="{$quantity}" maxlength="4" name="quantity"/>
             <input type="hidden" name="pid" value="$pid"/>
             <input type="hidden" name="sid" value="$sid"/>
          </div>
	</div>
	$button_html
        </form>
      </div>
    </div>
  </div>
EOF;
      return $html;
    }

   



}

?>
