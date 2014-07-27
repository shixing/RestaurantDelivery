<?php
class Cart_Model extends CI_Model {
  
  function __construct(){
    parent::__construct();
  }
  
  function get_entries_num($uid){
    $this->db->where('uid',$uid);
    $query = $this->db->get('cart');
    return $query->num_rows();
  }
  
  function init_from_cid($cid){
    $query = $this->db->get_where('cart', Array('cid'=>$cid));
    $data = $query->row_array();
    return $data;
  }

  function init_from_uid_pid($uid,$pid){
    $query = $this->db->get_where('cart', Array('uid'=>$uid,'pid'=>$pid));
    $data = $query->row_array();
    return $data;
  }
  
  function init_from_uid($uid){
    $query = $this->db->get_where('cart', Array('uid'=>$uid));
    $data = $query->result_array();
    return $data;
  }

  function insert_db($uid,$pid,$quantity){
    $this->db->set('uid',$uid);
    $this->db->set('pid',$pid);
    $this->db->set('quantity',$quantity);
    $this->db->insert('cart');
  }

  function update_db($uid,$pid,$quantity){
    $this->db->set('quantity',$quantity);
    $this->db->where('uid',$uid);
    $this->db->where('pid',$pid);
    $this->db->update('cart');
  }

  function delete_db($uid,$pid){
    $this->db->where('uid',$uid);
    $this->db->where('pid',$pid);
    $this->db->delete('cart');
  }

  function delete_db_uid($uid){
    $this->db->where('uid',$uid);
    $this->db->delete('cart');
  }
  
  

}
?>
