<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dhaqane World Travel & Cargo Agency</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/dhaqane_agency_system/assets/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="top-header">
            <div class="header-title">
                <h2>
                    <?php echo isset($pageInfo['title']) ? $pageInfo['title'] : 'Dashboard'; ?>
                </h2>
                <small>
                    <?php echo isset($pageInfo['subtitle']) ? $pageInfo['subtitle'] : 'Overview'; ?>
                </small>
            </div>
            <div class="user-profile">
                <span>Welcome, <b>
                        <?php echo $_SESSION['username'] ?? 'User'; ?>
                    </b></span>
                <div class="profile-icon">
                    <i class="fas fa-user-circle fa-2x"></i>
                </div>
                <a href="/dhaqane_agency_system/logout.php" class="btn btn-primary"
                    style="padding: 5px 10px; font-size: 0.8rem;">Logout</a>
            </div>
        </div>
        <div class="dashboard-container">