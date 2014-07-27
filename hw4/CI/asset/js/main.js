function vl_all()
{
    if (vl_string('un','un-ok') && vl_string('pw','pw-ok')){
	return true;
    }
    else{
	return false;
    }
    mg('Invaild Input!');
}

function mg(s){
    t = $('#mg').html(s);
}



function vl_string(id,img_id){
    p = /^\S+$/g;
    t = $('#'+id).val();
    if (t.search(p) == 0){
	set_img(img_id,1);
	return true;
    }
    else
    {
	set_img(img_id,2);
	return false;
    }
}

function set_img(id,ok){
    img = $('#'+id);
    if (ok==1){
	img.attr("src","/hw4/CI/asset/img/ok.png")
	img.css('display',"block");
    } else if (ok==2){
	img.attr("src","/hw4/CI/asset/img/bad.png")
	img.css('display',"block");
    } else if (ok==0){
	img.css('display',"none");
    }
}


function generate_url(){ // generate url and search
    table = $('#table').val();
    sql = '';
    if (table == 'employee'){
	sql = 'select * from employee where 1=1';
	type_value = $('[name=type]').val();
	if (type_value!='')
	    sql += " type = '"+type_value+"' ";
	pay_s = $('#payment-s').val();
	if (pay_s == ''){pay_s = '0';}
	sql += " and payment >= "+pay_s+" ";
	pay_b = $('#payment-b').val();
	if (pay_b != '')
	    sql += " and payment <= "+ pay_b +" ";
	sql += ';';
    } else if (table == 'category'){
	name_value = $('#name').val();
	sql = "select * from category where name like '%"+name_value+"%';";
    } else if (table == 'product'){
	name_value = $('#product_name').val();
	sql = "select * from product where product_name like '%"+name_value+"%' ";
	c_value = $('[name=cid]').val();
	if (c_value != '')
	    sql += ' and cid = ' + c_value + ' ';
	price_s = $('#price-s').val();
	if (price_s == ''){price_s = '0';}
	sql += " and price >= "+price_s+" ";
	price_b = $('#price-b').val();
	if (price_b != '')
	    sql += " and price <= "+ price_b +" ";
	sql += ';';
    } else if (table == 'sale'){
	sql = "select * from product, sale where product.pid = sale.pid ";
	name_value = $('#product_name').val();
	sql += " and product.product_name like '%"+name_value+"%' ";
	c_value = $('[name=cid]').val();
	if (c_value != '')
	    sql += ' and product.cid = ' + c_value + ' ';
	percent_s = $('#percent-s').val();
	if (percent_s == ''){percent_s = '0';}
	sql += " and sale.percent >= "+percent_s+" ";
	percent_b = $('#percent-b').val();
	if (percent_b != '')
	    sql += " and sale.percent <= "+ percent_b +" ";
	s = $('#start_date-s').val();
	if (s != '')
	    sql += " and sale.start_date >= date("+s+") ";
	b = $('#end_date-b').val();
	if (b != '')
	    sql += " and sale.start_date <= date("+b+") ";
	s = $('#end_date-s').val();
	if (s != '')
	    sql += " and sale.end_date >= date("+s+") ";
	b = $('#end_date-b').val();
	if (b != '')
	    sql += " and sale.end_date <= date("+b+") ";
	sql += ';';
    }
    
    $.ajax({
	url:"sql.php",
	data: {
	    sql:sql
	},
	type: POST,
	success: function(data){$('#result').html(data);}
    });    
    return sql;
}

function category_menu(){
    value = $('#category_menu').val();
    if (value!=''){
	window.location.href = '/hw4/CI/index.php/display/home/'+value;
    }
}

function vl_password(id1,id2,report_id){
    if ($('#'+id1).val() == $('#'+id2).val()){
	return true
    } else {
	$('#'+report_id).html("Password Doesn't Match!");
	return false
    }
}

$(document).ready(function(){
    $(".numeric").numeric();
});
