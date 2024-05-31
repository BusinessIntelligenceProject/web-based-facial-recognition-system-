<?php
$con = mysqli_connect("localhost", "root", "", "test1");
if ($con === false) {
    die("Connection Error" . mysqli_connect_error());
} else {
    echo "";
}

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $password = mysqli_real_escape_string($con, $_POST['password']); // Escape user input

    // Hash the password with a secure algorithm
    $hashedPassword = password_hash($password, PASSWORD_ARGON2ID); // Replace with desired algorithm

    $originalFilename = $_FILES['image']['name'];
    $uniqueFilename = md5(uniqid(rand(), true)) . '_' . $originalFilename;
    $tempname = $_FILES['image']['tmp_name'];
    $folder = 'login/' . $uniqueFilename;
    $uniquename = 'login/' . $uniqueFilename;

    // Prepare the insert statement with password field
    $stmt = mysqli_prepare($con, "INSERT INTO login (name, username, password, image_path) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssss', $name, $username, $hashedPassword, $uniquename); // Bind hashed password

    if (mysqli_stmt_execute($stmt)) {
        if (move_uploaded_file($tempname, $folder)) {
            ;

            // Get the ID of the recently inserted record
            $lastInsertedID = mysqli_insert_id($con);

            // Prepare a query to retrieve the image path for the latest record
            $stmt = mysqli_prepare($con, "SELECT image_path FROM login WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'i', $lastInsertedID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);

            // Display the uploaded image
            $imagePath = $row['image_path'];
            echo "<img src='$imagePath' />";
        } else {
            echo "<h2>File not uploaded</h2>";
        }
    } else {
        echo "<h2>Failed to insert data</h2>";

    }
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "test1";

// Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $sql = "SELECT * FROM verification ORDER BY ID DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $last_row = $result->fetch_assoc();
        $column_value = $last_row['image_comparison'];
        echo "<h2>HI</h2>" ;
    }

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


    // Close statements
    mysqli_stmt_close($stmt);
    mysqli_close($con); // Close connection after processing
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        body {
            background-color: #f5f5f5; /* Light background color */
        }

        .registration-form {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            text-align: center; /* Center form elements */
        }

        .registration-form h2 {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            border-radius: 5px; /* Rounded corners for input fields */
        }

        .btn-primary {
            background-color: #007bff; /* Blue button */
            border-color: #007bff;
            border-radius: 5px; /* Rounded corners for button */
        }

        #image-preview {
            margin-top: 20px;
            display: none; /* Initially hide image preview */
        }

        #image-preview img {
            width: 100%;
            max-width: 200px;
            border-radius: 5px; /* Rounded corners for image preview */
        }

        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container registration-form">
        <h2>User Login</h2>

        <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">

            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required 
                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" onkeyup="validatePassword()">
                <span id="password-error" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="image" class="form-label">Upload Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="showPreview(this)">
            </div>

            <div id="image-preview"></div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>

    <script>
        function validatePassword() {
            var passwordInput = document.getElementById('password');
            var passwordError = document.getElementById('password-error');

            if (passwordInput.validity.patternMismatch) {
                passwordError.textContent = 'Password must contain at least 8 characters, including one uppercase letter, one lowercase letter, and one number.';
                return false;
            } else {
                passwordError.textContent = '';
                return true;
            }
        }

        function validateForm() {
            return validatePassword();
        }
    </script>
</body>

</html>