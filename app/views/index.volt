<html>
	<head>
		<link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
		<meta name="viewport" content="width=550, user-scalable=no"/>
		<meta charset="UTF-8"/>
		<title>Report</title>

		<!-- jQuery -->
		<script src="/js/jquery.min.js"></script>

		<!-- Bootstrap 3.3.5 -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css{#/css/bootstrap.min.css#}"/
		<link rel="stylesheet" href="/css/bootstrap-social.min.css"/>
		<link rel="stylesheet" href="/css/font-awesome.min.css"/>
		<script src="/js/bootstrap.min.js"></script>

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
