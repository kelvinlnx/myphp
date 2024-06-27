<?php
// query.php

include(__DIR__ . '/../includes/db.php');

// Load configuration
$config = include(__DIR__ . '/../config/config.php');

// Display server name and IP address
echo '<h2>Server Information</h2>';
echo '<table>';
echo '<tr><th>Server Name</th><td>' . safe_output(gethostname()) . '</td></tr>';
echo '<tr><th>Server IP Address</th><td>' . safe_output($_SERVER['SERVER_ADDR']) . '</td></tr>';
echo '</table>';

// Display environment variables if set
echo '<h2>Environment Variables</h2>';
if ($config['env_msg'] !== false || $config['env_value1'] !== false) {
    echo '<table>';
    echo '<tr><th>Environment Variable</th><th>Value</th></tr>';
    echo '<tr><td>' . safe_output('MSG') . '</td>';
    echo '<td>' . safe_output($config['env_msg']) . '</td>';
    echo '</tr>';
    
    echo '<tr><td>' . safe_output('VALUE1') . '</td>';
    echo '<td>' . safe_output($config['env_value1']) . '</td>';
    echo '</tr>';
    
    echo '</table>';
} else {
    echo '<p class="warning">Environment variables MSG and VALUE1 are not set. Unable to display environment variables.</p>';
}

// Persistent Volume
echo '<h2>Persistent Volume Test</h2>';
echo "Contents of /opt/data <br>";
if (is_dir("/opt/data")) {
    $data = scandir("/opt/data");

    echo "<ul>";
    foreach($data as $item) {
        // Skip the special directories '.' and '..'
        if ($item != "." && $item != "..") {
            echo "<li>" . htmlspecialchars($item) . "</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>Warning: The directory /opt/data does not exist.</p>";
}

// Database operations
echo '<h2>Database Test</h2>';
$servername = $config['db_host'];

if (!$servername) {
    echo '<p class="warning">Database connection info not provided!</p>';
    echo '<p class="warning">Did you set the environment variables (DB_HOST, DB_NAME, DB_USER and DB_PASS)?</p>';
} elseif ($servername == gethostbyname($servername)) {
    echo '<p class="warning">The database server name "' . safe_output($servername) . '" could not be resolved. Unable to connect to the database.</p>';
} else {
    $conn = getDbConnection();

    // Check if the 'users' table exists
    $table_exists = tableExists($conn, 'users');

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['generate_data'])) {
            // Generate or clear table based on existence
            if ($table_exists) {
                truncateUsersTable($conn);
            } else {
                createUsersTable($conn);
                $table_exists = true;
            }

            // Insert 3 to 8 random records
            insertRandomRecords($conn, rand(3, 8));
        } elseif (isset($_POST['clear_table'])) {
            // Clear table if it exists
            if ($table_exists) {
                truncateUsersTable($conn);
            } else {
                echo '<p class="warning">Table "users" does not exist.</p>';
            }
        } elseif (isset($_POST['delete_table'])) {
            // Delete table if it exists
            if ($table_exists) {
                deleteUsersTable($conn);
                $table_exists = false;
            } else {
                echo '<p class="warning">Table "users" does not exist.</p>';
            }
        }
    }

    // Display data from the 'users' table if it exists
    if ($table_exists) {
        displayUsersTable($conn);
    }

    // Display buttons below the table
    echo '<form method="POST">';
    if ($table_exists) {
        echo '<button type="submit" name="clear_table">Clear Table</button>';
        echo ' ';
        echo '<button type="submit" name="delete_table">Delete Table</button>';
    }
    echo '<br><br>';
    echo '<button type="submit" name="generate_data">Generate Data</button>';
    echo '</form>';

    $conn->close();
}
?>
