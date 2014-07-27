<div class="panel p-wider">

<?php
foreach ($datas as $data){
  echo $this->display_h->to_product_html($data,$config);
}
?>

</div>
