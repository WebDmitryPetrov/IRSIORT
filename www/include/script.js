
var tooltip = {


	options: {
		attr_name: "tooltip",
		blank_text: "(blank text)",
		newline_entity: "  ",
		max_width: 0,
		delay: 0,
		skip_tags: ["link", "style"]
	},


	t: document.createElement("DIV"),
	c: null,
	g: false,
	canvas: document.getElementsByTagName(document.compatMode && document.compatMode == "CSS1Compat" ? "HTML" : "BODY")[0],

	m: function(e){
		if (tooltip.g){
			var x = window.event ? event.clientX + tooltip.canvas.scrollLeft : e.pageX;
			var y = window.event ? event.clientY + tooltip.canvas.scrollTop : e.pageY;
			tooltip.a(x, y);
		}
	},

	d: function(){
		tooltip.t.setAttribute("id", "tooltip");
		document.body.appendChild(tooltip.t);
		var a = document.all && !window.opera ? document.all : document.getElementsByTagName("*"); // in opera 9 document.all produces type mismatch error
		var l = a.length;
		for (var i = 0; i < l; i++){

			if (!a[i] || tooltip.options.skip_tags.in_array(a[i].tagName.toLowerCase())) continue;

			var tooltip_title = a[i].getAttribute("title"); // returns form object if IE & name="title"; then IE crashes; so...
			if (tooltip_title && typeof tooltip_title != "string") tooltip_title = "";

			var tooltip_alt = a[i].getAttribute("alt");
			var tooltip_blank = a[i].getAttribute("target") && a[i].getAttribute("target") == "_blank" && tooltip.options.blank_text;
			if (tooltip_title || tooltip_blank){
				a[i].setAttribute(tooltip.options.attr_name, tooltip_blank ? (tooltip_title ? tooltip_title + " " + tooltip.options.blank_text : tooltip.options.blank_text) : tooltip_title);
				if (a[i].getAttribute(tooltip.options.attr_name)){
					a[i].removeAttribute("title");
					if (tooltip_alt && a[i].complete) a[i].removeAttribute("alt");
					tooltip.l(a[i], "mouseover", tooltip.s);
					tooltip.l(a[i], "mouseout", tooltip.h);
				}
			}else if (tooltip_alt && a[i].complete){
				a[i].setAttribute(tooltip.options.attr_name, tooltip_alt);
				if (a[i].getAttribute(tooltip.options.attr_name)){
					a[i].removeAttribute("alt");
					tooltip.l(a[i], "mouseover", tooltip.s);
					tooltip.l(a[i], "mouseout", tooltip.h);
				}
			}
			if (!a[i].getAttribute(tooltip.options.attr_name) && tooltip_blank){
				//
			}
		}
		document.onmousemove = tooltip.m;
		window.onscroll = tooltip.h;
		tooltip.a(-99, -99);
	},

	_: function(s){
		s = s.replace(/\&/g,"&amp;");
		s = s.replace(/\</g,"&lt;");
		s = s.replace(/\>/g,"&gt;");
		return s;
	},

	s: function(e){
		var d = window.event ? window.event.srcElement : e.target;
		if (!d.getAttribute(tooltip.options.attr_name)) return;
		var s = d.getAttribute(tooltip.options.attr_name);
		if (tooltip.options.newline_entity){
			var s = tooltip._(s);
			s = s.replace(eval("/" + tooltip._(tooltip.options.newline_entity) + "/g"), "<br />");
			tooltip.t.innerHTML = s;
		}else{
			if (tooltip.t.firstChild) tooltip.t.removeChild(tooltip.t.firstChild);
			tooltip.t.appendChild(document.createTextNode(s));
		}
		tooltip.c = setTimeout("tooltip.t.style.visibility = 'visible'", tooltip.options.delay);
		tooltip.g = true;
	},

	h: function(e){
		tooltip.t.style.visibility = "hidden";
		if (!tooltip.options.newline_entity && tooltip.t.firstChild) tooltip.t.removeChild(tooltip.t.firstChild);
		clearTimeout(tooltip.c);
		tooltip.g = false;
		tooltip.a(-99, -99);
	},

	l: function(o, e, a){
		if (o.addEventListener) o.addEventListener(e, a, false); // was true--Opera 7b workaround!
		else if (o.attachEvent) o.attachEvent("on" + e, a);
			else return null;
	},

	a: function(x, y){
		var w_width = tooltip.canvas.clientWidth ? tooltip.canvas.clientWidth + tooltip.canvas.scrollLeft : window.innerWidth + window.pageXOffset;
		var w_height = window.innerHeight ? window.innerHeight + window.pageYOffset : tooltip.canvas.clientHeight + tooltip.canvas.scrollTop; // should be vice verca since Opera 7 is crazy!

		tooltip.t.style.width = tooltip.options.max_width && tooltip.t.offsetWidth > tooltip.options.max_width ? tooltip.options.max_width + "px" : "auto";

		var t_width = tooltip.t.offsetWidth;
		var t_height = tooltip.t.offsetHeight;



		tooltip.t.style.left = x + 8 + "px";
		tooltip.t.style.top = y + 8 + "px";

		if (x + t_width > w_width) tooltip.t.style.left = w_width - t_width + "px";
		if (y + t_height > w_height) tooltip.t.style.top = w_height - t_height + "px";
	}
}

