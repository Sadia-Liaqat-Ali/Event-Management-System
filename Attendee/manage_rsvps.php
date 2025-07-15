<?php
session_start();
require_once '../db.php';

// Check authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'attendee') {
    header("Location: ../login.php");
    exit;
}

// Handle RSVP status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $rsvp_id = $_POST['rsvp_id'];
    $new_status = $_POST['status'];
    
    // Validate status against allowed values
    $valid_statuses = ['attending', 'maybe', 'declined'];
    if (!in_array($new_status, $valid_statuses)) {
        $_SESSION['error'] = "Invalid status selected";
        header("Location: manage_rsvps.php");
        exit;
    }
    
    try {
        // Update status in database
        $stmt = $pdo->prepare("UPDATE event_rsvps SET status = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$new_status, $rsvp_id, $_SESSION['user_id']]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = "RSVP status updated successfully";
        } else {
            $_SESSION['error'] = "No changes made or RSVP not found";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }
    
    header("Location: manage_rsvps.php");
    exit;
}

// Fetch attendee's RSVPs
try {
    $stmt = $pdo->prepare("
        SELECT er.id, e.event_name, er.attendee_name, er.status, er.created_at
        FROM event_rsvps er
        JOIN events e ON er.event_id = e.id
        WHERE er.user_id = ?
        ORDER BY er.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $rsvps = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Failed to load RSVPs: " . $e->getMessage();
    $rsvps = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage RSVPs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .status-attending {
            background-color: #d4edda;
            color: #155724;
        }
        .status-maybe {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-declined {
            background-color: #f8d7da;
            color: #721c24;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-light">
    <?php include("header.php"); ?>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Manage Your RSVPs</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <?= $_SESSION['success'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?= $_SESSION['error'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <?php if (count($rsvps) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Event</th>
                                            <th>Attendee</th>
                                            <th>Status</th>
                                            <th>RSVP Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rsvps as $rsvp): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($rsvp['event_name']) ?></td>
                                                <td><?= htmlspecialchars($rsvp['attendee_name']) ?></td>
                                                <td>
                                                    <span class="status-badge status-<?= $rsvp['status'] ?>">
                                                        <?= ucfirst($rsvp['status']) ?>
                                                    </span>
                                                </td>
                                                <td><?= date('M j, Y g:i A', strtotime($rsvp['created_at'])) ?></td>
                                                <td>
                                                    <form method="POST" class="d-flex gap-2">
                                                        <input type="hidden" name="rsvp_id" value="<?= $rsvp['id'] ?>">
                                                        <select name="status" class="form-select form-select-sm" style="width: 120px;">
                                                            <option value="attending" <?= $rsvp['status'] === 'attending' ? 'selected' : '' ?>>Attending</option>
                                                            <option value="maybe" <?= $rsvp['status'] === 'maybe' ? 'selected' : '' ?>>Maybe</option>
                                                            <option value="declined" <?= $rsvp['status'] === 'declined' ? 'selected' : '' ?>>Declined</option>
                                                        </select>
                                                        <button type="submit" name="update_status" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-save me-1"></i>Update
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                <h5>No RSVPs Found</h5>
                                <p class="text-muted">You haven't RSVP'd to any events yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>