"<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Dashboard
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #fff;
            border-right: 1px solid #e0e0e0;
            padding: 20px;
        }

        .sidebar .logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .sidebar .logo img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .sidebar .logo span {
            font-size: 20px;
            font-weight: 700;
        }

        .sidebar .menu {
            list-style: none;
            padding: 0;
        }

        .sidebar .menu li {
            margin-bottom: 20px;
        }

        .sidebar .menu li a {
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
            font-size: 16px;
        }

        .sidebar .menu li a i {
            margin-right: 10px;
        }

        .sidebar .menu li a.active {
            background-color: #dfffe0;
            padding: 10px;
            border-radius: 5px;
            color: #333;
        }

        .sidebar .menu li a .badge {
            background-color: #ff4d4f;
            color: #fff;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 12px;
            margin-left: auto;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header .search-bar {
            display: flex;
            align-items: center;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .header .search-bar input {
            border: none;
            outline: none;
            margin-left: 10px;
            font-size: 16px;
        }

        .header .user-info {
            display: flex;
            align-items: center;
        }

        .header .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .header .user-info .name {
            font-size: 16px;
            font-weight: 600;
        }

        .header .user-info .role {
            font-size: 14px;
            color: #888;
        }

        .header .notifications {
            margin-left: 20px;
            font-size: 20px;
            color: #888;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            margin: 0 0 10px;
            font-size: 18px;
            font-weight: 600;
        }

        .card .value {
            font-size: 24px;
            font-weight: 700;
        }

        .card .subtext {
            font-size: 14px;
            color: #888;
        }

        .card .icon {
            font-size: 24px;
            color: #888;
        }

        .card .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            margin-top: 10px;
        }

        .card .badge.green {
            background-color: #dfffe0;
            color: #28a745;
        }

        .card .badge.red {
            background-color: #ffe0e0;
            color: #dc3545;
        }

        .card .badge.yellow {
            background-color: #fffbe0;
            color: #ffc107;
        }

        .card .chart {
            height: 200px;
            background-color: #f0f0f0;
            border-radius: 10px;
            margin-top: 20px;
        }

        .card .progress-bar {
            height: 10px;
            background-color: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 10px;
        }

        .card .progress-bar .progress {
            height: 100%;
            border-radius: 5px;
        }

        .card .progress-bar .progress.green {
            background-color: #28a745;
        }

        .card .progress-bar .progress.yellow {
            background-color: #ffc107;
        }

        .card .progress-bar .progress.red {
            background-color: #dc3545;
        }

        .tasks {
            display: flex;
            flex-direction: column;
        }

        .tasks .task {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .tasks .task .date {
            font-size: 14px;
            color: #888;
            margin-right: 10px;
        }

        .tasks .task .description {
            font-size: 16px;
            flex: 1;
        }

        .tasks .task .icon {
            font-size: 20px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <img alt="Logo" height="30" src="https://storage.googleapis.com/a1aa/image/NpxyVAFMPfxfME3OZ07GP1NTEjjXnkNEBbQN3exrxBg4vLEoA.jpg" width="30" />
                <span>
                    Lodgify
                </span>
            </div>
            <ul class="menu">
                <li>
                    <a class="active" href="#">
                        <i class="fas fa-tachometer-alt">
                        </i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-calendar-alt">
                        </i>
                        Reservation
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-bed">
                        </i>
                        Rooms
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-envelope">
                        </i>
                        Messages
                        <span class="badge">
                            7
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-broom">
                        </i>
                        Housekeeping
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-box">
                        </i>
                        Inventory
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-calendar">
                        </i>
                        Calendar
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-file-invoice-dollar">
                        </i>
                        Financials
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-star">
                        </i>
                        Reviews
                    </a>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <div class="search-bar">
                    <i class="fas fa-search">
                    </i>
                    <input placeholder="Search room, guest, book, etc" type="text" />
                </div>
                <div class="user-info">
                    <img alt="User" height="40" src="https://storage.googleapis.com/a1aa/image/Zb3pOVEz3KqlEBk3K0Mg7yezD9KEQ4QZCalrEi74WVz97CBKA.jpg" width="40" />
                    <div>
                        <div class="name">
                            Jaylon Dorwart
                        </div>
                        <div class="role">
                            Admin
                        </div>
                    </div>
                    <div class="notifications">
                        <i class="fas fa-bell">
                        </i>
                    </div>
                </div>
            </div>
            <div class="dashboard">
                <div class="card">
                    <h3>
                        User
                    </h3>
                    <div class="value">
                        840
                    </div>
                    <div class="subtext">
                        from last week
                    </div>
                    <div class="badge green">
                        8.70%
                    </div>
                </div>
                <div class="card">
                    <h3>
                        Booking
                    </h3>
                    <div class="value">
                        231
                    </div>
                    <div class="subtext">
                        from last week
                    </div>
                    <div class="badge yellow">
                        3.56%
                    </div>
                </div>
                <div class="card">
                    <h3>
                        Room
                    </h3>
                    <div class="value">
                        124
                    </div>
                    <div class="subtext">
                        from last week
                    </div>
                    <div class="badge red">
                        1.06%
                    </div>
                </div>
                <div class="card">
                    <h3>
                        Total Revenue
                    </h3>
                    <div class="value">
                        $123,980
                    </div>
                    <div class="subtext">
                        from last week
                    </div>
                    <div class="badge green">
                        5.70%
                    </div>
                </div>
                <div class="card">
                    <h3>
                        Overall Rating
                    </h3>
                    <div class="value">
                        4.6/5
                    </div>
                    <div class="subtext">
                        Impressive from 2546 reviews
                    </div>
                    <div class="progress-bar">
                        <div class="progress green" style="width: 88%;">
                        </div>
                    </div>
                    <div class="subtext">
                        Facilities 4.4
                    </div>
                    <div class="progress-bar">
                        <div class="progress green" style="width: 94%;">
                        </div>
                    </div>
                    <div class="subtext">
                        Cleanliness 4.7
                    </div>
                    <div class="progress-bar">
                        <div class="progress green" style="width: 92%;">
                        </div>
                    </div>
                    <div class="subtext">
                        Services 4.6
                    </div>
                    <div class="progress-bar">
                        <div class="progress green" style="width: 96%;">
                        </div>
                    </div>
                    <div class="subtext">
                        Comfort 4.8
                    </div>
                    <div class="progress-bar">
                        <div class="progress green" style="width: 90%;">
                        </div>
                    </div>
                    <div class="subtext">
                        Location 4.5
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>