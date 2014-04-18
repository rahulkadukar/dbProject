<!DOCTYPE html>
<html>
    <head>
		<link rel="stylesheet" href="CSS/reset.css" type="text/css" />
		<link rel="stylesheet" href="CSS/jquery.mobile.css" type="text/css" />
        <link rel="stylesheet" href="CSS/main.css" type="text/css" />
		<title>Steam Reader</title>
		<script type="text/javascript" src="JavaScript/jquery.js"></script>
		<script type="text/javascript" src="JavaScript/jquery.mobile.js"></script>
		<script type="text/javascript" src="JavaScript/jquery.dataTables.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				var width = $(window).height() - 200;
				width /= 32;
				width = Math.floor(width);
				console.log(width);
				$('#example').dataTable({
					"bJQueryUI": true,	
					"bProcessing": true,
					"bServerSide": true,
					"iDisplayLength": width,
					"sPaginationType": "full_numbers",
					"sAjaxSource": "Fetch.php",
					"aoColumns": [
						{ "sName": "engine" },
						{ "sName": "browser" },
						{ "sName": "platform" },
						{ "sName": "version" },
						{ "sName": "grade" }
					]
				});
			});
		</script>
	</head>
	<body>
		<div data-role="page" data-theme="a">
			<div data-role="header">
				<h1>Page Title</h1>
			</div>
			
			<div id="content" data-role="content">	
				<div id="table">
					<table id="example" class="table">
						<thead>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>			
			</div>
		
			<div id="footer" data-role="footer">
				<h4>Page Footer</h4>
			</div>
		</div>
	</body>
</html>