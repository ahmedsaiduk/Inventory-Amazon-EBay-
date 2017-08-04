@extends('layouts.master')

@section('content')

	@component('layouts.header')
		<i class="fa fa-database"></i> Inventory
	@endcomponent
	@include('layouts.sidebar')
	<main class="mdl-layout__content mdl-color--grey-100">
	    <div class="mdl-grid--no-spacing mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col">
			<div class="mdl-grid">
			    <div class="mdl-layout-spacer"></div>
			    <div class="mdl-cell">
				    <span class="mdl-chip">
				        <span class="mdl-chip__text">{{ $spierItem->store_category->name }}</span>
				    </span>
				</div>
			</div>
			<div class="mdl-grid">
				<table class="mdl-data-table mdl-js-data-table item-table">
					<thead>
						<tr>
							<th style="width: 30%;">Attribute</th>
							<th>Description</th>
						</tr>
					</thead>
					<tbody>
						<!-- basic info -->
						<tr>
							<td>Title</td>
							<td>{{ $spierItem->title }}</td>
						</tr>
						<tr>
							<td>SKU</td>
							<td>{{ $spierItem->sku }}</td>
						</tr>
						<tr>
							<td>UPC</td>
							<td>{{ $spierItem->upc }}</td>
						</tr>
						<tr>
							<td>Description</td>
							<td>{{ $spierItem->description }}</td>
						</tr>
						<tr>
							<td>Quantity available / sold</td>
							<td>{{ $spierItem->quantityAvailable }} / @if($spierItem->quantitySold) {{ $spierItem->quantitySold }} @else 0 @endif</td>
						</tr>
						<tr>
							<td>Condition</td>
							<td>{{ $spierItem->condition }}</td>
						</tr>
						<tr>
							<td>Condition description</td>
							<td>{{ $spierItem->conditionDescription }}</td>
						</tr>
						<tr>
							<td>Vendor name</td>
							<td>{{ $spierItem->vendorName }}</td>
						</tr>
						<tr>
							<td>Created at</td>
							<td>{{ $spierItem->created_at->diffForHumans() }}</td>
						</tr>
						<tr>
							<td>Last update</td>
							<td>{{ $spierItem->updated_at->diffForHumans() }}</td>
						</tr>
						<!-- end of basic info -->
						<!-- attributes -->
						@foreach($spierItem->attributes as $attribute)
						<tr>
							<td>{{ $attribute->name }}</td>
							<td>{{ $attribute->value }}</td>
						</tr>
						@endforeach
						<!-- end of attributes -->
						<!-- market places -->
						<!-- foreach -->
						<!-- end of market places -->

					</tbody>
				</table>
			</div>
</div>
@endsection