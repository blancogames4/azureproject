<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define database connection variables
$servername = "pg-server-sql.mysql.database.azure.com";
$username = "admin1";
$password = "GG#238Lanco";
$dbname = "pg-db-1";

// Create connection
$conn = mysqli_init();
mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT | MYSQLI_CLIENT_SSL);

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the request URI and remove query string
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = rtrim($requestUri, '/');

// Routing logic
if ($requestUri === '' || $requestUri === '/employees') {
    // Display all employees
    $sql = "SELECT * FROM employees";
    $result = $conn->query($sql);

    echo "<h1>All Employees</h1>";
    if ($result && $result->num_rows > 0) {
        echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Email</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row["emp_no"]) . "</td><td>" . htmlspecialchars($row["first_name"]) . "</td><td>" . htmlspecialchars($row["email_id"]) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No employees found.";
    }
} elseif (preg_match('#^/employees/(\d+)$#', $requestUri, $matches)) {
    // Display specific employee by ID
    $employeeId = $matches[1];
    $stmt = $conn->prepare("SELECT * FROM employees WHERE emp_no = ?");
    $stmt->bind_param("i", $employeeId);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h1>Employee Details</h1>";
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<p><strong>ID:</strong> " . htmlspecialchars($row["emp_no"]) . "</p>";
        echo "<p><strong>Name:</strong> " . htmlspecialchars($row["first_name"]) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($row["email_id"]) . "</p>";
    } else {
        echo "Employee not found.";
    }
    $stmt->close();
} else {
    // Handle 404 Not Found
    http_response_code(404);
    echo "<h1>404 Not Found</h1>";
    echo "<p>The requested URL " . htmlspecialchars($requestUri) . " was not found on this server.</p>";
}

$conn->close();
?>
