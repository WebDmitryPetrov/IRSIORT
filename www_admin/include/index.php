<?php
include('include/_func.php');

if(isset($_GET['exit'])&&$_GET['exit']=="exit")
{
	ses_destr();
}
if(empty($_GET['ooops_auth']))
auth('/');

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=windows-1251">
<link rel="stylesheet" type="text/css" href="css/css.css">
<title><?php echo $lang['_title']; ?></title>
<? if(isset($_SESSION['u_id'])) { ?>
<script type="text/javascript" src="js.js"></script>

<script type="text/javascript">
function passw()
{
   if ((document.getElementById('password').value==document.getElementById('password2').value) && (document.getElementById('password').value!='') && (document.getElementById('password2').value!=''))
   {
   document.getElementById('button').disabled=false;
   document.getElementById('button').value="Сохранить пароль";
   }
   else
   {
   document.getElementById('button').disabled=true;
   document.getElementById('button').value="Пароли не совпадают";

   }
}
</script>
<? } ?>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.7.2.custom.css"
	rel="stylesheet" />

<script type="text/javascript" src="include/jquery.js"></script>
<script type="text/javascript"
	src="/include/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript"> 

	$(document).ready(function(){
		$("#butBig").hover(
		  function () {
			$(this).attr('src','img/control-big-over.png')
		  }, 
		  function () {
			$(this).attr('src','img/control-big.png')
		  }
		);
        		$("#armBig").hover(
		  function () {
			$(this).attr('src','img/arm-big-over.png')
		  },
		  function () {
			$(this).attr('src','img/arm-big.png')
		  }
		);
		
		
		$("#small a").hover(
		  function () {
			$(this).css('background','url(img/button-small-over.gif) no-repeat center top')
		  }, 
		  function () {
			$(this).css('background','url(img/button-small.gif) no-repeat center top')
		  }
		);
		<?php if(!isset($_SESSION['u_id'])) { ?> 
		$('#auth_form').dialog({
			autoOpen: true,
			width: 600,
			modal: true,
			bgiframe: true,
			 draggable: false,
			 resizable: false,
			 title: 'Авторизация пользователя',
				 closeOnEscape: false,
				   open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); }
		});

<?php } ?>
<?php if(isset($_SESSION['u_id'])) { ?>
$('#change_passwd').click(function()
{
	$.get("provider_irud.php", {'0x0':'show_change_pass_user_irud','u_id':'<?php echo $_SESSION['u_id']; ?>'},function(data)
			{
				var update = new Array();
				if(data.indexOf('|' != -1)) 
			    {
			        update = data.split("|");
		    	  	$('#'+update[0]).html(update[1]);
		      		$('#'+update[0]).dialog({
		      				title: 'Сменить пароль',
		      				//autoOpen: true,
		      				width: 450,
		      				modal: true,
		      				bgiframe:true,
		      				resizable: false,
		      				buttons: {
								'Отмена': function(){
									$(this).dialog('close');
										}
									}	
				   	   		});
		      		$('#'+update[0]).dialog('open');
    		    }
			});
});


<?php } ?>
	});
function send_passwd()
{
$.get('provider_irud.php', {'0x0':'change_pass_user_irud',password:$('#password').val(),password2:$('#password2').val()},
		function(data)
		{
				var update = new Array();
					if(data.indexOf('|' != -1)) 
				    {
				        update = data.split('|');
				        $('#'+update[0]).dialog("close");
			    	  	$('#message').html(update[1]);
			    	    $('#message').show();
			    	  //	alert($('#'+update[0]).html())
			      		
	    		    }
		}
		);
};


</script>
<style type="text/css">
a {
	color: #0066FF;
}

body {
	background-color: #FFFFFF;
}
-->
</style>

</head>
<body bottommargin="0" leftmargin="0" marginheight="0" marginwidth="0"
	rightmargin="0" topmargin="0">
<?php
if(!isset($_SESSION['u_id']))
{
	?>
<div id="auth_form">
<form action="<?=$_SERVER['PHP_SELF']?>?t=<?=time();?>" method=post>
<table width="500" border="0" cellpadding="1" cellspacing="1">
	<tr>
		<td height="128" align="right"
			style="background-image: url('ico/Keychain.gif'); background-repeat: no-repeat; background-position: -20px; padding: 3px">
			<?
			#echo $_SERVER["SSL_SESSION_ID"];
			if(!empty($_GET['ooops_auth'])and $_GET['ooops_auth'] == 'x4') echo '<h3 style="color:red">Время сесии закончелось</h3>';
			if(!empty($_GET['ooops_auth'])and $_GET['ooops_auth'] == '2') echo '<h3 style="color:red">Пустой логин</h3>';
			if(!empty($_GET['ooops_auth'])and $_GET['ooops_auth'] == '2') echo '<h3 style="color:red">Пустой пароль</h3>';
			if(!empty($_GET['ooops_auth'])and $_GET['ooops_auth'] == '3') echo '<h3 style="color:red">Неправильный логин или пароль</h3>';
			if(!empty($_GET['exit'])and $_GET['exit'] == 'exit') echo '<h3 style="color:red">Сеанс завершён!</h3>';

			?>
		<table width="80%" border="0" cellspacing="1" cellpadding="1"
			style="padding: 0px">
			<tr>
				<td align="right" valign="middle"><span class="style1">Логин</span></td>
				<td align="left" valign="middle"><input name="login" type="text"
					class="style1" id="textfield" style="width: 100%"></td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="style1">Пароль</td>
				<td align="left" valign="middle"><input name="password"
					type="password" class="style1" id="textfield2" style="width: 100%"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td  align="left"><input name="button" type="submit" class="style1" id="button"
					value="Войти"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style="font-size: 110%"  align="left">Справки по телефону: <span
					style="color: red"><nobr>15-67</nobr></span><br>
				Получить пароль в канцелярии: <span style="color: red"><nobr>25-30</nobr></span>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>


</form>
</div>






			<?
}
if ( !isset($_SESSION['privelegies']['admin']) ) {
	$admin=' href="#" ';
}
else {
	$admin=' href="admin.php" ';
}
$changePasswd='';
if ( isset( $_SESSION['u_id'] ) ) {
	//$changePasswd=' href="#"  onclick="alert(1);show_change_pass_user_irud('.$_SESSION["u_id"].')" ';
}
else
{
	//$changePasswd=' href="#" ';
}
?>
<div id=center>
<div id=head><?php echo $lang['_title']; ?></div>
<div id=buttons><a href="/control/"><img src=img/control-big.png id=butBig></a>
    <a href="/arm/"><img src=img/arm-big.png id=armBig></a>
<div id="message"></div>
<div id=small>
<a id="change_passwd">Сменить пароль</a> 
<a <?php echo $admin; ?>>Администрировать</a> 
<a target="_blank" href="./help/help.php">Справка</a>
<a href="/index.php?exit=exit">Выход</a></div>
</div>
</div>
<div id="content"></div>
<div id=foot><a href="http://old.rudn.ru/?pagec=2719">© 2007-<?=date("Y")?>
 Все права защищены “Российский университет дружбы народов”</a></div>
<div id=left><img src=img/0.gif width=50></div>
<div id=right><img src=img/0.gif width=50></div>
</body>
</html>
