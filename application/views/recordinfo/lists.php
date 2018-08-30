<!DOCTYPE html>
<html lang="tw">
<head>
	<meta charset="utf-8">
	<title>Table</title>
</head>
<body>
<div id="container">
<?php if (count($result) > 0): ?>
	<table border="1" width="100%">
		<tr>
		<?php foreach ($result[0] as $field => $val): ?>
			<td><?=$field?></td>
		<?php endforeach; ?>
		</tr>
	<?php foreach ($result as $key => $row): ?>
		<tr>
		<?php foreach ($row as $field => $val): ?>
			<td><?=$val?></td>
		<?php endforeach; ?>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>
</body>
</html>