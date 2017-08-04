<div class="mdl-layout__drawer mdl-color--grey-700">
    <div class="mdl-grid">
        <a class="mdl-color-text--white" href="{{ url('home') }}">
            <img src="{{ url('images/sellerpier-logo.png') }}" alt="logo" class="logo">
        </a>
    </div>
    <div class="mdl-grid">
        <p style="font-family: 'Lobster', cursive; font-size: 28px; color: #fff;">SellerPier</p>
    </div>
    <nav class="mdl-navigation">
        <div class="mdl-accordion">
            <a class="mdl-navigation__link mdl-color-text--white" href="{{ url('/items') }}">    
                <i class="fa fa-database" style="color: #bbd239;"></i> Inventory    
            </a>
                    <!-- <a class="mdl-navigation__link mdl-color-text--white" href="{{ url('/categories') }}">Categories</a> -->
                
        </div>
        <a class="mdl-navigation__link mdl-color-text--white" href="{{ url('/orders')}}">        
            <i class="fa fa-cart-arrow-down" style="color: #00abac;"></i> Orders
        </a>
        <div class="mdl-accordion">
            <a class="mdl-navigation__link mdl-accordion__button mdl-color-text--white ">
                <i class="fa fa-line-chart" style="color: #7a2b8b;"></i> Reports 
                <i class="material-icons">keyboard_arrow_right</i>
            </a>
            <div class="mdl-accordion__content-wrapper">
                <div class="mdl-accordion__content mdl-animation--default">
                    <a class="mdl-navigation__link mdl-color-text--white side-sub" href="">Sales</a>
                    <a class="mdl-navigation__link mdl-color-text--white side-sub" href="">Low stock</a>
                </div>
            </div>
        </div>
        <a class="mdl-navigation__link mdl-color-text--white" href="{{ url('/integrations') }}">
            <i class="fa fa-exchange" style="color: #f05a3a;"></i> Integrations
        </a>
    </nav>
</div>

@push('js')
<script>
    $(function(){
        $('.mdl-accordion__content').each(function(){
            var content = $(this);
            content.css('margin-top', -content.height());
        });
        $(document.body).on('click', '.mdl-accordion__button', function(){

            $(this).parent('.mdl-accordion').toggleClass('mdl-accordion--opened');
            
            if($(this).children('.material-icons').text() == 'keyboard_arrow_right')
            {
                $(this).children('.material-icons').text('keyboard_arrow_down');   
            }
            else
            {
                $(this).children('.material-icons').text('keyboard_arrow_right');   
            }
        });
    });
</script>
@endpush