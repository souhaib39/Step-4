<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>BMI Calculator with jQuery and Bootstrap</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #007bff;
        }

        .form-group label {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1 class="mt-5">BMI Calculator</h1>
        <div id="result" class="mt-3"></div>
        <form id="bmiForm" class="mt-3">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="weight">Weight (kg):</label>
                <input type="number" id="weight" name="weight" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="height">Height (m):</label>
                <input type="number" step="0.1" id="height" name="height" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Calculate</button>
        </form>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('#bmiForm').submit(function(e) {
            e.preventDefault();

            // Validate inputs using jQuery
            var name = $('#name').val().trim();
            var weight = parseFloat($('#weight').val());
            var height = parseFloat($('#height').val());

            if (name === "" || isNaN(weight) || isNaN(height) || weight <= 0 || height <= 0) {
                $('#result').html('<div class="alert alert-warning">Please enter valid values in all fields.</div>');
                return;
            }

            // Send data via AJAX
            $.ajax({
                url: 'calculate.php',
                type: 'POST',
                data: {
                    name: name,
                    weight: weight,
                    height: height
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var alertClass = 'alert-info';

                        if (response.bmi < 18.5) {
                            alertClass = 'alert-warning';
                        } else if (response.bmi < 25) {
                            alertClass = 'alert-success';
                        } else if (response.bmi < 30) {
                            alertClass = 'alert-info';
                        } else {
                            alertClass = 'alert-danger';
                        }

                        $('#result').html('<div class="alert ' + alertClass + '">' + response.message + '</div>');
                    } else {
                        $('#result').html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                },
                error: function() {
                    $('#result').html('<div class="alert alert-danger">Server error occurred.</div>');
                }
            });
        });
    });
</script>

</html>