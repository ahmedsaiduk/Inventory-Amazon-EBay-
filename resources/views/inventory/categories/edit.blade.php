@extends('layouts.master')

@section('content')

	@component('layouts.header')
		<i class="fa fa-database"></i> Inventory
	@endcomponent
	@include('layouts.sidebar')
	<main class="mdl-layout__content mdl-color--grey-100">
	    <div class="mdl-grid--no-spacing mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col">
			<h4>Edit category</h4>
			<div class="mdl-grid">
				<form action="{{ url('inventory/categories/'.$category->id) }}" method="POST" role="form">
					{{ csrf_field() }}
					{{ method_field('PUT')}}
					<div class="mdl-grid">
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						 	<input class="mdl-textfield__input" type="text" id="catName" name="catName" value="{{ $category->name }}" required>
						 	<label class="mdl-textfield__label" for="catName">Name</label>
						</div>
					</div>
					<div class="mdl-grid">
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						 	<input class="mdl-textfield__input" type="number" id="order" value="{{ $category->order }}" min="1" required > <!-- name="order" -->
						 	<label class="mdl-textfield__label" for="order">Order</label>
							<span class="mdl-textfield__error">Input is not a number!</span>
						</div>
					</div>

					<!-- move to another parent -->
					<!-- <div class="form-group">
						<label for="parent category">Parent Category</label>
						<select name="parent_category_id" class="form-control" required="required">
							<option value=""></option>
						</select>
					</div> -->

					<button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--accent mdl-color-text--accent-contrast"><i class="fa fa-share"></i> update</button>
					<form action="{{ url('inventory/categories/'.$category->id) }}" method="POST" role="form">
						{{ csrf_field() }}
						{{ method_field('DELETE')}}
						<button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--red mdl-color-text--accent-contrast" onclick="return confirm('Are you sure ?')"><i class="fa fa-trash"></i> Delete category</button>
					</form>
				</form>
			</div>
		</div>
	</main>
@endsection