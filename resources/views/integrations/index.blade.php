@extends('layouts.master')

@section('content')

	@component('layouts.header')
		<i class="fa fa-exchange"></i> Integrations
	@endcomponent
	@include('layouts.sidebar')
	<main class="mdl-layout__content mdl-color--grey-100">
		<div class="mdl-grid--no-spacing mdl-cell mdl-cell--12-col">
			<div class="mdl-grid">
				<div class="mdl-cell mdl-cell--6-col">
					<h3>Integrations</h3>
				</div>
				<div class="mdl-layout-spacer"></div>
				<div class="mdl-cell mdl-cell--middle">
					<a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--primary mdl-color-text--accent-contrast" href="{{ url('/integrations/create') }}"><i class="fa fa-plus-circle"></i> Add new integration</a>
					@if(!$integrations->isEmpty())
						<!-- <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--green mdl-color-text--accent-contrast" href="{{ url('/refreshall') }}"><i class="fa fa-refresh"></i> Refresh All</a> -->
					@endif
				</div>
			</div>
			<div class="mdl-grid">
			@if(!$integrations->isEmpty())
				<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
					<thead>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">Marketplace</th>
							<th class="mdl-data-table__cell--non-numeric">Site</th>						
							<th class="mdl-data-table__cell--non-numeric">Enabled</th>
							<th class="mdl-data-table__cell--non-numeric">Sync quantities</th>
							<th>Listed items</th>
							<th>Inventory percentage</th>
							<th class="mdl-data-table__cell--non-numeric">Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach($integrations as $integration)
						<tr>
							<td><img src="/images/{{ $integration->site }}.jpg" alt="" style="width: 100px; height: 100px; border-radius: 30%;"></td>
							<td class="mdl-data-table__cell--non-numeric">{{ $integration->site }}</td>
							<td class="mdl-data-table__cell--non-numeric">
								<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
								  <input type="checkbox" class="mdl-switch__input"
								  	@if($integration->enabled) checked @else @endif
								  >
								  <span class="mdl-switch__label"></span>
								</label>
							</td>
							<td class="mdl-data-table__cell--non-numeric">
								<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
								  <input type="checkbox" class="mdl-switch__input"
								  	@if($integration->qtySync) checked @else @endif
								  >
								  <span class="mdl-switch__label"></span>
								</label>
							</td>
							<td>{{ $integration->items()->count() }} / {{ Auth::user()->spier_items_count }}</td>
							<td>
								<canvas id="{{ $integration->site }}" height="100px" width="100px"></canvas>
							</td>
							<td>
								<a href="{{ url('/integrations/refresh/'.$integration->id) }}" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--teal"><i class="fa fa-refresh"></i> Refresh</a>
								<a href="" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--blue"><i class="fa fa-edit"></i> View</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			@else
			<h6>No integrations yet</h6>
			@endif
			</div>
		</div>
	</main>
@endsection
@push('js')
	@foreach($integrations as $integration)
	<script>
		var ctx = document.getElementById("{{ $integration->site }}");
	    var myChart = new Chart(ctx, {
	        type: 'pie',
	        data: {
	            datasets: [{
	                data: [{{ $integration->items()->count() }}, {{ Auth::user()->spier_items_count - $integration->items()->count() }} ],
	                backgroundColor: [
	                    '#08A6A3',
	                    '#A0A0A0'
	                ]
	            }]
	        }
	    });
	</script>
	@endforeach
@endpush