<?php
session_start();
if (!isset($_SESSION["full_name"])) { // Check if the user session exists
    header("Location: login.php");
    exit(); // Ensure the script stops executing after the redirect
}

include "layout/header.php";
?>

<title>Home Page</title>
<div class="container text-center py-5">
    <h1 class="display-4">Welcome, <?= htmlspecialchars($_SESSION["full_name"]) ?>!</h1> <!-- Display the user's full name -->
    <p class="lead">This is your home page.</p>
    <div class="mt-4">
        <a href="logout.php"  class="btn btn-secondary">Logout</a>
    </div>
</div>

<?php include "layout/footer.php"; ?>
