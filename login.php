<?php
// Database Connection
$host = 'localhost';
$dbname = 'event_management';
$user = 'root'; // Replace with your DB username
$pass = '';     // Replace with your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle Login Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $role = htmlspecialchars($_POST['role']);

    try {
        $stmt = $pdo->prepare("SELECT * FROM register WHERE email = ? AND role = ?");
        $stmt->execute([$email, $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];

            echo "<script>
                    alert('Login successful!');
                    window.location.href = '" . ($role === 'organizer' ? 'organizor/organizer_dashboard.php' : 'Attendee/attendee_dashboard.php') . "';
                  </script>";
        } else {
            echo "<script>alert('Invalid email, password, or role!');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>

          <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Event Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('assets/bge.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .login-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 450px;
        }
        .card-header {
            background: linear-gradient(135deg, #6f42c1 0%, #4a1d96 100%);
            color: white;
            text-align: center;
            padding: 1.5rem;
            border-bottom: none;
        }
        .form-control:focus {
            border-color: #6f42c1;
            box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
        }
        .btn-purple {
            background-color: #6f42c1;
            border-color: #6f42c1;
        }
        .btn-purple:hover {
            background-color: #5a32a8;
            border-color: #5a32a8;
        }
        .nav-links {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        .forgot-password {
            text-align: right;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="card login-card mx-auto">
            <div class="card-header">
                <h2 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Login</h2>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    
                    <div class="forgot-password">
                        <a href="forgot-password.php" class="text-white">Forgot Password?</a>
                    </div>
                    
                    <div class="mb-4">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="organizer">Organizer</option>
                            <option value="attendee">Attendee</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-purple btn-lg w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                    
                    <div class="nav-links">
                        <a href="index.php" class="text-white">
                            <i class="fas fa-home me-1"></i>Homepage
                        </a>
                        <a href="register.php" class="text-white">
                            <i class="fas fa-user-plus me-1"></i>Create Account
                        </a>
                    </div>
                    <div class="btn-group">
    <a href="index.php" class="btn btn-dark btn-lg">Go to Homepage</a>
    <a href="login.php" class="btn btn-success btn-lg">Login</a>
    <a href="register.php" class="btn btn-primary btn-lg">Register</a>
</div>

                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>