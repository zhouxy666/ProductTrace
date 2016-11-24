<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>企业抽检信息</title>
	<link href="<?php echo base_url()?>assets/hplus/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/font-awesome.min.css" rel="stylesheet">
	<!--link href="<?php echo base_url()?>assets/hplus/css/plugins/iCheck/custom.css" rel="stylesheet"-->
	<link href="<?php echo base_url()?>assets/hplus/css/animate.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/bootstrap-table.min.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/style.min.css" rel="stylesheet">
	<style>
		.nav{text-align:center; height:50px;line-height:50px;}
		.nav ul li{display:inline-block;margin:0 10px;}
		.nav ul li a{display: block;padding:0 10px;transform:all 2s ease;}
		.nav ul li a:hover{background:#1ab394;color:#fff;}
	</style>
</head>
<body class="gray-bg top-navigation">
<div id="wrapper">
	<div id="page-wrapper" class="gray-bg">
		<div class="row border-bottom white-bg">
			<nav class="navbar navbar-static-top" role="navigation">
				<div class="nav">
					<ul>
						<?php foreach($regions as $id=>$name):?>
							<li><a href="<?php echo site_url("product/inspect/index")."?region=$id"?>"><?php echo $name?></a></li>
						<?php endforeach;?>
					</ul>
				</div>
			</nav>
		</div>

		<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-sm-4">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>企业列表 <small>请选择需要进行抽检的企业</small></h5>
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
																	<span class="label label-warning"><i class="fa fa-laptop"></i></span> <a href="<?php echo site_url("product/inspect/index/".$ent['EntId'])?>"><?php echo $ent["Name"]?></a>
																<?php else:?>
																	<span class="label label-info"><i class="fa fa-laptop"></i></span> <a href="<?php echo site_url("product/inspect/index/".$ent['EntId'])?>"><?php echo $ent["Name"]?></a>
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
																<span class="label label-info"><i class="fa fa-laptop"></i></span> <a href="<?php echo site_url("product/inspect/index/".$ent['EntId'])?>"><?php echo $ent["Name"]?></a>
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

						<div class="col-lg-12">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>检测合格率</h5>
									<div class="ibox-tools">
										<a class="collapse-link">
											<i class="fa fa-chevron-up"></i>
										</a>
										<a class="dropdown-toggle" data-toggle="dropdown" href="graph_flot.html#">
											<i class="fa fa-wrench"></i>
										</a>
										<ul class="dropdown-menu dropdown-user">
											<li><a href="graph_flot.html#">选项1</a>
											</li>
											<li><a href="graph_flot.html#">选项2</a>
											</li>
										</ul>
										<a class="close-link">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								<div class="ibox-content">
									<div class="echarts" id="echarts-line-chart"></div>
								</div>
							</div>

						</div>
						<!--
						<div class="col-sm-3">
							<div class="ibox">
								<div class="ibox-content">
									<h5>累计生产批次</h5>
									<h1 class="no-margins"><?php echo $productBatchNum?></h1>
									<div class="stat-percent font-bold text-navy">100% <i class="fa fa-bolt"></i></div>
									<small>合格率</small>
								</div>
							</div>

							<div class="ibox">
								<div class="ibox-content">
									<h5>累计抽检批次</h5>
									<h1 class="no-margins"><?php echo $inspectBatchNum?></h1>
									<div class="stat-percent font-bold text-navy">100% <i class="fa fa-bolt"></i></div>
									<small>平均合格率</small>
								</div>
							</div>
						</div>
						-->
					</div>
					<div class="row row-lg">

						<div class="col-sm-12 proTable">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>生产任务信息</h5>
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
												<th>批次名称</th>
												<th class="text-center">产品</th>
												<th class="text-center">生产日期</th>
												<th class="text-center">生产数量</th>
												<th class="text-center">检测时间</th>
												<th class="text-center">合格率</th>
												<th class="text-center">操作</th>
											</tr>
											</thead>
											<tbody>
											<?php foreach($result as $row):?>
												<tr>
													<td class="text-center"><?php echo $row->BatId?></td>
													<td><a href="<?php echo site_url("productTrace/product_batches/".$enterprise_id."/".$row->BatId)?>"><?php echo $row->BatName?></a></td>
													<td class="text-center"><?php echo $row->ProductName?></td>
													<td class="text-center"><?php echo date("Y-m-d",strtotime($row->ProductDateTime))?></td>
													<td class="text-center"><?php echo $row->ProductNum?></td>
													<?php if(empty($row->InspectDateTime)):?>
														<td class="text-center"></td>
													<?php else:?>
														<td class="text-center"><?php echo date("Y-m-d",strtotime($row->InspectDateTime))?></td>
													<?php endif;?>
													<?php if(empty($row->QualifiedRate)):?>
														<td></td>
													<?php elseif($row->QualifiedRate>80):?>
														<td><span class="pie"><?php echo $row->QualifiedRate?>/100</span>  <?php echo $row->QualifiedRate?>%</td>
													<?php elseif($row->QualifiedRate>50):?>
														<td><span class="pie2"><?php echo $row->QualifiedRate?>/100</span>  <?php echo $row->QualifiedRate?>%</td>
													<?php else:?>
														<td><span class="pie3"><?php echo $row->QualifiedRate?>/100</span>  <?php echo $row->QualifiedRate?>%</td>
													<?php endif;?>
													<td class="text-center">
														<a href="javascript:;" onclick="openWin(<?php echo $enterprise_id?>,<?php echo $row->BatId?>)">抽检</a>
													</td>
												</tr>
											<?php endforeach;?>
											</tbody>
										</table>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div aria-relevant="all" aria-live="polite" role="alert" id="DataTables_Table_0_info" class="dataTables_info">显示 <?php echo $page["from"]?> 到 <?php echo $page["to"]?> 项，共 <?php echo $page["total"]?> 项</div>
										</div>
										<div class="col-sm-6">
											<?php echo $page["link"];?>
										</div>
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
			<form role="form" action="<?php echo site_url("product/inspect/save")?>" method="post" id="ajax-form">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" onclick="closeWin()"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span>
				</button>
				<i class="fa fa-laptop modal-icon"></i>
				<h4 class="modal-title">抽检窗口</h4>
				<small class="font-bold">请输入抽检样品的合格率。</small>
			</div>
			<div class="modal-body">
				<input type="hidden" name="EntId" value="" id="EntId"/>
				<input type="hidden" name="BatId" value="" id="BatId"/>

				<div class="form-group">
					<label>抽检数量</label>
					<input type="text" name="SampleNum" placeholder="请输入抽检数量" class="form-control" value="" />
				</div>
				<div class="form-group">
					<label>合格数量</label>
					<input type="text" name="QualifiedNum" placeholder="请输入不合格品数量" class="form-control" value="" />
				</div>
				<!--div class="form-group">
					<label>抽检日期</label>
					<input type="text" name="InsepctDateTime" placeholder="请输入抽检日期" class="form-control" value="<?php echo date("Y-m-d")?>" />
				</div-->
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
<script src="<?php echo base_url()?>assets/hplus/js/plugins/echarts/echarts-all.js"></script>
<?php if($enterprise_id):?>
<script src="<?php echo site_url("product/inspect/chart/".$enterprise_id)?>"></script>
<?php endif;?>
<script src="<?php echo base_url()?>assets/hplus/js/bootstrap-table.min.js"></script>
<script src="<?php echo base_url()?>assets/hplus/js/bootstrap-table-zh-CN.min.js"></script>
<script>
	function openWin(EntId,BatId){
		$("#EntId").val(EntId);
		$("#BatId").val(BatId);
		$("input[name=SampleNum]").val("");
		$("input[name=QualifiedNum]").val("");
		$("#myModal").show();
	}
	function closeWin(){
		$("#myModal").hide();
	}

	//同步批次数据
	function syncBatch(){
		$.ajax({
			url:"<?php echo site_url("product/Inspect/syncBatch/$enterprise_id")?>",
			data:{},
			dataType:"json",
			error:function(){
				swal("出错了!", "系统发生了一个错误！", "error");
			},
			success:function(data){
				if(data.status && data.status==1){
					swal("恭喜!", "更新同步批次数据成功！", "success");
					window.location.reload();
				}
			}
		});
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
						//alert(data.message);
//						swal({
//							title: "恭喜！",
//							text: "更新检测数据成功",
//							type: "success"
//						});
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