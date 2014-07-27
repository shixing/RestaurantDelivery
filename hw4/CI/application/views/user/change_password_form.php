<div id="panel-1" class="panel p-normal" style="display:block">
  <?php echo validation_errors(); ?>
  <form action="/hw4/CI/index.php/user/change_password" onsubmit="return vl_password('pw','pw2','mg');" method="post"> 
	<div class="container">
	  <div class="row">
	    <div class="col-2 font-big font-bold">Old Password</div>
	    <div class="col-2"><input id="un" class="input-text font-big" type="password" onKeyUp="vl_string('un','un-ok')" onchange="vl_string('un','un-ok')" maxlength="50" name="old_password"/></div>
	    <div class="col-1 float-right"><img id="un-ok" class="vl-img" src="/hw3/img/bad.png"/></div>
	  </div>

	  <div class="row">
	    <div class="col-2 font-big font-bold">New Password</div>
	    <div class="col-2"><input id="pw" class="input-text font-big" type="password" onKeyUp="vl_string('pw','pw-ok')" onChange="vl_string('pw','pw-ok')" name="new_password_1"/></div>
	    <div class="col-1 float-right"><img id="pw-ok" class="vl-img" src="/hw3/img/bad.png"/></div>
	  </div>

	  <div class="row">
	    <div class="col-2 font-big font-bold">Re-enter Password</div>
	    <div class="col-2"><input id="pw2" class="input-text font-big" type="password" onKeyUp="vl_string('pw2','pw2-ok')" onChange="vl_string('pw2','pw2-ok')" name="new_password"/></div>
	    <div class="col-1 float-right"><img id="pw2-ok" class="vl-img" src="/hw3/img/bad.png"/></div>
	  </div>


	  <div class="row">
	    <div class="col-4"><span id="mg"></span></div>
	    <div class="float-right col-2"><input id="lg-bt" class="form-button green font-big" type="submit" value="Submit"/></div>
	  </div>
	</div>
	<input type="hidden" name="action" value="change_password"/>
  </form>
</div>
