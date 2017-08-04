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
					<h3>{{ $category->name }}</h3>
				</div>
				<div class="mdl-cell mdl-cell--middle">
					<a href="{{ url('inventory/categories/'.$category->id.'/edit')}}" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--accent mdl-color-text--accent-contrast"><i class="fa fa-edit"></i> Edit </a>
				</div>
			</div>
			<div class="mdl-grid">
				<div class="mdl-cell">
					@if($category->sub_categories)
						<ul class="mdl-list">
							@foreach($category->sub_categories as $sub_category)
								<li class="mdl-list__item" style="padding: 1px;">
									<a href="{{ url('inventory/categories/' . $sub_category->id) }}">
										<span class="mdl-chip">
						    	    		<span class="mdl-chip__text">
												{{ $sub_category->name }}
											</span>
										</span>
									</a>
								</li>
						    @endforeach
					    </ul>
				    @endif
				</div>
			</div>
		</div>
	</main>
@endsection