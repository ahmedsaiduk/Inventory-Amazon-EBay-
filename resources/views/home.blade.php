@extends('layouts.master')

@section('content')
	@component('layouts.header')
		<i class="fa fa-area-chart"></i> Dashboard
	@endcomponent

    @include('layouts.sidebar')
    <main class="mdl-layout__content mdl-color--grey-100">
    	<div class="mdl-grid">
			<div class="mdl-cell mdl-cell--8-col mdl-color--white mdl-shadow--2dp">
				<div class="mdl-grid">
					<div class="mdl-layout-spacer"></div>
					<h3><i class="fa fa-line-chart mdl-color-text--primary"></i> Orders by country</h3>
					<div class="mdl-layout-spacer"></div>
				</div>
				<div class="mdl-grid">
		    		<div class="mdl-cell mdl-cell--3-col">
						<ul class="mdl-list">
							<?php $n=1; ?>
							@foreach($countries as $country)
							<li class="mdl-list__item mdl-list__item--two-line">
								<span class="mdl-list__item-primary-content">
								<span><?php echo $n++;  ?> - {{ $country->country }} <br>"{{ $country->site }}"</span>
								<span class="mdl-list__item-sub-title">{{ $country->orders_count }} orders</span>
								</span>
							</li>
							@endforeach
						</ul>
		    		</div>
		    		<div class="mdl-cell mdl-cell--9-col">
			    	    <div id="map-container" style="height: 300px;"></div>
		    		</div>
				</div>
    		</div>
    		<div class="mdl-cell mdl-cell--4-col mdl-color--white mdl-shadow--2dp">
    			<div class="mdl-grid">
    				<div class="mdl-grid">
			    		<h3><i class="fa fa-star mdl-color-text--primary"></i> Top 5 Products</h3>
    				</div>
    				<div class="mdl-grid">
		  				<canvas id="myChart">
				  		</canvas>	
    				</div>
    			</div>
			</div>
    	</div>
    	<div class="mdl-grid">
    	    <div class="mdl-cell mdl-cell--3-col mdl-color--white mdl-shadow--2dp">
    	    	<div class="mdl-grid">
					<h3>Inventory items</h3>
    	    	    <div class="mdl-layout-spacer"></div>
	    	    	<h4><i class="fa fa-tags mdl-color-text--primary"></i> {{ $items }}</h4>
    	    	</div>
    	    </div>
    	    <div class="mdl-cell mdl-cell--3-col mdl-color--white mdl-shadow--2dp">
    	    	<div class="mdl-grid">
					<h3>Orders this month</h3>
    	    	    <div class="mdl-layout-spacer"></div>
	    	    	<h4><i class="fa fa-cart-plus mdl-color-text--primary"></i> {{ $orders }}</h4>
    	    	</div>
    	    </div>
    	    <div class="mdl-cell mdl-cell--3-col mdl-color--white mdl-shadow--2dp">
				<div class="mdl-grid">
					<h3>Sales this month</h3>
    	    	    <div class="mdl-layout-spacer"></div>
	    	    	<h4><i class="fa fa-dollar mdl-color-text--primary"></i> {{ number_format($monthlyRevenue, 2) }}</h4>
    	    	</div>
    	    </div>
    	    <div class="mdl-cell mdl-cell--3-col mdl-color--white mdl-shadow--2dp">
				<div class="mdl-grid">
					<h3>Recently repriced items</h3>
    	    	    <div class="mdl-layout-spacer"></div>
	    	    	<h4><i class="fa fa-refresh mdl-color-text--primary"></i> 302</h4>
    	    	</div>
    	    </div>
    	</div>
	    <div class="mdl-grid">
	  		<div class="mdl-cell mdl-cell--6-col mdl-color--white mdl-shadow--2dp">
	  			<div class="mdl-grid">
			  		<canvas id="myChart2" height="200px">
			  		</canvas>
	  			</div>
	  		</div>
	  		<div class="mdl-cell mdl-cell--6-col mdl-color--white mdl-shadow--2dp">
		  		<div class="mdl-grid">
		  			<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
						<div class="mdl-tabs__tab-bar">
							<a href="#by-day" class="mdl-tabs__tab is-active">Sales by day</a>
							<a href="#by-month" class="mdl-tabs__tab">Sales by month</a>
						</div>
						<div class="mdl-tabs__panel is-active" id="by-day">
							<canvas id="myChart4" height="200px">
					  		</canvas>
						</div>
						<div class="mdl-tabs__panel" id="by-month">
							<canvas id="myChart3" height="200px">
					  		</canvas>
						</div>
					</div>
		  		</div>
	  		</div>
	    </div>
	</main>
