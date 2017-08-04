@extends('layouts.master')

@section('content')

	@component('layouts.header')
		<i class="fa fa-database"></i> Inventory
	@endcomponent
	@include('layouts.sidebar')
	<main class="mdl-layout__content mdl-color--grey-100">
	    <div class="mdl-grid--no-spacing mdl-cell mdl-cell--12-col">
			<div class="mdl-grid">
				<div class="mdl-cell mdl-cell--6-col">
					<h3>Inventory Items</h3>
				</div>
				<div class="mdl-layout-spacer"></div>
				<a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--teal mdl-color-text--accent-contrast" href="{{ url('/categories') }}" style="margin-right: 10px;"><i class="fa fa-edit"></i> Manage categories</a>
				<a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--primary mdl-color-text--accent-contrast" href="{{ url('/items/create') }}"><i class="fa fa-plus-circle"></i> Add new items</a>
			</div>
			<div class="mdl-grid">
				<div class="mdl-cell--12-col">
					<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
						<div class="mdl-tabs__tab-bar">
							<a href="#inventory" class="mdl-tabs__tab is-active">Inventory</a>
							@foreach($integrations as $integration)
								<a href="#{{ $integration->site }}" class="mdl-tabs__tab">
									<img src="{{ url('images/'.$integration->site.'.jpg') }}" alt="{{ $integration->site }}" style="width: 50px; height: 50px; border-radius: 50%; " > {{ $integration->site }} ({{ $integration->items->count() }})
								</a>
							@endforeach
						</div>
						<div class="mdl-tabs__panel is-active" id="inventory">
							<table class="ui celled table compact small" id="inventory-table">
							    <thead>
							        <tr class="center aligned">
							            <th>SKU</th>
							            <th>UPC/EAN</th>
							            <th>Title</th>
							            <th>Quantity</th>
							            <th>Condition</th>
							            <th>Marketplaces</th>
							        </tr>
							    </thead>
							    <tbody>
							    	@foreach($items as $item)
							        <tr class="center aligned">
							            <td>{{ substr($item->sku, 0, 15 ) }}</td>
							            <td>{{ $item->upc ? $item->upc: $item->ean }}</td>
							            <td>{{ substr($item->title, 0,40) }}</td>
							            <td>{{ $item->quantityAvailable }}</td>
							            <td>{{ $item->condition }}</td>
							            <td>
							            	@foreach($item->listedOn() as $site)
							            	<img src="/images/{{ $site }}.jpg" alt="{{ $site }}" style="width: 50px; height: 50px; border-radius: 50%; ">
							            	@endforeach
							            </td>
							        </tr>
							        @endforeach
							    </tbody>
							</table>
						</div>
						@foreach($integrations as $integration)
						<div class="mdl-tabs__panel" id="{{ $integration->site }}">
							<table class="ui celled table compact small" id="{{ $integration->site }}-table">
							    <thead>
							        <tr class="center aligned">
							            <th>SKU</th>
							            <th>UPC/EAN</th>
							            <th>Title</th>
							            <th>Price</th>
							            <th>Quantity</th>
							            <th>Rank</th>
							            <th>Strategy</th>
							        </tr>
							    </thead>
							    <tbody>
							    	@foreach($integration->items as $item)
							        <tr class="center aligned">
							            <td>{{ $item->sku }}</td>
							            <td>{{ $item->upc ? $item->upc : $item->ean }}</td>
							            <td>{{ $item->spier_item->title }}</td>
							            <td>{{ $integration->currency }} {{ $item->price }}</td>
							            <td>{{ $item->quantityAvailable }}</td>
							            <td>{{ $item->currentRank ?: '-'  }}</td>
							            <td>-</td>
							        </tr>
							        @endforeach
							    </tbody>
							</table>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</main>
@endsection
@push('js')
<script src="/vendor/datatables/buttons.server-side.js"></script>
<script>
	$(document).ready(function() {
	    $('#inventory-table').DataTable();
	    
	    @foreach($integrations as $integration)
		    $('#{{ $integration->site }}-table').DataTable();
	    @endforeach
	});
</script>
@endpush