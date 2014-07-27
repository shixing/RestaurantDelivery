<?php
class Orderdetail_Model extends CI_Model {
  
  function __construct(){
    parent::__construct();
  }
  
  function init_from_odid($odid){
    $query = $this->db->get_where('new_orderdetail', Array('odid'=>$odid));
    $data = $query->row_array();
    return $data;
  }
  

}

?>  
