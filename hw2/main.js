//global data
var users={};
var user_infos={};

function vl_repeat(id,id2,img_id){
    t1 = document.getElementById(id).value;
    t2 = document.getElementById(id2).value;
    img = document.getElementById(img_id);
    if (t1==t2){
	set_img(img_id,1);
	return true;
    } else {
	set_img(img_id,2);
	return false;
    }
}

function set_img(id,ok){
    img = document.getElementById(id);
    if (ok==1){
	img.src="ok.png";
	img.style.display="block";
    } else if (ok==2){
	img.src="bad.png";
	img.style.display="block";
    } else if (ok==0){
	img.style.display="none";
    }
}

function test_repeat(un){
    for (eun in users){
	if (eun==un){
	    return false;
	}
    }
    return true;
}


function vl_username(){
    p = /^[a-zA-Z]+[a-zA-Z0-9\-]*$/g;
    t = document.getElementById('un').value;
    if (t.search(p) == 0 && test_repeat(t)){
	set_img("un-ok",1);
	return true;
    }
    else
    {
	set_img("un-ok",2);
	return false;
    }
}

function vl_email(){
    p = /^\S+@\S+$/i;
    t = document.getElementById('em').value;
    if (t.search(p) == 0){
	set_img("em-ok",1);
	return true
    }
    else
    {
	set_img("em-ok",2);
	return false;
    }
}

function vl_password(){
    p = /^\S+$/g;
    t = document.getElementById('pw').value;
    if (t.search(p) == 0){
	set_img("pw-ok",1);
	return true;
    }
    else
    {
	set_img("pw-ok",2);
	return false;
    }
}

function vl_user_all(){
    if (! vl_username()) {return false;}
    if (! vl_email()) {return false;}
    if (! vl_repeat('em','re','re-ok')) {return false;}
    if (! vl_password()) {return false;}
    if (! vl_repeat('pw','rp','rp-ok')) {return false;}
    return true;
}

function vl_name(id,img_id){
    p = /^[a-zA-Z]+$/g;
    t = document.getElementById(id).value;
    if (t.search(p)==0){
	set_img(img_id,1);
	return true;
    }else{
	set_img(img_id,2);
	return false;
    }
}

function vl_check(name){
    radios = document.getElementsByName(name);
    for (i =0;i<radios.length;i+=1){
	r = radios[i]
	if (r.checked){
	    return true;
	}
    }
    return false;
}

function vl_select(sid){
    s = document.getElementById(sid).value;
    if (s=='null'){
	return false;
    }else{
	return true;}
}

