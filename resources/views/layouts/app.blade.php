<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="0" />
		
		<title>{{ config('', 'Kiosk SOA') }}</title>
        <link rel="shortcut icon" href="{{ asset('image/icons8-calculator-48.png') }}">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<script src="{{ asset('js/app.js') }}" defer></script>
		<script src="https://cdn.jsdelivr.net/npm/vue-loading-overlay@4"></script>
		<link href="https://cdn.jsdelivr.net/npm/vue-loading-overlay@4/dist/vue-loading.css" rel="stylesheet">
		<script lang="javascript" src="dist/xlsx.full.min.js"></script>

		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
		

	</head>
		<body >
			<div id="app" style="height: 50vh !important; border: 1px solid green" >
				<main style="height: 50vh !important; border: 1px solid green">
					@yield('content')
				</main>
			</div>
		</body>
</html>
