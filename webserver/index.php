<!DOCTYPE HTML>
<html>
<head>

<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">

<h1><p align="center"><font size="10" color=DarkOrange> Takeaway Case Study </font></p></h1>
<p align="right">

<?php
include("refresh_dttm.php");
?>

<script>
window.onload = function() {
 
var dataPoints = [];
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	zoomEnabled: true,
	title: {
		text: "Bitcoin Price - " + new Date()
	}, 
	axisY: {
                minimum: 9000,
		title: "Price in USD",
		titleFontSize: 20,
		prefix: "$"
	},
        axisX: {
                title: "Refresh time",
		titleFontSize: 20
        },
	data: [{
		type: "line",
		yValueFormatString: "$#,##0.00",
		dataPoints: dataPoints
	}]
});
 
function addData() {
        data=<?php echo json_encode($json_array) ?>;
	for (var i = 0; i < data.length; i++) {
                t=data[i].Last_Refresh_Dttm.split(/[- :]/);
		dataPoints.push({
			x: new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]),
			y: parseFloat(data[i].Exchange_Rate)
		});
	}
	chart.render();
}

addData();
 
}
</script>

<script type="text/javascript">
function empty() {
    var x;
    x = document.getElementById("minthreshold").value;
    y = document.getElementById("maxthreshold").value;
    if (x == "" && y == "") {
        alert("Please set atleast one Threshold");
        return false;
    };
}
</script>

</head>

<body>
<div align="center">
<div id="chartContainer" style="height: 370px; width: 95%;"></div></div>

<form action="action_page.php" method="post"><p align="center">
  Min Threshold: <input type="number" name="minthreshold" id="minthreshold">
  &nbsp;
  Max Threshold: <input type="number" name="maxthreshold" id="maxthreshold">
<input type="submit" value="Update" onClick="return empty()" />
</p>
</form>

<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
</body>
</html>     
