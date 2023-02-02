<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
<h1>Welcome</h1>
<p>Hello <?php if (isset($name)) {
		echo htmlspecialchars($name);
	} ?>!</p>
<ul>
	<?php if (isset($colours)) {
		foreach ($colours as $colour): ?>
            <li><?php echo htmlspecialchars($colour); ?></li>
		<?php endforeach;
	} ?>
</ul>
</body>
</html>