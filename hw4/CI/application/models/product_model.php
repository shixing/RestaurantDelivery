<?php
class Product_Model extends CI_Model {
  
  function __construct(){
    parent::__construct();
    $this->load->model('category_model');
    $this->load->model('sale_model');
    $this->load->model('cart_model');
  }
  
  function init_from_pid($pid){
    $query = $this->db->get_where('product', Array('pid'=>$pid));
    $data = $query->row_array();
    return $data;
  }

  function init_all_from_pid($pid){
    // init product, sale, and category together
    $product_data = $this->init_from_pid($pid);
    $category_data = $this->category_model->init_from_cid($product_data['cid']);
    $sale_data = $this->sale_model->init_from_pid($pid);
    $data = Array();
    $data['product'] = $product_data;
    $data['category'] = $category_data;
    if (count($sale_data) > 0 ){
      $data['sale'] = $sale_data;
      $data['product']['final_price'] = $product_data['price'] * 0.01 * $sale_data['percent'];
    }
    $data['product']['quantity'] = 1;
    $data['product']['final_price'] = $data['product']['price'] ;
    return $data;
  }
  
  function init_all_from_sid($sid){
    // init product, sale and category together
    $sale_data = $this->sale_model->init_from_sid($sid);
    $product_data = $this->init_from_pid($sale_data['pid']);
    $category_data = $this->category_model->init_from_cid($product_data['cid']);
    $data = Array('sale'=>$sale_data,'product'=>$product_data,'category'=>$category_data);
    $data['product']['final_price'] = $product_data['price'] * 0.01 * $sale_data['percent'];
    $data['product']['quantity'] = 1;
    return $data;
  }
  
  function init_all_from_odid($odid){
    // just the price should follow the final price
    $orderdetail_data = $this->orderdetail_model->init_from_odid($odid);
    $data = $this->init_all_from_pid($orderdetail_data['pid']);
    $data['orderdetail'] = $orderdetail_data;
    $data['product']['final_price'] = $orderdetail_data['price'];
    $data['product']['quantity'] = $orderdetail_data['quantity'];
    return $data;
  }
  
  function init_all_from_cartid($cid){
    $cart_data = $this->cart_model->init_from_cid($cid);
    $data = $this->init_all_from_pid($cart_data['pid']);
    $data['product']['quantity'] = $cart_data['quantity'];
    return $data;
  }

  function get_recommand_product($pid){
    $query = $this->db->query('select distinct b.pid from new_orderdetail as a, new_orderdetail as b where a.pid=? and a.pid != b.pid and a.oid = b.oid',array($pid));
    $datas = Array();
    $rows = $query->result_array();
    foreach($rows as $row){
      $data = $this->init_all_from_pid($row['pid']);
      $datas[] = $data;
    }
    return $datas;
  }



  function get_product_of_category($cid){
    $norm_datas = Array();
    $sale_datas = Array();

    $this->db->select('pid');
    $this->db->from('product');
    $this->db->where('cid',$cid);
    $query = $this->db->get();
    $rows = $query->result_array();
    foreach ($rows as $row){
      $pid = $row['pid'];
      $data = $this->init_all_from_pid($pid);
      if (isset($data['sale'])){
	$sale_datas[] = $data;
      } else {
	$norm_datas[] = $data;
      }
    }
    return array_merge($sale_datas,$norm_datas);
  }

}

?>
