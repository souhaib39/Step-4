<?php
require_once 'config/database.php'; // Include the database connection class
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previous BMI Calculations</title>
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
        <h1 class="mb-4 text-center">Previous BMI Calculations</h1>
        <?php
        // Create a database connection
        $database = new Database();
        $db = $database->connect();

        try {
            // Fetch BMI records from the database
            $query = "SELECT id, name, weight, height, bmi, status, timestamp FROM bmi_records ORDER BY timestamp DESC";
            $stmt = $db->prepare($query);
            $stmt->execute();

            // Display the records in a table
            echo '<div class="table-responsive">';
            echo '<table class="table table-bordered table-striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Name</th>';
            echo '<th>Weight (kg)</th>';
            echo '<th>Height (m)</th>';
            echo '<th>BMI</th>';
            echo '<th>Status</th>';
            echo '<th>Timestamp</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['weight']) . '</td>';
                echo '<td>' . htmlspecialchars($row['height']) . '</td>';
                echo '<td>' . htmlspecialchars(number_format($row['bmi'], 2)) . '</td>';
                echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                echo '<td>' . htmlspecialchars($row['timestamp']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error fetching BMI records: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
    </div>
</body>

</html>