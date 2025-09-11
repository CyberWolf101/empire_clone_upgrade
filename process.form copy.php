<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
    exit('Error: POST request required.');
}

// Check if files are uploaded
if (empty($_FILES)) {
    exit('Error: No files uploaded.');
}

// Debug: Output $_FILES array
echo "<pre>";
print_r($_FILES);
echo "</pre>";

// Get file details
$file = $_FILES["file"];
$fileName = basename($file["name"]);
$fileTmpPath = $file["tmp_name"];
$fileError = $file["error"];
$fileSize = $file["size"];

// Define upload directory (case-sensitive, matching your 'Uploads/' folder)
$uploadDir = __DIR__ . '/Uploads/';

// Sanitize filename: keep alphanumeric, -, _, and extension; remove unsafe characters
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$baseName = pathinfo($fileName, PATHINFO_FILENAME);
$safeBaseName = preg_replace('/[^A-Za-z0-9_-]/', '_', $baseName); // Replace unsafe chars with _
$newFileName = $safeBaseName . '.' . $fileExt;

// Check if file exists and generate unique name if needed
$destination = $uploadDir . $newFileName;
$counter = 1;
while (file_exists($destination)) {
    $newFileName = $safeBaseName . '_' . uniqid() . '.' . $fileExt;
    $destination = $uploadDir . $newFileName;
}

// Validation
$errors = [];

// Check for upload errors
if ($fileError !== UPLOAD_ERR_OK) {
    $errors[] = "Upload error code: $fileError.";
}

// Check file size (max 5MB)
$maxSize = 5 * 1024 * 1024;
if ($fileSize > $maxSize) {
    $errors[] = "File too large. Max size: " . ($maxSize / 1024 / 1024) . "MB.";
}

// Check if upload directory exists and is writable
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        $errors[] = "Failed to create upload directory: $uploadDir. Error: " . error_get_last()['message'];
    }
} elseif (!is_writable($uploadDir)) {
    $errors[] = "Upload directory exists but is not writable: $uploadDir.";
}

// If no errors, move the file
if (empty($errors)) {
    if (move_uploaded_file($fileTmpPath, $destination)) {
        // Generate full URL for display (optional)
        $relativePath = 'Uploads/' . $newFileName;
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        $fullUrl = $baseUrl . $relativePath;

        // Output success message and filename
        echo "File uploaded successfully!<br>";
        echo "Filename: " . htmlspecialchars($newFileName) . "<br>";
        echo "Full URL (for reference): <a href='" . htmlspecialchars($fullUrl) . "' target='_blank'>" . htmlspecialchars($fullUrl) . "</a><br>";

        // Display image preview or download link
        if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<img src='" . htmlspecialchars($fullUrl) . "' alt='Uploaded File' style='max-width: 100%; height: auto;'>";
        } else {
            echo "<p><a href='" . htmlspecialchars($fullUrl) . "' target='_blank'>View/Download File</a></p>";
        }
    } else {
        $errors[] = "Failed to move uploaded file. Error: " . error_get_last()['message'];
    }
}

// Output errors if any
if (!empty($errors)) {
    echo "Upload failed with the following errors:<br>";
    foreach ($errors as $error) {
        echo htmlspecialchars($error) . "<br>";
    }
    exit;
}
?>