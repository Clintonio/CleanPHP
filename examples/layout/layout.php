<html>
<head>
	<title>Example of the Layout class</title>
	<style>
		* {
			margin: 0px;
			padding: 0px;
			font-size: 14px;
		}
		
		body {
			margin: 10px;
			font-family: Arial;
		}
		
		p {
			margin: 15px;
		}
		
		ul {
			list-style: inside;
			margin: 15px;
		}
		
		pre {
			display: inline;
			font-size: 10px;
			float: none;
		}
		
		h1, h2 {
			margin: 10px;
		}
		
		h1 {
			font-size: 16px;
		}
		
	</style>
</head>
<body>
	<h1>Layout Example File</h1>
	<!-- Echoing an included variable -->
	<h2>The source code for the example.php file:</h2>
	<pre><?php echo $src ?></pre>
	
	<!-- While Example -->
	<h2>A counter from 1 to 10</h2>
	<ul>
	<?php for($x = 1; $x <= 10; $x++) { ?>
		<!-- An example of the shorter open tag -->
		<li><?=$x?></li>
	<?php } ?>
	</ul>
	
	<p>
		Any PHP command can be used, but it's recommended to stick to <pre>if</pre>,
		<pre>while</pre>, <pre>for</pre>, and echoing variables for a very simple
		template implementation
	</p>
</body>
</html>
