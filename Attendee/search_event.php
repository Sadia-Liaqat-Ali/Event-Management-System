<?php
session_start();
require_once '../db.php';

// Check if the user is logged in and is an attendee
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'attendee') {
    header("Location: login.php");
    exit;
}

// Initialize variables
$events = [];
$message = '';

// Handle search functionality
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchQuery = trim($_GET['search']);

    // Validate the search query as a date (YYYY-MM-DD)
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $searchQuery)) {
        $stmt = $pdo->prepare("SELECT * FROM events WHERE event_date = ?");
        $stmt->execute([$searchQuery]);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$events) {
            $message = 'No events found for the given date.';
        }
    } else {
        $message = 'Invalid date format. Please use YYYY-MM-DD.';
    }
} elseif (isset($_GET['search']) && empty(trim($_GET['search']))) {
    $message = 'Search query is empty.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .search-bar {
            margin-bottom: 30px;
        }
        .message {
            margin-bottom: 20px;
            color: red;
            font-weight: bold;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card img {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .card-title {
            font-weight: bold;
            font-size: 1.25rem;
        }
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
        <?php include 'header.php'; ?>

    <div class="container">
        <h1 class="text-center text-primary">Search Events</h1>
        
        <!-- Guideline Message -->
        <div class="alert alert-info">
            <strong>Note:</strong> Please enter the event date in the format YYYY-MM-DD to search for available events.
        </div>

        <!-- Search Form -->
        <form class="search-bar" method="GET" action="">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by event date (YYYY-MM-DD)" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <!-- Display Message if Search Fails -->
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- Display Events if Search is Successful -->
        <?php if ($events): ?>
            <div class="row">
                <?php foreach ($events as $event): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="uploads/<?php echo htmlspecialchars($event['image'] ?: 'default.png'); ?>" alt="Event Image" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($event['event_name']); ?></h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars($event['description']); ?></p>
                                <p><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
                                <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>
</body>
</html>
