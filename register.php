<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $servername = "localhost"; // Change if your database server is different
    $username = "root"; // Your MySQL username
    $password = ""; // Your MySQL password
    $dbname = "miniproject"; // Your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $contact_number = trim($_POST['contact_number']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $event_list = $_POST['event_list'];

    $errors = [];

    // Validate First Name
    if (empty($first_name) || !preg_match("/^[a-zA-Z]+$/", $first_name)) {
        $errors[] = "First Name is required and must contain only letters.";
    }

    // Validate Last Name
    if (empty($last_name) || !preg_match("/^[a-zA-Z]+$/", $last_name)) {
        $errors[] = "Last Name is required and must contain only letters.";
    }

    // Validate Contact Number
    if (empty($contact_number) || !is_numeric($contact_number)) {
        $errors[] = "Contact Number must be valid and numeric.";
    }

    // Validate Email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email must follow a proper email format.";
    }

    // Validate Password
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Validate Confirm Password
    if ($password !== $confirm_password) {
        $errors[] = "Password and Confirm Password must match.";
    }

    // Validate Event List
    if (empty($event_list)) {
        $errors[] = "You must select an event.";
    }

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Construct the SQL query
        $sql = "INSERT INTO users (first_name, last_name, contact_number, email, password, event_list)
                VALUES ('$first_name', '$last_name', '$contact_number', '$email', '$hashed_password', '$event_list')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // Redirect to login page
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }

    // Close the connection
    $conn->close();
}
?>