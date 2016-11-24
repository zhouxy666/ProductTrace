var lineChart = echarts.init(document.getElementById("echarts-line-chart"));
var lineoption = {
title : {
text: '抽检合格率变化'
},
tooltip : {
trigger: 'axis'
},
legend: {
data:['合格率']
//data:['合格率','不合格率']
},
grid:{
x:40,
x2:40,
y2:24
},
calculable : true,
xAxis : [
{
type : 'category',
boundaryGap : false,
data : [<?php echo join(",",$xAxis)?>]
}
],
yAxis : [
{
type : 'value',
axisLabel : {
formatter: '{value} %'
}
}
],
series : [
{
	name:'合格率',
	type:'line',
	data:[<?php echo join(",",$series1)?>],
	markPoint : {
		data : [
			{type : 'max', name: '最大值'},
			{type : 'min', name: '最小值'}
		]
	},
	markLine : {
		data : [
		{type : 'average', name: '平均值'}
		]
	}
}
/*,
{
	name:'不合格率',
	type:'line',
	data:[1, -2, 2, 5, 3, 2, 0],
	markPoint : {
		data : [
			{name : '周最低', value : -2, xAxis: 1, yAxis: -1.5}
		]
	},
	markLine : {
		data : [
		{type : 'average', name : '平均值'}
		]
	}
}
*/
]
};
lineChart.setOption(lineoption);
$(window).resize(lineChart.resize);