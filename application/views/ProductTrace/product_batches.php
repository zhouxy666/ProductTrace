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
            <div class="container-fluid">
                <div class="row row-lg">
                    <div class="col-md-12 detail-list">
                        <div class="row row-lg">
                            <div class="col-sm-12 proTable">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>生产任务信息</h5>
                                    </div>
                                    <div class="ibox-content">
                                        <div class="example">
                                            <table id="exampleTableFromData" data-toggle="table"data-mobile-responsive="true">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">ID</th>
                                                    <th>批次名称</th>
                                                    <th>产品</th>
                                                    <th class="text-center">生产日期</th>
                                                    <!--
                                                    <th>规则</th>
                                                    <th>批次起始数</th>
                                                    <th>批次终止数</th>
                                                    -->
                                                    <th class="text-center">生产数量</th>
                                                    <th class="text-center">溯源二维码</th>
                                                    <!--
                                                    <th>是否审核</th>
                                                    <th>新增时间</th>
                                                    -->
                                                    <th class="text-center">生成时间</th>
                                                    <th class="text-center">操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($result as $row):?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $row->PK_REPBID?></td>
                                                            <td><?php echo $row->REPB_Name?></td>
                                                            <td><?php echo $row->CPI_Name?></td>
                                                            <td class="text-center"><?php echo date("Y-m-d",strtotime($row->REPB_PDate))?></td>
                                                            <!--
                                                            <td><?php echo $row->RER_Name?></td>
                                                            <td><?php echo $row->REPB_StartNums?></td>
                                                            <td><?php echo $row->REPB_EndNums?></td>
                                                            -->
                                                            <td class="text-center"><?php echo $row->REPB_Nums?></td>
                                                            <td class="text-center"><a href="http://08.88721.com/bu.aspx?d=<?php echo $row->REPS_Dan?>" target="_blank"><?php echo $row->REPS_Dan?></a></td>
                                                            <!--
                                                            <td><?php echo $row->REPB_ISCheck?></td>
                                                            <td><?php echo date("Y-m-d",strtotime($row->REPB_DateTime))?></td>
                                                            -->
                                                            <td class="text-center"><?php echo date("Y-m-d",strtotime($row->REPB_DateTimeOK))?></td>
                                                            <td class="text-center"><a href="<?php echo site_url("productTrace/batch_detail")."/".$enterprise_id."/".$row->PK_REPBID."/".$row->REPS_Dan?>">查看</a></td>
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
</body>
</html>
