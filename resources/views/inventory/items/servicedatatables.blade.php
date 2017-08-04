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
				<div class="mdl-cell mdl-cell--middle">
					<a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--primary mdl-color-text--accent-contrast" href="{{ url('/items/create') }}"><i class="fa fa-plus-circle"></i> Add new items</a>
				</div>
			</div>
			<div class="mdl-grid">
				<div class="mdl-cell--12-col">
					{!! $dataTable->table() !!}
				</div>
			</div>
		</div>
	</main>

@endsection

@push('js')
	<script src="/vendor/datatables/buttons.server-side.js"></script>
	{!! $dataTable->scripts() !!}
@endpush