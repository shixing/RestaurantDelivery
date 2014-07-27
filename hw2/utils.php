<?php
function check_timeout($ss)
{
  $log_time = $ss['time'];
  if (strlen($log_time)>0){
    $current = time();
    if ($current - $log_time > 300){
      header('Location: time_out.php');
      exit();
    }
  }
}



function row_generate($content){
  $html = '<div class="row">'. $content . '</div>';
  return $html;
}

function column_generate($class, $content){
  $html = '<div class="' . $class . '">' .$content . '</div>';
  return $html;
}

function select_generate($name, $values, $texts, $selected){
  $html = '<select class="input-select font-normal" name="'.$name.'">';
  for ($i = 0; $i < count($values) ; $i += 1){
    $value = $values[$i];
    $text = $texts[$i];
    if ($value == $selected){
      $html .= '<option value="'.$value.'" selected>'.$text.'</option>';
    } else {
      $html .= '<option value="'.$value.'">'.$text.'</option>';
    }
  }
  $html .= '</select>';
  return $html;
}

function form_generate($content,$action){
  $html = '<form method="POST" enctype="multipart/form-data" action="'.$action.'">'.$content;
  $html .= '<input type="hidden" name="table_request" value="1"/>';
  $html .= '</form>';
  return $html;
}

function button_generate($value){
  $html = '<input class="form-button green font-big" type="submit" value="'.$value.'"/>';
  return $html;
}
function guide_generate_worker($values1,$texts1,$values2,$texts2){
  // operations;
  $select_operation = select_generate('operation',$values1,$texts1,'');
  $col_operation = column_generate('col-3',$select_operation);
  //table
  $select_table = select_generate('table',$values2,$texts2,'');
  $col_table = column_generate('col-3',$select_table);
  //h-fill
  $col_fill = column_generate('col-1','&nbsp;');
  //button
  $button = button_generate('Submit');
  $col_button = column_generate('col-2 float-right',$button);
  //$row
  $row = row_generate($col_operation . $col_table . $col_button);
  $action = 'user_home.php';
  $form = form_generate($row,$action);
  return $form;
}

function guide_generate($type){
  if ($type=='admin'){
    $values1 = Array('null','Create','Modify','Delete');
    $texts1 = Array('Choose Operation','create','modify','delete');
    $values2 = Array('null','employee');
    $texts2 = Array('Choose Table','employee');
    return guide_generate_worker($values1,$texts1,$values2,$texts2);
  } else if ($type == 'manager') {
    $values1 = Array('null','View');
    $texts1 = Array('Choose Operation','view');
    $values2 = Array('null','product','employee','sale','category','orders','product_sold');
    $texts2 = Array('Choose Table','product','employee','sale','category','orders','product sold');
    return guide_generate_worker($values1,$texts1,$values2,$texts2);
  } else if ($type == 'sale_manager'){
    $values1 = Array('null','Create','Modify','Delete');
    $texts1 = Array('Choose Operation','create','modify','delete');
    $values2 = Array('null','product','sale','category');
    $texts2 = Array('Choose Table','product','sale','category');
    return guide_generate_worker($values1,$texts1,$values2,$texts2);
  }
}

// for DB

function get_connect(){
  $con = mysql_connect('localhost:5000','root','');
  mysql_select_db('company', $con);
  return $con;
}

function vl_operation_table($operation,$table){
  if ( $operation == 'null' or $table == 'null'){
    return false;
  } else {
    return true;
    }
}

function input_generate($type,$name,$value,$class){
  $html = '<input type="'.$type.'" name="'.$name.'" class="'.$class.'" value="'.$value.'"/>';
  return $html;
}

function label_generate($content){
  $html = '<div class="col-3 font-bold font-big">'.$content.'</div>';
  return $html;
}

function get_list($table,$col){
  $con = get_connect();
  $sql = "select $col from $table";
  $res = mysql_query($sql);
  $list = Array();
  $list[] = '';
  while ($res && $row = mysql_fetch_array($res)){
    $list[] = $row[0];
  }
  return $list;
}

