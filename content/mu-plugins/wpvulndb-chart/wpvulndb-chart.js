(function() {
	var httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState === 4) {
			if (httpRequest.status === 200) {
				var data = JSON.parse(httpRequest.responseText);
				var slugs = [],
					counts = [];
				for ( i = 0, c = data.top.length; i < c && i < 10; i++ ) {
					slugs.push( data.top[i].slug );
					counts.push( data.top[i].count );
				}
				var ctx = document.getElementById("wpvulndb_chart_1");
				var myChart = new Chart(ctx, {
					type: 'bar',
					data: {
						labels: slugs,
						datasets: [{
							label: '# of vulnerabilities',
							data: counts,
							backgroundColor: '#0093C2',
							borderWidth: 1
						}]
					},
					options: {
						title: {
							display: true,
							text: 'Top 10 WordPress plugins with the most reported vulnerabilities per ' + data.last_update.split( ' ' )[0]
						},
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero:true
								}
							}]
						}
					}
				});
			}
		}
	};
	httpRequest.open('GET', 'https://www.bjornjohansen.com/wpvulndb/plugins-top.json?v=3' );
	httpRequest.send();
})();
