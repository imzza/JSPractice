<?php

// Includs database connection
include "db_connect.php";

$query = "SELECT rowid, * FROM students";
$result = $db->query($query);


?>
<!DOCTYPE html>
<html>
<head>
	<title>Data List</title>
</head>
<body>
	<div style="width: 500px; margin: 20px auto;">
		<a href="insert.php">Add New</a>
		<table width="100%" cellpadding="5" cellspacing="1" border="1">
			<tr>
				<td>Name</td>
				<td>Email</td>
				<td>Action</td>
			</tr>
			<?php while($row = $result->fetchArray()) {?>
			<tr>
				<td><?php echo $row['name'];?></td>
				<td><?php echo $row['email'];?></td>
				<td>
					<a href="update.php?id=<?php echo $row['rowid'];?>">Edit</a> | 
					<a href="delete.php?id=<?php echo $row['rowid'];?>" onclick="return confirm('Are you sure?');">Delete</a>
				</td>
			</tr>
			<?php } ?>
		</table>
	</div>
</body>
</html>