function table_create($table,$fill_values){
  $con = get_connect();
  $sql = 'select column_name,data_type,column_key,is_nullable from information_schema.columns where table_name = "'. $table .'";';
  $res = mysql_query($sql);
  $content = '';
  $table_title = row_generate(label_generate('Table') . label_generate($table));
  $input_table = input_generate('hidden','table',$table,'');
  $content = $table_title . $input_table;
  while ( $row = mysql_fetch_array($res) ) {
    $col_name = $row[0];
    $col_type = $row[1];
    $col_key = $row[2];
    $is_null = $row[3];
    $fill_value = $fill_values[$col_name];
    if ($col_key == 'PRI'){continue;}
    $label_name = $col_name;
    if ($is_null=='NO'){
      $label_name .= '*';
    }
    if ($col_type == 'date'){
      $label_name .= '(YYYYMMDD)';
    }
    $col_tag = label_generate($label_name);

    if ($col_key == 'MUL'){
      if ($col_name == 'cid') { //category foreign key
	$list = get_list('category','cid');
	$texts = get_list('category','name');
	$input = select_generate($col_name,$list,$texts,$fill_value);
	$col_input = column_generate('col-2',$input);
	$row = row_generate($col_tag.$col_input);
	$content .= $row;
      }else if ($col_name == 'pid') { // product foreign ke
	$list = get_list('product','pid');
	$texts = get_list('product','product_name');

	$input = select_generate($col_name,$list,$texts,$fill_value);
	$col_input = column_generate('col-2',$input);
	$row = row_generate($col_tag.$col_input);
	$content .= $row;	
      }
    } else if ($col_name == 'product_img') {
      $input = input_generate('file',$col_name,$fill_value,'');
      $col_input = column_generate('col-4',$input);
      $row = row_generate($col_tag.$col_input);
      $content .= $row;
    } else if($table == 'employee' && $col_name == 'type'){
      $list = Array('admin','manager','sale_manager');
      $input = select_generate($col_name,$list,$list,$fill_value);
      $col_input = column_generate('col-2',$input);
      $row = row_generate($col_tag.$col_input);
      $content .= $row;
    }else {
      $input = input_generate('text',$col_name,$fill_value,'input-text font-big');
      $col_input = column_generate('col-4',$input);
      $row = row_generate($col_tag.$col_input);
      $content .= $row;
    }
  }
  $button = button_generate('Create');
  $col_button = column_generate('col-2',$button);
  $row_button = row_generate($col_button);
  $content .= $row_button;
  $action = 'create.php';
  $form = form_generate($content,$action);
  mysql_close($con);
  return $form;
}

function table_generate($row_head,$row_array){
  $html = '<table class="table"><thead>'.$row_head.'</thead><tbody>';
  foreach($row_array as $row){
    $html .= $row;
  }
  $html .= '<tbody></table>';
  
  return $html;
}

function table_row_generate($column_contents){
  $html = '<tr>';
  foreach ($column_contents as $c){
    $html .= '<td>'.$c.'</td>';
  }
  $html .= '</tr>';
  return $html;
}

function get_primary_key($table){
  $m = Array( 'employee' => 'uid',
	      'category' => 'cid',
	      'product' => 'pid',
	      'sale' => 'sid');
  return $m[$table];
}

function table_list($table,$radio,$form_action,$button_text){
  // $ratio = 1:ratio 2:checkbox 0:list
  $con = get_connect();
  $sql = "select * from $table;";
  $res = mysql_query($sql);
  $thead = '';
  $trows = Array();
  $thead_done = false;
  
  while ($row = mysql_fetch_assoc($res)){
    $thead_cols = Array();
    $trow_cols = Array();

    if ($radio == 1){
      $thead_cols[] = '';
      $trow_cols[] = input_generate('radio',get_primary_key($table), $row[get_primary_key($table)],'input-radio');
    } else {
      $thead_cols[] = '';
      $trow_cols[] = input_generate('checkbox',get_primary_key($table).'[]',$row[get_primary_key($table)],'input-checkbox');
    }

    foreach ($row as $title => $value){
      if ($title == 'password'){continue;}
      if (!$thead_done){
	$thead_cols[] = $title;
      }
      $trow_cols[] = $value;
    }

    if (!$thead_done){
      $thead = table_row_generate($thead_cols);
      $thead_done = true;
    }
    $tmp = table_row_generate($trow_cols);
    $trows[] = $tmp;

  }
  $html_table = table_generate($thead,$trows);
  $input_table = input_generate('hidden','table',$table,'');
  $form_content = $input_table . $html_table;
  if ($form_action != ''){
    $form_content .= row_generate(column_generate('col-2',button_generate($button_text)));
  }
  $form = form_generate($form_content,$form_action);
  return $form;
}


function operate_table($operation,$table){
  if ($operation == 'Create'){
    return table_create($table,Array());
  }
  if ($operation == 'Modify'){
    return table_list($table,1,"modify_record.php",'Modify');
  }
  if ($operation == 'Delete'){
    return table_list($table,2,"delete.php",'Delete');
  }
  if ($operation == 'View'){
    return search_table($table);
  }
}

