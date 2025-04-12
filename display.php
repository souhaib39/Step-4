<?php
require_once 'config/database.php'; // Include the database connection class
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        h1 {
            color: #007bff;
        }

        table {
            background-color: white;
        }

        .table th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1 class="mb-4 text-center">Search BMI Record</h1>
        <form method="GET" class="mb-4">
            <div class="form-group">
                <label for="name">Enter Name:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter user name" required>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <?php
        // Create a database connection
        $database = new Database();
        $db = $database->connect();

        // Get the user name from the query string (e.g., display.php?name=John)
        $name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';

        if (!empty($name)) {
            try {
                // Fetch a single BMI record from the database based on the name
                $query = "SELECT id, name, weight, height, bmi, status, timestamp FROM bmi_records WHERE name = :name";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->execute();

                // Check if a record was found
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<table class="table table-bordered table-striped">';
                    echo '<tr><th>ID</th><td>' . htmlspecialchars($row['id']) . '</td></tr>';
                    echo '<tr><th>Name</th><td>' . htmlspecialchars($row['name']) . '</td></tr>';
                    echo '<tr><th>Weight (kg)</th><td>' . htmlspecialchars($row['weight']) . '</td></tr>';
                    echo '<tr><th>Height (m)</th><td>' . htmlspecialchars($row['height']) . '</td></tr>';
                    echo '<tr><th>BMI</th><td>' . htmlspecialchars(number_format($row['bmi'], 2)) . '</td></tr>';
                    echo '<tr><th>Status</th><td>' . htmlspecialchars($row['status']) . '</td></tr>';
                    echo '<tr><th>Timestamp</th><td>' . htmlspecialchars($row['timestamp']) . '</td></tr>';
                    echo '</table>';
                } else {
                    echo '<div class="alert alert-warning">No record found for the given name.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="alert alert-danger">Error fetching BMI record: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        ?>
    </div>
</body>

</html>