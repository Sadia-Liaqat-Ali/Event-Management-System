<?php 
session_start();
require_once '../db.php';

// Check if the user is logged in and is an organizer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.php");
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $date = htmlspecialchars($_POST['date']);
    $time = htmlspecialchars($_POST['time']);
    $location = htmlspecialchars($_POST['location']);
    $description = htmlspecialchars($_POST['description']);
    $category = htmlspecialchars($_POST['category']);

    // Handle image upload
    $image_upload_path = null;
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $image_tmp_name = $_FILES['event_image']['tmp_name'];
        $image_name = $_FILES['event_image']['name'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($image_ext), $allowed_exts)) {
            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }

            $image_new_name = uniqid('', true) . '.' . $image_ext;
            $image_upload_path = 'uploads/' . $image_new_name;
            move_uploaded_file($image_tmp_name, $image_upload_path);
        } else {
            $message = 'Invalid image format. Please upload jpg, jpeg, png, or gif images.';
        }
    }

    try {
        // Insert event
        $stmt = $pdo->prepare("INSERT INTO events (organizer_id, event_name, event_date, event_time, location, description, category, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $name, $date, $time, $location, $description, $category, $image_upload_path]);
        $event_id = $pdo->lastInsertId();

        // Insert notification
        $notif_title = "New Event: $name";
        $notif_msg = "You’re invited to \"$name\" happening at $location on $date at $time. Don’t miss out on the experience—check details now!";
        $insertNotif = $pdo->prepare("INSERT INTO event_notifications (event_id, sender_id, notification_title, notification_message, status) VALUES (?, ?, ?, ?, 'unread')");
        $insertNotif->execute([$event_id, $_SESSION['user_id'], $notif_title, $notif_msg]);

        echo "<script>
                alert('Event has been created successfully!');
                window.location.href = 'organizer_dashboard.php';
              </script>";
        exit;
    } catch (PDOException $e) {
        $message = "Error creating event: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eaf4fc;
        }
        .container {
            margin-top: 50px;
            max-width: 700px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="container">
    <h2 class="text-center">Create a New Event</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Event Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Event Date</label>
            <input type="date" id="date" name="date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="time" class="form-label">Event Time</label>
            <input type="time" id="time" name="time" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Event Location</label>
            <input type="text" id="location" name="location" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Event Category</label>
            <select id="category" name="category" class="form-control" required>
                <option value="conference">Conference</option>
                <option value="seminar">Seminar</option>
                <option value="workshop">Workshop</option>
                <option value="festival">Festival</option>
                <option value="meetup">Meetup</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Event Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label for="event_image" class="form-label">Event Image</label>
            <input type="file" id="event_image" name="event_image" class="form-control" accept=".png, .jpg, .jpeg, .gif" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Event</button>
    </form>
</div>

</body>
</html>
