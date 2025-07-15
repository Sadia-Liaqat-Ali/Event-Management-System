<?php
session_start();
require_once '../db.php';

// Check if the user is logged in and is an attendee
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'attendee') {
    header("Location: ../login.php");
    exit;
}

// Fetch all events from the database
$sql = "SELECT * FROM events";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Attendee Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .dashboard-header {
        background: linear-gradient(135deg, #007bff 0%, #00b4ff 100%);
        color: white;
        padding: 40px 20px;
        margin-bottom: 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .container {
        margin-top: 30px;
        max-width: 1400px;
    }
    .event-card {
        transition: all 0.3s ease;
        margin-bottom: 25px;
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
    }
    .card-img-top {
        height: 220px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .event-card:hover .card-img-top {
        transform: scale(1.03);
    }
    .card-body {
        padding: 20px;
        background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);
    }
    .card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
    }
    .card-text {
        color: #7f8c8d;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .event-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
    }
    .meta-item {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
    }
    .meta-icon {
        margin-right: 8px;
        color: #3498db;
        font-size: 1rem;
    }
    .card-footer {
        background: linear-gradient(to right, #3498db 0%, #2ecc71 100%);
        border: none;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .btn-view {
        background-color: white;
        color: #2c3e50;
        border: none;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-view:hover {
        background-color: #2c3e50;
        color: white;
        transform: translateY(-2px);
    }
    .search-container {
        background: white;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    .section-title {
        position: relative;
        margin-bottom: 30px;
        color: #2c3e50;
    }
    .section-title::after {
        content: "";
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(to right, #3498db 0%, #2ecc71 100%);
        border-radius: 3px;
    }
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    .empty-icon {
        font-size: 5rem;
        color: #bdc3c7;
        margin-bottom: 20px;
    }
  </style>
</head>
<body>

<?php include('header.php'); ?>
<div class="dashboard-header">
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (Attendee)</h1>
        <p class="lead">Your personalized dashboard to stay updated on events and notifications. Explore, participate, and make the most of every opportunity!</p>
    </div>
</div>

<div class="container my-5">
    <h1 class="section-title">Upcoming Events</h1>


<!-- Search and Filter Section -->
        <div class="search-container">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search events by name, location or description...">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" class="form-control" id="dateFilter">
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <div>
                            <button class="btn btn-sm btn-outline-primary me-2 active filter-btn" data-filter="all">All Events</button>
                            <button class="btn btn-sm btn-outline-primary me-2 filter-btn" data-filter="today">Today</button>
                            <button class="btn btn-sm btn-outline-primary me-2 filter-btn" data-filter="week">This Week</button>
                            <button class="btn btn-sm btn-outline-primary filter-btn" data-filter="month">This Month</button>
                        </div>
                        <div>
                            <select class="form-select form-select-sm" id="sortSelect" style="width: 150px;">
                                <option value="date-asc">Date: Oldest First</option>
                                <option value="date-desc" selected>Date: Newest First</option>
                                <option value="name-asc">Name: A-Z</option>
                                <option value="name-desc">Name: Z-A</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="row" id="eventsContainer">
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <div class="col-lg-4 col-md-6 mb-4 event-item"
                     data-name="<?php echo strtolower(htmlspecialchars($event['event_name'])); ?>"
                     data-date="<?php echo htmlspecialchars($event['event_date']); ?>"
                     data-location="<?php echo strtolower(htmlspecialchars($event['location'])); ?>"
                     data-description="<?php echo strtolower(htmlspecialchars($event['description'])); ?>">
                    <div class="card event-card h-100">
                        <?php
                            $imagePath = '../organizor/' . $event['image'];
                            if (!empty($event['image']) && file_exists($imagePath)):
                        ?>
                            <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($event['event_name']); ?>">
                        <?php else: ?>
                            <img src="../assets/default-event.jpg" class="card-img-top" alt="Default Event Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($event['event_name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                            <div class="event-meta">
                                <div class="meta-item">
                                    <i class="fas fa-calendar-day meta-icon"></i>
                                    <span><?php echo date('M j, Y', strtotime($event['event_date'])); ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-clock meta-icon"></i>
                                    <span><?php echo date('g:i A', strtotime($event['event_time'])); ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-map-marker-alt meta-icon"></i>
                                    <span><?php echo htmlspecialchars($event['location']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a class="btn btn-warning btn-lg" href="event_details.php?id=<?php echo $event['id']; ?>">
                                <i class="fas fa-eye me-1"></i> View Details
                            </a>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-users me-1"></i> <?php echo $event['Attendees_No'] ?? 0; ?> attending
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-calendar-times empty-icon"></i>
                    <h3>No Events Available</h3>
                    <p class="text-muted">There are currently no upcoming events. Please check back later.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const dateFilter = document.getElementById('dateFilter');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const sortSelect = document.getElementById('sortSelect');
    const eventItems = document.querySelectorAll('.event-item');

    // Search functionality
    searchInput.addEventListener('input', filterEvents);
    dateFilter.addEventListener('change', filterEvents);
    sortSelect.addEventListener('change', sortEvents);

    // Filter buttons
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filterEvents();
        });
    });

    function filterEvents() {
        const searchTerm = searchInput.value.toLowerCase();
        const dateTerm = dateFilter.value;
        const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;
        const today = new Date().toISOString().split('T')[0];

        eventItems.forEach(item => {
            const eventDate = item.dataset.date;
            const matchesSearch =
                item.dataset.name.includes(searchTerm) ||
                item.dataset.location.includes(searchTerm) ||
                item.dataset.description.includes(searchTerm);
            const matchesDate = dateTerm === '' || eventDate === dateTerm;

            let matchesFilter = true;
            const eventDateObj = new Date(eventDate);
            const todayObj = new Date();

            if (activeFilter === 'today') {
                matchesFilter = eventDate === today;
            } else if (activeFilter === 'week') {
                const nextWeek = new Date(todayObj);
                nextWeek.setDate(todayObj.getDate() + 7);
                matchesFilter = eventDateObj >= todayObj && eventDateObj <= nextWeek;
            } else if (activeFilter === 'month') {
                const nextMonth = new Date(todayObj);
                nextMonth.setMonth(todayObj.getMonth() + 1);
                matchesFilter = eventDateObj >= todayObj && eventDateObj <= nextMonth;
            }

            if (matchesSearch && matchesDate && matchesFilter) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function sortEvents() {
        const sortValue = sortSelect.value;
        const container = document.getElementById('eventsContainer');
        const items = Array.from(document.querySelectorAll('.event-item'));

        items.sort((a, b) => {
            const aName = a.dataset.name;
            const bName = b.dataset.name;
            const aDate = a.dataset.date;
            const bDate = b.dataset.date;

            switch (sortValue) {
                case 'date-asc':
                    return new Date(aDate) - new Date(bDate);
                case 'date-desc':
                    return new Date(bDate) - new Date(aDate);
                case 'name-asc':
                    return aName.localeCompare(bName);
                case 'name-desc':
                    return bName.localeCompare(aName);
                default:
                    return 0;
            }
        });

        items.forEach(item => container.appendChild(item));
    }
});
</script>


</body>
</html>
