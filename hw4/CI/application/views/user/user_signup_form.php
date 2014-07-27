<div class = "panel p-normal">
  <?php echo validation_errors(); ?>
  <?php if (isset($err_msg)){echo $err_msg;} ?>
<?php
  
  $hidden_names = $data['hidden_names'];
  $names = $data['names'];
  $labels = $data['labels'];
  $action = $data['action'];
  $submit_value = $data['submit_value'];
  $update_flag = false;

  if ($hidden_names['action'] == "update"){$update_flag = true;}
  $html = '<form method="POST" enctype="multipart/form-data" action="'.$action.'">';
  
  $keys = array_keys($names);
  for ($i = 0; $i<count($labels); $i+=1){
    if ($update_flag && $keys[$i] == 'password') {continue;}
    $col_label = $this->generate_h->label_generate($labels[$i]);
    $col_input = $this->generate_h->column_generate('col-4',$this->generate_h->input_generate('text',$keys[$i],$names[$keys[$i]],'input-text-small font-big'));
    if ($keys[$i] == 'password'){
      $col_input = $this->generate_h->column_generate('col-4',$this->generate_h->input_generate('password',$keys[$i],$names[$keys[$i]],'input-text-small font-big'));
    }
    $row = $this->generate_h->row_generate($col_label . $col_input);
    $html .= $row;
  }
  //for hidden
  $keys = array_keys($hidden_names);
  for ($i = 0; $i < count($hidden_names);$i += 1){
    $input = $this->generate_h->input_generate('hidden',$keys[$i],$hidden_names[$keys[$i]],'');
    $html .= $input;
  }
  //for buttion
  $row_button = $this->generate_h->row_generate($this->generate_h->column_generate('col-2 float-right',$this->generate_h->button_generate($submit_value)));
  $html .= $row_button;
  $html .= '</form>';
  echo $html;
?>
</div>

