<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Generate a random background color for fun
$randomColor = sprintf("#%06X", mt_rand(0, 0xFFFFFF));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random File Upload Test</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 20px;
            background-color: <?php echo $randomColor; ?>;
            color: #333;
            text-align: center;
        }
        .container {
            background: rgba(255, 255, 255, 0.9);
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        input[type="file"], input[type="submit"] {
            display: block;
            margin: 10px auto;
            padding: 10px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #ff6f61;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #e55a50;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #90ee90;
        }
        .error {
            background-color: #ffcccb;
        }
        .preview img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            border: 2px dashed #333;
        }
        .preview a {
            color: #0066cc;
            text-decoration: none;
        }
        .preview a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Random File Upload Test</h1>
        <form enctype="multipart/form-data" method="POST" action="process.form.php">
            <label for="file">Pick any file (max 5MB):</label>
            <input type="file" name="file" id="file">
            <input type="submit" value="Upload It!">
        </form>
    </div>
</body>
</html>