Array.prototype.in_array = function(value){
	var l = this.length;
	for (var i = 0; i < l; i++)
		if (this[i] === value) return true;
	return false;
};

var root = window.addEventListener || window.attachEvent ? window : document.addEventListener ? document : null;
if (root){
	if (root.addEventListener) root.addEventListener("load", tooltip.d, false);
	else if (root.attachEvent) root.attachEvent("onload", tooltip.d);
}

	/* Создание нового объекта XMLHttpRequest для общения с Web-сервером */
	var xmlHttp = false;
	/*@cc_on @*/
	/*@if (@_jscript_version >= 5)
	try {
	  xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
	  try {
	    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	  } catch (e2) {
	    xmlHttp = false;
	  }
	}
	@end @*/

	if (!xmlHttp && typeof XMLHttpRequest != 'undefined') {
	  xmlHttp = new XMLHttpRequest();
	}
 var Url8 = {

     // public method for url encoding
     encode : function (string) {
         return escape(this._utf8_encode(string));
     },

     // public method for url decoding
     decode : function (string) {
         return this._utf8_decode(unescape(string));
     },

     // private method for UTF-8 encoding
     _utf8_encode : function (string) {
         string = string.replace(/\r\n/g,"\n");
         var utftext = "";

         for (var n = 0; n < string.length; n++) {

             var c = string.charCodeAt(n);

             if (c < 128) {
                 utftext += String.fromCharCode(c);
             }
             else if((c > 127) && (c < 2048)) {
                 utftext += String.fromCharCode((c >> 6) | 192);
                 utftext += String.fromCharCode((c & 63) | 128);
             }
             else {
                 utftext += String.fromCharCode((c >> 12) | 224);
                 utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                 utftext += String.fromCharCode((c & 63) | 128);
             }

         }

         return utftext;
     },

     // private method for UTF-8 decoding
     _utf8_decode : function (utftext) {
         var string = "";
         var i = 0;
         var c = c1 = c2 = 0;

         while ( i < utftext.length ) {

             c = utftext.charCodeAt(i);

             if (c < 128) {
                 string += String.fromCharCode(c);
                 i++;
             }
             else if((c > 191) && (c < 224)) {
                 c2 = utftext.charCodeAt(i+1);
                 string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                 i += 2;
             }
             else {
                 c2 = utftext.charCodeAt(i+1);
                 c3 = utftext.charCodeAt(i+2);
                 string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                 i += 3;
             }

         }

         return string;
     }

 }

function show_group()
{
  var url = "./provider_admin.php?0x0=show_groups";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}
function updateContent()
{
  if (xmlHttp.readyState == 4) {
  //alert('1');
    var response = xmlHttp.responseText;

    document.getElementById("content").innerHTML = response;
  }
}
function show_rename_group_field(id)
{

  var url = "./provider_admin.php?0x0=show_rename_group_field&g_id="+id;
    url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;

  // SПередать запрос
  xmlHttp.send(null);
}

