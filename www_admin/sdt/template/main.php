<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=windows-1251">
<title><?php echo $lang['_title']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="content/css/bootstrap.min.css" rel="stylesheet">
    <!--<link href="content/css/cerulean.css" rel="stylesheet">-->
<link href="content/css/bootstrap-responsive.css" rel="stylesheet">
<link href="content/css/datepicker.css" rel="stylesheet">
<link href="/css/ui-lightness/jquery-ui-1.7.2.custom.css"
	rel="stylesheet">

<script src="/include/jquery.js"></script>
<script src="/include/jquery-ui-1.7.2.custom.min.js"></script>
<script src="content/js/underscore-min.js"></script>
<script src="content/js/bootstrap.min.js"></script>
<script src="content/js/bootstrap-datepicker.js"></script>
    <script src="content/js/locales/bootstrap-datepicker.ru.js"></script>
<script src="content/js/jquery.form.js"></script>
<link href="content/css/main.css" rel="stylesheet">
<script type="text/javascript">
function dump(obj) {
    var out = "";
    if (obj && typeof(obj) == "object") {
        for (var i in obj) {
            out += i + ": " + obj[i] + "\n";
        }
    } else {
        out = obj;
    }
    alert(out);
}

$(function(){
//        $('.datepicker').datepicker({ dateFormat:''});
    $('.datepicker').datepicker({
        format: 'dd.mm.yyyy',
        language: "ru",
        autoclose: true
//			startDate: '-3d'
    })
});
</script>
</head>
<body>

	<div class="navbar  navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container-fluid">
				<!--			<div class="flag">
					<img src="/sdt/content/img/rus.gif">
				</div>
				<a class="brand" href="/sdt/"><img src="/sdt/content/img/bg.jpg"> </a>-->
				<div class="nav-collapse collapse">
					
					<!-- <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul> -->
				</div>
			</div>
		</div><p class="navbar-text pull-right" style="padding-right:5px">
						<span class="logged_as">Вошли как <strong><?php echo $_SESSION['surname'];?>
						</strong>
						</span>
					</p>
	</div>

	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span2">
				<div class="well sidebar-nav">
					<ul class="nav nav-list">
						<?php include 'menu.php';?>
					</ul>
				</div>
				<!--/.well -->
			</div>
			<!--/span-->
			<div class="span10">
				<?php if(!empty($_SESSION['flash'])):?>
				<div class="row-fluid">
					<div class="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<?php echo $_SESSION['flash']; $_SESSION['flash']=null;?>
					</div>
				</div>
				<?php endif;?>
				<div class="row-fluid" id="content">
					<?php echo $content;  ?>
				</div>
			</div>

		</div>
	</div>
	<!-- ip: <?=$_SERVER['SERVER_ADDR']?> -->
</body>
</html>
