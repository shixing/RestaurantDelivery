<div class="navbar fix-top">
      <div class="navbar-container">
	<a class="navbar-brand" href="/hw4/CI/index.php/display/home">RestaurantDelivery</a>
	<select id="category_menu" class="narbar-content cate_select" onchange="category_menu()">
	  <option value="">Category</option>
	  <?php
	  echo $this->ad_header_h->get_cate_options();
	  ?>
	</select>
      </div>
</div>
<div class="navbar-white fix-top-50">
      <div class = "navbar-container">
	<!-- For the shopping cart -->
	<?php
	$num_items = $this->ad_header_h->get_cart_item_num();
	echo <<< END
	  <a type="button" class="navbar-content" style="padding-top:10;" onclick="window.location.href = '/hw4/CI/index.php/cart/display_cart';"><img src="/hw4/CI/asset/img/cart.png" width="30" height="30"><span class="num_item">$num_items</span></a>
END;
	?>

<?php 
	// make sure you have session variable
	if ($this->session_h->is_login()){
	  echo <<<END
<input type="button" class="navbar-button " value="{$this->session->userdata['user']}" onclick="window.location.href = '/hw4/CI/index.php/user/home';"/>
END;
	} else {
	  echo <<<END
	  <input type="button" class="navbar-button" value="Sign In" onclick="window.location.href = '/hw4/CI/index.php/user/signin';"/>
END;
	}
	?>


	<input type="button" class="navbar-button" value="Sign Out" onclick="window.location.href = '/hw4/CI/index.php/user/signout';"/>	
		
      </div>
</div> <!--navbar-->
