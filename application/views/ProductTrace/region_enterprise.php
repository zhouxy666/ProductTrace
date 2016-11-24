<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>企业信息表</title>
    <link href="<?php echo base_url()?>assets/hplus/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/hplus/css/bootstrap-table.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/hplus/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/hplus/css/style.min.css" rel="stylesheet">
</head>
<body class="gray-bg top-navigation">
<div id="wrapper">
    <div id="page-wrapper" class="gray-bg">
        <div class="wrapper wrapper-content">
            <div class="container-fluid">
                <div class="row row-lg">
                    <div class="col-md-12 detail-list">
                        <div class="row row-lg">
                            <div class="col-sm-12 proTable">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>企业信息表</h5>
                                    </div>
                                    <div class="ibox-content">
                                        <div class="example">
                                            <table id="exampleTableFromData" data-toggle="table"data-mobile-responsive="true">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">序号</th>
                                                    <th>企业名称</th>
                                                    <th>类型</th>
                                                    <th class="text-center">行政区域</th>
                                                    <th class="text-center">经纬度</th>
                                                    <th class="text-center">操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($result as $row):?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $row->rn?></td>
                                                        <td><?php echo $row->Name?></td>
                                                        <td><?php echo $row->Cate?></td>
                                                        <td class="text-center"><?php echo $row->County?></td>
                                                        <td class="text-center"><?php echo $row->Lng?>,<?php echo $row->Lat?></td>
                                                        <td class="text-center">
                                                        <a href="<?php echo site_url("productTrace/product_batches")."/".$row->EntId ?>" target="_blank">查看</a>
                                                        <a href="javascript:getLngLat(<?php echo $row->EntId?>);">获取经纬度</a>
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
    function getLngLat(enterpriseId){
        var url="<?php echo site_url("productTrace/getLngLat")?>"+"/"+enterpriseId;
        $.ajax({
            url:url,
            data:{},
            dataType:'text',
            type:'get',
            success:function(msg){
                console.log(msg)
            },
            error:function(){
                console.log('error');
            }
        });
    }
</script>
</body>
</html>