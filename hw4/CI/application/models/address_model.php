<?php

class Address_Model extends CI_Model {

  function __construct(){
    parent::__construct();
  }

  public function init_from_uid($uid){
    $query = $this->db->get_where('address',Array('uid'=>$uid));
    return $query->row_array();
  }

}
?>
