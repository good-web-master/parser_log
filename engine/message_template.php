<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title; ?></title>
	<style type="text/css"> 
		* {margin:0px; padding:0px; outline:0px;}
		html, body { 
			background: #f9f9f9;
			color: #333;
			direction: ltr;
			font-family: sans-serif;
			font-size: 11px;
			margin: 0px;
			width: 100%;
		}

		#page {
			margin: 50px auto;
			background:#FFF;
			border: 1px solid #DFDFDF;
			width:600px;
			min-width:600px;
			padding:20px;
			-o-border-radius: 5px;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			-khtml-border-radius: 5px;
			border-radius: 5px; 
		}
		
		#page p {
			font-size: 14px;
			line-height: 1.5;
			margin:10px 0;
			word-wrap: break-word;
		}
		#page code {
			font-family: Consolas, Monaco, monospace;
		}
		ul li {
			margin-bottom: 10px;
			font-size: 14px ;
		}
		a {
			color: #21759B;
			text-decoration: none;
		}
		a:hover {
			color: #D54E21;
		}

		.button {
			font-family: sans-serif;
			text-decoration: none;
			font-size: 14px !important;
			line-height: 16px;
			padding: 4px 12px;
			cursor: pointer;
			border: 1px solid #ccc;
			color: #464646;
			background-color: #f5f5f5;
			border-radius:10px;
		}

		.button:hover {
			color: #000;
			border-color: #CCC;
			background:#E4E4E4;
		}

		body { font-family: Tahoma, Arial; }
	</style>
</head>
<body>
	<div id="page">
		<h1><?php echo $title; ?></h1>
		<?php echo $message; ?>
	</div>
</body>
</html>