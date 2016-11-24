<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>运城市智慧食药大数据中心</title>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/hplus/css/bootstrap.min.css">
	<script src="<?php echo base_url()?>assets/hplus/js/jquery.min.js"></script>
	<script src="<?php echo base_url()?>assets/hplus/js/bootstrap.min.js"></script>
	<style type="text/css">
		.m-number{background-color: #FFF; text-align: center;}
		.m-number .title{font: 22px/26px microsoft yahei;font-weight: bold;color: #666;}
		.m-number .cont {margin-top:20px;}
		.m-number .cont span{ border-bottom:4px solid #cdd0d7; color:#e84c3d; margin:0 3px; font: 116px/80px "Tw Cen MT, Arial, Century Gothic";}
		.m-number .title2{line-height:50px}
		.m-number .lt{font: 16px "microsoft yahei";}
		.m-number .rt{color: #e84c3d;font-weight: bold;font-size:18px}

		.m-top{font-family: Arial;border-collapse: collapse;background-color: #f7fbff;}
		.m-top .hd {border-bottom: 1px solid #d8dbe1;}
		.m-top .hd .tt{border-bottom:2px solid #b1b7c3;padding-bottom: 6px;}
		.m-top .i1{color:#e84c3d;}
		.m-top .i2{color:#e96f49;}
		.m-top .i3{color:#eaa451;}
		.m-top .i4{color:#e9b945;}
		.m-top .i5{color:#ead24d;}

		.m-risk{}

		.f-h10{height:10px;}
	</style>
	<script src="<?php echo base_url()?>assets/hplus/js/plugins/echarts/echarts-all.js"></script>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>assets/hplus/js/plugins/lrTab/lrTab.css"  />
	<script type="text/javascript" src="<?php echo base_url()?>assets/hplus/js/plugins/lrTab/lrTab.js"></script>
</head>
<body>

<div class="container" style="width:100%" >

	<div class="row" style="background-color:#046dae;">
		<div class="col-md-12" style="text-align: center;color:#FFFFFF;">
			<h1 style="font: 30px/56px 'microsoft yahei';"><a href="../main/a_t_sy_qy_list.aspx" style="color:white;text-decoration:none">运城市智慧食药大数据中心</a></h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-7 m-number">
			<h2 class="title">已监管生产企业生产产品总数</h2>
			<div class="cont">
				<?php
				$str=sprintf("%08d",$productionNum);
				for($i=0;$i<strlen($str);$i++):?>
				<span><?php echo $str[$i]?></span>
				<?php endfor;?>
			</div>
			<h2 class="title2">
				<span class="lt">生产企业上线总数：</span>
				<span class="rt"><?php echo $entNum?></span>
				&nbsp;&nbsp;
				<span class="lt">产品品种总数：</span>
				<span class="rt"><?php echo $productTypeNum?></span>
				&nbsp;&nbsp;
				<span class="lt">批次总数：</span>
				<span class="rt"><?php echo $productBatNum?></span>
				&nbsp;&nbsp;
				<span class="lt">原料台账总数：</span>
				<span class="rt"><?php echo $productResNum?></span>
			</h2>

			<div class="row" style="margin-top: 10px;" >
				<div class="col-md-12" style="text-align: center;">
					<div id="wrap_tabs" style="width:100%;margin-left: 15px;margin-top: 0;" >
						<div id="list" style="width:100%" >
							<div class="item" style="width:100%;" >
								<div class="tab">
									<ul>
										<li class="current">企业风险统计</li>
										<li>企业上线情况统计</li>
										<li>企业上线地域统计</li>
										<li>企业分类统计</li>
										<li>生产批次年统计</li>
										<li>生产批次月统计</li>
										<li>企业市场占有率</li>
									</ul>
									<span class="switchBtn"><a href="javascript:;" class="prevNot">左</a><a href="javascript:;" class="next">右</a></span>
								</div>
								<div class="items">
									<div id="chart1" style="width:100%;height:400px;">数据加载中,请稍后...</div>
									<script src="<?php echo site_url("product/index/chart")."?name=countRiskLevGroupByEntType&title=企业风险统计&type=mbar&id=chart1"?>"></script>
								</div>
								<div class="items">
									<div id="chart2" style="width:100%;height:400px;">数据加载中,请稍后...</div>
									<script src="<?php echo site_url("product/index/chart")."?name=countEntNum&title=生产企业上线情况统计&type=pie&id=chart2"?>"></script>
								</div>
								<div class="items">
									<div id="chart3" style="width:100%;height:400px;">数据加载中,请稍后...</div>
									<script src="<?php echo site_url("product/index/chart")."?name=countEntNumGroupByRegion&title=生产企业上线地域统计&type=pie&id=chart3"?>"></script>
								</div>
								<div class="items">
									<div id="chart4" style="width:100%;height:400px;">数据加载中,请稍后...</div>
									<script src="<?php echo site_url("product/index/chart")."?name=countEntNumGroupByCate&title=生产企业分类统计&type=bar&id=chart4"?>"></script>
								</div>
								<div class="items">
									<div id="chart5" style="width:100%;height:400px;">数据加载中,请稍后...</div>
									<script src="<?php echo site_url("product/index/chart")."?name=countBatNumGroupByMonth&title=生产批次一年统计&type=bar&id=chart5"?>"></script>
								</div>
								<div class="items">
									<div id="chart6" style="width:100%;height:400px;">数据加载中,请稍后...</div>
									<script src="<?php echo site_url("product/index/chart")."?name=countBatNumGroupByDay&title=生产批次一月统计&type=line&id=chart6"?>"></script>
								</div>
								<div class="items">
									<div id="chart7" style="width:100%;height:400px;">数据加载中,请稍后...</div>
									<script src="<?php echo site_url("product/index/chart")."?name=countMarketRateGroupByEnt&title=企业市场占有率&type=bar&id=chart7"?>"></script>
								</div>
							</div>
						</div>
						<!--/list-->
					</div>

				</div>
			</div>

		</div>
		<div class="col-md-5" style="background-color: #FFF; ;">


			<div class="row" style="margin-top: 10px;" >
				<div class="col-md-12" style="text-align: center;color:#FFFFFF;">
					<div id="div_map" style="width:100%;height:370px;"></div>
					<script src="<?php echo site_url("product/index/chart")."?name=countEntNumGroupByRegion&type=map&id=div_map"?>"></script>
				</div>
			</div>


			<div class="row" style="margin-top: 10px;" >
				<div class="col-md-12" style="text-align: center;">
					<div class="m-risk">
					<table width="100%" style="border: 1px solid #4bacff;font-family: Arial;border-collapse: collapse;background-color: #f7fbff;">
									<tbody><tr>
										<td width="40%" height="30" align="center" style="background-color: #4bacff;color:#FFF">
											企业性质\风险等级
										</td>
										<td width="15%" align="center" style="background-color: #4bacff;color:#FFF"> A(0-30含)
										</td>
										<td width="15%" align="center" style="background-color: #4bacff;color:#FFF">B(30-45含)
										</td>
										<td width="15%" align="center" style="background-color: #4bacff;color:#FFF">C(45-60含)
										</td>
										<td width="15%" align="center" style="background-color: #4bacff;color:#FFF">D(&gt;60)
										</td>
									</tr>


									<tr>
										<td height="26" align="center" style="border: 1px solid #4bacff;">食品生产企业</td>


										<td align="center" style="border: 1px solid #4bacff;">
											0
										</td>

										<td align="center" style="border: 1px solid #4bacff;">
											0
										</td>

										<td align="center" style="border: 1px solid #4bacff;">
											0
										</td>

										<td align="center" style="border: 1px solid #4bacff;">
											0
										</td>

									</tr>

									<tr>
										<td height="26" align="center" style="border: 1px solid #4bacff;">食品销售企业</td>


										<td align="center" style="border: 1px solid #4bacff;">
											2
										</td>

										<td align="center" style="border: 1px solid #4bacff;">
											0
										</td>

										<td align="center" style="border: 1px solid #4bacff;">
											0
										</td>

										<td align="center" style="border: 1px solid #4bacff;">
											0
										</td>

									</tr>

									<tr>
										<td height="26" align="center" style="border: 1px solid #4bacff;">餐饮服务企业</td>


										<td align="center" style="border: 1px solid #4bacff;">
											0
										</td>

										<td align="center" style="border: 1px solid #4bacff;">
											0
										</td>

										<td align="center" style="border: 1px solid #4bacff;">
											0
										</td>

										<td align="center" style="border: 1px solid #4bacff;">
											0
										</td>

									</tr>


									</tbody></table>
					</div>
					<div class="f-h10">

					</div>

					<table width="100%">
						<tr>
							<td width="49%">
								<table width="100%" class="m-top">
									<tbody>
									<tr class="hd">
										<td height="30">
											<span class="tt">&nbsp;&nbsp;<b>企业生产批次数量统计 TOP 5</b></span>
										</td>
										<td align="center">数量</td>
									</tr>
									<?php foreach($productBatNumTop as $i=>$row):?>
									<tr>
										<td valign="bottom" height="26" align="left">
											<em class="i<?php echo $i+1?>"><?php echo $i+1?></em>&nbsp;&nbsp;<?php echo $row["EntName"]?></td>
										<td width="50" align="center"><?php echo $row["BatNum"]?></td>
									</tr>
									<?php endforeach;?>
									</tbody>
								</table>
							</td>
							<td width="2%"></td>
							<td width="49%">
								<table width="100%" class="m-top">
									<tbody>
									<tr class="hd">
										<td height="30">
											<span class="tt">&nbsp;&nbsp;<b>企业生产批次产品数量统计 TOP 5</b></span>
										</td>
										<td align="center">数量</td>
									</tr>
									<?php foreach($productionNumTop as $i=>$row):?>
										<tr>
											<td valign="bottom" height="26" align="left">
												<em class="i<?php echo $i+1?>"><?php echo $i+1?></em>&nbsp;&nbsp;<?php echo $row["EntName"]?></td>
											<td width="50" align="center"><?php echo $row["ProductNum"]?></td>
										</tr>
									<?php endforeach;?>
									</tbody>
								</table>
							</td>
						</tr>
						<tr><td height=10 colspan=2 ></td></tr>
					</table>



				</div>
			</div>


		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			$(".items").hide().eq(0).show();
		});
	</script>
	<div class="row" style="background-color:#046dae;">
		<div class="col-md-12" style="text-align: center;color:#FFFFFF;height:30px;">
			<span style="font: 12px 'microsoft yahei';line-height:30px">技术支持：山西寰烁电子科技股份有限公司</span>
		</div>
	</div>
</div>
</body>
</html>
