<?php
session_start();
require_once '../db.php'; 

// Check if the user is logged in and is an organizer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.php");
    exit;
}

$message = '';
$event_id = $_GET['id'] ?? null;

// Ensure event ID is valid and numeric
if ($event_id && is_numeric($event_id)) {
    // Fetch the event details
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ? AND organizer_id = ?");
    $stmt->execute([$event_id, $_SESSION['user_id']]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        $message = "Event not found!";
    }
} else {
    header("Location: organizer_dashboard.php");
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = htmlspecialchars($_POST['event_name']);
    $event_date = htmlspecialchars($_POST['event_date']);
    $event_time = htmlspecialchars($_POST['event_time']);
    $location = htmlspecialchars($_POST['location']);
    $description = htmlspecialchars($_POST['description']);
    $image = $_FILES['image'];

    try {
        // Handle image upload if a new image is provided
        if ($image['name']) {
            $image_path = 'uploads/' . time() . '_' . $image['name'];
            move_uploaded_file($image['tmp_name'], $image_path);

            // Update event details with the new image
            $stmt = $pdo->prepare("UPDATE events SET event_name = ?, event_date = ?, event_time = ?, location = ?, description = ?, image = ? WHERE id = ? AND organizer_id = ?");
            $stmt->execute([$event_name, $event_date, $event_time, $location, $description, $image_path, $event_id, $_SESSION['user_id']]);
        } else {
            // Update event details without changing the image
            $stmt = $pdo->prepare("UPDATE events SET event_name = ?, event_date = ?, event_time = ?, location = ?, description = ? WHERE id = ? AND organizer_id = ?");
            $stmt->execute([$event_name, $event_date, $event_time, $location, $description, $event_id, $_SESSION['user_id']]);
        }

        echo "<script>
                alert('Event has been successfully updated!');
                window.location.href = 'organizer_dashboard.php';
              </script>";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eaf4fc;
            margin: 0;
            padding-top: 0; /* Space for header */
        }
        .header h1 {
            margin: 0;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 20px auto; /* Center the form */
            margin-top: 120px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-label {
            font-weight: bold;
        }
        h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
            color: #FFA500; /* Light Orange Color */
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="form-container">
    <h2 class="form-title">Edit Event</h2>
    
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="event_name" class="form-label">Event Name</label>
            <input type="text" class="form-control" id="event_name" name="event_name" value="<?php echo htmlspecialchars($event['event_name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="event_date" class="form-label">Event Date</label>
            <input type="date" class="form-control" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event['event_date'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="event_time" class="form-label">Event Time</label>
            <input type="time" class="form-control" id="event_time" name="event_time" value="<?php echo htmlspecialchars($event['event_time'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($event['location'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($event['description'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="image" class="form-label">Event Image</label>
            <input type="file" class="form-control" id="image" name="image">
            <?php if (!empty($event['image'])): ?>
                <img src="<?php echo $event['image']; ?>" alt="Event Image" style="max-width: 100px; margin-top: 10px;">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update Event</button>
    </form>
</div>

</body>
</html>
