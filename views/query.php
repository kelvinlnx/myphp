<?php
include(__DIR__ . '/../includes/db.php');

// Function to safely output variables
function safe_output($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Load configuration
$config = include(__DIR__ . '/../config/config.php');

// Display server name and IP address
echo '<h2>Server Information</h2>';
echo '<table>';
echo '<tr><th>Server Name</th><td>' . safe_output($gethostname()) . '</td></tr>';
echo '<tr><th>Server IP Address</th><td>' . safe_output($_SERVER['SERVER_ADDR']) . '</td></tr>';
echo '</table>';


// Display environment variables if set
echo '<h2>Environment Variables</h2>';
if ($config['env_msg'] !== false || $config['env_value1'] !== false) {
    echo '<table>';
    echo '<tr><th>Environment Variable</th><th>Value</th></tr>';
    echo '<tr><td>' . safe_output('MSG') . '</td>';
    if ($config['env_msg'] !== false ) {
        echo '<td>' . safe_output($config['env_msg']) . '</td>';
    } else {
        echo '<td class="warning">Warning: ' . safe_output('MSG') . ' is not set!</td>';
    }
    echo '</tr>';
    
    echo '<tr><td>' . safe_output('VALUE1') . '</td>';
    if ($config['env_value1'] !== false ) {
        echo '<td>' . safe_output($config['env_value1']) . '</td>';
    } else {
        echo '<td class="warning">Warning: ' . safe_output('VALUE1') . ' is not set!</td>';
    }    
    echo '</tr>';
    
    echo '</table>';
} else {
    echo '<p class="warning">Environment variables MSG and VALUE1 are not set. Unable to display environment variables.</p>';
}

// Database operations
$servername = $config['db_host'];

echo '<h2>Database Query</h2>';
if ($servername == false) {
    echo '<p class="warning">Database connection info not provided!</p>';
    echo '<p class="warning">Did you set the environment variables (DB_HOST, DB_NAME, DB_USER and DB_PASS)?</p>';
} elseif ($servername == gethostbyname($servername)) {
    echo '<p class="warning">The database server name "' . safe_output($servername) . '" could not be resolved. Unable to connect to the database.</p>';
} else {
    $conn = getDbConnection();

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
