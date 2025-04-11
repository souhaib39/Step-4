<?php
header('Content-Type: application/json');
require_once 'config/database.php'; // Include the database connection class

if (isset($_POST['name'], $_POST['weight'], $_POST['height'])) {
    $name = htmlspecialchars($_POST['name']);
    $weight = floatval($_POST['weight']);
    $height = floatval($_POST['height']);

    if ($weight <= 0 || $height <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid input values. Weight and height must be greater than zero.'
        ]);
        exit;
    }

    $bmi = $weight / ($height * $height);

    if ($bmi < 18.5) {
        $interpretation = "Underweight";
    } elseif ($bmi < 25) {
        $interpretation = "Normal weight";
    } elseif ($bmi < 30) {
        $interpretation = "Overweight";
    } else {
        $interpretation = "Obesity";
    }

    $message = "Hello, $name. Your BMI is " . number_format($bmi, 2) . " ($interpretation).";

    // Save the result to the database
    $database = new Database();
    $db = $database->connect();

    try {
        $query = "INSERT INTO bmi_records (name, weight, height, bmi, status) VALUES (:name, :weight, :height, :bmi, :status)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':height', $height);
        $stmt->bindParam(':bmi', $bmi);
        $stmt->bindParam(':status', $interpretation);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Database Insert Error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Failed to save BMI record to the database.'
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'bmi' => $bmi,
        'message' => $message
    ]);
    exit;
}

echo json_encode([
    'success' => false,
    'message' => 'Data not received.'
]);
exit;
