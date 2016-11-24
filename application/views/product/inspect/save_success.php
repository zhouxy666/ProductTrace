<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>成功页面</title>
	<link rel="shortcut icon" href="favicon.ico"> <link href="css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/animate.min.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/style.min.css?v=4.1.0" rel="stylesheet">
	<script language="javascript" type="text/javascript">
		var i = 3;
		var intervalid;
		intervalid = setInterval("fun()", 1000);
		function fun() {
			if (i == 0) {
				window.location.href = "<?php echo site_url("ProductInspect/batch/$EnterpriseId/$BatchId")?>";
				clearInterval(intervalid);
			}
			document.getElementById("mes").innerHTML = i;
			i--;
		}
	</script>
</head>

<body class="gray-bg">
<div class="middle-box text-center animated fadeInDown">
	<h1>：）</h1>
	<h3 class="font-bold">操作成功！</h3>
	<div class="error-desc">
		系统将在 <span id="mes">3</span> 秒后返回上一页面！
	</div>
</div>
<script src="<?php echo base_url()?>assets/hplus/js/jquery.min.js?v=2.1.4"></script>
<script src="<?php echo base_url()?>assets/hplus/js/bootstrap.min.js?v=3.3.6"></script>
</body>
</html>