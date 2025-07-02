<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f6f8fa;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .sidebar-fixed {
            width: 230px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1030;
            background: #222c36;
            box-shadow: 2px 0 12px rgba(0,0,0,0.04);
            display: flex;
            flex-direction: column;
            padding: 0;
        }
        .sidebar-fixed h4 {
            padding: 32px 0 16px 0;
            margin: 0;
            font-weight: 700;
            letter-spacing: 1px;
            color: #fff;
            text-align: center;
            font-size: 1.3rem;
        }
        .sidebar-fixed .nav {
            flex: 1;
            padding-bottom: 24px;
        }
        .sidebar-fixed .nav-link {
            color: #cfd8dc;
            font-size: 1.05rem;
            padding: 14px 32px;
            border-radius: 0 24px 24px 0;
            margin: 2px 0;
            transition: 
                background 0.18s cubic-bezier(.4,0,.2,1),
                color 0.18s cubic-bezier(.4,0,.2,1),
                transform 0.12s cubic-bezier(.4,0,.2,1);
        }
        .sidebar-fixed .nav-link.active,
        .sidebar-fixed .nav-link:focus,
        .sidebar-fixed .nav-link:hover {
            background: #fff;
            color: #222c36;
            font-weight: 600;
            transform: translateX(6px) scale(1.04);
            box-shadow: 0 2px 8px rgba(34,44,54,0.06);
            text-decoration: none;
        }
        .sidebar-fixed .nav-link:active {
            background: #e3e7ed;
            color: #222c36;
        }
        .main-content {
            margin-left: 230px;
            width: calc(100% - 230px);
            min-height: 100vh;
            background: #f6f8fa;
            padding: 32px 24px 24px 24px;
            transition: background 0.2s;
        }
        @media (max-width: 900px) {
            .sidebar-fixed {
                width: 100vw;
                height: auto;
                position: static;
                flex-direction: row;
                box-shadow: none;
            }
            .sidebar-fixed .nav {
                flex-direction: row;
                justify-content: space-around;
                padding-bottom: 0;
            }
            .sidebar-fixed .nav-link {
                border-radius: 12px;
                margin: 4px;
                padding: 10px 18px;
            }
            .main-content {
                margin-left: 0;
                width: 100vw;
                padding: 18px 6vw 24px 6vw;
            }
        }
    </style>
</head>
<body>
    <nav class="sidebar-fixed">
        <h4>Menu</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link{{ request()->is('unit') ? ' active' : '' }}" href="/unit">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ request()->is('trucks') ? ' active' : '' }}" href="/trucks">Trucks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ request()->is('trip-histories') ? ' active' : '' }}" href="/trip-histories">Trip History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ request()->is('settings') ? ' active' : '' }}" href="/settings">Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/logout">Logout</a>
            </li>
        </ul>
    </nav>
    <div class="main-content">
        @yield('content')
    </div>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>