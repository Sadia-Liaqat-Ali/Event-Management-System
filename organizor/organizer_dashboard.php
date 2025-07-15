<?php
session_start();
require_once '../db.php';

// Check if the user is logged in and is an organizer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: ../login.php");
    exit;
}

// Fetch organizer events and their attendee count
$stmt = $pdo->prepare("
    SELECT e.*, COUNT(er.id) AS attendees_count
    FROM events e
    LEFT JOIN event_rsvps er ON e.id = er.event_id
    WHERE e.organizer_id = ?
    GROUP BY e.id
");
$stmt->execute([$_SESSION['user_id']]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch organizer profile
$stmt = $pdo->prepare("SELECT * FROM register WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle delete event
if (isset($_GET['delete'])) {
    $event_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ? AND organizer_id = ?");
    $stmt->execute([$event_id, $_SESSION['user_id']]);
    header("Location: organizer_dashboard.php");
    exit;
}

$message = ''; // Initialize message variable
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
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card img {
            border-top-left-radius: 8px;
            height: 300px;
            wid: 412px;
            border-top-right-radius: 8px;
        }
        .card {
            background-color: pink;
        }
        .card-title {
            font-weight: bold;
            font-size: 1.25rem;
        }
        .btn-action {
            width: 48%;
        }
        .dashboard-container {
            margin-top: 20px;
        }
        .btn-header:hover {
            transform: translateY(10px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.9);
        
        }
        .attendees-count {
            color: red;
            font-weight: bold;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'header.php'; ?>

    <div class="dashboard-header">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (Organizer)</h1>
        <p>Your personalized dashboard to manage and organize events effortlessly. Stay organized, and let's make your events a success!</p>
    </div>

    <div class="container dashboard-container">
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <h2 class="text-primary mb-4">Your Events</h2>
        
        <!-- Search Bar Added Here -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Search Events</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="searchName" class="form-label">Event Name</label>
                        <input type="text" class="form-control" id="searchName" placeholder="Search by event name...">
                    </div>
                    <div class="col-md-4">
                        <label for="searchDate" class="form-label">Event Date</label>
                        <input type="date" class="form-control" id="searchDate">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button id="searchBtn" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <?php foreach ($events as $event): ?>
                <div class="col-md-4 mb-4 event-card">
                    <div class="card">
                        <img src="<?php echo $event['image']; ?>" alt="Event Image">
                        <div class="card-body">
                            <h3 class="card-title event-name"><?php echo htmlspecialchars($event['event_name']); ?></h3>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($event['description']); ?></p>
                            <p><strong>Date:</strong> <span class="event-date"><?php echo htmlspecialchars($event['event_date']); ?></span></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                            <p><strong>Attendees: </strong><span class="attendees-count"><?php echo $event['Attendees_No']; ?> attendees</span></p>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-warning btn-action"><i class="fas fa-edit"></i> Edit</a>
                            <a href="organizer_dashboard.php?delete=<?php echo $event['id']; ?>" class="btn btn-danger btn-action" onclick="return confirm('Are you sure you want to delete this event?');"><i class="fas fa-trash-alt"></i> Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($events)): ?>
                <div class="alert alert-info text-center">
                    <h3 class="text-danger">You haven't created any events yet!</h3>
                    <p>Get started now and create your first event to engage your audience and make a lasting impression.</p>
                    <a href="create_event.php" class="btn-header btn-lg" style="background-color: darkmagenta; color: white; border: none; border-radius: 8px; padding: 10px 20px; font-size: 1.2rem; text-transform: uppercase; text-decoration: none;">
                        Create Your First Event
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>
    
    <!-- Search Functionality JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchBtn = document.getElementById('searchBtn');
        const nameInput = document.getElementById('searchName');
        const dateInput = document.getElementById('searchDate');
        const eventCards = document.querySelectorAll('.event-card');
        
        function filterEvents() {
            const nameTerm = nameInput.value.toLowerCase();
            const dateTerm = dateInput.value;
            
            eventCards.forEach(card => {
                const eventName = card.querySelector('.event-name').textContent.toLowerCase();
                const eventDate = card.querySelector('.event-date').textContent.trim();
                
                const nameMatch = nameTerm === '' || eventName.includes(nameTerm);
                const dateMatch = dateTerm === '' || eventDate.includes(dateTerm);
                
                card.style.display = (nameMatch && dateMatch) ? 'block' : 'none';
            });
        }
        
        searchBtn.addEventListener('click', filterEvents);
        
        // Search when pressing Enter in name field
        nameInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') filterEvents();
        });
        
        // Search when date changes
        dateInput.addEventListener('change', filterEvents);
    });
    </script>
</body>
</html>