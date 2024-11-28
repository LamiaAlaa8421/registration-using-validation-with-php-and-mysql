<?php
session_start(); 
require_once "public/database.php";

$full_name = $email = $password = $passwordRepeat = $phone = $address = "";
$full_name_error = $email_error = $password_error = $passwordRepeat_error = $phone_error = $address_error = "";
$errors = false;

// Database connection
$conn = getDbConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    // Validate inputs
    if (empty($full_name)) {
        $full_name_error = "Full name is required.";
        $errors = true;
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = empty($email) ? "Email is required." : "Invalid email format.";
        $errors = true;
    }
    if (empty($password)) {
        $password_error = "Password is required.";
        $errors = true;
    } elseif (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters.";
        $errors = true;
    }
    if (empty($passwordRepeat) || $password !== $passwordRepeat) {
        $passwordRepeat_error = empty($passwordRepeat) ? "Please repeat your password." : "Passwords do not match.";
        $errors = true;
    }
    if (empty($phone) || preg_match('/^01[0-2|5][0-9]{8}$/', $phone) === 0) {
        $phone_error = empty($phone) ? "Phone number is required." : "Invalid phone number format.";
        $errors = true;
    }
    if (empty($address)) {
        $address_error = "Address is required.";
        $errors = true;
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $email_error = "Email already exists.";
        $errors = true;
    }
    $stmt->close();

    // Register user if no errors
    if (!$errors) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $full_name, $email, $passwordHash, $phone, $address);

        if ($stmt->execute()) {
            $insert_id = $stmt->insert_id;
            $stmt->close(); 
    
            $_SESSION['id'] = $insert_id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['email'] = $email;
            $_SESSION['passwordHash'] = $passwordHash;
            $_SESSION['phone'] = $phone;
            $_SESSION['address'] = $address;
           
            header("Location: login.php");
            exit();
        } else {
            die("Something went wrong. Please try again later.");
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-12 col-md-8">
                <div class="shadow-lg p-3 mb-5 bg-body-tertiary rounded" style="border-radius: 20px;">
                    <div class="card-body p-5">
                        <h3 class="mb-4 text-center">Register</h3>
                        <form action="register.php" method="POST">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($full_name) ?>" required>
                                <span class="text-danger"><?= $full_name_error ?></span>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                                <span class="text-danger"><?= $email_error ?></span>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <span class="text-danger"><?= $password_error ?></span>
                            </div>
                            <div class="mb-3">
                                <label for="repeat_password" class="form-label">Repeat Password</label>
                                <input type="password" class="form-control" id="repeat_password" name="repeat_password" required>
                                <span class="text-danger"><?= $passwordRepeat_error ?></span>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
                                <span class="text-danger"><?= $phone_error ?></span>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($address) ?>" required>
                                <span class="text-danger"><?= $address_error ?></span>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Accept terms and conditions</label>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary btn-lg w-100">Register</button>
                            </div>
                        </form>
                        <div class="mt-3 text-center">
                            <p>Already registered? <a href="login.php">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
