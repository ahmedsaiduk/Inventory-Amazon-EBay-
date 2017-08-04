@extends('layouts.master')

@section('content')

	@component('layouts.header')
		<i class="fa fa-database"></i> Inventory
	@endcomponent
	@include('layouts.sidebar')
		<main class="mdl-layout__content mdl-color--grey-100">
		    <div class="mdl-grid--no-spacing mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col">
		    	<div class="mdl-grid">
					<div class="mdl-cell">
						<h3>Publish items to eBay</h3>
					</div>
				</div>
		    </div>
	    </main>
	
@endsection