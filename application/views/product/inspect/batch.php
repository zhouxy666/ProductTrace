<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>生产任务信息</title>
	<link href="<?php echo base_url()?>assets/hplus/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/bootstrap-table.min.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/style.min.css" rel="stylesheet">
</head>
<body class="gray-bg top-navigation">
<div id="wrapper">
	<div id="page-wrapper" class="gray-bg">
		<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-sm-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>产品抽检表 <small>请要求输入检测的样品数量及合格率</small></h5>
							<div class="ibox-tools">
								<a class="collapse-link">
									<i class="fa fa-chevron-up"></i>
								</a>
								<a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
									<i class="fa fa-wrench"></i>
								</a>
								<ul class="dropdown-menu dropdown-user">
									<li><a href="form_basic.html#">选项1</a>
									</li>
									<li><a href="form_basic.html#">选项2</a>
									</li>
								</ul>
								<a class="close-link">
									<i class="fa fa-times"></i>
								</a>
							</div>
						</div>
						<div class="ibox-content">
							<!--h3 class="m-t-none m-b">基本信息</h3-->
							<!--p>欢迎使用药品抽检系统(⊙o⊙)</p-->
							<form role="form" action="<?php echo site_url("ProductInspect/save")?>" method="post" id="ajax-form">
								<input type="hidden" name="EnterpriseId" value="<?php echo $enterprise_id?>"/>
								<input type="hidden" name="BatchId" value="<?php echo $batch_id?>"/>
								<div class="form-group">
									<label>企业名称</label>
									<input type="text" name="EnterpriseName" placeholder="请输入企业的名称" class="form-control" value="<?php echo $enterprise['C_Name']?>" />
								</div>
								<div class="form-group">
									<label>抽检批次</label>
									<input type="text" name="BatchName" placeholder="请输入抽检批次的名称" class="form-control" value="<?php echo $batch['REPB_Name']?>" />
								</div>
								<div class="form-group">
									<label>抽检数量</label>
									<input type="text" name="SampleNum" placeholder="请输入抽检数量" class="form-control" value="" />
								</div>
								<div class="form-group">
									<label>合格数量</label>
									<input type="text" name="QualifiedNum" placeholder="请输入不合格品数量" class="form-control" value="" />
								</div>
								<div class="form-group">
									<label>抽检日期</label>
									<input type="text" name="InsepctDateTime" placeholder="请输入抽检日期" class="form-control" value="<?php echo date("Y-m-d")?>" />
								</div>
								<div>
									<button class="btn btn-primary" type="submit"><strong>提交</strong>
									</button>
								</div>

							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url()?>assets/hplus/js/jquery.min.js"></script>
<script src="<?php echo base_url()?>assets/hplus/js/bootstrap.min.js"></script>
<script>
	$('#ajax-form').submit(function(){
		$.ajax({
			url:$(this).attr("action"),
			data:$(this).serialize(),
			dataType:"json",
			error:function(data){
				alert(data);
			},
			success:function(data){
				if(data.status && data.status==1){
					alert(data.message);
				}
			}
		});
		return false;
	});
</script>
</body>
</html>