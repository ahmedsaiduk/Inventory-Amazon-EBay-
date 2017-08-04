@extends('layouts.master')

@section('content')

	@component('layouts.header')
		<i class="fa fa-cart-arrow-down"></i> Orders
	@endcomponent
	@include('layouts.sidebar')
	<main class="mdl-layout__content mdl-color--grey-100">
    	<div class="mdl-grid">
			<div class="mdl-cell--12-col">
				<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
					<div class="mdl-tabs__tab-bar">
						<a href="#all-orders" class="mdl-tabs__tab is-active">All orders</a>
						@foreach(Auth::user()->integrations as $integration)
							<a href="#{{ $integration->site }}" class="mdl-tabs__tab">
								<img src="{{ url('images/'.$integration->site.'.jpg') }}" alt="{{ $integration->site }}" style="width: 50px; height: 50px; border-radius: 50%; " > {{ $integration->site }} ({{ $integration->orders->count() }})
							</a>
						@endforeach
					</div>
					<div class="mdl-tabs__panel is-active" id="all-orders">
						<table class="ui celled table compact small" id="all-orders-table">
							<thead>
								<tr class="center aligned">
									<th>Site Order Id</th>
									<th>Marketplace</th>
									<th>Date</th>
									<th>Status</th>
									<th>Total price</th>
									<th>Total shipping</th>
									<th>Shipping service</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach($orders as $order)
									<tr class="center aligned">
										<td>{{ $order->siteOrderId }}</td>
										<td>
											<img src="/images/{{ $order->integration->site }}.jpg" alt="{{ $order->integration->site }}" width="50px" height="50px">
										</td>
										<td>{{ date("d-M Y", strtotime($order->purchaseDate)) }}</td>
										<td>@if($order->status == 'shipped')
												<span class="mdl-chip mdl-color--green mdl-color-text--white">
												    <span class="mdl-chip__text">{{ $order->status }}</span>
												</span>
											@else
												<span class="mdl-chip">
												    <span class="mdl-chip__text">{{ $order->status }}</span>
												</span>
											@endif
										</td>
										<td>{{ $order->integration->currency }} {{ $order->totalPrice }}</td>
										<td>{{ $order->integration->currency }} {{ $order->totalShipping ?: '00.00' }}</td>
										<td>{{ $order->shippingService }}</td>
										<td>
											<a href="{{ $order->getPackingSlipURL() }}" target="blank" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--accent"><i class="fa fa-print"></i>Packing slip</a>
											<a class="mdl-button mdl-js-button mdl-js-ripple-effect order-details"><i class="fa fa-list"></i> View details</a>
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
									<th>Site Order Id</th>
									<th>Date</th>
									<th>Status</th>
									<th>Total price</th>
									<th>Total shipping</th>
									<th>Shipping service</th>
									<th>Buyer name</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach($integration->orders as $order)
									<tr class="center aligned">
										<td>{{ $order->siteOrderId }}</td>
										<td>{{ date("d-M Y", strtotime($order->purchaseDate)) }}</td>
										<td>@if($order->status == 'shipped')
												<span class="mdl-chip mdl-color--green mdl-color-text--white">
												    <span class="mdl-chip__text">{{ $order->status }}</span>
												</span>
											@else
												<span class="mdl-chip">
												    <span class="mdl-chip__text">{{ $order->status }}</span>
												</span>
											@endif
										</td>
										<td>{{ $order->integration->currency }} {{ $order->totalPrice }}</td>
										<td>{{ $order->integration->currency }} {{ $order->totalShipping ?: '00.00' }}</td>
										<td>{{ $order->shippingService }}</td>
										<td>{{ $order->buyerName }}</td>
										<td>
											<a href="{{ $order->getPackingSlipURL() }}" target="blank" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--accent"><i class="fa fa-print"></i>Packing slip</a>
											<a class="mdl-button mdl-js-button mdl-js-ripple-effect order-details"><i class="fa fa-list"></i> View details</a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					@endforeach
				</div>
			</div>
		</div>
    </main>
    <dialog class="mdl-dialog" id="order-dialog">
	    <div class="mdl-dialog__content">
	        <p>
	            Allow this site to improve your experience?
	        </p>
	    </div>
	    <div class="mdl-dialog__actions mdl-dialog__actions--full-width">
	        <button type="button" class="mdl-button">Agree</button>
	        
	    </div>
	</dialog>
@endsection
@push('js')
<script>
	$(document).ready(function() {
	    $('#all-orders-table').DataTable({
	    	"ordering":false
	    });
	    @foreach(Auth::user()->integrations as $integration)
		    $('#{{ $integration->site }}-table').DataTable({
		    	"ordering":false
		    });
	    @endforeach
	});
</script>
<script>
    var dialog = document.querySelector('#order-dialog');
    var showModalButton = document.querySelector('.order-details');
    if (! dialog.showModal) {
      dialogPolyfill.registerDialog(dialog);
    }
    showModalButton.addEventListener('click', function() {
      dialog.showModal();
    });
    dialog.querySelector('.close').addEventListener('click', function() {
      dialog.close();
    });
</script>

@endpush
