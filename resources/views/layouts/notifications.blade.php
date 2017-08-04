@if(session('message'))
    <div id="note" class="mdl-js-snackbar mdl-snackbar mdl-color--{{ session('color') }}">
      <div class="mdl-snackbar__text"></div>
      <button class="mdl-snackbar__action" type="button"></button>
    </div>

    <script>
        r(function(){
            var snackbarContainer = document.querySelector('#note');
            var data = {message: '{{ session('message') }}' };
            snackbarContainer.MaterialSnackbar.showSnackbar(data);
        });
        function r(f){ /in/.test(document.readyState)?setTimeout('r('+f+')',9):f()}
    </script>
@endif
