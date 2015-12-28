<html>
	<head>
		<link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
		<meta name="viewport" content="width=550, user-scalable=no">
		<title>Report</title>

		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

		<!-- Bootstrap 3.3.5 -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/4.11.0/bootstrap-social.min.css"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

		<!-- Custom Css/JS -->
		<link rel="stylesheet" href="/css/main.css"/>
		<script src="/js/main.js"></script>

	</head>
	<body>
		{{ partial('partial/header') }}
		<div id="wrapper" {% if dbs is defined and dbs %}class="c2"{% endif %}>

			{% if dbs is defined and dbs %}
				{{ partial('partial/menu') }}
			{% endif %}

			<!-- Page Content -->
			<div id="pg-wrapper" class="container-fluid" >
				{{ flash.output() }}
				{{ content() }}
				<div id="loadModal"></div>
			</div>

		</div>
		{{ partial('partial/footer') }}
	</body>
</html>