// for create.php

function insert_table($p,$f){
  $table = $p['table'];
  $con = get_connect();
  $sql = 'select column_name,data_type,column_key,is_nullable from information_schema.columns where table_name = "'. $table .'";';
  $res = mysql_query($sql);
  $suc = true;
  $err_msg = '';
  $result = Array();
  while ( $row = mysql_fetch_array($res) ) {
    $col_name = $row[0];
    $col_type = $row[1];
    $col_key = $row[2];
    $is_null = $row[3];
    // check for null;
    $value = $p[$col_name];
    if ($col_key == 'PRI') {continue;}
    if (strlen($value) == 0 && $is_null == 'NO'){
      $suc = false;
      $err_msg = "$col_name is null, invalid input";
      return Array($suc,$err_msg);
    }
    // check for type
    if ($col_type == 'int'){
      if (ctype_digit($value)){
	$intvalue = intval($value);
	// check for percent
	if ($col_name == 'percent' && ($intvalue > 100 || $intvale < 0)) {
	  $suc = false;
	  $err_msg = "$col_name larger than 100 or less than 0, invalid input";
	  return Array($suc, $err_msg);
	} 
	$result[$col_name] = $value;
      } else{
	$suc = false;
	$err_msg = "$col_name is not int, invalid input";
	return Array($suc, $err_msg);
      }
    }
    // check for file
    // check for date    
    if ($col_type == 'date'){
      if (strlen($value) != 8 || !checkdate(substr($value,4,2),
					 substr($value,6,2),
					 substr($value,0,4))) {
	$suc = false;
	$err_msg = "$col_name date format error, invalid input";
	return Array($suc, $err_msg);
      } else {
	$result[$col_name] = $value;
      }
    }
    // check for text
    if ($col_type=='text'){
      if ($col_name == 'product_img' ){
      //upload file
	if ($f[$col_name]['error'] > 0){
	  } else {
	  move_uploaded_file($f[$col_name]["tmp_name"], "img/" . $f[$col_name]["name"]);
	  $result[$col_name] = "'".'img/'.$f[$col_name]["name"]."'";
	}
      } else {
      	$result[$col_name] = "'".$value."'";
      }
    }
    // check for double
    if ($col_type=='double'){
      if (!is_numeric($value)){
	$suc = false;
	$err_msg = "$col_name is not double format, invalid input";
	return Array($suc, $err_msg);
      } else {
	$result[$col_name] = $value;
      }
    }
  }

  $col_names = '';
  $col_values = '';
  foreach ($result as $name => $value){
    $col_names .= $name . ',';
    if ($name == 'password'){
      $col_values .= "password($value),";
    } else if ($name=="start_date" || $name =="end_date") {
      $col_values .= "date($value),";
    } else {
      $col_values .= $value . ',';
    }
  }
  $col_names = rtrim($col_names, ",");
  $col_values = rtrim($col_values, ",");
  $sql = "insert into $table($col_names) values($col_values);";
  $res = mysql_query($sql);
  if (!$res){
    
    $suc = false;
    $err_msg = "DB error:".mysql_error().$sql;
    return Array($suc, $err_msg);
  }
  mysql_close($con);
  return Array(true,'Successfully create record!');
}

function success_page($err_msg,$url,$button_text){
  $label = '<h4 class="warning">'.$err_msg.'</h4>';
  $col_warning = column_generate('col-8',$label);
  $url = "'".$url."'";
  $button = '<input id="lg-bt" class="form-button green font-big" type="button" value="'.$button_text.'" onclick="window.location.href='.$url.'"/>';
  $col_button = column_generate('col-2 float-right',$button);
  $html = row_generate($col_warning) . row_generate($col_button);
  return $html;
}
function fail_page($err_msg){
  $label = '<h4 class="warning">'.$err_msg.'</h4>';
  $col_warning = column_generate('col-8',$label);
  $html = row_generate($col_warning);
  return $html;
}

function test(){
  $a = Array();
  $a[] = '1';
  $a[] = '2';
  $h = table_row_generate($a);
  echo $h;
}

// for delete.php

function delete_table($p){
  $suc = true;
  $err_msg = 'Delete successfully!';
  $con = get_connect();
  $table = $p['table'];
  $primary = get_primary_key($table);
  $ids = $p[$primary];
  if (!ids || count($ids)==0){
    $suc = false;
    $err_msg = 'Invalid Input!';
    return Array($suc,$err_msg);
   
  }
  foreach ($ids as $id){
    $sql = "delete from $table where $primary=$id;";
    $res = mysql_query($sql);
    if (!$res){
      $suc = false;
      $err_msg = 'DB error!';
      return Array($suc,$err_msg);
    }
  }
  return Array($suc,$err_msg);
}

