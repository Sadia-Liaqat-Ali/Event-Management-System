<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendee Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- FontAwesome Icons -->
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
        .header .title {
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
    <div class="title">Event Management System</div>
    <div class="buttons">
        <a href="attendee_dashboard.php" class="btn btn-primary">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="Atndprofile.php" class="btn btn-secondary">
            <i class="fas fa-user-edit"></i> Edit Profile
        </a>
        <a href="manage_rsvps.php" class="btn btn-warning">
            <i class="fas fa-calendar-check"></i> Manage RSVPs
        </a>
        <a href="receive_notification.php" class="btn notification-btn">
            <i class="fas fa-bell"></i> Receive Notifications
        </a>
        <a href="../logout.php" class="btn logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
