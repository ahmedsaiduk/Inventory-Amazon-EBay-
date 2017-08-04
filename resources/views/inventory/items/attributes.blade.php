<h4>Basic Info</h4>

<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	<input type="text" class="mdl-textfield__input" name="sku" required>
	<label for="sku" class="mdl-textfield__label">SKU</label>
</div>
<div class="mdl-grid">
    <label for="category" class="required">Store Category</label>
    <select id="category" name="storeCategoryId" required>
      	@foreach($storeCats as $category)
      		@if(count($category->sub_categories))
      			@foreach($category->sub_categories as $sub_category)
	      		<optgroup label="{{ $category->name }}">
		      		<option value="{{ $sub_category->ebay_category_id }}">
				    	{{ $sub_category->name }} 
			    	</option>
	      		</optgroup>
	      		@endforeach
	      	@else
	      		<option value="{{ $category->ebay_category_id }}">
			    	{{ $category->name }} 
		    	</option>
      		@endif
	    @endforeach  
    </select>
</div>
<div class="mdl-grid">
	<label for="condition" class="required">Condition</label>
	<select name="condition">
    	<option value="1000">New</option>
    	<option value="1500">New other (see details)</option>
    	<option value="2000">Manufacturer refurbished</option>
    	<option value="2500">Seller refurbished</option>
    	<option value="3000">Used</option>
    </select>
</div>
<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	<input type="text" class="mdl-textfield__input" name="conditionDescription">
	<label for="condition description" class="mdl-textfield__label">Condition description</label>
</div>
<div class="mdl-grid">
	<label for="country" class="required">Country</label>
	<select name="country">
    	<option value="US">USA</option>
    </select>
</div>
<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
  <input class="mdl-textfield__input" type="number" id="postalCode" name="postalCode" min="11111" max="99999" required >
  <label class="mdl-textfield__label" for="postalCode">Postal code</label>
</div>
<div class="mdl-grid">
	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		<input type="number" class="mdl-textfield__input" name="quantityAvailable" min="1" required >
		<label for="quantity" class="mdl-textfield__label">Quantity</label>
	</div>
</div>

<h4>Pricing Info</h4>
<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	<input type="number" class="mdl-textfield__input" name="cost" min="1">
	<label for="cost" class="mdl-textfield__label">Cost</label>
</div>
<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	<input type="number" class="mdl-textfield__input" name="mapPrice" min="1">
	<label for="map price" class="mdl-textfield__label">MAP Price</label>
</div>
<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	<input type="number" class="mdl-textfield__input" name="priceRetail" min="1">
	<label for="price" class="mdl-textfield__label">Retail Price</label>
</div>
<!-- <label for="currency" class="required">Currency :</label>
<select name="currency" required>
	<option value="USD">USD</option>
</select> -->
<h4>Market places</h4>
<!-- Tabs -->
<div class="mdl-cell mdl-cell--6-col">
	<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
		<div class="mdl-tabs__tab-bar">
		  	@foreach($integrations as $integration)
		  		@if($loop->first)
				    <a href="#{{ $integration }}-tab" class="mdl-tabs__tab is-active">{{ $integration }}</a>
			    @else
				    <a href="#{{ $integration }}-tab" class="mdl-tabs__tab">{{ $integration }}</a>
			    @endif
			@endforeach
	  	</div>
		@foreach($integrations as $integration)
			@if($loop->first)
			    <div class="mdl-tabs__panel is-active" id="{{ $integration }}-tab">
		    @else
			    <div class="mdl-tabs__panel" id="{{ $integration }}-tab">
		    @endif
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="number" class="mdl-textfield__input" name="{{ $integration }}-price" min="1" required>
						<label for="price" class="mdl-textfield__label">Price</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="number" class="mdl-textfield__input" name="{{ $integration }}-priceMin" min="1">
						<label for="minimum price" class="mdl-textfield__label">Minimum price</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="number" class="mdl-textfield__input" name="{{ $integration }}-priceMax" min="1">
						<label for="maximum price" class="mdl-textfield__label">Maximum price</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="number" class="mdl-textfield__input" name="{{ $integration }}-pricePreferred" min="1">
						<label for="price" class="mdl-textfield__label">Price preferred</label>
					</div>
			    </div>
	  	@endforeach
	</div>
