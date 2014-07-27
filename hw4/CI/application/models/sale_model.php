<?php
class Sale_Model extends CI_Model {
  
  function __construct(){
    parent::__construct();
  }

  function init_from_sid($sid){
    $query = $this->db->get_where('sale', Array('sid'=>$sid));
    $data = $query->row_array();
    return $data;
  }

  function init_from_pid($pid){
    $query = $this->db->get_where('sale',Array('pid'=>$pid));
    $data = $query->row_array();
    return $data;
  }

  function get_sid_list(){
    $this->db->select('sid');
    $query = $this->db->get('sale');
    $rows = $query->result_array();
    $sids = Array();
    foreach ($rows as $row){
      $sids[] = $row['sid'];
    }
    return $sids;
  }

}
?>
