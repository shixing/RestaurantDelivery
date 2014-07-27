<?php
class User_Model extends CI_Model {
  
  function __construct(){
    parent::__construct();
    $this->load->model('creditcard_model');
    $this->load->model('address_model');
  }
  
  function check_password($uid,$password){
    $this->db->where('uid',$uid);
    $this->db->where('password',"password({$this->db->escape($password)})",FALSE);
    $query = $this->db->get('user');
    if ($query->num_rows() == 1 ){
      return true;
    } else {
      return false;
    }
  }
  
  function login($username,$password) {
    $this->db->where('email',$username);
    $this->db->where('password',"password({$this->db->escape($password)})",FALSE);
    $query = $this->db->get('user');
    if ($query->num_rows() == 1 ){
      $rows = $query->result_array();
      $row = $rows[0];
      $this->session_h->login_s($row['email'], $row['uid'], $row['fn'],$row['ln']);
      return true;
    } else {
      return false;
    }
  }

  function init_from_uid($uid){
    $query = $this->db->get_where('user',Array('uid'=>$uid));
    return $query->row_array();
  }

  function init_data(){
    $labels = Array('Email','Password','First Name','Last Name','Address','City','State','Zipcode','Credit Card','Name on Card','Expire Month(MM)','Expire Year(YY)');
    $names = Array('email'=>'','password'=>'','fn'=>'','ln'=>'','street'=>'','city'=>'','us_state'=>'','zipcode'=>'','card_number'=>'','name'=>'','expire_month'=>'','expire_year'=>'');
    $action = '/hw4/CI/index.php/user/signup';
    $hidden_names = Array('action'=>'create','uid'=>'','aid'=>'','cid'=>'');
    $submit_value = 'Sign Up';
    $types = Array('email'=>'t','password'=>'t','fn'=>'t','ln'=>'t','street'=>'t','city'=>'t','us_state'=>'t','zipcode'=>'t','card_number'=>'t','name'=>'t','expire_month'=>'i','expire_year'=>'i');
    
    $data = Array();
    $data['labels'] = $labels;
    $data['names'] = $names;
    $data['action'] = $action;
    $data['submit_value'] = $submit_value;
    $data['hidden_names'] = $hidden_names;
    $data['types'] = $types;
    return $data;
  }

  function init_data_update($data){
    $data['hidden_names']['action'] = 'update';
    $data['action'] = '/hw4/CI/index.php/user/edit_profile';
    $data['submit_value'] = "Update";
    return $data;
  }

  function init_data_from_post(){
    $data = $this->init_data();
    $keys = array_keys($data['names']);
    for ($i = 0; $i < count($data['names']); $i+=1 ){
      $value = $this->input->post($keys[$i]);
      $data['names'][$keys[$i]] = $value;
    }
    //for hidden values;
    $keys = array_keys($data['hidden_names']);
    for ($i = 0; $i < count($keys); $i+=1 ){
      $value = $this->input->post($keys[$i]);
      $data['hidden_names'][$keys[$i]] = $value;
    }
    return $data;
  }

  function init_data_from_db($uid){
    $data = $this->init_data();
    $user_data = $this->user_model->init_from_uid($uid);
    $creditcard_data = $this->creditcard_model->init_from_uid($uid);
    $address_data = $this->address_model->init_from_uid($uid);
    $data['hidden_names']['uid'] = $uid;
    $data['hidden_names']['cid'] = $creditcard_data['cid'];
    $data['hidden_names']['aid'] = $address_data['aid'];
    
    $data['names']['email'] = $user_data['email'];
    $data['names']['fn'] = $user_data['fn'];
    $data['names']['ln'] = $user_data['ln'];
    
    $data['names']['card_number'] = $creditcard_data['card_number'];
    $data['names']['name'] = $creditcard_data['name'];
    $data['names']['expire_month'] = $creditcard_data['expire_month'];
    $data['names']['expire_year'] = $creditcard_data['expire_year'];
    
    $data['names']['street'] = $address_data['street'];
    $data['names']['city'] = $address_data['city'];
    $data['names']['us_state'] = $address_data['us_state'];
    $data['names']['zipcode'] = $address_data['zipcode'];

    return $data;
  }




  function get_password_hash($password){
    $this->db->select("password({$this->db->escape($password)}) as phash",false);
    $query = $this->db->get();
    $row = $query->row_array();
    return $row['phash'];
  }

  

  function insert_three_table($data){
    $this->db->trans_start();

    $user_data = Array('email' => $data['names']['email'], 
		       'password'=> $this->get_password_hash($data['names']['password']),
		       'fn' => $data['names']['fn'],
		       'ln' => $data['names']['ln']);
    $this->db->insert('user',$user_data);

    $uid = $this->db->insert_id();
    
    $address_data = Array('street' => $data['names']['street'],
			  'city' => $data['names']['city'],
			  'us_state' => $data['names']['us_state'],
			  'zipcode' => $data['names']['zipcode'],
			  'uid' => $uid
			  );

    $this->db->insert('address',$address_data);
    
    $creditcard_data = Array('card_number'=> $data['names']['card_number'],
			     'name' => $data['names']['name'],
			     'expire_month' => $data['names']['expire_month'],
			     'expire_year' => $data['names']['expire_year'],
			     'uid' => $uid
			     );

    $this->db->insert('creditcard',$creditcard_data);
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE){
      log_message('error',$this->db->_error_message());
      return false;
    } else {
      return true;
    }

  }

  function update_three_table($data){
    $this->db->trans_start();

    $user_data = Array('email' => $data['names']['email'], 
		       'fn' => $data['names']['fn'],
		       'ln' => $data['names']['ln']);
    $this->db->where('uid',$data['hidden_names']['uid']);
    $this->db->update('user',$user_data);
    
    $address_data = Array('street' => $data['names']['street'],
			  'city' => $data['names']['city'],
			  'us_state' => $data['names']['us_state'],
			  'zipcode' => $data['names']['zipcode'],
			  );
    $this->db->where('aid',$data['hidden_names']['aid']);
    $this->db->update('address',$address_data);
    
    $creditcard_data = Array('card_number'=> $data['names']['card_number'],
			     'name' => $data['names']['name'],
			     'expire_month' => $data['names']['expire_month'],
			     'expire_year' => $data['names']['expire_year'],
			     );
    $this->db->where('cid',$data['hidden_names']['cid']);
    $this->db->update('creditcard',$creditcard_data);
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE){
      log_message('error',$this->db->_error_message());
      return false;
    } else {
      return true;
    }

  }

  function change_password($uid,$new_password){
    $phash = $this->get_password_hash($new_password);
    $this->db->set('password',$phash);
    $this->db->where('uid',$uid);
    $this->db->update('user');
  }
  
  


}
?>
