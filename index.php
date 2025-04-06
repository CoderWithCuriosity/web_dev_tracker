<?php
session_start(); // Start a session to store the activation state

$correctCode = "mubbyspark"; // Define the correct activation code

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputCode = $_POST['code'];

    // Check if the entered code matches the correct one
    if ($inputCode === $correctCode) {
        $_SESSION['activated'] = true; // Set session to true if code is correct
        header("Location: email.php"); // Redirect to email form after activation
        exit();
    } else {
        $error = "Invalid activation code!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans flex justify-center items-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6">Enter Activation Code</h2>

        <form method="POST">
            <div class="mb-4">
                <label for="code" class="block text-sm text-gray-600 mb-2">Activation Code</label>
                <input type="text" name="code" id="code" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required />
            </div>

            <button type="submit" class="w-full py-2 px-4 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Activate</button>

            <?php if (!empty($error)) echo "<p class='text-red-500 text-center mt-4'>$error</p>"; ?>
        </form>
    </div>

</body>
</html>

