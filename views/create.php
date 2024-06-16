<?php
include(__DIR__ . '/../includes/db.php'); // Include the database connection script

// Function to safely output variables
function safe_output($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Array of mythical character names
$mythical_characters = ["Zeus", "Hercules", "Athena", "Apollo", "Artemis", "Poseidon", "Hades", "Ares", "Hermes", "Aphrodite", "Thor", "Loki", "Odin", "Freya", "Baldur"];

// Function to get a random mythical character name
function getRandomCharacter($characters) {
    return $characters[array_rand($characters)];
}

// Load configuration
$config = include(__DIR__ . '/../config/config.php');

// Check if db_host is set in the configuration
if (!isset($config['db_host']) || empty($config['db_host'])) {
    echo '<p class="warning">The database host is not set. Please declare the DB_HOST environment variable.</p>';
    echo '<div class="button-container">';
    echo '<button onclick="history.back()">Go Back</button>';
    echo '</div>';
    exit; // Exit the script to prevent further execution
}

$servername = $config['db_host'];

// Resolve server IP
$server_ip = gethostbyname($servername);

if ($server_ip == $servername) {
    echo '<p class="warning">The database server name "' . safe_output($servername) . '" could not be resolved. Unable to connect to the database.</p>';
    echo '<div class="button-container">';
    echo '<button onclick="history.back()">Go Back</button>';
    echo '</div>';
} else {
    $conn = getDbConnection();

    // Check if the 'users' table exists
    $table_exists = $conn->query("SHOW TABLES LIKE 'users'")->num_rows > 0;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['create_table'])) {
            if (!$table_exists) {
                // Create table and insert random data
                $create_table_sql = "CREATE TABLE users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user VARCHAR(255) NOT NULL,
                    age INT NOT NULL
                )";
                
                if ($conn->query($create_table_sql) === TRUE) {
                    // Generate a random number of records between 3 and 8
                    $num_records = rand(3, 8);

                    // Insert random records with mythical character names
                    $insert_data_sql = "INSERT INTO users (user, age) VALUES ";
                    $values = [];
                    for ($i = 0; $i < $num_records; $i++) {
                        $name = getRandomCharacter($mythical_characters);
                        $age = rand(20, 50);
                        $values[] = "('$name', $age)";
                    }
                    $insert_data_sql .= implode(", ", $values);
                                        
                    if ($conn->query($insert_data_sql) === TRUE) {
                        echo '<p>Table "users" created successfully and ' . $num_records . ' random records inserted.</p>';
                    } else {
                        echo '<p class="warning">Error inserting data: ' . $conn->error . '</p>';
                    }
                } else {
                    echo '<p class="warning">Error creating table: ' . $conn->error . '</p>';
                }
            } else {
                echo '<p class="warning">Table "users" already exists.</p>';
            }
        } elseif (isset($_POST['delete_table'])) {
            if ($table_exists) {
                // Drop the 'users' table
                $delete_table_sql = "DROP TABLE users";
                if ($conn->query($delete_table_sql) === TRUE) {
                    echo '<p>Table "users" deleted successfully.</p>';
                    $table_exists = false; // Update the table_exists flag
                } else {
                    echo '<p class="warning">Error deleting table: ' . $conn->error . '</p>';
                }
            } else {
                echo '<p class="warning">Table "users" does not exist.</p>';
            }
        }
    }

    if ($table_exists) {
        echo '<p class="warning">Table "users" already exists.</p>';
        echo '<div class="button-container">';
        echo '<form method="POST">';
        echo '<button type="submit" name="delete_table">Delete Table</button>';
        echo '</form>';
        echo '</div>';
    } else {
        echo '<div class="button-container">';
        echo '<form method="POST">';
        echo '<button type="submit" name="create_table">Create Table and Insert Data</button>';
        echo '</form>';
        echo '</div>';
    }

    $conn->close();
}
?>
