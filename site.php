<!DOCTYPE html>
<html lang="en-us">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Cinenymizer</title>
	<meta name="description" content="A silly gizmo that synonomizes movie titles (or any other text to be fair) for the sake of hilarity.">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
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

		.question {
			border-bottom: 2px solid #ebebeb;
			border-radius: 0;
			padding-bottom: 1em;
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
			width: 90%;
			margin-right: 10px;
			margin-left: 10px;
		}

		.block > p {
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
			width: 1em;
			display: block;
			transition: transform 0.5s ease 0s;
		}

		.refresh > a:hover {
			transform: rotate(-360deg);
		}

		.spoiler {
			color: rgba(0, 0, 0, 0);
		}

		.spoiler:hover, .answer:hover {
			background-color: #485563;
		}

		a:active, .answer:active {
			color: #fff;
		}

		.spoiler, .answer {
			transition: all 0.2s ease 0s;
			border-radius: 25px;
			background-color: #4e5d6c;
			cursor: pointer;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}

		@media (min-width: 992px) {
			.block {
				width: 50%;
				margin-right: 0;
				margin-left: 0;
			}
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="block">
			<p id="question" class="question"><?= $new_title ?></p>
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
