@extends('layouts.master')

@section('content')
	<main class="mdl-layout__content mdl-color--grey-100">
	<form action="" method="get" accept-charset="utf-8">
		<label>name</label>
		<input type="text" name="" value="" placeholder="" id="x">
	</form>
	</main>
@endsection
@push('js')
	<script>
		$(document).ready(function () {
			document.getElementById('x').style.visibility = 'hidden';
		});
	</script>
@endpush