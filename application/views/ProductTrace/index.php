
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>生产溯源监管端</title>
	<style type="text/css">
		*{margin:0;padding:0;}
		ul li{list-style:none;}
		ul{margin-bottom:0;}
		a{text-decoration: none;}
		.mymap{height:310px;border:1px solid #ccc;box-shadow:0 0 7px #ccc;}
		.barGraph{position:relative;}
		.tips{position:absolute;top:1px;left:23px;z-index: 100;}
		.tips ul li{display:inline-block;margin-right:1px;padding:2px 5px;background:#1ab394;text-align: center;border-radius:0 0 5px 5px;}
		.tips ul li a{font-size:12px;color:#fff;display:block;}
		.tips ul li:hover{background:#666;}

		.barCom{height:309px;overflow:hidden;border:1px solid #1ab394;box-shadow: 0 0 8px #1ab385}
		.countyList{text-align:center;height:50px;line-height:50px;}
		.countyList ul{display:inline-block;}
		.countyList ul li{display:inline-block;margin:0 1px;}
		.countyList ul li a{display: block;padding:0 8px;transform:all 2s ease;}
		.countyList ul li a:hover{background:#1ab394;color:#fff;}
        .box2 {text-align:center;}
        .box2 h3 span{font-size:30px;letter-spacing:.2em;color:#f9a123;font-weight:bold;font-family:'微软雅黑'}
        .box3{margin-top:10px;text-align:center;}
        .box3 div{color:#666;}
        .box3 span{font-size:20px;color:#666;
			font-weight:bold;}
		.box3 small{color:#1ab385;}
        .box3 i{font-weight:bold;font-style:normal;font-size:18px;color:#666}
		/*mainPage - start*/
        #mainPage .mainPage-detail{margin-bottom:20px;font-family: '微软雅黑';color:#666;
            font-weight:bold;padding-bottom:10px;}
        #mainPage .mainPage-detail span{margin-right:17px;
            color:#f90;font-size:20px;}
		#mainPage .batchSort ul li{padding:4px 0;color:#ccc;}
		#mainPage .batchSort ul li a{color:#999;}
		#mainPage .batchSort ul li span{margin-right:5px;color:#1AB385;}
		/*mainPage - end*/
		/*#districtPage{height:547px;background-color:#fff;}*/
		/* infowindow信息窗口的样式 - start*/
		.markerFrame{border:1px solid #ccc;}
		.markerFrame .title{background:blue;color:#fff;padding:10px;}
		.markerFrame .content{padding:10px;}
		.markerFrame .content .img{float:left;}
		.markerFrame .content .cont{float:left;padding:10px;}
		.markerFrame .content .cont p{font-size:12px}
		/* infowindow信息窗口的样式 - end*/
		.clearfix:after{content:'.';height:0;display: block;visibility: hidden;clear:both;}

	</style>
    <link href="<?php echo base_url()?>assets/hplus/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/hplus/css/style.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/hplus/css/bootstrap-table.min.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/hplus/css/animate.css" rel="stylesheet">
</head>

<body class="gray-bg top-navigation">
    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom white-bg">
                <nav class="navbar navbar-static-top" role="navigation">
					<div class="countyList">
						<ul class="listGroup1">
							<li><a href="javascript:void(0)" data-regcode='140800' style="font-weight: bold;">运城市</a></li>
							<li><a href="javascript:void(0)" data-regcode='140802'>盐湖区</a></li>
							<li><a href="javascript:void(0)" data-regcode='140882'>河津市</a></li>
							<li><a href="javascript:void(0)" data-regcode='140881'>永济市</a></li>
							<li><a href="javascript:void(0)" data-regcode='140824'>稷山县</a></li>
							<li><a href="javascript:void(0)" data-regcode='140823'>闻喜县</a></li>
							<li><a href="javascript:void(0)" data-regcode='140822'>万荣县</a></li>
							<li><a href="javascript:void(0)" data-regcode='140825'>新绛县</a></li>
							<li><a href="javascript:void(0)" data-regcode='140821'>临猗县</a></li>
							<li><a href="javascript:void(0)" data-regcode='140829'>平陆县</a></li>
							<li><a href="javascript:void(0)" data-regcode='140830'>芮城县</a></li>
							<li><a href="javascript:void(0)" data-regcode='140827'>垣曲县</a></li>
							<li><a href="javascript:void(0)" data-regcode='140826'>绛县</a></li>
							<li><a href="javascript:void(0)" data-regcode='140828'>夏县</a></li>
						</ul>
						<ul class="listGroup2">
							<li><a href="javascript:void(0)" data-regcode='140830400000'>风陵渡经济开发区</a></li>
							<li><a href="javascript:void(0)" data-regcode='140802401000'>空港经济开发区</a></li>
							<li><a href="javascript:void(0)" data-regcode='140826400000'>绛县经济开发区</a></li>
							<li><a href="javascript:void(0)" data-regcode='140802400000'>运城经济开发区</a></li>
						</ul>
					</div>
                </nav>
            </div>
            <div class="wrapper wrapper-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-5">
							<div class="row">
								<div class="col-md-12">
									<div class="ibox float-e-margins">
										<div class="ibox-title">
											<h5>生产溯源总体态势</h5>
										</div>
										<div class="ibox-content text-muted" style="height:165px;">
											<div class="row box2">
												<h3 class="no-margins"><a href="javascript:void(0)"><span id="totalProNum"></span></a></h3>
												<div class="font-bold text-navy ">
													<small>生产产品总数</small>
												</div>
											</div>
											<div class="row box3">
												<div class="col-md-3">
													<p class="no-margins"><a><span id="totalOnlineNum"></span></a></p>
													<div class="font-bold text-navy">
														<small>上线总数 【家】</small>
													</div>
												</div>
												<div class="col-md-3">
													<p class="no-margins"><a><span id="totalSpeNum"></span></a></p>
													<div class="font-bold text-navy">
														<small>品种总数 【种】</small>
													</div>
												</div>
												<div class="col-md-3">
													<p class="no-margins"><a><span id="totalBatchNum"></span></a></p>
													<div class="font-bold text-navy">
														<small>批次总数【批】</small>
													</div>
												</div>
												<div class="col-md-3">
													<p class="no-margins"><a><span id="totalStockNum"></span></a></p>
													<div class="font-bold text-navy">
														<small>原料台账 【个】</small>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="mymap" id="mapContainer"></div>
								</div>
							</div>
                        </div>
                        <div class="col-md-7" id="mainPage">
							<div class="ibox">
								<div class="row">
									<div class="col-md-6">
										<div class="ibox-title">
											<h5> 生产批次数量 TOP 5</h5>
										</div>
										<div class="ibox-content batchSort" style="height:165px;">
											<ul>
												<?php foreach($batNumTop as $k=>$row):?>
												<li>
													<span><?php echo $k+1?></span><a href="<?php echo site_url("productTrace/product_batches/".$row["EntId"])?>" target="_blank"><?php echo $row["EntName"]?></a>
												</li>
												<?php endforeach;?>
											</ul>
										</div>
									</div>
									<div class="col-md-6">
										<div class="ibox-title">
											<h5> 生产产品数量 TOP 5</h5>
										</div>
										<div class="ibox-content batchSort" style="height:165px;">
											<ul>
												<?php foreach($productNumTop as $k=>$row):?>
													<li>
														<span><?php echo $k+1?></span><a href="<?php echo site_url("productTrace/product_batches/".$row["EntId"])?>" target="_blank"><?php echo $row["EntName"]?></a>
													</li>
												<?php endforeach;?>
											</ul>
										</div>
									</div>
								</div>
							</div>
                        	<div class="row barGraph">
								<div class="tips" id="tips">
									<ul>
										<li><a href="javascript:void(0)">按区域统计</a></li>
										<li><a href="javascript:void(0)">按类型统计</a></li>
										<li><a href="javascript:void(0)">抽检合格率统计</a></li>
										<li><a href="javascript:void(0)">企业市场占有率</a></li>
										<li><a href="javascript:void(0)">企业产值统计</a></li>
									</ul>
								</div>
								<div class="col-md-12" id="cont">
									<div class="barArea barCom" id="dataCounty"></div>
									<div class="barEnt barCom" id="dataCate" style="display:none;"></div>
									<div class="barPassRate barCom" id="dataPass" style="display:none;"></div>
									<div class="barBenefits barCom" id="dataBenefit" style="display:none;"></div>
									<div class="barOccupyRate barCom" id="dataOccupy" style="display:none;"></div>
								</div>
							</div>
                        </div>
                        <div class="col-md-7" id="districtPage" style="display:none">
							<div class="ibox float-e-margins">
					            <div class="ibox-title">
					                <h5>企业列表</h5>
					            </div>
					            <div class="ibox-content">
                					<div class="row row-lg">
                						<div class="col-sm-12">
					                        <div class="example" style="min-height:466px;">
					                            <table id="entTable" data-toggle="table" data-query-params="queryParams" data-mobile-responsive="true" data-height="406" data-pagination="true" data-icon-size="outline" data-search="false">
				                                    <thead>
				                                        <tr>
				                                            <th data-field="state" data-checkbox="true"></th>
				                                            <th data-field="id">ID</th>
				                                            <th data-field="name">企业名称</th>
				                                            <!--th data-field="cateLev1">一级分类</th>
				                                            <th data-field="qrcode">二维码</th-->
				                                            <th data-field="url">生产监管</th>
				                                        </tr>
				                                    </thead>
				                                </table>
					                        </div>
				                        </div>
					   				</div>
					            </div>
       					 	</div>
                        </div>             
                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="pull-right">
                    By：<a href="http://www.huanshuo.net/" target="_blank">山西寰烁电子科技股份有限公司</a>
                </div>
                <div>
                    <strong>Copyright</strong> 运城市食品药品监督管理局 &copy; 2016
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url()?>assets/hplus/js/jquery.min.js"></script>
    <script src="<?php echo base_url()?>assets/hplus/js/bootstrap.min.js"></script>
	<script src="http://webapi.amap.com/maps?v=1.3&key=ecd1b8ee647a25e85fb83164dbbc4f10"></script>
	<script src="<?php echo base_url()?>assets/hplus/js/bootstrap-table.min.js"></script>
    <script src="<?php echo base_url()?>assets/hplus/js/bootstrap-table-zh-CN.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>assets/hplus/js/echarts.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>assets/hplus/js/main.js"></script>
</body>
</html>
