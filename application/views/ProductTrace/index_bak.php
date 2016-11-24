<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>生产溯源监管端</title>
    <style type="text/css">
        *{margin:0;padding:0;}
        ul li{list-style:none;}
        a{text-decoration: none;}
        .mymap{height:474px;border:1px solid #ccc;box-shadow:0 0 7px #ccc;}
        .barGraph{position:relative;}
        .tips{position:absolute;top:1px;left:23px;z-index: 100;}
        .tips ul li{float:left;margin-right:1px;width:80px;height:20px;background:#1ab394;line-height:20px;text-align: center;border-radius:0 0 5px 5px;}
        .tips ul li a{font-size:12px;color:#fff;display:block;}
        .tips ul li:hover{background:#1ab385;}

        .barArea{height:314px;width:652px;overflow:hidden;border:1px solid #1ab394;box-shadow: 0 0 8px #1ab385}
        .barEnt{height:314px;width:652px;overflow:hidden;border:1px solid #1ab394;box-shadow: 0 0 8px #1ab385}
        .btnCity{text-align:center;height:50px;line-height:50px;}
        .ibox2{padding:5px 20px;}
        .btnCity ul li{display:inline-block;margin:0 1px;}
        .btnCity ul li a{display: block;padding:0 8px;transform:all 2s ease;}
        .btnCity ul li a:hover{background:#1ab394;color:#fff;}
        #box2 a{width:80px;display: block;height:16px;line-height:16px;float: left;font-size:14px;}
        #districtPage{height:474px;background-color:#fff;}
    </style>
    <link href="<?php echo base_url()?>assets/hplus/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/hplus/css/style.min.css?v=4.1.0" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/hplus/css/bootstrap-table.min.css" rel="stylesheet">
</head>

<body class="gray-bg top-navigation">
<div id="wrapper">
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
            <nav class="navbar navbar-static-top" role="navigation">
                <div class="btnCity">
                    <ul>
                        <li>
                            <a href="javascript:void(0)" style="font-weight:bold;">运城市</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">盐湖区</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">河津市</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">闻喜县</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">稷山县</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">万荣县</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">临猗县</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">新绛县</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">平陆县</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">芮城县</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">垣曲县</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">夏县</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">绛县</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" id="developCounty">空港开发区</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" id="developCounty">运城开发区</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" id="developCounty">绛县开发区</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" id="developCounty">风陵渡开发区</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="wrapper wrapper-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="mymap" id="mapContainer"></div>
                    </div>
                    <div class="col-md-7" id="mainPage">
                        <div class="row">
                           <!-- <div class="col-md-6">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>TOP5</h5>
                                    </div>
                                    <div class="ibox-content" id="box2">
                                        <p class="text-muted clearfix">
                                            <a href="<?php /*echo site_url("productTrace/product_batches/15")*/?>" target="_blank">王来成</a>
                                            <a href="<?php /*echo site_url("productTrace/product_batches/-1")*/?>" target="_blank">福同惠</a>
                                            <a href="<?php /*echo site_url("productTrace/product_batches/-12")*/?>" target="_blank">晟浩粮油</a>
                                        </p>
                                        <p class="text-muted clearfix">
                                            <a href="<?php /*echo site_url("productTrace/product_batches/-2")*/?>" target="_blank">晶鑫达</a>
                                            <a href="<?php /*echo site_url("productTrace/product_batches/-11")*/?>" target="_blank">瑞芝生物</a>
                                        </p>
                                    </div>
                                </div>
                            </div>-->
                            <div class="col-md-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>企业接入情况</h5>
                                    </div>
                                    <div class="ibox-content">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h1 class="no-margins"><a href="<?php echo site_url("productTrace/region_enterprise")?>" target="_blank"><?php echo $productEntNum?>家</a></h1>
                                                <div class="font-bold text-navy">
                                                    <small>接入企业数量</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h1 class="no-margins">150种</h1>
                                                <div class="font-bold text-navy">
                                                    <small>溯源产品类型</small>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row barGraph">
                            <div class="tips" id="tips">
                                <ul>
                                    <li><a href="javascript:void(0)">按区域统计</a></li>
                                    <li><a href="javascript:void(0)">按类型统计</a></li>
                                </ul>
                            </div>
                            <div class="col-md-12" id="cont">
                                <div class="barArea" id="barArea"></div>
                                <div class="barEnt" id="barEnt" style="display:none"></div>
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
                                        <div class="example">
                                            <table id="entTable" data-toggle="table" data-url="about:blank" data-query-params="queryParams" data-mobile-responsive="true" data-height="400" data-pagination="true" data-icon-size="outline" data-search="false">
                                                <thead>
                                                <tr>
                                                    <th data-field="state" data-checkbox="true"></th>
                                                    <!--th data-field="id" class="text-center">ID</th-->
                                                    <th data-field="name">企业名称</th>
                                                    <th data-field="county" class="text-center">所属区县</th>
                                                    <th data-field="cate">产品分类</th>
                                                    <th data-field="url" class="text-center">生产监管</th>
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
<script src="http://webapi.amap.com/maps?v=1.3&key=8325164e247e15eea68b59e89200988b"></script>
<script src="<?php echo base_url()?>assets/hplus/js/bootstrap-table.min.js"></script>
<script src="<?php echo base_url()?>assets/hplus/js/bootstrap-table-zh-CN.min.js"></script>
<script src="<?php echo base_url()?>assets/hplus/js/echarts.min.js"></script>
<script>
    $(document).ready(function(){
        //定义dom元素
        var $btnCity = $(".btnCity");
        var $box2 = $("#box2");
        var $tips = $("#tips");
        var $developCounty = $("#developCounty");
        var	myChart1 = echarts.init(document.getElementById('barArea'));
        var	myChart2 = echarts.init(document.getElementById('barEnt'));
        //定义请求柱状图的url
        var dataCounty = {
            url:'<?php echo site_url("productTrace/county")?>',
            name:'dataCounty'
        }
        var dataCate = {
            url:'<?php echo site_url("productTrace/Cate")?>',
            name:'dataCate'
        }
        var comUrl = '<?php echo site_url("productTrace/ent")."?region="?>'
        //定义默认的柱状图数据
        var option = {
            title:{
                text:"接入溯源体系的企业数量统计表",
                subtext: '按产品类型划分',
                x:'right',
                y:'top'
            },
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            legend: {
                data:[]
            },
            grid: {
                show:true,
                left: '1%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    data : [],
                    nameLocation : 'end',
                    axisLabel:{
                        interval:'0'
                    },
                    axisLine:{
                        lineStyle:{
                            width:1
                        }
                    }
                }
            ],
            yAxis : [
                {
                    type : 'value',
                    name : '统计数量'
                }
            ],
            series : [
                {
                    name:'数量',
                    type:'bar',
                    data:[],
                    barWidth :25,
                    barGap:'5%'
                }
            ]
        };
        var comListData = [];
        //定义ajax响应的数据变量
        var barDataCounty = new Array();
        var barDataCate = new Array();
        //定义开发区经纬度数据
        var lnglatData = [
            [34.6286925306,110.3318541313],//风陵渡
            [35.0962145278,111.0630221572],//空港
            [35.5087879616,111.6622204042],//绛县
            [35.0517078795,111.0265060607]
        ];

        /**
         * 事件绑定区
         */
        //绑定echarts点击事件
        myChart1.on('click',function(params){
            comListRequest(params.name);
            $('#mainPage').fadeOut(100);
            $('#districtPage').fadeIn(1000);
            showMap(params.name,true);
        });
        myChart2.on('click',function(params){

        })
        //绑定点击事件
        $btnCity.find('li').on({
            'mouseenter':function(){
                if(!$(this).children('a').attr('id')){
                    var countyName = $(this).children('a').html();
                    showMap(countyName,false);
                }else{
                    //如果是经济开发区，进行撒点
                }
            },
            'click':function(){
                if(!$(this).children('a').attr('id')){
                    var countyName = $(this).children('a').html();
                    if(countyName == '运城市'){
                        $('#districtPage').fadeOut(100);
                        $('#mainPage').fadeIn(1000);
                    }else if(countyName == '河津市'){
                        comListRequest(countyName);
                        $('#mainPage').fadeOut(100);
                        $('#districtPage').fadeIn(1000);
                        showMap('河津市',true);

                    }else if(countyName == '盐湖区'){
                        comListRequest(countyName);
                        $('#mainPage').fadeOut(100);
                        $('#districtPage').fadeIn(1000);
                        showMap('盐湖区',true);
                    }else if(countyName == '永济市'){
                        comListRequest(countyName);
                        $('#mainPage').fadeOut(100);
                        $('#districtPage').fadeIn(1000);
                        showMap('永济市',true);
                    }else if(countyName == '稷山县'){
                        comListRequest(countyName);
                        $('#mainPage').fadeOut(100);
                        $('#districtPage').fadeIn(1000);
                        showMap('稷山县',true);
                    }else if(countyName == '万荣县'){
                        comListRequest(countyName);
                        $('#mainPage').fadeOut(100);
                        $('#districtPage').fadeIn(1000);
                        showMap('万荣县',true);
                    }else if(countyName == '临猗县'){
                        comListRequest(countyName);
                        $('#mainPage').fadeOut(100);
                        $('#districtPage').fadeIn(1000);
                        showMap('临猗县',true);
                    }else if(countyName == '闻喜县'){
                        comListRequest(countyName);
                        $('#mainPage').fadeOut(100);
                        $('#districtPage').fadeIn(1000);
                        showMap('闻喜县',true);
                    }else if(countyName == '新绛县'){
                        comListRequest(countyName);
                        $('#mainPage').fadeOut(100);
                        $('#districtPage').fadeIn(1000);
                        showMap('新绛县',true);
                    }else if(countyName == '平陆县'){
                        comListRequest(countyName);
                        $('#mainPage').fadeOut(100);
                        $('#districtPage').fadeIn(1000);
                        showMap('平陆县',true);
                    }else if(countyName == '芮城县'){
                        comListRequest(countyName);
                        $('#mainPage').fadeOut(100);
                        $('#districtPage').fadeIn(1000);
                        showMap('芮城县',true);
                    }else if(countyName == '夏县'){
                        comListRequest(countyName);
                        $('#mainPage').fadeOut(100);
                        $('#districtPage').fadeIn(1000);
                        showMap('夏县',true);
                    }else if(countyName == '绛县'){
                        comListRequest(countyName);
                        $('#mainPage').fadeOut(100);
                        $('#districtPage').fadeIn(1000);
                        showMap('绛县',true);
                    }else if(countyName == '垣曲县'){
                        comListRequest(countyName);
                        $('#mainPage').fadeOut(100);
                        $('#districtPage').fadeIn(1000);
                        showMap('垣曲县',true);
                    }else{
                        alert('null');
                    }
                }else{
                    addMarker($(this).children('a').html());
                }
            }
        });
        $tips.find('li').on('mouseenter',function(){
            if($(this).index() == 0){
                $('#barArea').show();
                $('#barEnt').hide();

            }else{
                $('#barArea').hide();
                $('#barEnt').show();
            }
        });
        myChart1.on('click', function (params) {
            console.log(1);
        });
        /**
         * 方法定义区
         */
        /**
         * [ajaxRequest 封装好的ajax请求方方法]
         * @param  {[type]} url [请求的地址]
         * @param  {[type]} opt [存放成功响应数据的数组变量]
         * @return {[type]}     [null]
         */
        function ajaxRequest(data){
            $.ajax({
                url:data.url,
                dataType:'json',
                type:'post',
                success:function(msg){
                    changeOption(msg,data.name);
                },
                error:function(){
                    console.log('error');
                }
            });
        };
        /**
         * [comListRequest description]
         * @param  {[type]} county [description]
         * @return {[type]}        [description]
         */
        function comListRequest(county){
            $('#entTable').bootstrapTable('refresh', {
                url:comUrl+encodeURIComponent(county),
                dataType:'json'
            });;
        };
        /**
         * [changeOption 修改option参数]
         * @param  {[type]} opt [传入需要修改的参数]
         * @return {[type]}     [null]
         */
        function changeOption(opt,name){
            var legendData = option.legend.data;
            var xAxisData = option.xAxis[0].data;
            var series =option.series[0].data;
            if(name == 'dataCounty'){
                option.title.subtext = "按区域划分";
                for(var i = 0; i<opt.length;i++){
                    legendData.push(opt[i].county);
                    xAxisData.push(opt[i].county);
                    series.push(opt[i].entNum);
                }
                myChart1.setOption(option);
                option.legend.data = [];option.xAxis[0].data = [];option.series[0].data = [];
            }else if(name == 'dataCate'){
                option.title.subtext = "按产品类型划分";
                option.xAxis[0].axisLabel.interval = 'auto';
                for(var i = 0; i<opt.length;i++){
                    legendData.push(opt[i].cate);
                    xAxisData.push(opt[i].cate);
                    series.push(opt[i].entNum);
                }
                myChart2.setOption(option);
                option.legend.data = [];option.xAxis[0].data = [];option.series[0].data = [];
            }else{
                return;
            }
        };
        /**
         * [addMarker 对指定的开发区进行撒点]
         * @param {[type]} ele [传入开发区参数]
         */
        function addMarker(ele){
            if(ele == '空港开发区'){
                createMarker(lnglatData[1]);
            }else if(ele == '运城开发区'){
                createMarker(lnglatData[3]);
            }else if(ele == '绛县开发区'){
                createMarker(lnglatData[2]);
            }else{
                createMarker(lnglatData[0]);
            }
        };
        /**
         * [createMarker 执行撒点的方法]
         * @param  {[type]} lnglat [点位的经纬度]
         * @return {[type]}        [null]
         */
        function createMarker(lnglat){
            var map = new AMap.Map('mapContainer', {
                resizeEnable: true,
                zoom:8,
                center: [111.15142822, 35.15809125]
            });
            marker = new AMap.Marker({
                icon: "images/mark_b.png",
                position:[35.0962145278,111.0630221572]
            });
        };
        /**
         * [showMap 创建地图，并显示响应的行政区划边界]
         * @param  {[type]} eleCity [指定的行政区划城市]
         * @param  {[type]} getFlag [是否需要自适应界面]
         * @return {[type]}         [null]
         */
        function showMap(eleCity,getFlag){

            //创建一个地图
            var map = new AMap.Map('mapContainer', {
                resizeEnable: true,
                zoom:8,
                center: [111.15142822, 35.15809125]
            });
            //默认显示 运城市 的行政边界
            AMap.service('AMap.DistrictSearch',function(){
                //实例化DistrictSearch,并初始化
                districtSearch = new AMap.DistrictSearch({
                    level:'city',
                    subdistrict:2,
                    extensions:'all'
                });
                //调用districtSearch对象的search调用行政区查询功能
                districtSearch.search(eleCity,function(status,result){
                    //显示运城地区所有的下级区县的信息
                    var bounds = result.districtList[0].boundaries;
                    var polygons = [];
                    if (bounds) {
                        for (var i = 0, l = bounds.length; i < l; i++) {
                            //生成行政区划polygon
                            var polygon = new AMap.Polygon({
                                map: map,
                                strokeWeight: 1,
                                path: bounds[0],
                                fillOpacity: 0.7,
                                fillColor: '#CCF3FF',
                                strokeColor: '#CC66CC'
                            });
                            polygons.push(polygon);

                        }
                        getFlag?map.setFitView():function(){return};//地图自适应
                    }
                })
            });
        }
        /**
         * 方法初始化区域
         */
        //初始化显示地图
        showMap('运城市',false);
        //初始化数据
        ajaxRequest(dataCounty);
        ajaxRequest(dataCate);
        /**
         * 方法执行区
         */
        //显示柱状图2
        //myChart1.setOption(option);
        //显示柱状图2
        //myChart2.setOption(optionCate);

    });
</script>
</body>
</html>
