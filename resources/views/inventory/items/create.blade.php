@extends('layouts.master')

@section('content')

	@component('layouts.header')
		<i class="fa fa-database"></i> Inventory
	@endcomponent
	@include('layouts.sidebar')
	<main class="mdl-layout__content mdl-color--grey-100">
		<div class="mdl-grid">
		    <h5><i class="fa fa-plus-circle"></i> listing new product</h5>
		</div>
	    <div class="mdl-grid--no-spacing mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col">
			<div class="mdl-grid">
			    <div class="mdl-cell">
			    	@if($step == 1)
			        <span class="mdl-chip mdl-chip--contact mdl-color--primary">
			        @else
			        <span class="mdl-chip mdl-chip--contact">
			        @endif
				        <span class="mdl-chip__contact mdl-color--primary-dark mdl-color-text--white">1</span>
				        <span class="mdl-chip__text mdl-color-text--white">Search by UPC or EAN</span>
				    </span>
			    </div>
			    <div class="mdl-cell">
			    	@if($step == 2)
			        <span class="mdl-chip mdl-chip--contact mdl-color--primary">
			        @else
			        <span class="mdl-chip mdl-chip--contact">
			        @endif
				        <span class="mdl-chip__contact mdl-color--primary-dark mdl-color-text--white">2</span>
				        <span class="mdl-chip__text mdl-color-text--white">Fill your specifics</span>
				    </span>
			    </div>
			    <div class="mdl-cell">
			    	@if($step == 3)
			        <span class="mdl-chip mdl-chip--contact mdl-color--primary">
			        @else
			        <span class="mdl-chip mdl-chip--contact">
			        @endif
				        <span class="mdl-chip__contact mdl-color--primary-dark mdl-color-text--white">3</span>
				        <span class="mdl-chip__text mdl-color-text--white">Save and publish</span>
				    </span>
			    </div>
			</div>
			<div class="mdl-grid">
				@if($step == 1)
				<div class="mdl-layout-spacer"></div>
				<div class="mdl-cell mdl-cell--middle">
					<form action="{{ url('/items/create') }}" method="GET" role="search"> <!-- action -->
					<h5>list by UPC or EAN</h5>
						<div class="mdl-grid">
					    	<input type="text" class="mdl-textfield__input" placeholder="ex:885370808278" name="upc" required>
						</div>
						<div class="mdl-grid">
							<div class="mdl-layout-spacer"></div>
							<button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--primary mdl-color-text--accent-contrast"><i class="fa fa-search"></i></button>
						</div>
					</form>
				</div>
				<div class="mdl-layout-spacer"></div>
				@elseif($step ==2)
				<div class="mdl-grid">
					<!-- validation -->
					@if (count($errors) > 0)
			            @foreach ($errors->all() as $error)
			                <span class="mdl-chip mdl-chip--contact mdl-color--red-900">
		                	     <span class="mdl-chip__contact mdl-color--red-900 mdl-color-text--white"><i class="fa fa-exclamation"></i></span>
		                	     <span class="mdl-chip__text mdl-color-text--white">{{ $error }}</span>
		                	 </span>	 
			            @endforeach
					@endif
					<h4>{{ $item['title'] }}</h4> <img src="{{ $item['imgURL'] }}">
					<form action="{{ url('/items') }}" method="POST" role="form" enctype="multipart/form-data">
						{{ csrf_field() }}
						@include('inventory.items.attributes')
						<div class="mdl-grid">
							<div class="mdl-layout-spacer"></div>
							<button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--accent mdl-color-text--accent-contrast">Create</button>
						</div>
						<input type="hidden" name="upc" value="{{ $upc }}">
					</form>
				</div>
				@endif
			</div>
		</div>
	</main>
@endsection