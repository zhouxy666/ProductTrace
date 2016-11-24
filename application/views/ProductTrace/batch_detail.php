<?php
function showTree($result,$i=0){
    if(is_array($result)){
        foreach($result as $row){
            echo "<td><nobr>".str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;",$i).$row["ToUser"]."</nobr></td><td class='text-center'><nobr>".$row["OrderNum"]."</nobr></td><td class='text-center'><nobr>".$row["StockNum"]."</nobr></td></tr>\n";
            if(!empty($row["childs"])){
                showTree($row["childs"],$i+1);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>批次详细页面</title>
    <style type="text/css">
        @grid-gutter-width:15px
        *{margin:0;padding:0;}
        ul li{list-style: none;}
        a{text-decoration: none;}

        .ibox{transition: all .3s ease;}
        .ibox-title{transition:background .3s ease;}
        .ibox:hover{box-shadow: 0 0 8px #999;}
        .ibox:hover .ibox-title{background:#1ab385;color:#fff;}
        .detail-right .qr-table .qr-table-left{float:left;}
        .detail-right .qr-table .qr-title {text-align:center;}
        .detail-right .qr-table .qr-title h2{font-family: '微软雅黑';font-weight: bold;}
        .detail-right .qr-table .qr-img{padding:0 20px 20px 20px;}
        .detail-right .qr-table .qr-table-right{float:left;height:200px;width:100px;}

        .wrap{height:2000px;}
        #toTop{width:140px;height:180px;background:#1ab385;color: #fff;position: fixed;bottom:200px;right:30px;cursor:pointer;-webkit-touch-callout: none;-webkit-user-select: none;-khtml-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;transition: all .3s ease;font-size:8px;}
        #toTop:hover{ -webkit-transform: scale(1.1);-moz-transform: scale(1.1);-ms-transform:scale(1.1);}
    </style>

    <link href="<?php echo base_url()?>assets/hplus/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/hplus/css/bootstrap-table.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/hplus/css/style.min.css" rel="stylesheet">
</head>
<body class="gray-bg top-navigation">

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="container-fluid">
                <div class="row row-lg">
                    <div class="col-md-9 detail-list">
                        <div class="row row-lg">
                            <div class="col-sm-6 baseTable">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>基本信息</h5>
                                    </div>
                                    <div class="ibox-content">
                                        <div class="example">
                                            <table data-toggle="table" data-height="200">
                                                <thead>
                                                    <tr>
                                                        <th>产品</th>
                                                        <th>批次名称</th>
                                                        <th>生产日期</th>
                                                        <th>批次数量</th>
                                                        <!--th>生成时间</th-->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($batch_base as $row):?>
                                                    <tr>
                                                        <td><nobr><?php echo $row->CPI_Name?></nobr></td>
                                                        <td><nobr><?php echo $row->REPB_Name?></nobr></td>
                                                        <td><nobr><?php echo date("Y-m-d",strtotime($row->REPB_PDate))?></nobr></td>
                                                        <td><nobr><?php echo $row->REPB_Nums?></nobr></td>
                                                        <!--td><nobr><?php echo $row->REPB_DateTimeOK?></nobr></td-->
                                                    </tr>
                                                <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 stockTable">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>原料信息</h5>
                                    </div>
                                    <div class="ibox-content">
                                        <div class="example">
                                            <table id="exampleTableFromData" data-toggle="table" data-height="200">
                                                <thead>
                                                <tr>
                                                    <th>原料</th>
                                                    <th>数量</th>
                                                    <th>入库人</th>
                                                    <th>入库时间</th>
                                                    <th>保质天数</th>
                                                    <th>入库名称</th>
                                                    <th>供应商</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($batch_inbound as $row):?>
                                                    <tr>
                                                        <td><nobr><?php echo $row->REPBM_Name?></nobr></td>
                                                        <td><nobr><?php echo $row->REPBM_Value?><?php echo $row->REPBM_Exp?></nobr></td>
                                                        <td><nobr><?php echo $row->RES_User?></nobr></td>
                                                        <td><nobr><?php echo date("Y-m-d",strtotime($row->RES_LDate))?></nobr></td>
                                                        <td><nobr><?php echo $row->RES_LDays?></nobr></td>
                                                        <td><nobr><?php echo $row->RES_Name?></nobr></td>
                                                        <td><nobr><?php echo $row->CS_Name?></nobr></td>
                                                    </tr>
                                                <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-lg">
                            <div class="col-sm-12 proTable">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>生产质检信息</h5>
                                    </div>
                                    <div class="ibox-content">
                                        <div class="example">
                                            <table id="exampleTableFromData" data-toggle="table"data-mobile-responsive="true">
                                                <thead>
                                                <tr>
                                                    <th>生产流程</th>
                                                    <th>流程描述</th>
                                                    <th>操作人员</th>
                                                    <th>操作时间</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($batch_qc as $row):?>
                                                    <tr>
                                                        <td><?php echo $row->REPBP_Name?></td>
                                                        <td><?php echo $row->REPBM_Content?></td>
                                                        <td><?php echo $row->REPBM_User?></td>
                                                        <td><?php echo date("Y-m-d",strtotime($row->REPBM_DateTime))?></td>
                                                    </tr>
                                                <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 detail-right">
                        <div class="row payTable">
                            <div class="col-sm-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>出库环节</h5>
                                    </div>
                                    <div class="ibox-content">
                                        <div class="example">
                                            <table  id="exampleTableFromData" data-height="200" data-toggle="table"data-mobile-responsive="true">
                                                <thead>
                                                <tr>
                                                    <th>经销商</th>
                                                    <th>出库人</th>
                                                    <th>出库时间</th>
                                                    <th>出库数量</th>
                                                    <th>产品总数量</th>
                                                    <th>二维码</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($batch_outbound as $row):?>
                                                    <tr>
                                                        <td><nobr><?php echo $row->CD_Name?></nobr></td>
                                                        <td><nobr><?php echo $row->RESL_User?></nobr></td>
                                                        <td><nobr><?php echo date("Y-m-d",strtotime($row->RESL_Date))?></nobr></td>
                                                        <td><nobr><?php echo $row->REPDan_Nums?></nobr></td>
                                                        <td><nobr><?php echo $row->REPDan_Nums_All?></nobr></td>
                                                        <td><nobr><?php echo $row->REPDan_S?></nobr></td>
                                                    </tr>
                                                <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>





                            <div class="col-sm-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>流通环节</h5>
                                    </div>
                                    <div class="ibox-content">
                                        <div class="example">
                                            <table  id="exampleTableFromData" data-toggle="table"data-mobile-responsive="true">
                                                <thead>
                                                <tr>
                                                    <th>经销商</th>
                                                    <th>采购量</th>
                                                    <th>库存量</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php echo showTree($batch_circulate);?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row qr-table" id="toTop">
                            <div class="qr-table-left">
                                <div class="qr-title">
                                    <h2>扫一扫</h2>
                                </div>
                                <div class="qr-img">
                                    <?php if(!empty($qrcode_img)):?>
                                        <img src="<?echo base_url()?>qrcode/<?echo $qrcode_img?>" alt="溯源二维码" width="100" height="100"/>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="qr-table-right">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<script src="<?php echo base_url()?>assets/hplus/js/jquery.min.js"></script>
<script src="<?php echo base_url()?>assets/hplus/js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>assets/hplus/js/bootstrap-table.min.js"></script>
<script src="<?php echo base_url()?>assets/hplus/js/bootstrap-table-zh-CN.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        var $toTop = $('#toTop');
        $(window).scroll(function() {
            if ($(this).scrollTop()!= 0) {
                $('#toTop').fadeIn();
            } else {
                $('#toTop').fadeOut();
            }
        });
        $toTop.click(function(){
            $('body').stop(true,true).animate({scrollTop:0},300);
        });
    })
</script>
</body>
</html>