// for modify.php

function vl_modify($p){
  $suc = true;
  $err_msg = '';
  $con = get_connect();
  $table = $p['table'];
  $primary = get_primary_key($table);
  $id = $p[$primary];
  if (strlen($id) == 0 ){
    $suc = false;
    $err_msg = 'Invalid Input!';
    return Array($suc,$err_msg);
  }
  $sql = "select * from $table where $primary = $id;";
  $res = mysql_query($sql);
  if (!$res){
    $suc = false;
    $err_msg = "No such $primary:$id Input!";
    return Array($suc,$err_msg);

  }

  return Array($suc,$err_msg);
}

function record_modify($p){
  $con = get_connect();
  $table = $p['table'];
  $primary = get_primary_key($table);
  $id = $p[$primary];
  $sql = "select * from $table where $primary = $id;";
  $res = mysql_query($sql);
  $row = mysql_fetch_assoc($res);
  if (strlen($row['password'])>0){
    $row['password'] = '';
  }
  return table_modify($table,$row,$id);
}

function table_modify($table,$fill_values,$primary_id){
  $con = get_connect();
  $sql = 'select column_name,data_type,column_key,is_nullable from information_schema.columns where table_name = "'. $table .'";';
  $res = mysql_query($sql);
  $content = '';
  $table_title = row_generate(label_generate('Table') . label_generate($table));
  $input_table = input_generate('hidden','table',$table,'');
  $input_id = input_generate('hidden',get_primary_key($table),$primary_id,'');
  $content = $table_title . $input_table. $input_id;
  while ( $row = mysql_fetch_array($res) ) {
    $col_name = $row[0];
    $col_type = $row[1];
    $col_key = $row[2];
    $is_null = $row[3];
    $fill_value = $fill_values[$col_name];
    if ($col_key == 'PRI'){continue;}
    $label_name = $col_name;
    if ($is_null=='NO'){
      $label_name .= '*';
    }
    if ($col_type == 'date'){
      $label_name .= '(YYYYMMDD)';
    }
    $col_tag = label_generate($label_name);

    if ($col_key == 'MUL'){
      if ($col_name == 'cid') { //category foreign key
	$list = get_list('category','cid');
	$texts = get_list('category','name');
	$input = select_generate($col_name,$list,$texts,$fill_value);
	$col_input = column_generate('col-2',$input);
	$row = row_generate($col_tag.$col_input);
	$content .= $row;
      }else if ($col_name == 'pid') { // product foreign key
	$list = get_list('product','pid');
	$texts = get_list('product','product_name');
	$input = select_generate($col_name,$list,$texts,$fill_value);
	$col_input = column_generate('col-2',$input);
	$row = row_generate($col_tag.$col_input);
	$content .= $row;	
      }
    } else if ($col_name == 'product_img') {
      $input = input_generate('file',$col_name,$fill_value,'');
      $col_input = column_generate('col-4',$input);
      $row = row_generate($col_tag.$col_input);
      $content .= $row;
    } else if($table == 'employee' && $col_name == 'type'){
      $list = Array('admin','manager','sale_manager');
      $input = select_generate($col_name,$list,$list,$fill_value);
      $col_input = column_generate('col-2',$input);
      $row = row_generate($col_tag.$col_input);
      $content .= $row;
    }else {
      $input = input_generate('text',$col_name,$fill_value,'input-text font-big');
      $col_input = column_generate('col-4',$input);
      $row = row_generate($col_tag.$col_input);
      $content .= $row;
    }
  }
  $button = button_generate('Update');
  $col_button = column_generate('col-2',$button);
  $row_button = row_generate($col_button);
  $content .= $row_button;
  $action = 'modify.php';
  $form = form_generate($content,$action);
  mysql_close($con);
  return $form;
}

// for modify.php

function modify_record($p,$f){
  
  $table = $p['table'];
  $primary = get_primary_key($table);
  $primary_id = $p[$primary];
  // create
  $res = insert_table($p,$f);
  $suc = $res[0];
  $err_msg = $res[1];
  if (!$suc){
    return $res;
  }
  $con = get_connect();
  $sql = "delete from $table where $primary = $primary_id;";
  $res = mysql_query($sql);  
  $suc = true;
  $err_msg = 'Update Successfully!';
  if (!$res){
    $suc = false;
    $err_msg = "DB error:".mysql_error();
    return Array($suc,$err_msg);
  }
  return Array($suc,$err_msg);
}

