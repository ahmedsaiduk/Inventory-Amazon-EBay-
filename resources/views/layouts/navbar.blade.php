<nav class="navbar navbar-toggleable-md navbar-dark bg-inverse">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#app-navbar-collapse" aria-controls="app-navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/home') }}" style="font-family: 'Lobster', cursive; font-size: 48px;">
                {{ config('app.name', 'SellerPier') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav mr-auto navbar-toggler-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li class="nav-item">
                    <a href="{{ url('/login') }}" class="nav-link"><i class="fa fa-sign-in"></i> Login</a>
                    </li>
                    <li class="nav-item">
                    <a href="{{ url('/register') }}" class="nav-link"><i class="fa fa-arrow-up"></i> Register</a>
                    </li>
                @else
                    <li class="nav-item dropdown btn-group">
                        <a class="nav-link dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-database"></i> Inventory</a>
                        <div class="dropdown-menu dropdown" aria-labelledby="dropdownMenu1">
                            <a class="dropdown-item" href="{{ url('/categories') }}">Categories</a>
                            <a class="dropdown-item" href="{{ url('/items') }}">Items</a>
                            <a class="dropdown-item" href="{{ url('/publish') }}">Publish</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown btn-group">
                        <a class="nav-link dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cart-arrow-down"></i> Orders</a>
                        <div class="dropdown-menu dropdown" aria-labelledby="dropdownMenu1">
                            <a class="dropdown-item">Order Manager</a>
                            <a class="dropdown-item">File Upload</a>
                            <a class="dropdown-item">Packing slip settings</a>
                            <a class="dropdown-item">Advanced settings</a>
                            <a class="dropdown-item">Integrations</a>
                            <a class="dropdown-item">Walmart cancel order tool</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown btn-group">
                        <a class="nav-link dropdown-toggle" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-barcode"></i> Listings</a>
                        <div class="dropdown-menu dropdown" aria-labelledby="dropdownMenu2">
                            <a class="dropdown-item" href="{{ url('/listings/manage') }}">Manage</a>
                            <a class="dropdown-item" href="{{ url('/listings/publish') }}">Publish</a>
                            <a class="dropdown-item" href="{{ url('/listings/create')}}">Create</a>
                            <a class="dropdown-item">Integrations</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown btn-group">
                        <a class="nav-link dropdown-toggle" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-line-chart"></i> Reports</a>
                        <div class="dropdown-menu dropdown" aria-labelledby="dropdownMenu3">
                            <a class="dropdown-item" >Reports</a>
                            <a class="dropdown-item">Sales report</a>
                            <a class="dropdown-item">Low stock</a>
                            <a class="dropdown-item">Purchase history</a>
                            <a class="dropdown-item">BuyBox report</a>
                            <a class="dropdown-item">Purchase Queue</a>
                            <a class="dropdown-item">Price report</a>
                            <a class="dropdown-item">Error report</a>
                            <a class="dropdown-item">Listings report</a>
                            <a class="dropdown-item">FBA fees</a>
                            <a class="dropdown-item">Bundle report</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown btn-group">
                        <a class="nav-link dropdown-toggle" id="dropdownMenu4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-commenting"></i> Help</a>
                        <div class="dropdown-menu dropdown" aria-labelledby="dropdownMenu4">
                            <a class="dropdown-item" >Resources</a>
                            <a class="dropdown-item">Submit a request</a>
                            <a class="dropdown-item">Check existing request</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown btn-group">
                        <a class="nav-link dropdown-toggle" id="dropdownMenu4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="/images/user-icon.jpg" style="height: 30px; width: 30px; border-radius: 50%; margin-right: 10px;">{{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown" aria-labelledby="dropdownMenu4">
                                <a href="{{ url('/logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> Logout
                                </a>

                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>