function isValidDate(ds)
{
    p = /^\d{1,2}\d{1,2}\d{4}$/g;
    if(!p.test(ds))
        return false;

    // Parse the date parts to integers
    var day = parseInt(ds.substr(2,2), 10);
    var month = parseInt(ds.substr(0,2), 10);
    var year = parseInt(ds.substr(4,4), 10);

    // Check the ranges of month and year
    if(year < 1000 || year > 3000 || month == 0 || month > 12)
        return false;

    var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

    // Adjust for leap years
    if(year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
        monthLength[1] = 29;

    // Check the range of the day
    return day > 0 && day <= monthLength[month - 1];
}


function vl_date(id,img_id){
    t = document.getElementById(id).value;
    if (isValidDate(t)){
	set_img(img_id,1);
	return true;
    }else{
	set_img(img_id,2);
	return false;
    }
}


function vl_user_info_all(){
    if (!vl_name('fn','fn-ok')) {return false;}
    if (!vl_name('ln','ln-ok')) {return false;}
    if (!vl_check('gender')) {return false;}
    if (!vl_select('sm')) {return false;}
    if (!vl_select('sd')) {return false;}
    if (!vl_select('sy')) {return false;}
    if (!vl_check('privacy')) {return false;}
    if (!vl_check('connect')) {return false;}
    if (!vl_check('piu')) {return false;}
    if (!vl_check('contact')) {return false;}
    return true;
}

function reset_user_info(){
    mg2("");
    img_ids = ['fn-ok','ln-ok','lb-ok'];
    for (i = 0;i<img_ids.length;i+=1){
	set_img(img_ids[i],0);
    }
    text_ids = ['fn','ln','lb','sdn'];
    for (i = 0;i<text_ids.length;i+=1){
	document.getElementById(text_ids[i]).value = "";
    }
    select_ids = ['sm','sd','sy','sc','ss']
    for (i = 0;i<select_ids.length;i+=1){
	document.getElementById(select_ids[i]).value = "null";
    }
    mul_select_ids = ['scdt','scdp'];
    for (i = 0;i<mul_select_ids.length;i+=1){
	m = document.getElementById(mul_select_ids[i]);
	for (j =0;j<m.length;j+=1){
	    m[j].selected=false;
	}
    }
    radio_names = ['gender','privacy','piu','contact'];
    check_names = ['whom','whoshare','believe','connect'];
    tmp = []
    tmp=tmp.concat(radio_names);
    tmp=tmp.concat(check_names);
    for (i=0;i<tmp.length;i+=1){
	n = document.getElementsByName(tmp[i]);
	for (j=0;j<n.length;j+=1){
	    n[j].checked=false;
	}
    }
}



function save_user_info(){
    success = vl_user_info_all();
    if (!success){mg2('Invalid Input!'); return;}
    user_info={}
    text_ids = ['fn','ln','lb','sdn'];
    select_ids = ['sm','sd','sy','sc','ss'];
    val = []
    val = val.concat(text_ids);
    val = val.concat(select_ids);
    for (i = 0;i<val.length;i+=1){
	v = document.getElementById(val[i]).value;
	user_info[val[i]] = v;}
    mul_select_ids = ['scdt','scdp'];
    for (i = 0;i<mul_select_ids.length;i+=1){
	m = document.getElementById(mul_select_ids[i]);
	vs = []
	for (j =0;j<m.length;j+=1){
	    if(m[j].selected){vs.push(m[j].value);}
	}
	user_info[mul_select_ids[i]]=vs;
    }
    radio_names = ['gender','privacy','piu','contact'];
    check_names = ['whom','whoshare','believe','connect'];
    tmp = []
    tmp=tmp.concat(radio_names);
    tmp=tmp.concat(check_names);
    for (i=0;i<tmp.length;i+=1){
	n = document.getElementsByName(tmp[i]);
	vs = []
	for (j=0;j<n.length;j+=1){
	    if(n[j].checked){vs.push(n[j].value);}
	}
	user_info[tmp[i]]=vs;
    }
    username = document.getElementById('un2').innerHTML;
    user_infos[username]=user_info;
    mg2('Saved!')
}


function register_user(){
    success = vl_user_all()
    if (!success){
	mg('Invalid Input!');
    } else {
	user = {}
	user['un']=document.getElementById('un').value;
	user['em']=document.getElementById('em').value;
	user['pw']=document.getElementById('pw').value;
	users[user['un']] = user;
	mg('Register Successful!')
	rgbt = document.getElementById('rg-bt');
	rgbt.value="Edit More";
	rgbt.onclick= function(){shift_personal(user['un'],user['em'],false);}
    }
}

function mg(s){
    t = document.getElementById('mg');
    t.innerHTML = s;
}

function mg2(s){
    t = document.getElementById('mg2');
    t.innerHTML = s;
}

function reset_user(){
    img_ids = ['un-ok','em-ok','re-ok','pw-ok','rp-ok'];
    input_ids = ['un','em','re','pw','rp'];
    for (i = 0;i<input_ids.length;i+=1){
	input = document.getElementById(input_ids[i]);
	input.value = "";
	set_img(img_ids[i],0);
    }
    rgbt = document.getElementById('rg-bt');
    rgbt.value = "Register";
    rgbt.onclick = function(){register_user();};
    mg("");
}

function freeze_all(){
   inputs = document.getElementsByTagName('input');
   for( i= 0;i<inputs.length;i+=1){
       input = inputs[i];
       if (input.type=="button"){continue;}
       input.disabled = true;
   }
   selects = document.getElementsByTagName('select');
   for( i= 0;i<selects.length;i+=1){
       select = selects[i];
       select.disabled = true;
   }

}

function deforest_all(){
    inputs = document.getElementsByTagName('input');
    for( i= 0;i<inputs.length;i+=1){
	input = inputs[i];
	if (input.type=="button"){continue;}
	input.disabled = false;
    }
    selects = document.getElementsByTagName('select');
    for( i= 0;i<selects.length;i+=1){
	select = selects[i];
	select.disabled = false;
    }
}

function shift_personal(un,em,show){
    show_panel('panel-2');
    document.getElementById('un2').innerHTML = un;
    document.getElementById('em2').innerHTML = em;
    reset_user_info();
    
    if (! (un in user_infos)) {
	if (show){
	    document.getElementById('foot').style.display = "none";
	    freeze_all();
	} else {
	    document.getElementById('foot').style.display = "block";
	    deforest_all();
	}
	mg2("");
	return ;
    }
    user_info = user_infos[un];
    console.log(user_info);
    text_ids = ['fn','ln','lb','sdn'];
    select_ids = ['sm','sd','sy','sc','ss'];
    val = []
    val = val.concat(text_ids);
    val = val.concat(select_ids);
    for (i = 0;i<val.length;i+=1){
	if (! (val[i] in user_info)){continue;}
	document.getElementById(val[i]).value = user_info[val[i]];
    }
    mul_select_ids = ['scdt','scdp'];
    for (i = 0;i<mul_select_ids.length;i+=1){
	if (! (mul_select_ids[i] in user_info)){continue;}
	m = document.getElementById(mul_select_ids[i]);
	vs = user_info[mul_select_ids[i]];
	for (j = 0;j<vs.length;j+=1){
	    rank = vs[j];
	    m[rank].selected = true;
	    }
    }
    radio_names = ['gender','privacy','piu','contact'];
    check_names = ['whom','whoshare','believe','connect'];
    tmp = []
    tmp=tmp.concat(radio_names);
    tmp=tmp.concat(check_names);
    for (i=0;i<tmp.length;i+=1){
	if (! (tmp[i] in user_info)){continue;}
	n = document.getElementsByName(tmp[i]);
	vs = user_info[tmp[i]];
	for (j=0;j<vs.length;j+=1){
	    value = vs[j]
	    for (k=0;k<n.length;k+=1){
		if (n[k].value==value){
		    n[k].checked=true;
		}
	    }
	}
    }
    if (show){
	document.getElementById('foot').style.display = "none";
	freeze_all();
    } else {
	document.getElementById('foot').style.display = "block";
	deforest_all();
    }
    mg2("");
}

function show_panel(panel_id){
    panel_ids = ['panel-1','panel-2','panel-3','panel-4'];
    for (i = 0;i<panel_ids.length;i+=1){
	panel = document.getElementById(panel_ids[i]);
	if (panel != null){
	panel.style.display = 'none';}
    }
    document.getElementById(panel_id).style.display = "block";
    
}

function start_register(){
    show_panel('panel-1');
    deforest_all();
    reset_user();
}

function generate_ur(user){
    html = '<div class="row"> \
	      <div class="col-2 font-big">__UN__</div> \
	      <div class="col-4 font-big ">__EM__</div> \
	      <div class="float-right col-1"><input class="form-button green font-big" type="button" value="Edit" onclick="__EDIT__"/></div> \
	      <div class="float-right col-1"><input class="form-button blue font-big" type="button" value="Show" onclick="__SHOW__"/></div> \
	    </div>';
    html = html.replace('__UN__',user['un']);
    html = html.replace('__EM__',user['em']);
    html = html.replace('__EDIT__',"shift_personal('"+user['un']+"','"+user['em']+"',false)");
    html = html.replace('__SHOW__',"shift_personal('"+user['un']+"','"+user['em']+"',true)");
    return html;
}

function list_user(){
    show_panel('panel-3');
    s = '';
    for (un in users){
	user = users[un]
	s += generate_ur(user);
    }
    document.getElementById('urs').innerHTML=s;
}

function make_option(v,t){
    html = '<option value="__VL__">__TX__</option>';
    html = html.replace('__VL__',v);
    html = html.replace('__TX__',t);
    return html;
}

function load_month(){
    vs = ['null','1','2','3','4','5','6','7','8','9','10','11','12'];
    ts = ['Month','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    h = "";
    for (i=0;i<vs.length;i+=1){
	s = make_option(vs[i],ts[i]);
	h += s;
    }
    document.getElementById('sm').innerHTML = h;
}

function load_day(){
    month = document.getElementById('sm').value;
    d31 = ['1','3','5','7','8','10','12'];
    day = 30;
    if (month == '2'){
	day = 29;
    }else if (month in d31){
	day = 31;
    }
    h = make_option('null','Day');
    for (i = 1; i<=day;i+=1){
	s = make_option(i.toString(),i.toString());
	h+=s;
    }
    document.getElementById('sd').innerHTML = h;
}

function load_year(){
    h = make_option('null','Year');
    d = new Date();
    year = d.getFullYear();
    for (i = year; i>= 1900; i-=1){
	s = make_option(i.toString(),i.toString());
	h+=s;
    }
    document.getElementById('sy').innerHTML = h;
}

function load_state(){
    h = make_option('null',"State");
    c = document.getElementById('sc').value;
    cs="Anhui|Beijing|Chongqing|Fujian|Gansu|Guangdong|Guangxi|Guizhou|Hainan|Hebei|Heilongjiang|Henan|Hubei|Hunan|Jiangsu|Jiangxi|Jilin|Liaoning|Nei Mongol|Ningxia|Qinghai|Shaanxi|Shandong|Shanghai|Shanxi|Sichuan|Tianjin|Xinjiang|Xizang (Tibet)|Yunnan|Zhejiang";
    us="Alabama|Alaska|Arizona|Arkansas|California|Colorado|Connecticut|Delaware|District of Columbia|Florida|Georgia|Hawaii|Idaho|Illinois|Indiana|Iowa|Kansas|Kentucky|Louisiana|Maine|Maryland|Massachusetts|Michigan|Minnesota|Mississippi|Missouri|Montana|Nebraska|Nevada|New Hampshire|New Jersey|New Mexico|New York|North Carolina|North Dakota|Ohio|Oklahoma|Oregon|Pennsylvania|Rhode Island|South Carolina|South Dakota|Tennessee|Texas|Utah|Vermont|Virginia|Washington|West Virginia|Wisconsin|Wyoming";
    cs = cs.split('|');
    us = us.split('|');
    s = [];
    if (c=='china'){s=cs;}
    else if (c=='usa'){s=us;}
    for (i =0; i<s.length;i+=1){
	ss = make_option(s[i],s[i]);
	h+=ss;
    }
    document.getElementById('ss').innerHTML = h;
}
