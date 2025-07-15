<?php
require_once '../db.php';

// Check if the user is logged in and is an organizer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.php");
    exit;
}

// Fetch organizer profile
$stmt = $pdo->prepare("SELECT * FROM register WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch organizer events
$stmt = $pdo->prepare("SELECT * FROM events WHERE organizer_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eaf4fc;
        }
        .header {
            background-color: #343a40;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .header .btn {
            color: #ffffff;
            margin-left: 10px;
        }
        .header .btn:hover {
            color: #f1f1f1;
        }
        .header .profile-actions {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .header .profile-actions a {
            margin-right: 15px;
        }
        .header .notification-btn {
            background-color: #28a745;
            color: white;
            border: none;
            font-size: 1rem;
            margin-right: 20px;
        }
        .header .notification-btn:hover {
            background-color: #218838;
        }
        .header .logout-btn {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            background-color: #dc3545;
        }
        .header .logout-btn:hover {
            background-color: #c82333;
        }
        .header .event-management-title {
            font-size: 1.5rem;
            font-weight: bold;
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>
<body>

<div class="header">
    <!-- Event Management System Title -->
    <div class="event-management-title">
        Event Management System
    </div>

    <div class="profile-actions">
        <!-- Dashboard Button -->
        <a href="organizer_dashboard.php" class="btn btn-primary"><i class="fas fa-tachometer-alt"></i> View Dashboard</a>

        <!-- Create New Event Button -->
        <a href="create_event.php" class="btn btn-info"><i class="fas fa-plus"></i> Create New Event</a>

        <!-- Edit Profile Button -->
        <a href="orgprofile.php" class="btn btn-warning"><i class="fas fa-user-edit"></i> Edit Profile</a>

        <!-- Manage RSVPs Button -->
        <a href="manage_rsvp.php" class="btn btn-secondary"><i class="fas fa-key"></i> Manage RSVPs</a>

        <!-- Send Notification Button -->
        <a href="send_notification.php" class="btn notification-btn"><i class="fas fa-bell"></i> Send Notification</a>
        
        <!-- Logout Button -->
        <a href="../logout.php" class="btn logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<!-- Include Bootstrap JS and Popper JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>

</body>
</html>
