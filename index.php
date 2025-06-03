<!DOCTYPE html>
<html>
<head>
	<title>MySQL Table Viewer</title>
</head>
<body>
	<h1>MySQL Table Viewer</h1>
	<?php
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
		// Define database connection variables
		$servername = "pg-server-sql.mysql.database.azure.com";
		$username = "admin1";
		$password = "GG#238Lanco";
		$dbname = "pg-db-1";

		$ssl_ca = __DIR__ . "/BaltimoreCyberTrustRoot.crt.pem";

		// Create connection with SSL
		$conn = mysqli_init();
		mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL); // Don't pass $ssl_ca
		mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT | MYSQLI_CLIENT_SSL);

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		// Query database for all rows in the table
		$sql = "SELECT * FROM employees";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			// Display table headers
			echo "<table><tr><th>ID</th><th>Name</th><th>Email</th></tr>";
			// Loop through results and display each row in the table
			while($row = $result->fetch_assoc()) {
				echo "<tr><td>" . $row["emp_no"] . "</td><td>" . $row["first_name"] . "</td><td>" . $row["email_id"] . "</td></tr>";
			}
			echo "</table>";
		} else {
			echo "0 results";
		}

		// Close database connection
		$conn->close();
	?>
</body>
</html>