function group_rename()
{
id=document.getElementById("g_id").value;
name=Url8.encode(document.getElementById("new_name").value);

var url = "./provider_admin.php?0x0=group_rename&g_id="+id+"&name="+name;
url=url+"&ran="+Math.random()*5;
   // alert(url);
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;

  // SПередать запрос
  xmlHttp.send(null);
}
function show_add_group()
{
  var url = "./provider_admin.php?0x0=show_add_group";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  // alert('2');
  // SПередать запрос
  xmlHttp.send(null);
}
function group_add()
{

  name=Url8.encode(document.getElementById("new_name").value);
  var url = "./provider_admin.php?0x0=group_add&name="+name;
  url=url+"&ran="+Math.random()*5;

  xmlHttp.open("GET", url, true);
  xmlHttp.onreadystatechange = updateContent;
   xmlHttp.send(null);
}
function show_delete_group(id)
{
 if (confirm("Вы точно хотите удалить эту группу?"))
	 {

  var url = "./provider_admin.php?0x0=show_delete_group&g_id="+id;
  url=url+"&ran="+Math.random()*5;

  xmlHttp.open("GET", url, true);
  xmlHttp.onreadystatechange = updateContent;
   xmlHttp.send(null);
	}
}
function show_users()
{
  var url = "./provider_admin.php?0x0=show_users";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}

function show_rename_user_field(id)
{

  var url = "./provider_admin.php?0x0=show_rename_user_field&u_id="+id;
    url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;

  // SПередать запрос
  xmlHttp.send(null);
}
function show_arm_user_field(id)
{

  var url = "./provider_admin.php?0x0=user_rename_arm&u_id="+id;
    url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;

  // SПередать запрос
  xmlHttp.send(null);
}
function user_rename_arm()
{
var id=document.getElementById("u_id").value;
var login=Url8.encode(document.getElementById("login").value);
var surname=Url8.encode(document.getElementById("surname").value);

var firstname=Url8.encode(document.getElementById("firstname").value);

var url = "./provider_admin.php?0x0=user_arm_sql&u_id="+id+
"&login="+login+
"&surname="+surname+
"&firstname="+firstname;
url=url+"&ran="+Math.random()*5;

   // alert(url);
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;

  // Передать запрос
  xmlHttp.send(null);
}
function user_rename()
{
var id=document.getElementById("u_id").value;
var login=Url8.encode(document.getElementById("login").value);
var surname=Url8.encode(document.getElementById("surname").value);

var firstname=Url8.encode(document.getElementById("firstname").value);
var fathername=Url8.encode(document.getElementById("fathername").value);
    var univer_id=Url8.encode(document.getElementById("univer_id").value);
var url = "./provider_admin.php?0x0=user_rename&u_id="+id+
"&login="+login+
"&surname="+surname+
"&firstname="+firstname+
"&univer_id="+univer_id+
"&fathername="+fathername;
url=url+"&ran="+Math.random()*5;

   // alert(url);
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;

  // Передать запрос
  xmlHttp.send(null);
}

