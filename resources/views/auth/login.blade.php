@extends('layouts.master')

@section('content')
	<div class="mdl-grid">
		<div class="mdl-layout-spacer"></div>	
			<div class="mdl-card mdl-shadow--2dp">
				<div class="mdl-card__title mdl-card--expand mdl-color--primary">
					<div class="mdl-layout-spacer"></div>
				    <h4 class="mdl-color-text--primary-contrast"><i class="fa fa-lock"></i> Login</h4>
				    <div class="mdl-layout-spacer"></div>
				</div>
				<div class="mdl-card__actions mdl-card--border">
			        <form role="form" method="POST" action="{{ url('/login') }}">
			            {{ csrf_field() }}
			                
			            <div class="mdl-textfield mdl-js-textfield{{ $errors->has('email') ? ' has-error' : '' }}">
			              <input class="mdl-textfield__input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
			              <label class="mdl-textfield__label" for="email">email</label>

			                @if ($errors->has('email'))
			                    <span class="mdl-textfield__error">
			                        <strong>{{ $errors->first('email') }}</strong>
			                    </span>
			                @endif
			            </div>
			            <div class="mdl-textfield mdl-js-textfield{{ $errors->has('password') ? ' has-error' : '' }}">
			              <input class="mdl-textfield__input" type="password" id="password" name="password" required>
			              <label class="mdl-textfield__label" for="password">password</label>
			                @if ($errors->has('password'))
			                    <span class="mdl-textfield__error">
			                        <strong>{{ $errors->first('password') }}</strong>
			                    </span>
			                @endif
			            </div>


			            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="remember">
			              <input type="checkbox" id="remember" class="mdl-checkbox__input" name="remember">
			              <span class="mdl-checkbox__label">Remember me</span>
			            </label>

			            <button class="mdl-button mdl-js-button mdl-button--primary mdl-js-ripple-effect mdl-button--raised mdl-color-text--white" type="submit">Login
			            </button>
			            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" href="{{ url('/password/reset') }}">
			                Forgot Your Password?
			            </a>
			        </form>
				</div>
			</div>
		<div class="mdl-layout-spacer"></div>	
	</div>
@endsection