@endsection
@push('js')
	<script>
	    var ctx = document.getElementById("myChart");
	    var myChart = new Chart(ctx, {
	        type: 'doughnut',
	        data: {
	            labels: <?php echo json_encode($top5->pluck('sku')); ?>,
	            datasets: [{
	                label: 'sales',
	                data: <?php echo json_encode($top5->pluck('transactions_count')); ?>,
	                backgroundColor: [
	                    '#008785',
	                    '#04B7B4',
	                    '#06F5F1',
	                    '#79FFFD',
	                    '#BEFFFE'
	                ]
	            }]
	        }
	    });
	</script>
	<script>
	    var ctx = document.getElementById("myChart2");
	    var myChart = new Chart(ctx, {
	        type: 'bar',
	        data: {
	            labels: ["Today", "Yesterday", "2 days ago", "3 days ago", "4 days ago"],
	            datasets: [{
	                label: 'unshipped orders',
	                data: <?php echo json_encode($unshipped->values()) ?> ,
	                backgroundColor: 'rgba(54, 162, 235, 1)'
	            }]
	        },
	        options: {
	            scales: {
	                yAxes: [{
	                    ticks: {
	                        beginAtZero:true
	                    }
	                }]
	            }
	        }
	    });
	</script>
	<script>
		var months = {'01':'Jan', '02':'Feb', '03':'MAR', '04':'APR', '05':'MAY', '06':'JUN', '07':'JUL', '08':'AUG', '09':'SEP', '10':'OCT','11':'NOV', '12':'DEC'};
		var keys = <?php echo json_encode($revenue->keys()); ?>;
		var gKeys = [];
		
		keys.forEach(function (key, index){
			gKeys.push(months[key]);
		});
	    
	    var ctx = document.getElementById("myChart3");
	    var data = {
	        labels:  gKeys,
	        datasets: [
	            {
	                label: "Sales",
	                fill: false,
	                lineTension: 0.1,
	                backgroundColor: "rgba(75,192,192,0.4)",
	                borderColor: "rgba(75,192,192,1)",
	                borderCapStyle: 'butt',
	                borderDash: [],
	                borderDashOffset: 0.0,
	                borderJoinStyle: 'miter',
	                pointBorderColor: "rgba(75,192,192,1)",
	                pointBackgroundColor: "#fff",
	                pointBorderWidth: 1,
	                pointHoverRadius: 5,
	                pointHoverBackgroundColor: "rgba(75,192,192,1)",
	                pointHoverBorderColor: "rgba(220,220,220,1)",
	                pointHoverBorderWidth: 2,
	                pointRadius: 1,
	                pointHitRadius: 10,
	                data: <?php echo json_encode($revenue->values()); ?> ,
	                spanGaps: false,
	            }
	        ]
	    };
	    var myChart = new Chart(ctx, {
	        type: 'line',
	        data: data,
	        options: {
	            scales: {
	                yAxes: [{
	                    stacked: true
	                }]
	            }
	        }
	    });
	</script>
	<script>
		
		var keys = <?php echo json_encode($revenueByDay->keys()); ?>;
	    
	    var ctx = document.getElementById("myChart4");
	    var data = {
	        labels:  keys,
	        datasets: [
	            {
	                label: "Sales",
	                fill: false,
	                lineTension: 0.1,
	                backgroundColor: "rgba(75,192,192,0.4)",
	                borderColor: "rgba(75,192,192,1)",
	                borderCapStyle: 'butt',
	                borderDash: [],
	                borderDashOffset: 0.0,
	                borderJoinStyle: 'miter',
	                pointBorderColor: "rgba(75,192,192,1)",
	                pointBackgroundColor: "#fff",
	                pointBorderWidth: 1,
	                pointHoverRadius: 5,
	                pointHoverBackgroundColor: "rgba(75,192,192,1)",
	                pointHoverBorderColor: "rgba(220,220,220,1)",
	                pointHoverBorderWidth: 2,
	                pointRadius: 1,
	                pointHitRadius: 10,
	                data: <?php echo json_encode($revenueByDay->values()); ?> ,
	                spanGaps: false,
	            }
	        ]
	    };
	    var myChart = new Chart(ctx, {
	        type: 'line',
	        data: data,
	        options: {
	            scales: {
	                yAxes: [{
	                    stacked: true
	                }]
	            }
	        }
	    });
	</script>
	<!-- map -->
	<script>
	    var map = new Datamaps({
	    	element: document.getElementById('map-container'),
	    	fills: {
	    		HIGH: '#00AAFF',
	            LOW: '#B7E7FF',
	            MEDIUM: '#60CAFF',
	            UNKNOWN: 'rgb(0,0,0)',
	    		defaultFill: '#BABABA'
	    	},
	    	data: {
	            MEX: {
	                fillKey: 'LOW',
	                orders: 0
	            },
	            CAN: {
	                fillKey: 'MEDIUM',
	                orders: 10
	            },
	            USA: {
	            	fillKey: 'HIGH',
	            	orders: 662
	            }
	        },
	        geographyConfig: {
	            popupTemplate: function(geo, data) {
	                return ['<div class="hoverinfo"><strong>',
	                        'Orders ' + geo.properties.name,
	                        ': ' + data.orders,
	                        '</strong></div>'].join('');
	            }
	        }
	    });
	</script>
@endpush