// for 

function input2_generate($type,$name,$value,$class,$id){
  $html = '<input id="'.$id.'" type="'.$type.'" name="'.$name.'" class="'.$class.'" value="'.$value.'"/>';
  return $html;
}


function gso($label_content,$type,$name,$values,$texts){
  $row_content = '';
  $label = label_generate($label_content);
  $row_content .= $label;
  if ($type == 'text') { //name
    $input = column_generate('col-2',input2_generate('text',$name,'','input-text-small font-big',$name));
    $row_content .= $input;
  } else if ($type == 'range') { //range
    $input1 = column_generate('col-2',input2_generate('text',$name,'','input-text-small font-big',$name.'-s'));
    $input2 = column_generate('col-2',input2_generate('text',$name,'','input-text-small font-big',$name.'-b'));
    $row_content .= $input1 . $input2;
  } else if ($type == 'select') { //select
    $select = column_generate('col-2',select_generate($name,$values,$texts,''));
    $row_content .= $select;
  }
  $html = row_generate($row_content);
  return $html;
}


function search_table($table){
  $html = $table_input = input2_generate('hidden','table',$table,'','table');   $html .= row_generate(label_generate('Table') . label_generate($table));
  if ($table == 'employee'){
    //employee type
    $values = Array('','admin','manager','sale_manager');
    $html .= gso('type','select','type',$values, $values);
    //pay range
    $name = 'payment';
    $html .= gso($name,'range',$name,Array(),Array());
  } else if ($table == 'product') {
    //product name
    $name = 'product_name';
    $html .= gso($name,'text',$name,Array(),Array());
    //product category
    $name = 'cid';
    $list = get_list('category','cid');
    $texts = get_list('category','name');
    $html .= gso('category','select',$name,$list,$texts);
    //product price range
    $name = 'price';
    $html .= gso($name,'range',$name,Array(),Array());
  } else if ($table == 'sale') {
    //product name
    $name = 'product_name';
    $html .= gso($name,'text',$name,Array(),Array());
    //product category
    $name = 'cid';
    $list = get_list('category','cid');
    $texts = get_list('category','name');
    $html .= gso('category','select',$name,$list,$texts);
    //product percent range
    $name = 'percent';
    $html .= gso($name,'range',$name,Array(),Array());
    //product start date
    $name = 'start_date';
    $html .= gso($name,'range',$name,Array(),Array());
    //product end date
    $name = 'end_date';
    $html .= gso($name,'range',$name,Array(),Array());
  } else if ($table == 'category') {
    //category name
    $name = 'name';
    $html .= gso($name,'text',$name,Array(),Array());
  } else if ($table == 'orders') {
    //order_date
    $label = 'order_date (YYYYMMDD)'; 
    $name = 'order_date';
    $html .= gso($label,'range',$name,Array(),Array());
  } else if ($table == 'product_sold') {
    //product name;
    $name = 'product_name';
    $html .= gso($name,'text',$name,Array(),Array());
    //product category;
    $name = 'cid';
    $list = get_list('category','cid');
    $texts = get_list('category','name');
    $html .= gso('category','select',$name,$list,$texts);
    //date range;
    $label = 'order_date (YYYYMMDD)'; 
    $name = 'order_date';
    $html .= gso($label,'range',$name,Array(),Array());
    // group_by;
    $name = 'group_by';
    $list = Array('pid','cid');
    $texts = Array('Product Name','Category');
    $html .= gso('Group By','select',$name,$list,$texts);
  }
  $html .= row_generate(column_generate('col-2',button2_generate('Search')));
  $html .= '<div id="result"></div>';
  return $html;
}

function button2_generate($value){
  $html = '<input onclick="generate_url()" class="form-button green font-big" type="submit" value="'.$value.'"/>';
  return $html;
}

function format_sql($sql){
  // $ratio = 1:ratio 2:checkbox 0:list
  $con = get_connect();
  $res = mysql_query($sql);
  $thead = '';
  $trows = Array();
  $thead_done = false;
  
  while ($row = mysql_fetch_assoc($res)){
    $thead_cols = Array();
    $trow_cols = Array();

    foreach ($row as $title => $value){
      if ($title == 'password'){continue;}
      if (!$thead_done){
	$thead_cols[] = $title;
      }
      $trow_cols[] = $value;
    }

    if (!$thead_done){
      $thead = table_row_generate($thead_cols);
      $thead_done = true;
    }
    $tmp = table_row_generate($trow_cols);
    $trows[] = $tmp;

  }
  $html_table = table_generate($thead,$trows);
  return $html_table;
}





?>
