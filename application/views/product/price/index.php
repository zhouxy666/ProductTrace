<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>产品价格信息</title>
	<link href="<?php echo base_url()?>assets/hplus/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/font-awesome.min.css" rel="stylesheet">
	<!--link href="<?php echo base_url()?>assets/hplus/css/plugins/iCheck/custom.css" rel="stylesheet"-->
	<link href="<?php echo base_url()?>assets/hplus/css/animate.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/bootstrap-table.min.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/style.min.css" rel="stylesheet">
</head>
<body class="gray-bg top-navigation">
<div id="wrapper">
	<div id="page-wrapper" class="gray-bg">
		<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-sm-4">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>企业列表 <small>请选择需要设置价格的企业</small></h5>
							<div class="ibox-tools">
								<a class="collapse-link">
									<i class="fa fa-chevron-up"></i>
								</a>
								<a class="close-link">
									<i class="fa fa-times"></i>
								</a>
							</div>
						</div>
						<div class="ibox-content">
							<div class="dd" id="nestable2">
								<ol class="dd-list">
									<?php foreach($tree as $region):?>
										<?php if(in_array($enterprise_id,array_keys($region["Enterprises"]))):?>
											<li class="dd-item" data-id="1">
												<button type="button" data-action="collapse" style="display: block;">关闭</button>
												<button type="button" data-action="expand" style="display: none;">展开</button>
												<div class="dd-handle">
													<span class="label label-info"><i class="fa fa-users"></i></span> <?php echo $region["Name"]?>
												</div>
												<ol class="dd-list">
													<?php foreach($region["Enterprises"] as $ent):?>
														<li class="dd-item" data-id="2">
															<div class="dd-handle">
																<?php if($ent['EntId']==$enterprise_id):?>
																	<span class="label label-warning"><i class="fa fa-laptop"></i></span> <a href="<?php echo site_url("product/price/index/".$ent['EntId'])?>"><?php echo $ent["Name"]?></a>
																<?php else:?>
																	<span class="label label-info"><i class="fa fa-laptop"></i></span> <a href="<?php echo site_url("product/price/index/".$ent['EntId'])?>"><?php echo $ent["Name"]?></a>
																<?php endif;?>
															</div>
														</li>
													<?php endforeach;?>
												</ol>
											</li>
										<?php else:?>
											<li class="dd-item dd-collapsed" data-id="1">
												<button type="button" data-action="collapse" style="display: none;">关闭</button>
												<button type="button" data-action="expand" style="display: block;">展开</button>
												<div class="dd-handle">
													<span class="label label-info"><i class="fa fa-users"></i></span> <?php echo $region["Name"]?>
												</div>
												<ol class="dd-list" style="display: none;">
													<?php foreach($region["Enterprises"] as $ent):?>
														<li class="dd-item" data-id="2">
															<div class="dd-handle">
																<span class="label label-info"><i class="fa fa-laptop"></i></span> <a href="<?php echo site_url("product/price/index/".$ent['EntId'])?>"><?php echo $ent["Name"]?></a>
															</div>
														</li>
													<?php endforeach;?>
												</ol>
											</li>
										<?php endif;?>
									<?php endforeach;?>
								</ol>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-8 animated fadeInRight">
					<?php if($result):?>
					<div class="row row-lg">
						<div class="col-lg-6">
							<div class="ibox">
								<div class="ibox-content">
									<h5>本月产值</h5>
									<h1 class="no-margins"><?php echo $curMonthPrice?></h1>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="ibox">
								<div class="ibox-content">
									<h5>累计产值</h5>
									<h1 class="no-margins"><?php echo $accPrice?></h1>
								</div>
							</div>
						</div>
					</div>
					<div class="row row-lg">

						<div class="col-sm-12 proTable">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>产品价格信息</h5>
								</div>
								<div class="ibox-content">
									<!--
									<a href="javascript:;" onclick="syncBatch()" class="btn btn-primary">获取生产批次数据 <?php echo $inspectBatchNum?>/<?php echo $productBatchNum?></a>
									-->
									<div class="example">
										<table id="exampleTableFromData" data-toggle="table" data-mobile-responsive="true">
											<thead>
											<tr>
												<th class="text-center">ID</th>
												<th>产品</th>
												<th class="text-center">品牌</th>
												<th class="text-center">市场价格</th>
												<th class="text-center">操作</th>
											</tr>
											</thead>
											<tbody>
											<?php foreach($result as $row):?>
												<tr>
													<td class="text-center"><?php echo $row->ProductId?></td>
													<td><?php echo $row->ProductName?></td>
													<td class="text-center"><?php echo $row->ProductBrand?></td>
													<td class="text-center"><?php echo $row->ProductPrice?></td>
													<td class="text-center">
														<a href="javascript:;" onclick="openWin(<?php echo $enterprise_id?>,<?php echo $row->ProductId?>)">定价</a>
													</td>
												</tr>
											<?php endforeach;?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated bounceInRight">
			<form role="form" action="<?php echo site_url("Product/Price/save")?>" method="post" id="ajax-form">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" onclick="closeWin()"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span>
				</button>
				<i class="fa fa-laptop modal-icon"></i>
				<h4 class="modal-title">价格窗口</h4>
				<small class="font-bold">请输入产品的价格。</small>
			</div>
			<div class="modal-body">
				<input type="hidden" name="EntId" value="" id="EntId"/>
				<input type="hidden" name="ProductId" value="" id="ProductId"/>

				<div class="form-group">
					<label>价格</label>
					<input type="text" name="ProductPrice" placeholder="请输入产品价格" class="form-control" value="" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal" onclick="closeWin()">关闭</button>
				<button type="submit" class="btn btn-primary">保存</button>
			</div>
			</form>
		</div>
	</div>
