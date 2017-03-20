<!DOCTYPE html>
<html>
<head>
	<title>Cinenymizer</title>
	<style type="text/css">
		html, body {
			margin: 0;
			padding: 0;
			height: 100%;
		}

		body {
			font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
			background-color: #2b3e50;
			color: #ebebeb;
		}

		a {
			text-decoration: none;
			color: #ebebeb;
		}

		a:hover {
			color: #fff;
		}

		.container {
			height: 100%;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.block {
			flex: none;
			align-self: center;
			text-align: center;
		}

		.block > p {
			border-radius: 25px;
			padding: 10px;
			font-size: 2em;
			margin-bottom: 0.5em;
		}

		.refresh {
			margin-top: 0;
		}

		.refresh > a {
			font-size: 2em;
			margin: 0 auto;
			display: block;
			width: 1em;
			transition: transform 0.5s ease 0s;
		}

		.refresh > a:hover {
			transform: rotate(-360deg);
		}

		.spoiler {
			color: #4e5d6c;
			-webkit-touch-callout: none;
			-webkit-user-select: none;
			-khtml-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}

		.spoiler:hover {
			color: #485563;
		}

		.spoiler:active {
			color: #2a323a;
		}

		.spoiler:hover, .answer:hover {
			background-color: #485563;
		}

		.answer:active {
			color: #fff;
		}

		.spoiler:active, .answer:active {
			background-color: #2a323a;
		}

		.spoiler, .answer {
			background-color: #4e5d6c;
			cursor: pointer;
		}

		hr {
			color: #ebebeb;
		}

		@media (min-width: 768px) {
			.block {
				width: 50%;
			}
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="block">
			<p id="question" class="question"><?= $new_title ?></p>
			<hr>
			<p id="answer" title="Get the answer" class="spoiler"><?= $original_title ?></p>
			<p class="refresh"><a title="Next Title" href="index.php">&#x21ba;</a></p>
		</div>
	</div>
	<script type="text/javascript">
		var elem = document.getElementById('answer');
		elem.onclick = function() {
			if (elem.className == 'spoiler') {
				elem.title = '';
				elem.className = 'answer';
			} else {
				elem.title = "Get the answer";
				elem.className = 'spoiler';
			}
		};
	</script>
</body>
</html>
