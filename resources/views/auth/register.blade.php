@extends('layouts.master')

@section('content')
<div class="mdl-grid">
    <div class="mdl-layout-spacer"></div>   
    <div class="mdl-card mdl-shadow--2dp">
        <div class="mdl-card__title mdl-card--expand mdl-color--primary">
            <div class="mdl-layout-spacer"></div>
            <h4 class="mdl-color-text--primary-contrast"><i class="fa fa-lock"></i> Register</h4>
            <div class="mdl-layout-spacer"></div>
        </div>
        <div class="mdl-card__actions mdl-card--border">
            <form role="form" method="POST" action="{{ url('/register') }}">
                {{ csrf_field() }}
                <div class="mdl-textfield mdl-js-textfield{{ $errors->has('name') ? ' has-error' : '' }}">
                    <input id="name" type="text" class="mdl-textfield__input" name="name" value="{{ old('name') }}" required autofocus>

                    @if ($errors->has('name'))
                        <span class="mdl-textfield__error">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                    <label for="name" class="mdl-textfield__label">Name</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input id="email" type="email" class="mdl-textfield__input" name="email" value="{{ old('email') }}" required>

                    @if ($errors->has('email'))
                        <span class="mdl-textfield__error">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                    <label for="email" class="mdl-textfield__label">E-Mail Address</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield{{ $errors->has('password') ? ' has-error' : '' }}">

                    <input id="password" type="password" class="mdl-textfield__input" name="password" required>

                    @if ($errors->has('password'))
                        <span class="mdl-textfield__error">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                    <label for="password" class="mdl-textfield__label">Password</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield">
                    <input id="password-confirm" type="password" class="mdl-textfield__input" name="password_confirmation" required>
                    <label for="password-confirm" class="mdl-textfield__label">Confirm Password</label>
                </div>
                
                <div class="mdl-grid">
                    <div class="mdl-layout-spacer"></div>
                        <button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect">
                            Register
                        </button>
                    <div class="mdl-layout-spacer"></div>
                </div>
            </form>
        </div>
    </div>  
</div>
@endsection
