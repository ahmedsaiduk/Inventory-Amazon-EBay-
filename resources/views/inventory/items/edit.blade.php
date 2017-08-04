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
						<h3>Edit Item</h3>
					</div>
					<div class="mdl-layout-spacer"></div>
					<div class="mdl-cell mdl-cell--middle">
						<form action="{{ url('/items/'.$spierItem->id) }}" method="POST" role="form">
						{{ csrf_field() }}
						{{ method_field('DELETE')}}
							<button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--red mdl-color-text--accent-contrast" onclick="return confirm('Are you sure ?')">Delete item</button>
						</form>
					</div>
				</div>
				<div class="mdl-grid">
					<form action="{{ url('/items/' . $spierItem->id )}}" method="POST" role="form">
						{{ csrf_field() }}
						{{ method_field('PUT') }}
						
						<div class="mdl-grid">
							<div class="mdl-cell">
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input type="text" class="mdl-textfield__input" name="title" value="{{ $spierItem->title }}" required>
									<label for="title" class="mdl-textfield__label">Title</label>
								</div>
							</div>
							<div class="mdl-cell mdl-cell--middle">	
								<!-- get store categories -->
								<label for="store category">Store category :</label>
								<select name="catName">
									<option value="">{{ $spierItem->store_category->name }}</option>
								</select>
							</div>
							<div class="mdl-cell mdl-cell--middle">
								<!-- condition select -->
								<label for="condition">Condition</label>
								<select name="condition">
									<option value="1000">{{ $spierItem->condition }}</option>
								</select>
							</div>
						</div>
						<div class="mdl-grid">
							<div class="mdl-grid">
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input type="text" class="mdl-textfield__input" name="sku" value="{{ $spierItem->sku }}" required>
									<label for="sku" class="mdl-textfield__label">SKU</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input type="text" class="mdl-textfield__input" name="upc" value="{{ $spierItem->upc }}">
									<label for="upc" class="mdl-textfield__label">UPC</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input type="textarea" class="mdl-textfield__input" name="description" value="{{ $spierItem->description }}" required>
									<label for="description" class="mdl-textfield__label">Description</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input type="number" class="mdl-textfield__input" name="quantityAvailable" value="{{ $spierItem->quantityAvailable }}" min="0" required>
									<label for="quantityAvailable" class="mdl-textfield__label">Quantity available</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input type="text" class="mdl-textfield__input" name="conditionDescription" value="{{ $spierItem->conditionDescription }}">
									<label for="conditionDescription" class="mdl-textfield__label">Condition description</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input type="text" class="mdl-textfield__input" name="vendorName" value="{{ $spierItem->vendorName }}">
									<label for="vendorName" class="mdl-textfield__label">Vendor name</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input type="text" class="mdl-textfield__input" name="leadTimeToShip" value="{{ $spierItem->leadTimeToShip }}">
									<label for="leadTimeToShip" class="mdl-textfield__label">Lead time to ship</label>
								</div>
								<div class="mdl-cell">
									<label for="currency" class="required">Currency :</label>
									<select class="form-control" name="currency" required>
								      <option>USD</option>
								    </select>
							    </div>
							</div>
							<!-- restock date remove -->
							<h6>Specifications</h6>
							<!-- attributes -->
							<div class="mdl-grid">
								@foreach($spierItem->attributes as $attribute)
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
										<input type="text" class="mdl-textfield__input" name="{{ $attribute->name }}" value="{{ $attribute->value }}">
										<label for="{{ $attribute->name }}" class="mdl-textfield__label">{{ $attribute->name }}</label>
									</div>
								@endforeach
							</div>
							<!-- end of attributes -->
							<h6>Market places</h6>
							<!-- integrations --> <!-- foreach integrations -->
							<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
								<div class="mdl-tabs__tab-bar">
								  	@foreach($integrations as $integration)
								    <a href="#{{ $integration }}-tab" class="mdl-tabs__tab">{{ $integration }}</a>
									@endforeach
							  	</div>
							    <div class="mdl-tabs__panel" id="{{ $integration }}-tab">
						        	@foreach($integrations as $integration)
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input type="number" class="mdl-textfield__input" name="{{ $integration }}-price" value="{{ $marketPlaces[$integration]['price'] }}" min="1" required>
											<label for="price" class="required mdl-textfield__label">Price</label>
										</div>
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input type="number" class="mdl-textfield__input" name="{{ $integration }}-priceMin" min="1" value="{{ $marketPlaces[$integration]['priceMin'] }}">
											<label for="minimum price" class="mdl-textfield__label">Minimum price</label>
										</div>
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input type="number" class="mdl-textfield__input" name="{{ $integration }}-priceMax" min="1" value="{{ $marketPlaces[$integration]['priceMax'] }}">
											<label for="maximum price" class="mdl-textfield__label">Maximum price</label>
										</div>
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input type="number" class="mdl-textfield__input" name="{{ $integration }}-pricePreferred" min="1" value="{{ $marketPlaces[$integration]['pricePreferred'] }}">
											<label for="price" class="mdl-textfield__label">Price preferred</label>
										</div>
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input type="number" class="mdl-textfield__input" name="{{ $integration }}-shipping" placeholder="leave it blank for free shipping" min="1" value="{{ $marketPlaces[$integration]['shipping'] }}">
											<label for="price" class="mdl-textfield__label">Shipping</label>
										</div>
								  	@endforeach
							    </div>
							</div>
							<!-- end of market places -->
						</div>
						<input type="hidden" name="spitem_id" value="{{ $spierItem->id }}">
						<div class="mdl-grid">
							<div class="mdl-layout-spacer"></div>
							<button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--accent mdl-color-text--accent-contrast">Update</button>
						</div>
					</form>
				</div>
			</div>
		</main>
@endsection