<?php
class Order_Model extends CI_Model {
  
  function __construct(){
    parent::__construct();
    $this->load->model('product_model');
    $this->load->model('cart_model');
  }
  
  function init_all_from_uid($uid){
    $datas = Array();
    $query = $this->db->get_where('new_orders',Array('uid'=>$uid));
    $rows = $query->result_array();
    foreach ($rows as $row){
      $data = $this->init_all_from_oid($row['oid']);
      $datas[] = $data;
    }
    return $datas;
  }

  function init_all_from_oid($oid){
    // oid
    // uid
    // order_data
    // orderdetails Array
    $query = $this->db->get_where('new_orders',Array('oid'=>$oid));
    $data = $query->row_array();
    $query = $this->db->get_where('new_orderdetail',Array('oid'=>$oid));
    $details = $query->result_array();
    $data['orderdetails'] = $details;
    $total = 0;
    foreach( $details as $od ) {
      $total += $od['quantity'] * $od['price'];
    }
    $data['total'] = $total;
    return $data;
  }


  function place_order($uid){
    $this->db->trans_start();
    $pid_quan = Array();
    $data = $this->cart_model->init_from_uid($uid);
    foreach ($data as $row){
      $pid =$row['pid'];
      $quantity = $row['quantity'];
      $pid_quan[$pid] = $quantity;
    }
    
    // insert order
    $order_date = date('Y').'-'.date('m').'-'.date('d');
    $this->db->set('order_date',$order_date);
    $this->db->set('uid',$uid);
    $this->db->insert('new_orders');
    
    $oid = $this->db->insert_id();
    
    //insert orderdetail
    foreach ($pid_quan as $pid => $quantity){
      $data = $this->product_model->init_all_from_pid($pid);
      $final_price = $data['product']['final_price'];
      $this->db->set('price',$final_price);
      $this->db->set('quantity',$quantity);
      $this->db->set('pid',$pid);
      $this->db->set('oid',$oid);
      $this->db->insert('new_orderdetail');
    }

    //delete cart info
    $this->db->where('uid',$uid);
    $this->db->delete('cart');
    $this->db->trans_complete();
    
    if ($this->db->trans_status() === FALSE){
      log_message('error',$this->db->_error_message());
      return false;
    } else {
      return true;
    }

  }
  

}

?>
