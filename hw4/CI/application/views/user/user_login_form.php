<div id="panel-1" class="panel p-normal" style="display:block">
  <?php echo validation_errors(); ?>
  <?php 
  $attr = Array('onsubmit'=>'return vl_all();');
  echo form_open('user/signin')
  ?>
	<div class="container">
	  <div class="row">
	    <div class="col-2 font-big font-bold">Email</div>
	    <div class="col-2"><input id="un" class="input-text font-big" type="text" onKeyUp="vl_string('un','un-ok')" onchange="vl_string('un','un-ok')" maxlength="50" name="username"/></div>
	    <div class="col-1 float-right"><img id="un-ok" class="vl-img" src="/hw3/CI/asset/bad.png"/></div>
	  </div>

	  <div class="row">
	    <div class="col-2 font-big font-bold">Password</div>
	    <div class="col-2"><input id="pw" class="input-text font-big" type="password" onKeyUp="vl_string('pw','pw-ok')" onChange="vl_string('pw','pw-ok')" name="password"/></div>
	    <div class="col-1 float-right"><img id="pw-ok" class="vl-img" src="/hw4/CI/asset/img/bad.png"/></div>
	  </div>

	  <div class="row">
	    <div class="col-4"><span id="mg"></span></div>
	    <div class="float-right col-2"><input id="lg-bt" class="form-button green font-big" type="submit" value="Sign In"/></div>
	    <div class="float-right col-2"><input id="lg-bt" class="form-button green font-big" type="button" value="Sign Up" onclick="window.location.href = '/hw4/CI/index.php/user/signup'"/></div>
	  </div>
	</div>
  </form>
</div>
