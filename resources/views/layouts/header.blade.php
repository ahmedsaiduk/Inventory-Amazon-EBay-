<header class="mdl-layout__header mdl-color--primary-dark">
    <div class="mdl-layout__header-row">
        <span class="mdl-layout-title">
            {{ $slot }}
        </span>
        <div class="mdl-layout-spacer"></div>
        <div class="material-icons mdl-badge mdl-badge--overlap" data-badge="1">mail</div>    
        <div class="material-icons mdl-badge mdl-badge--overlap" data-badge="3">notifications</div>
        {{ Auth::user()->name }}
        <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="settings">
            <i class="fa fa-caret-down"></i>
        </button>
        <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="settings">
            <li class="mdl-menu__item">
                <a class="mdl-color-text--black" href="" style="text-decoration: none;">
                    <i class="fa fa-wrench"></i> Settings
                </a>
            </li>
            <li class="mdl-menu__item">
                <a class="mdl-color-text--black" href="" style="text-decoration: none;">
                    <i class="fa fa-support"></i> Support
                </a>
            </li>
            <li class="mdl-menu__item">
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
                <a class="mdl-color-text--black" href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="text-decoration: none;"><i class="fa fa-sign-out"></i> Logout</a>
            </li>
        </ul>
    </div>
</header>