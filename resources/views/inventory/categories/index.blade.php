@extends('layouts.master')

@section('content')

	@component('layouts.header')
		<i class="fa fa-database"></i> Inventory
	@endcomponent
	@include('layouts.sidebar')
	<main class="mdl-layout__content mdl-color--grey-100">
	    <div class="mdl-grid--no-spacing mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col">
			<div class="mdl-grid">
				<div class="mdl-cell mdl-cell--6-col">
					<h3>Manage store categories</h3>
				</div>
				<div class="mdl-layout-spacer"></div>
				<div class="mdl-cell mdl-cell--middle">
				<button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--primary mdl-color-text--accent-contrast show-modal"><i class="fa fa-plus-circle"></i> add new category</button>
				</div>
			</div>
			<div class="mdl-grid">
				@if($storeCategories)
					<ul class="mdl-list">
					@foreach($storeCategories as $storeCategory)
					    <li class="mdl-list__item">
						    <a href="{{ url('/categories/' . $storeCategory->id) }}">{{ $storeCategory->name }}</a>
					    </li>
					    @if($storeCategory->sub_categories)
					    	<ul>
					    		@foreach($storeCategory->sub_categories as $sub_category)
					    	    <li>
					    	    	<a href="{{ url('/categories/' . $sub_category->id) }}">
					    	    		{{ $sub_category->name }}
					    	    	</a>
					    	    	<!-- count items -->
				    	    	</li>
				    	    	@endforeach
					    	</ul>
					    @endif
					@endforeach
					</ul>
				@else
					<div class="alert alert-info" role="alert">
						<strong>Heads up!</strong> You have no categories yet.
					</div>
				@endif
			</div>
	    </div>
	</main>
	<dialog class="mdl-dialog mdl-cell mdl-cell--4-col mdl-cell--4-offset" id="add-category">
		<div class="mdl-dialog__title">
			<div class="mdl-grid">
				<div class="mdl-layout-spacer"></div>
				<span>
					<button class="mdl-button close" >
						<i class="fa fa-close"></i>
					</button>
				</span>
			</div>
		</div>
	    <div class="mdl-dialog__content">
	    	<form role="form" action="{{ url('/categories') }}" method="POST" id="cat-form">
				{{ csrf_field() }}
				<div class="mdl-grid">
					<label for="parent category">Parent category : </label>
					<select name="parentId">
						@foreach($storeCategories as $storeCategory)
							@if($storeCategory->id == 1)
							<option value="{{ $storeCategory->ebay_category_id }}">--- New Category ---</option>
							@else
							<option value="{{ $storeCategory->ebay_category_id }}">{{ $storeCategory->name }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="mdl-grid">
					<div class="mdl-textfield mdl-js-textfield">
					  <input class="mdl-textfield__input" type="text" id="cat name" name="catName" required>
					  <label class="mdl-textfield__label" for="cat name">electronics, luxury, ...</label>
					</div>
				</div>
				<div class="mdl-grid">
					<div class="mdl-layout-spacer"></div>
					<button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--accent mdl-color-text--accent-contrast pull-right">Save</button>
				</div>
			</form>
	    </div>
	</dialog>
@endsection
@push('js')
	<script>
	    var dialog = document.querySelector('#add-category');
	    var showModalButton = document.querySelector('.show-modal');
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