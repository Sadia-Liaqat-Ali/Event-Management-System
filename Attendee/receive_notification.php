<?php
session_start();
require_once '../db.php';

// Check authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'attendee') {
    header("Location: ../login.php");
    exit;
}

// Fetch notifications
try {
    $stmt = $pdo->prepare("
        SELECT n.notification_title, n.notification_message, e.event_name, n.created_at
        FROM event_notifications n
        JOIN events e ON n.event_id = e.id
        WHERE n.status = 'sent'
        ORDER BY n.created_at DESC
        LIMIT 5
    ");
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $notifications = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --success-color: #1cc88a;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fa;
            --dark-color: #5a5c69;
        }
        
        body {
            background-color: var(--secondary-color);
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
          
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border-radius: 0.35rem 0.35rem 0 0 !important;
        }
        
    
        
        .notification-card {
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }
        
        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .event-date {
            color: var(--dark-color);
            font-weight: 600;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 10%, #224abe 100%);
        }
    </style>
</head>
<body>
    <?php include("header.php"); ?>

    <div class="container-fluid py-4">
        <div class="row">
            <!-- Sidebar -->

                  <div class="d-flex justify-content-center align-items-start" style="min-height: 100vh;">
    <div class="card my-0" style="max-width: 500px; width: 100%;">
        <div class="card-header">
            <i class="fas fa-bell me-2"></i>Recent Notifications
        </div>
        <div class="card-body">
            <?php if (count($notifications) > 0): ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="list-group-item notification-card mb-2">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 text-primary"><?= htmlspecialchars($notification['notification_title']) ?></h6>
                                <small><?= date('M j', strtotime($notification['created_at'])) ?></small>
                            </div>
                            <p class="mb-1"><?= htmlspecialchars($notification['notification_message']) ?></p>
                            <small class="text-muted">Event: <?= htmlspecialchars($notification['event_name']) ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-3">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No new notifications</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enable tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>