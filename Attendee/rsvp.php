<?php
session_start();
require_once '../db.php';

// Check if the user is logged in and has the 'attendee' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'attendee') {
    header("Location: login.php");
    exit;
}

// Check if the event ID is provided
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Prevent duplicate RSVPs
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM event_rsvps WHERE event_id = ? AND user_id = ?");
    $checkStmt->execute([$event_id, $user_id]);
    $alreadyRSVPed = $checkStmt->fetchColumn();

    if ($alreadyRSVPed) {
        // Redirect if already RSVP'd
        header("Location: attendee_dashboard.php?message=already_rsvped");
        exit;
    }

    // Insert RSVP into the database
    $stmt = $pdo->prepare("INSERT INTO event_rsvps (event_id, attendee_name, status, created_at, user_id) VALUES (?, ?, 'attending', NOW(), ?)");
    $stmt->execute([$event_id, $_SESSION['username'], $user_id]);

    // Increment the Attendees_No for the event
    $updateStmt = $pdo->prepare("UPDATE events SET Attendees_No = Attendees_No + 1 WHERE id = ?");
    $updateStmt->execute([$event_id]);

    // Get event name for confirmation message
    $eventStmt = $pdo->prepare("SELECT event_name FROM events WHERE id = ?");
    $eventStmt->execute([$event_id]);
    $event = $eventStmt->fetch(PDO::FETCH_ASSOC);
    $event_name = $event['event_name'] ?? 'the event';

    // Display a success message
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>RSVP Confirmed | Event Management</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>
    <style>
        :root {
            --primary-color: #4e73df;
            --success-color: #1cc88a;
            --warning-color: #f6c23e;
            --info-color: #36b9cc;
            --light-color: #f8f9fa;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        
        .confirmation-card {
            width: 100%;
            max-width: 600px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
            overflow: hidden;
            text-align: center;
            padding: 2.5rem;
        }
        
        .confirmation-icon {
            font-size: 5rem;
            color: var(--success-color);
            margin-bottom: 1.5rem;
            animation: bounce 1s infinite alternate;
        }
        
        @keyframes bounce {
            from { transform: translateY(0); }
            to { transform: translateY(-10px); }
        }
        
        .confirmation-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        
        .confirmation-message {
            color: #5a5c69;
            line-height: 1.8;
            margin-bottom: 2rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.8rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            margin: 0.5rem;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.8rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            margin: 0.5rem;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        
        @media (max-width: 576px) {
            .confirmation-card {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>
    <div class='confirmation-card'>
        <div class='confirmation-icon'>
            <i class='fas fa-check-circle'></i>
        </div>
        <h1 class='confirmation-title'>You're Confirmed!</h1>
        <p class='confirmation-message'>
            Thank you for RSVP'ing to <strong>{$event_name}</strong>. We're excited to see you there!<br><br>
            You can update your RSVP status at any time if your plans change.
        </p>
        
        <div class='alert alert-success' role='alert'>
            <i class='fas fa-info-circle me-2'></i>
            Your current status: <strong>Attending</strong>
        </div>
        
        <div class='action-buttons'>
            <a href='manage_rsvps.php' class='btn btn-primary'>
                <i class='fas fa-calendar-check me-2'></i> Manage My RSVPs
            </a>
            <a href='attendee_dashboard.php' class='btn btn-outline-primary'>
                <i class='fas fa-arrow-left me-2'></i> Back to Dashboard
            </a>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>";
    exit;
} else {
    // Redirect to dashboard if no event ID is provided
    header("Location: attendee_dashboard.php");
    exit;
}
?>