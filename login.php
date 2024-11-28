<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "layout/header.php";
require_once "public/database.php";


$conn = getDbConnection(); // Object-Oriented database connection
$email = $password = "";
$email_error = $password_error = "";
$error = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validation
    if (empty($email)) {
        $email_error = "Email is required";
        $error = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format";
        $error = true;
    }

    if (empty($password)) {
        $password_error = "Password is required";
        $error = true;
    }

    if (!$error) {

        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, full_name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {

            $stmt->bind_result($id, $full_name, $db_email, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {

                echo "Password verified. Redirecting...<br>";

                // Session variables
                $_SESSION["id"] = $id;
                $_SESSION["full_name"] = $full_name;
                $_SESSION["email"] = $db_email;

                // Redirect to home.php after login
                header("Location: home.php");
                exit();
            } else {
            }
        } else {
            $email_error = "Email not found.";
        }

        $stmt->close();
    }
}

$conn->close(); // Close the database connection
?>

<div class="container py-5" ;>
    <div class="row justify-content-center align-items-center" >
        <div class="col-12 col-md-8">
            <div class="shadow p-3 mb-5 bg-body-tertiary rounded style= border-radius: 15px" class="w-75 p-3" style="background-color: #613d3d;">
                <div class="card-body p-3">
                    <h3 class="mb-4">Login</h3>
                    <form method="POST" action="login.php" >
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                            <span class="text-danger"><?= $email_error ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <span class="text-danger"><?= $password_error ?></span>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-info btn-lg" name="login">Login</button>
                        </div>
                        <p class="mt-3">Not registered yet? <a href="register.php">Register Here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "layout/footer.php"; ?>
