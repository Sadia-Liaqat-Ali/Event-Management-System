<?php
session_start();
require_once '../db.php';

// Check if the user is logged in and is an organizer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Organizer Panel'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eaf4fc;
            margin: 0;
        }
        .header {
            background-color: #343a40;
            color: #fff;
            padding: 20px;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        .header h1 {
            margin: 0;
        }
        .header a {
            color: #fff;
            margin-left: 20px;
        }
        .header a:hover {
            color: #f1f1f1;
        }
    </style>
</head>
<body>
<div class="header text-center">
    <h1><?php echo isset($pageTitle) ? $pageTitle : 'Organizer Panel'; ?></h1>
    <a href="organizer_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
