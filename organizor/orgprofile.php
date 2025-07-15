<?php
session_start();
require_once '../db.php'; 

// Check if the user is logged in and is an organizer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.php");
    exit;
}

$message = '';

// Fetch the user's current details
$stmt = $pdo->prepare("SELECT * FROM register WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the new password

    try {
        $stmt = $pdo->prepare("UPDATE register SET username = ?, email = ?, password = ? WHERE id = ?");
        $stmt->execute([$username, $email, $hashed_password, $_SESSION['user_id']]);
        
        // Use session flash message or a redirect with an alert message
        echo "<script>
                alert('Profile has been successfully updated!');
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
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
         
            background-color: #eaf4fc;
     
            margin: 0;
            padding-top: 0; /* Space for header */
        }
        .header {
            background-color: #343a40;
            color: #fff;
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
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
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
        .btn-custom {
            width: 100%;
            background-color: #007bff;
            color: #fff;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php include("header.php"); ?>


<div class="form-container">
    <h2 class="form-title">Update Your Profile</h2>
    
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
        </div>
        <button type="submit" style="background-color: blue; color:white; padding:10px 20px; border:none; border-radius:5px;">Update Profile</button>
    </form>
</div>

</body>
</html>
