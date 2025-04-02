<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch user details from the session
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$contact_number = $_SESSION['contact_number'] ?? 'N/A'; // Default value if not set
$email = $_SESSION['email'] ?? 'N/A'; // Default value if not set
$event_list = $_SESSION['event_list'] ?? 'N/A'; // Default value if not set

// Initialize profile picture variable
$profile_picture = '';

// Check if a profile picture has been uploaded
if (isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Validate file type (optional)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($file['type'], $allowed_types)) {
            // Move the uploaded file to a temporary location
            $profile_picture = 'uploads/' . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $profile_picture);
        } else {
            echo "<p style='color:red;'>Invalid file type. Please upload a JPEG, PNG, or GIF image.</p>";
        }
    } else {
        echo "<p style='color:red;'>Error uploading file.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #007bff;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            margin: 0 auto; /* Center horizontally */
        }
        .profile-picture img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-picture .upload-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 40px;
            color: #007bff;
            display: none; /* Initially hidden */
        }
        .profile-picture:hover .upload-icon {
            display: block; /* Show icon on hover */
        }
        .profile-section {
            margin-top: 20px;
            text-align: center; /* Center text */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5 text-center">Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>
        <p class="text-center">You have successfully logged in.</p>

        <div class="profile-section">
            <h3>Profile Picture</h3>
            <div class="profile-picture" onclick="document.getElementById('fileInput').click();">
                <?php if (!empty($profile_picture)): ?>
                    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
                <?php else: ?>
                    <div class="upload-icon">+</div>
                <?php endif; ?>
                <form action="" method="POST" enctype="multipart/form-data" class="mt-3" style="display: none;">
                    <input type="file" name="profile_picture" id="fileInput" accept="image/*" onchange="this.form.submit();" required>
                </form>
            </div>
        </div>

        <div class="mt-5">
            <h3>User Details</h3>
            <ul class="list-group">
                <li class="list-group-item"><strong>First Name:</strong> <?php echo htmlspecialchars($first_name); ?></li>
                <li class="list-group-item"><strong>Last Name:</strong> <?php echo htmlspecialchars($last_name); ?></li>
                <li class="list-group-item"><strong>Contact Number:</strong> <?php echo htmlspecialchars($contact_number); ?></li>
                <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></li>
                <li class="list-group-item"><strong>Event List:</strong> <?php echo htmlspecialchars($event_list); ?></li>
            </ul>
        </div>

        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
    </div>
</body>
</html>