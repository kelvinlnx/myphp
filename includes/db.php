<?php
function getDbConnection() {
    $config = include(__DIR__ . '/../config/config.php');

    $conn = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to safely output variables
function safe_output($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Function to check if table exists
function tableExists($conn, $table_name) {
    $result = $conn->query("SHOW TABLES LIKE '$table_name'");
    return $result->num_rows > 0;
}

// Function to create 'users' table
function createUsersTable($conn) {
    $create_table_sql = "CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user VARCHAR(255) NOT NULL,
        age INT NOT NULL
    )";
    
    if ($conn->query($create_table_sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to truncate 'users' table
function truncateUsersTable($conn) {
    $truncate_sql = "TRUNCATE TABLE users";
    return $conn->query($truncate_sql);
}

// Function to delete 'users' table
function deleteUsersTable($conn) {
    $delete_sql = "DROP TABLE IF EXISTS users";
    return $conn->query($delete_sql);
}

// Function to insert random records into 'users' table
function insertRandomRecords($conn, $num_records) {
    $mythical_characters = ["Zeus", "Hercules", "Athena", "Apollo", "Artemis", "Poseidon", "Hades", "Ares", "Hermes", "Aphrodite", "Thor", "Loki", "Odin", "Freya", "Baldur"];
    $values = [];
    for ($i = 0; $i < $num_records; $i++) {
        $name = $mythical_characters[array_rand($mythical_characters)];
        $age = rand(20, 50);
        $values[] = "('$name', $age)";
    }
    $insert_data_sql = "INSERT INTO users (user, age) VALUES " . implode(", ", $values);
    return $conn->query($insert_data_sql);
}

// Function to display contents of 'users' table
function displayUsersTable($conn) {
    $sql = "SELECT id, user, age FROM users";
    $result = $conn->query($sql);

    echo '<h2>Data from Database</h2>';
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr><th>ID</th><th>User</th><th>Age</th></tr>';
        while ($row = $result->fetch_assoc()) {
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
}
?>
