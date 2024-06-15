<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Welcome to My Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 50%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .warning {
            color: red;
        }
    </style>
</head>
<body>
    <?php
    // Function to safely output variables
    function safe_output($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    // Display server name and IP address
    echo '<h2>Server Information</h2>';
    echo '<table>';
    //echo '<tr><th>Server Name</th><td>' . safe_output($_SERVER['SERVER_NAME']) . '</td></tr>';
    echo '<tr><th>Server Name</th><td>' . safe_output(gethostname()) . '</td></tr>';
    echo '<tr><th>Server IP Address</th><td>' . safe_output($_SERVER['SERVER_ADDR']) . '</td></tr>';
    echo '</table>';

    // Check if both environment variables are set
    $key1_name = 'USERNAME';
    $key2_name = 'ENV_VAR2';
    $env_var1 = getenv($key1_name);
    $env_var2 = getenv($key2_name);
    $envVarsSet = ($env_var1 !== false || $env_var2 !== false);

    // Display environment variables if set
    echo '<h2>Environment Variables</h2>';
    if ($envVarsSet) {
        echo '<table>';
        echo '<tr><th>Environment Variable</th><th>Value</th></tr>';
        echo '<tr><td>' . safe_output($key1_name) . '</td>';
	if ($env_var1 !== false) {
            echo '<td>' . safe_output($env_var1) . '</td>';
        } else {
            echo '<td class="warning">Warning: ' . safe_output($key1_name) . ' is not set!</td>';
            $allSet = false;
        }
        echo '</tr>';

        echo '<tr><td>' . safe_output($key2_name) . '</td>';
	if ($env_var2 !== false) {
            echo '<td>' . safe_output($env_var2) . '</td>';
        } else {
            echo '<td class="warning">Warning: ' . safe_output($key2_name) . ' is not set!</td>';
            $allSet = false;
        }
        echo '</tr>';
        echo '</table>';
    } else {
        echo '<p class="warning">Both environment variables ' . safe_output($key1_name) . ' and ' . safe_output($key2_name) . ' are not set.</p>';
    }

    // Display data from MySQL database regardless of environment variables
    // Database connection parameters
    $servername = "mydb.dbproj.svc";
    $username = "kelvin"; // Replace with your MySQL username
    $password = "abc"; // Replace with your MySQL password
    $dbname = "training";

    echo '<h2>Database Query</h2>';
    // Check if the server name is resolvable
    $server_ip = gethostbyname($servername);
    if ($server_ip == $servername) {
        echo '<p class="warning">The database server name "' . safe_output($servername) . '" could not be resolved. Unable to connect to the database.</p>';
    } else {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to fetch data from table ABC
        $sql = "SELECT id, username, age FROM users";
        $result = $conn->query($sql);

        echo '<h2>Data from Database</h2>';
        if ($result->num_rows > 0) {
            echo '<table>';
            echo '<tr><th>User</th><th>Name</th><th>Age</th></tr>';
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . safe_output($row["id"]) . '</td>';
                echo '<td>' . safe_output($row["username"]) . '</td>';
                echo '<td>' . safe_output($row["age"]) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p class="warning">No data found in table ABC</p>';
        }

        // Close connection
        $conn->close();
    }
    ?>
</body>
</html>
