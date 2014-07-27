<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Generate_h {
	
  public function __construct(){
    $this->CI =& get_instance();
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
  
  
  function button2_generate($value){
    $html = '<input onclick="generate_url()" class="form-button green font-big" type="submit" value="'.$value.'"/>';
    return $html;
  }
  
  
  function input_generate($type,$name,$value,$class){
    $html = '<input type="'.$type.'" name="'.$name.'" class="'.$class.'" value="'.$value.'"/>';
    return $html;
  }

  function input2_generate($type,$name,$value,$class,$id){
    $html = '<input id="'.$id.'" type="'.$type.'" name="'.$name.'" class="'.$class.'" value="'.$value.'"/>';
    return $html;
  }
  
  
  function label_generate($content){
    $html = '<div class="col-3 font-bold font-big">'.$content.'</div>';
    return $html;
  }
  
  function table_generate2($row_head,$rows_html,$row_foot){
    $html = '<table class="table"><thead>'.$row_head.'</thead><tbody>';
    $html .= $rows_html.'</tbody>';
    if (strlen($row_foot)>0){
      $html .= '<tfoot>'.$row_foot.'</tfood>';
    }
    $html .= '</table>';
    return $html;
  }
  

  
  function table_generate($row_head,$row_array){
    $html = '<table class="table"><thead>'.$row_head.'</thead><tbody>';
    foreach($row_array as $row){
      $html .= $row;
    }
    $html .= '</tbody></table>';
    
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
  
  // used in display_cart.php
  function remove_check($url,$button_text,$url2,$button_text2){
    $url2 = "'".$url2."'";
    $button2 = '<input id="lg-bt" class="form-button green font-big" type="button" value="'.$button_text2.'" onclick="window.location.href='.$url2.'"/>';
    $col_button2 = $this->column_generate('col-3 float-right',$button2);
    
    $button = <<<EOF
  <form action="$url" method="POST">
  <input type="hidden" name="remove_all" value="1"/>
  <input id="lg-bt" class="form-button green font-big" type="submit" value="$button_text"/>
  <form>
EOF;
$col_button = $this->column_generate('col-2 float-right',$button);
$html = $this->row_generate($col_button2 . $col_button);
return $html;
}

function buttons_row($url_text){ //generate button in a row
  $html = "";
  foreach($url_text as $url => $text){
    $url = "'".$url."'";
    $button = '<input class="form-button green font-big" type="button" value="'.$text.'" onclick="window.location.href='.$url.'"/>';
    $col_button = $this->column_generate('col-2',$button);
    $html .=  $col_button;
  }
  return $this->row_generate($html);
}

function success_page($err_msg,$url,$button_text){
  $label = '<h4 class="warning">'.$err_msg.'</h4>';
  $col_warning = $this->column_generate('col-8',$label);
  $url = "'".$url."'";
  $button = '<input id="lg-bt" class="form-button green font-big" type="button" value="'.$button_text.'" onclick="window.location.href='.$url.'"/>';
  $col_button = $this->column_generate('col-2 float-right',$button);
  $html = $this->row_generate($col_warning) . $this->row_generate($col_button);
  return $html;
}

function success_page2($err_msg,$url,$button_text,$url2,$button_text2){
  $label = '<h4 class="warning">'.$err_msg.'</h4>';
  $col_warning = $this->column_generate('col-8',$label);

  $url2 = "'".$url2."'";
  $button2 = '<input id="lg-bt" class="form-button green font-big" type="button" value="'.$button_text2.'" onclick="window.location.href='.$url2.'"/>';
  $col_button2 = $this->column_generate('col-3 float-right',$button2);

  $url = "'".$url."'";
  $button = '<input id="lg-bt" class="form-button green font-big" type="button" value="'.$button_text.'" onclick="window.location.href='.$url.'"/>';
  $col_button = $this->column_generate('col-2 float-right',$button);
  if (strlen($err_msg) == 0){
    $html = $this->row_generate($col_button2 . $col_button);
  } else {
    $html = $this->row_generate($col_warning) . $this->row_generate($col_button2 . $col_button);
  }
  return $html;
}

function fail_page($err_msg){
  $label = '<h4 class="warning">'.$err_msg.'</h4>';
  $col_warning = $this->column_generate('col-8',$label);
  $html = $this->row_generate($col_warning);
  return $html;
}

}

?>
