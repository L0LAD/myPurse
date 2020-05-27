google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
	var data = google.visualization.arrayToDataTable(pieTable);

	var options = 	{'title':'My Average Day',
					'width':550,
					'height':400,
					'colors':colorTable,
					'pieSliceText':'none'
				  	};

	var chart = new google.visualization.PieChart(document.getElementById('pieChart'));
	chart.draw(data, options);
}