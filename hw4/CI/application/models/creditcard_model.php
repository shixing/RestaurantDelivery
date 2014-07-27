<?php
class Creditcard_Model extends CI_Model {
  function __construct(){
    parent::__construct();
  }

  function init_from_uid($uid){
    $query = $this->db->get_where('creditcard',Array('uid'=>$uid));
    return $query->row_array();
  }
}
?>
