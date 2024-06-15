<?php
include(__DIR__ . '/../includes/db.php');

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

// Database operations
$servername = "MYSERVER";
$server_ip = gethostbyname($servername);

if ($server_ip == $servername) {
    echo '<p class="warning">The database server name "' . safe_output($servername) . '" could not be resolved. Unable to connect to the database.</p>';
} else {
    $conn = getDbConnection();

    // Check if the 'users' table exists
    $table_exists = $conn->query("SHOW TABLES LIKE 'users'")->num_rows > 0;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_table'])) {
        if (!$table_exists) {
            // Create table and insert random data
            $create_table_sql = "CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user VARCHAR(255) NOT NULL,
                age INT NOT NULL
            )";
            
            if ($conn->query($create_table_sql) === TRUE) {
                // Insert 3 random records with mythical character names
                $insert_data_sql = "INSERT INTO users (user, age) VALUES
                    ('" . getRandomCharacter($mythical_characters) . "', " . rand(20, 50) . "),
                    ('" . getRandomCharacter($mythical_characters) . "', " . rand(20, 50) . "),
                    ('" . getRandomCharacter($mythical_characters) . "', " . rand(20, 50) . ")";
                
                if ($conn->query($insert_data_sql) === TRUE) {
                    echo '<p>Table "users" created successfully and 3 random records inserted.</p>';
                } else {
                    echo '<p class="warning">Error inserting data: ' . $conn->error . '</p>';
                }
            } else {
                echo '<p class="warning">Error creating table: ' . $conn->error . '</p>';
            }
        } else {
            echo '<p class="warning">Table "users" already exists.</p>';
        }
    } else {
        if ($table_exists) {
            echo '<p class="warning">Table "users" already exists.</p>';
        } else {
            echo '<div class="button-container">';
            echo '<form method="POST">';
            echo '<button type="submit" name="create_table">Create Table and Insert Data</button>';
            echo '</form>';
            echo '</div>';
        }
    }

    $conn->close();
}
?>
