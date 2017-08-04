@if( isset($notification['message']) )
	@if( $notification['title'] )
    <script>   
    	toastr.{{ $notification['type']}}("{{ $notification['message'] }}","{{ $notification['title'] }}");
    </script>
    @else
    <script>
   		toastr.{{ $notification['type']}}("{{ $notification['message'] }}");
	</script>
	@endif
@endif

<!-- <script>
	toastr.options = {
  "closeButton": true,
  "newestOnTop": true,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "FadeIn",
  "hideMethod": "FadeOut"
};
</script> -->