</div>
<script src="<?php echo base_url()?>assets/hplus/js/jquery.min.js"></script>
<script src="<?php echo base_url()?>assets/hplus/js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>assets/hplus/js/plugins/peity/jquery.peity.min.js"></script>
<script src="<?php echo base_url()?>assets/hplus/js/plugins/nestable/jquery.nestable.js"></script>
<script src="<?php echo base_url()?>assets/hplus/js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="<?php echo base_url()?>assets/hplus/js/bootstrap-table.min.js"></script>
<script src="<?php echo base_url()?>assets/hplus/js/bootstrap-table-zh-CN.min.js"></script>
<script>
	function openWin(EntId,ProductId){
		$("#EntId").val(EntId);
		$("#ProductId").val(ProductId);
		$("input[name=ProductPrice]").val("");
		$("#myModal").show();
	}
	function closeWin(){
		$("#myModal").hide();
	}

	$(function() {
		$('#ajax-form').submit(function(){
			$.ajax({
				url:$(this).attr("action"),
				data:$(this).serialize(),
				dataType:"json",
				error:function(data){
					swal("出错了!", "系统发生了一个错误！", "error");
				},
				success:function(data){
					if(data.status && data.status==1){
						swal("恭喜!", data.message, "success");
						$("#myModal").hide();
						window.location.reload();
					}
				}
			});
			return false;
		});

		//$('#nestable2').nestable({group: 1});
		$('#nestable2>.dd-list>.dd-item>button').on('click', function () {
			if($(this).parent().hasClass("dd-collapsed")){
				$(this).parent().removeClass("dd-collapsed");
				$(this).parent().children("button").eq(0).show();
				$(this).parent().children("button").eq(1).hide();
				$(this).parent().children(".dd-list").show();
			}else{
				$(this).parent().addClass("dd-collapsed");
				$(this).parent().children("button").eq(0).hide();
				$(this).parent().children("button").eq(1).show();
				$(this).parent().children(".dd-list").hide();
			}
		});

		$("span.pie").peity("pie", {fill: ['#1ab394', '#d7d7d7', '#ffffff']});
		$("span.pie2").peity("pie", {fill: ['#f8ac59', '#d7d7d7', '#ffffff']});
		$("span.pie3").peity("pie", {fill: ['red', '#d7d7d7', '#ffffff']});
	});
</script>
</body>
</html>