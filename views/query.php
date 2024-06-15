<?php
include(__DIR__ . '/../includes/db.php');

// Function to safely output variables
function safe_output($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Load configuration
$config = include(__DIR__ . '/../config/config.php');
$servername = $config['db_host'];

// Database operations
$server_ip = gethostbyname($servername);

// Display server name and IP address
echo '<h2>Server Information</h2>';
echo '<table>';
echo '<tr><th>Server Name</th><td>' . safe_output($_SERVER['SERVER_NAME']) . '</td></tr>';
echo '<tr><th>Server IP Address</th><td>' . safe_output($_SERVER['SERVER_ADDR']) . '</td></tr>';
echo '</table>';

if ($server_ip == $servername) {
    echo '<p class="warning">The database server name "' . safe_output($servername) . '" could not be resolved. Unable to connect to the database.</p>';
} else {
    $conn = getDbConnection();

    // Check if both environment variables are set
    $env_var1 = getenv('ENV_VAR1');
    $env_var2 = getenv('ENV_VAR2');
    $envVarsSet = ($env_var1 !== false && $env_var2 !== false);

    // Display environment variables if set
    if ($envVarsSet) {
        echo '<h2>Environment Variables</h2>';
        echo '<table>';
        echo '<tr><th>Environment Variable</th><th>Value</th></tr>';
        echo '<tr><td>' . safe_output('ENV_VAR1') . '</td><td>' . safe_output($env_var1) . '</td></tr>';
        echo '<tr><td>' . safe_output('ENV_VAR2') . '</td><td>' . safe_output($env_var2) . '</td></tr>';
        echo '</table>';
    } else {
        echo '<p class="warning">Environment variables ENV_VAR1 and ENV_VAR2 are not both set. Unable to display environment variables.</p>';
    }

    // Check if the 'users' table exists
    $table_exists = $conn->query("SHOW TABLES LIKE 'users'")->num_rows > 0;

    if ($table_exists) {
        // Fetch data from the 'users' table
        $sql = "SELECT id, user, age FROM users";
        $result = $conn->query($sql);

        echo '<h2>Data from Database</h2>';
        if ($result->num_rows > 0) {
            echo '<table>';
            echo '<tr><th>ID</th><th>User</th><th>Age</th></tr>';
            while($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . safe_output($row["id"]) . '</td>';
                echo '<td>' . safe_output($row["user"]) . '</td>';
                echo '<td>' . safe_output($row["age"]) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p class="warning">No data found in table users</p>';
        }
    } else {
        echo '<p class="warning">Table "users" does not exist.</p>';
    }

    $conn->close();
}
?>
