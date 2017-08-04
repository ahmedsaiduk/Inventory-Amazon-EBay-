@extends('layouts.master')

@section('content')

	@component('layouts.header')
		<i class="fa fa-exchange"></i> Integrations
	@endcomponent
	
	@include('layouts.sidebar')
	<main class="mdl-layout__content mdl-color--grey-100">
	    <div class="mdl-grid--no-spacing mdl-cell mdl-cell--12-col">
			<div class="mdl-grid">
				<span class="mdl-chip mdl-chip--contact">
				    <span class="mdl-chip__contact mdl-color--teal mdl-color-text--white"><i class="fa fa-exclamation"></i></span>
				    <span class="mdl-chip__text">Note that: your first integration will be your primary integration.</span>
				</span>
			</div>
			<div class="mdl-grid">
			    <div class="mdl-cell mdl-cell--4-col">
				    <div class="mdl-card mdl-shadow--2dp"
			    	  style="width:320px;height:290px;">
			    	  <div class="mdl-card__title mdl-card--expand"
			    	  style="background: url('/images/amazon-icon.png') bottom right 15% no-repeat; background-size: 100px 100px;"
			    	  >
			    	    <h2 class="mdl-card__title-text">Amazon</h2
			    	    >
			    	  </div>
			    	  <div class="mdl-card__supporting-text">
			    	    Amazon, is an American electronic commerce and cloud computing company that was founded on July 5, 1994, by Jeff Bezos and is based in Seattle, Washington.
			    	  </div>
			    	  <div class="mdl-card__actions mdl-card--border">
			    	    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect amazon-modal">
			    	      Enable
			    	    </a>
			    	  </div>
			    	</div>
			    </div>
			    <div class="mdl-cell mdl-cell--4-col">
			    	<div class="mdl-card mdl-shadow--2dp"
			    	  style="width:320px;height:290px;">
			    	  <div class="mdl-card__title mdl-card--expand"
			    	  style="background: url('/images/ebay-icon.png') bottom right 15% no-repeat; background-size: 100px 100px;"
			    	  >
			    	    <h2 class="mdl-card__title-text">Ebay</h2
			    	    >
			    	  </div>
			    	  <div class="mdl-card__supporting-text">
			    	    eBay Inc. is a multinational e-commerce corporation, facilitating online consumer-to-consumer and business-to-consumer sales. It is headquartered in San Jose, California.
			    	  </div>
			    	  <div class="mdl-card__actions mdl-card--border">
			    	    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect ebay-modal">
			    	      Enable
			    	    </a>
			    	  </div>
			    	</div>
			    </div>
			</div>
		</div>
	</main>
	<!-- Ebay Dialog -->
	<dialog class="mdl-dialog mdl-cell mdl-cell--4-col mdl-cell--4-offset" id="ebay-enable">
		<div class="mdl-dialog__title">
			<div class="mdl-grid">
	    		Ebay integration
				<div class="mdl-layout-spacer"></div>
				<span>
					<button class="mdl-button close" >
						<i class="fa fa-close"></i>
					</button>
				</span>
	    	</div>
		</div>
	    <div class="mdl-dialog__content">
	    	<h4>SellerPier will redirect you to ebay site to grant access.</h4>
	    	<form action="{{ url('/integrations/create/ebay') }}" method="GET">
		    	Select Ebay site:
		    	<select name="site" required>
		    		<option value="us">Ebay-usa</option>
		    		<option value="ca">Ebay-canada</option>
		    		<option value="uk">Ebay-uk</option>
		    		<option value="au">Ebay-australia</option>
		    		<option value="at">Ebay-austria</option>
		    		<option value="fr">Ebay-france</option>
		    		<option value="de">Ebay-germany</option>
		    		<option value="it">Ebay-italy</option>
		    		<option value="motors">Ebay-motors</option>
		    	</select>
		    	<input type="hidden" name="ebay" value="ebay">
		    	<div class="mdl-grid">
		    		<div class="mdl-layout-spacer"></div>
			    	<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--primary-contrast">Connect</button>
		    	</div>
	    	</form>
	    </div>
	</dialog>
	<!-- End of Ebay dialog -->
    <!-- Amazon Dialog -->
	<dialog class="mdl-dialog mdl-cell mdl-cell--8-col mdl-cell--2-offset" id="amazon-enable">
	    <div class="mdl-dialog__title">
	    	<div class="mdl-grid">
	    		Amazon integration
				<div class="mdl-layout-spacer"></div>
				<span>
					<button class="mdl-button close" >
						<i class="fa fa-close"></i>
					</button>
				</span>
	    	</div>
	    </div>
	    <div class="mdl-dialog__content">
	        <p>
	            Use the following instructions to grant access to your Amazon seller account by SellerPier.
	        </p>
	        <ol>
	            <li>Go to the <a href="https://sellercentral.amazon.com/gp/account-manager/home.html">User Permissions page</a> in Seller Central and log into your Amazon seller account as the primary user.
					<ul>
					    <li>If you have never signed up for Amazon MWS, the <b>Sign up for MWS</b> button appears. Click <b>Sign up for MWS</b>.</li>
					    <li>If you have previously signed up for Amazon MWS, the <b>Authorize a developer</b> button appears. Click <b>Authorize a developer</b>.</li>
					</ul>
	            </li>
	            <li>On the Amazon MWS registration page, choose <b>I want to authorize a developer to access my Amazon seller account with Amazon MWS</b>.</li>
	            <li>In the <b>Developer's Name</b> text box, enter "sellerpier". This doesn't need to be the exact name; it is merely for your reference in the future.</li>
	            <li>In the <b>Developer Account Number</b> text box, enter "3570-1126-6717".</li>
	            <li>Click the <b>Next</b> button.</li>
	            <li>Check the box to confirm that you want to give the third party developer access to your account, and then click the <b>Next</b> button</li>
	        </ol>
	        <p>Your account identifiers (Seller ID and MWS Authorization Token) appear. Please copy those identifiers below to let SellerPier programmatically access your Amazon seller account. You can access these identifiers at any time on the <a href="https://sellercentral.amazon.com/gp/account-manager/home.html">User Permissions page</a> in Seller Central.</p>

			<form action="{{ url('/integrations') }}" method="POST">
				{{ csrf_field() }}
		        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					<input class="mdl-textfield__input" type="text" id="SellerId" name="sellerID" required>
					<label class="mdl-textfield__label" for="SellerId">Seller Id</label>
		        </div>
		        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					<input class="mdl-textfield__input" type="text" id="MWS authToken" name="authToken" required>
					<label class="mdl-textfield__label" for="MWS authToken">MWS auth token</label>
		        </div>
		        <select name="site" required>
		        	<option value="Amazon-us">Amazon USA</option>
		        	<option value="Amazon-ca">Amazon Canada</option>
		        	<option value="Amazon-mx">Amazon Mexico</option>
		        	<option value="Amazon-br">Amazon Brazil</option>
		        	<option value="Amazon-de">Amazon Deutch</option>
		        	<option value="Amazon-es">Amazon Spain</option>
		        	<option value="Amazon-fr">Amazon France</option>
		        	<option value="Amazon-it">Amazon Italy</option>
		        	<option value="Amazon-uk">Amazon UK</option>
		        	<option value="Amazon-in">Amazon India</option>
		        	<option value="Amazon-jp">Amazon Japan</option>
		        	<option value="Amazon-cn">Amazon China</option>
		        </select>
		        <input type="hidden" name="amazon" value="amazon">
		        <button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--primary-contrast">Connect</button>
			</form>
	    </div>
	</dialog>
	<!-- End of Amazon dialog -->
@endsection
@push('js')
<script>
    var amazonDialog = document.querySelector('#amazon-enable');
    var amazonDialogBtn = document.querySelector('.amazon-modal');
    if (! amazonDialog.showModal) {
      dialogPolyfill.registerDialog(amazonDialog);
    }
    amazonDialogBtn.addEventListener('click', function() {
      amazonDialog.showModal();
    });
    amazonDialog.querySelector('.close').addEventListener('click', function() {
      amazonDialog.close();
    });

    var ebayDialog = document.querySelector('#ebay-enable');
    var ebayDialogBtn = document.querySelector('.ebay-modal');
    if (! ebayDialog.showModal) {
      dialogPolyfill.registerDialog(ebayDialog);
    }
    ebayDialogBtn.addEventListener('click', function() {
      ebayDialog.showModal();
    });
    ebayDialog.querySelector('.close').addEventListener('click', function() {
      ebayDialog.close();
    });
</script>
@endpush