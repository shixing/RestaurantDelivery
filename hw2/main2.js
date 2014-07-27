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
    t = document.getElementById('mg');
    t.innerHTML = s;
}



function vl_string(id,img_id){
    p = /^\S+$/g;
    t = document.getElementById(id).value;
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


function generate_url(){ // generate url and search
    table = document.getElementById('table').value;
    sql = '';
    if (table == 'employee'){
	sql = 'select * from employee where 1=1';
	type_value = document.getElementsByName('type')[0].value;
	if (type_value!='')
	    sql += " type = '"+type_value+"' ";
	pay_s = document.getElementById('payment-s').value;
	if (pay_s == ''){pay_s = '0';}
	sql += " and payment >= "+pay_s+" ";
	pay_b = document.getElementById('payment-b').value;
	if (pay_b != '')
	    sql += " and payment <= "+ pay_b +" ";
	sql += ';';
    } else if (table == 'category'){
	name_value = document.getElementById('name').value;
	sql = "select * from category where name like '%"+name_value+"%';";
    } else if (table == 'product'){
	name_value = document.getElementById('product_name').value;
	sql = "select * from product where product_name like '%"+name_value+"%' ";
	c_value = document.getElementsByName('cid')[0].value;
	if (c_value != '')
	    sql += ' and cid = ' + c_value + ' ';
	price_s = document.getElementById('price-s').value;
	if (price_s == ''){price_s = '0';}
	sql += " and price >= "+price_s+" ";
	price_b = document.getElementById('price-b').value;
	if (price_b != '')
	    sql += " and price <= "+ price_b +" ";
	sql += ';';
    } else if (table == 'sale'){
	sql = "select * from product, sale where product.pid = sale.pid ";
	name_value = document.getElementById('product_name').value;
	sql += " and product.product_name like '%"+name_value+"%' ";
	c_value = document.getElementsByName('cid')[0].value;
	if (c_value != '')
	    sql += ' and product.cid = ' + c_value + ' ';
	percent_s = document.getElementById('percent-s').value;
	if (percent_s == ''){percent_s = '0';}
	sql += " and sale.percent >= "+percent_s+" ";
	percent_b = document.getElementById('percent-b').value;
	if (percent_b != '')
	    sql += " and sale.percent <= "+ percent_b +" ";
	s = document.getElementById('start_date-s').value;
	if (s != '')
	    sql += " and sale.start_date >= date("+s+") ";
	b = document.getElementById('end_date-b').value;
	if (b != '')
	    sql += " and sale.start_date <= date("+b+") ";
	s = document.getElementById('end_date-s').value;
	if (s != '')
	    sql += " and sale.end_date >= date("+s+") ";
	b = document.getElementById('end_date-b').value;
	if (b != '')
	    sql += " and sale.end_date <= date("+b+") ";
	sql += ';';
    } else if (table == 'orders') {
	s = document.getElementById('order_date-s').value;
	b = document.getElementById('order_date-b').value;
	var xmlhttp;
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		document.getElementById("result").innerHTML=xmlhttp.responseText;
	    }
	}
	xmlhttp.open("POST","/hw3/php/restful/orders_api.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("s="+s+"&b="+b);
	return true;
    } else if (table == 'product_sold') {
	name = document.getElementById('product_name').value;
	cid = document.getElementsByName('cid')[0].value;
	s = document.getElementById('order_date-s').value;
	b = document.getElementById('order_date-b').value;
	group_by = document.getElementsByName('group_by')[0].value;
	sql = "from orders, orderdetail, product, category where product.pid = orderdetail.pid and orders.oid = orderdetail.oid and category.cid = product.cid and product.product_name like '%"+name+"%'";
	if (cid != ''){sql += " and category.cid = "+cid + " ";}
	if (s != '')
	    sql += " and orders.order_date >= date("+s+") ";
	if (b != '')
	    sql += " and orders.order_date <= date("+b+") ";
	if (group_by == ''){
	    sql = "select sum(orderdetail.quantity*product.price) as sale" + sql+";";
	} else if (group_by == "pid") {
	    sql = "select product.product_name,sum(orderdetail.quantity*product.price) as sale " + sql + " group by product.pid;";
	} else if (group_by == "cid") {
	    sql = "select category.name,sum(orderdetail.quantity*product.price) as sale " + sql + " group by category.cid;";
	}
    }
    
    var xmlhttp;
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
	document.getElementById("result").innerHTML=xmlhttp.responseText;
	}
    }
    xmlhttp.open("POST","sql.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("sql="+sql);
    return sql;
}