function show_add_user()
{
  var url = "./provider_admin.php?0x0=show_add_user";
  url=url+"&ran="+Math.random()*5;

  xmlHttp.open("GET", url, true);


  xmlHttp.onreadystatechange = updateContent;

  xmlHttp.send(null);
}
function user_add()
{
var login=Url8.encode(document.getElementById("login").value);

var password=Url8.encode(document.getElementById("password").value);
var surname=Url8.encode(document.getElementById("surname").value);
var firstname=Url8.encode(document.getElementById("firstname").value);
var fathername=Url8.encode(document.getElementById("fathername").value);
    var univer_id=Url8.encode(document.getElementById("univer_id").value);
var url = "./provider_admin.php?0x0=user_add&login="+login+
    "&password="+password+
    "&surname="+surname+
    "&firstname="+firstname+
    "&univer_id="+univer_id+
    "&fathername="+fathername;
url=url+"&ran="+Math.random()*5;

  xmlHttp.open("GET", url, true);

  xmlHttp.onreadystatechange = updateContent;

   xmlHttp.send(null);
}
function show_delete_user(id)
{
 if (confirm("Вы точно хотите удалить этого пользователя?"))
	 {

  var url = "./provider_admin.php?0x0=show_delete_user&u_id="+id;
  url=url+"&ran="+Math.random()*5;

  xmlHttp.open("GET", url, true);
  xmlHttp.onreadystatechange = updateContent;
   xmlHttp.send(null);
	}
}
function show_change_pass_user(id)
{

  var url = "./provider_admin.php?0x0=show_change_pass_user&u_id="+id;
  url=url+"&ran="+Math.random()*5;
  xmlHttp.open("GET", url, true);
  xmlHttp.onreadystatechange = updateContent;
  xmlHttp.send(null);

}
function show_change_pass_user_irud(id)
{
  var url = "./provider_irud.php?0x0=show_change_pass_user_irud&u_id="+id;
  url=url+"&ran="+Math.random()*5;
  xmlHttp.open("GET", url, true);
  xmlHttp.onreadystatechange = updateContent;
  xmlHttp.send(null);
}
function change_pass_user()
{
	if (confirm("Вы точно хотите измен этого пользователя?"))
	 {
var id=Url8.encode(document.getElementById("u_id").value);
var  password=Url8.encode(document.getElementById("password").value);
  var url="./provider_admin.php?0x0=change_pass_user&u_id="+id+"&password="+password;
  url=url+"&ran="+Math.random()*5;

  xmlHttp.open("GET", url, true);
  xmlHttp.onreadystatechange = updateContent;
   xmlHttp.send(null);
	 }
}
function change_pass_user_irud()
{
	if (confirm("Вы точно хотите измен этого пользователя?"))
	 {
var id=Url8.encode(document.getElementById("u_id").value);
var  password=Url8.encode(document.getElementById("password").value);
  var url="./provider_irud.php?0x0=change_pass_user_irud&u_id="+id+"&password="+password;
  url=url+"&ran="+Math.random()*5;

  xmlHttp.open("GET", url, true);
  xmlHttp.onreadystatechange = updateContent;
   xmlHttp.send(null);
	 }
}
function show_add_to_group(id)
{
var url = "./provider_admin.php?0x0=show_add_to_group&g_id="+id;
url=url+"&ran="+Math.random()*5;

   // alert(url);
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;

  // Передать запрос
  xmlHttp.send(null);
}

function change_user_group(ch,g_id)
{
 var url = "./provider_admin.php?0x0=update_user_group&g_id="+g_id+"&u_id="+ch.value;
   if(ch.checked) url=url+"&do=add";
   else  url=url+"&do=del";

url=url+"&ran="+Math.random()*5;

   // alert(url);
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = function()
  {
  	if (xmlHttp.readyState == 4)
  	{
  		if (xmlHttp.responseText)
  		{
  			//alert(xmlHttp.responseText)
  		}
  	}
  }
  // Передать запрос
  xmlHttp.send(null);
}
function Update_table(b,id)
{

var s= document.getElementsByTagName('li');
for(var i = 0; i<s.length;i++)
s[i].className='';
document.getElementById(b).className="active";
b=Url8.encode(b);
var url = "./provider_admin.php?0x0=show_user_table&g_id="+id+"&b="+b;
url=url+"&ran="+Math.random()*5;

   // alert(url);
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;

  // Передать запрос
  xmlHttp.send(null);
}
function Update_table2(b,id)
{

var s= document.getElementsByTagName('li');
for(var i = 0; i<s.length;i++)
s[i].className='';
document.getElementById(b).className="active";
b=Url8.encode(b);
var url = "./provider_admin.php?0x0=show_users&b="+b;
url=url+"&ran="+Math.random()*5;

   // alert(url);
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;

  // Передать запрос
  xmlHttp.send(null);
}




