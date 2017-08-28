<!DOCTYPE html>
<html lang="en">
<head>
	<title>Test</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<script type="text/javascript" >
	var base_url='<?php echo $purl; ?>';
	</script>	
</head>
<body> 
	<div class="container">
		@yield('content') 
	</div> 
		@yield('scripts') 
</body>
</html> 