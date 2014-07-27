<?php
class Category_Model extends CI_Model {

  function __construct(){
    parent::__construct();
  }
  
  function get_entries()
  {
    $query = $this->db->get('category');
    return $query->result_array();
  }
  
  function get_entries_num(){
    return $this->db->count_all('category');
  }
  
  function init_from_cid($cid){
    $query = $this->db->get_where('category', Array('cid'=>$cid));
    $data = $query->row_array();
    return $data;
  }

}
?>
