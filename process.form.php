<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

function uploadFile($fileInputName, $uploadDir = 'Uploads/', $options = []) {
    $maxSize = isset($options['maxSize']) ? $options['maxSize'] : 5 * 1024 * 1024; // 5MB
    $allowedTypes = isset($options['allowedTypes']) ? $options['allowedTypes'] : null; // Null = all types
    $errors = [];

    // Check if file was uploaded
    if (!isset($_FILES[$fileInputName])) {
        $errors[] = "No file uploaded for input: $fileInputName.";
        return ['filename' => '', 'file_url' => '', 'errors' => $errors];
    }

    $file = $_FILES[$fileInputName];
    $fileName = basename($file["name"]);
    $fileTmpPath = $file["tmp_name"];
    $fileError = $file["error"];
    $fileSize = $file["size"];

    // Sanitize filename
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $baseName = pathinfo($fileName, PATHINFO_FILENAME);
    $safeBaseName = preg_replace('/[^A-Za-z0-9_-]/', '_', $baseName); // Replace unsafe chars
    $newFileName = $safeBaseName . '.' . $fileExt;

    // Ensure upload directory exists
    $uploadDir = rtrim($uploadDir, '/') . '/';
    $absoluteUploadDir = __DIR__ . '/' . $uploadDir;
    if (!is_dir($absoluteUploadDir)) {
        if (!mkdir($absoluteUploadDir, 0755, true)) {
            $errors[] = "Failed to create upload directory: $absoluteUploadDir. Error: " . error_get_last()['message'];
            return ['filename' => '', 'file_url' => '', 'errors' => $errors];
        }
    } elseif (!is_writable($absoluteUploadDir)) {
        $errors[] = "Upload directory exists but is not writable: $absoluteUploadDir.";
        return ['filename' => '', 'file_url' => '', 'errors' => $errors];
    }

    // Check for file existence and generate unique name
    $destination = $absoluteUploadDir . $newFileName;
    $counter = 1;
    while (file_exists($destination)) {
        $newFileName = $safeBaseName . '_' . uniqid() . '.' . $fileExt;
        $destination = $absoluteUploadDir . $newFileName;
    }

    // Validate file
    if ($fileError !== UPLOAD_ERR_OK) {
        $errors[] = "Upload error code: $fileError.";
    }
    if ($fileSize > $maxSize) {
        $errors[] = "File too large. Max size: " . ($maxSize / 1024 / 1024) . "MB.";
    }
    if ($allowedTypes !== null && !in_array($fileExt, $allowedTypes)) {
        $errors[] = "Invalid file type. Allowed: " . implode(', ', $allowedTypes) . ".";
    }

    // Move file if no errors
    if (empty($errors)) {
        if (move_uploaded_file($fileTmpPath, $destination)) {
            $relativePath = $uploadDir . $newFileName;
            $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
            $fileUrl = $baseUrl . $relativePath;
            return ['filename' => $newFileName, 'file_url' => $fileUrl, 'errors' => []];
        } else {
             echo "Failed to move uploaded file. Error: ";
        }
    }

    return ['filename' => '', 'file_url' => '', 'errors' => $errors];
}
?>