function NavigateThrough (event)
{

  if(event.keyCode == 27){
   if(document.getElementById('apDiv1')) document.getElementById('apDiv1').style.display='none';
   if(document.getElementById('apDiv2')) document.getElementById('apDiv2').style.display='none';
   if(document.getElementById('apDiv3')) document.getElementById('apDiv3').style.display='none';
   if(document.getElementById('apDiv4')) document.getElementById('apDiv4').style.display='none';
   if(document.getElementById('divt'))document.getElementById('divt').style.display='none';
  }


}
function show_stat()
{
  var url = "./provider_admin.php?0x0=show_stat";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}
function show_all_users()
{
  var url = "./provider_admin.php?0x0=show_all_users";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}
function show_kontr_otv_users()
{
  var url = "./provider_admin.php?0x0=show_kontr_otv_users";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}
function show_kontr_sotr_users()
{
  var url = "./provider_admin.php?0x0=show_kontr_sotr_users";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}
function show_kontr_ruk_users()
{
  var url = "./provider_admin.php?0x0=show_kontr_ruk_users";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}
function show_arm_users()
{
  var url = "./provider_admin.php?0x0=show_arm_users";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}
function show_arm_docs()
{
  var url = "./provider_admin.php?0x0=show_arm_docs";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}
function show_rekt_read_users()
{
  var url = "./provider_admin.php?0x0=show_rekt_read_users";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);

  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}

function show_count_docs_control()
{
  var url = "./provider_admin.php?0x0=show_count_docs_control";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);
  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}
function show_count_arch_docs_control()
{
  var url = "./provider_admin.php?0x0=show_count_arch_docs_control";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);
  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}

function show_count_vid_docs_control()
{
  var url = "./provider_admin.php?0x0=show_count_vid_docs_control";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);
  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}
function show_count_calender_docs_control()
{
	document.getElementById("content").innerHTML="c <input type=text id=first value='2000-01-01' > по <input type=text id=last value='2099-12-30'> <input type=button value='Построить отчет' onclick='show_docs_by_date()'>";
}
function show_docs_by_date()
{
  var url = "./provider_admin.php?0x0=show_docs_by_date";
  url=url+"&first="+document.getElementById("first").value;
    url=url+"&last="+document.getElementById("last").value;
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);
  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}
function show_docs_from_rukovod_calender()
{
	document.getElementById("content").innerHTML="c <input type=text id=first value='2000-01-01' > по <input type=text id=last value='2099-12-30'> <input type=button value='Построить отчет' onclick='show_docs_from_rukovod()'>";
}
function show_docs_from_rukovod()
{
  var url = "./provider_admin.php?0x0=show_docs_from_rukovod";
  url=url+"&first="+document.getElementById("first").value;
    url=url+"&last="+document.getElementById("last").value;
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);
  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}
function show_docs_from_rektorat_calender()
{
	document.getElementById("content").innerHTML="c <input type=text id=first value='2000-01-01' > по <input type=text id=last value='2099-12-30'> <input type=button value='Построить отчет' onclick='show_docs_from_rektorat_group()'>";
}
function show_docs_from_rektorat_group()
{
  var url = "./provider_admin.php?0x0=show_docs_from_rektorat";
  url=url+"&first="+document.getElementById("first").value;
    url=url+"&last="+document.getElementById("last").value;
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);
  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}
function show_docs_from_static_group_calender()
{
	document.getElementById("content").innerHTML="c <input type=text id=first value='2000-01-01' > по <input type=text id=last value='2099-12-30'> <input type=button value='Построить отчет' onclick='show_docs_from_static_group()'>";
}
function show_docs_from_static_group()
{
  var url = "./provider_admin.php?0x0=show_docs_from_static_group";
  url=url+"&first="+document.getElementById("first").value;
    url=url+"&last="+document.getElementById("last").value;
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);
  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}

function count_docs_from_statist()
{
  var url = "./provider_admin.php?0x0=count_docs_from_statist";
  url=url+"&ran="+Math.random()*5;
  // Открыть соединение с сервером
  xmlHttp.open("GET", url, true);
  // Установить функцию для сервера, которая выполнится после его ответа
  xmlHttp.onreadystatechange = updateContent;
  //  alert('2');
  // Передать запрос
  xmlHttp.send(null);
}