</div>
<div class="mdl-grid">
    <label for="shipping" class="required">Shipping type:</label>
	<select name="shippingType" required>
		<option value="Free">Free</option>
		<option value="Calculated">Calculated</option>
		<option value="CalculatedDomesticFlatInternational">Calculated Domestic Flat International</option>
		<option value="CustomCode">Custom Code</option>
		<option value="Flat">Flat</option>
		<option value="Freight">Freight</option>
		<option value="FreightFlat">Freight Flat</option>
		<option value="NotSpecified">Not Specified</option>
	</select>
	<label for="shipping" class="required">Shipping service:</label>
	<select name="shippingService" required disabled="true">
		<optgroup label="Economy services"></optgroup>
			<option value="8">USPS Parcel Select Ground (2 to 9 business days)</option>
			<option value="9">USPS Media Mail (2 to 8 business days)</option>
			<option value="63">FedEx SmartPost (2 to 8 business days)</option>
			<option value="32">USPS Retail Ground (2 to 9 business days)</option>
			<option value="14">Economy Shipping (1 to 10 business days)</option>
			<option value="184">UPS Surepost (1 to 6 business days)</option>
		<optgroup label="Standard services"></optgroup>
			<option value="10">USPS First Class Package (2 to 3 business days)</option>
			<option value="62">FedEx Ground or FedEx Home Delivery (1 to 5 business days)</option>
			<option value="3">UPS Ground (1 to 5 business days)</option>
			<option value="1">Standard Shipping (1 to 5 business days)</option>
		<optgroup label="Expedited services"></optgroup>
			<option value="7">USPS Priority Mail (1 to 3 business days)</option>
			<option value="2">Expedited Shipping (1 to 3 business days)</option>
			<option value="19">USPS Priority Mail Flat Rate Envelope (1 to 3 business days)</option>
			<option value="23">USPS Priority Mail Small Flat Rate Box (1 to 3 business days)</option>
			<option value="20">USPS Priority Mail Medium Flat Rate Box (1 to 3 business days)</option>
			<option value="22">USPS Priority Mail Large Flat Rate Box (1 to 3 business days)</option>
			<option value="24">USPS Priority Mail Padded Flat Rate Envelope (1 to 3 business days)</option>
			<option value="25">USPS Priority Mail Legal Flat Rate Envelope (1 to 3 business days)</option>
			<option value="11">USPS Priority Mail Express (1 business day)</option>
			<option value="21">USPS Priority Mail Express Flat Rate Envelope (1 business day)</option>
			<option value="26">USPS Priority Mail Express Legal Flat Rate Envelope (1 business day)</option>
			<option value="4">UPS 3 Day Select (3 business days)</option>
			<option value="5">UPS 2nd Day Air (2 business days)</option>
			<option value="64">FedEx Express Saver (1 to 3 business days)</option>
			<option value="65">FedEx 2Day (1 to 2 business days)</option>
		<optgroup label="One-day services"></optgroup>
			<option value="18">One-day Shipping (1 business day)</option>
			<option value="6">UPS Next Day Air Saver (1 business day)</option>
			<option value="12">UPS Next Day Air (1 business day)</option>
			<option value="66">FedEx Priority Overnight (1 business day)</option>
			<option value="67">FedEx Standard Overnight (1 business day)</option>
		<optgroup label="Freight"></optgroup>
			<option value="183">Flat Rate Freight</option>
	</select>
</div>
<div class="mdl-grid">
    <!-- A group of radio buttons to control camera's Flash setting -->
    <label>Returns : </label>
    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="accepted">
      <input class="mdl-radio__button" id="accepted" name="returns" type="radio" value="ReturnsAccepted">
      <span class="mdl-radio__label">Accepted </span>
    </label>
    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="notAccepted">
      <input checked class="mdl-radio__button" id="notAccepted" name="returns" type="radio" value="ReturnsNotAccepted">
      <span class="mdl-radio__label">Not accepted </span>
    </label>
</div>
<div class="mdl-grid">
    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="visaMcr">
      <input type="checkbox" id="visaMcr" class="mdl-checkbox__input" value="VisaMC" name="payment[]">
      <span class="mdl-checkbox__label"> Visa/MasterCard</span>
    </label>
    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="paypal">
      <input type="checkbox" id="paypal" class="mdl-checkbox__input" value="PayPal" name="payment[]">
      <span class="mdl-checkbox__label"><i class="fa fa-paypal"></i> PayPal</span>
    </label>
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
      <input class="mdl-textfield__input" type="email" id="paypalEmail" name="paypalEmail">
      <label class="mdl-textfield__label" for="paypalEmail">PayPal email</label>
    </div>
</div>