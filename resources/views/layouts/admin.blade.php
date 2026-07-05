<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - Dasmariñas Tourism</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- CRITICAL: Add base overflow styles -->
    <style>
        html,
        body {
            overflow-x: auto !important;
            /* Changed from hidden to auto */
            overflow-y: auto;
            height: 100%;
            width: 100%;
        }

        .admin-wrapper {
            overflow: visible;
            width: 100%;
        }

        .admin-main {
            overflow: visible;
            width: 100%;
        }

        .admin-content {
            overflow: visible;
            min-height: calc(100vh - 70px);
            width: 100%;
            padding: 1.5rem;
        }

        /* Ensure containers don't restrict width */
        .container-fluid {
            max-width: none !important;
            overflow: visible;
        }

        /* Allow horizontal scroll on smaller viewports */
        @media (max-width: 1400px) {
            .admin-content {
                min-width: fit-content;
            }
        }

        /* ========================================
           ADMIN PAGINATION STYLES (GLOBAL)
           ======================================== */
        .admin-pagination-nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            padding: 0;
        }

        @media (min-width: 640px) {
            .admin-pagination-nav {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .admin-pagination-info {
            color: #64748b;
            font-size: 0.875rem;
        }

        .admin-pagination-info p {
            margin: 0;
        }

        .admin-pagination-info .font-semibold {
            font-weight: 600;
            color: #1e293b;
        }

        .admin-pagination {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
            justify-content: center;
        }

        .admin-page-item {
            display: inline-flex;
        }

        .admin-page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #475569;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .admin-page-link:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .admin-page-item.active .admin-page-link {
            background: linear-gradient(135deg, #1a7838, #27a345);
            border-color: #1a7838;
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(26, 120, 56, 0.25);
        }

        .admin-page-item.disabled .admin-page-link {
            color: #cbd5e1;
            background: #f8fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
            pointer-events: none;
        }

        .admin-page-link i {
            font-size: 1.125rem;
        }

        /* Pagination wrapper for table card footers */
        .admin-table-card-footer {
            padding: 1.25rem 1.5rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .admin-pagination-wrapper {
            display: flex;
            justify-content: center;
        }

        /* Hide any default Laravel/Tailwind pagination styles that might conflict */
        .admin-pagination-nav nav,
        .admin-table-card-footer nav {
            all: unset;
        }

        .admin-pagination-nav .flex,
        .admin-table-card-footer .flex {
            display: flex;
        }

        /* Override any Tailwind pagination within admin pages */
        .admin-content nav[role="navigation"] {
            display: block;
        }

        .admin-content nav[role="navigation"]>div {
            display: none;
        }

        .admin-content nav[role="navigation"].admin-pagination-nav>div {
            display: block;
        }

        .admin-content nav[role="navigation"].admin-pagination-nav {
            display: flex;
        }
    </style>


    @stack('styles')
</head>

<body class="admin-layout">
    <!-- Mobile Overlay -->
    <div class="admin-sidebar-overlay" id="adminSidebarOverlay"></div>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-header">
                <div class="admin-logo">
                    <i class='bx bx-shield-quarter'></i>
                    <span>Admin Panel</span>
                </div>
                <button class="admin-sidebar-close" id="adminSidebarClose">
                    <i class='bx bx-x'></i>
                </button>
            </div>

            <nav class="admin-nav">
                <div class="admin-nav-section">
                    <span class="admin-nav-section-title">Main</span>
                    <a href="{{ route('admin.dashboard') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class='bx bxs-dashboard'></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="admin-nav-section">
                    <span class="admin-nav-section-title">Tourism</span>
                    <a href="{{ route('admin.attractions.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.attractions.*') ? 'active' : '' }}">
                        <i class='bx bx-map-pin'></i>
                        <span>Attractions</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class='bx bx-category'></i>
                        <span>Categories</span>
                    </a>
                    <a href="{{ route('admin.story-chapters.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.story-chapters.*') ? 'active' : '' }}">
                        <i class='bx bx-book-reader'></i>
                        <span>Story Mode</span>
                    </a>
                    <a href="{{ route('admin.hero-slides.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.hero-slides.*') ? 'active' : '' }}">
                        <i class='bx bx-image'></i>
                        <span>Hero Carousel</span>
                    </a>
                </div>

                <div class="admin-nav-section">
                    <span class="admin-nav-section-title">Business</span>
                    <a href="{{ route('admin.businesses.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.businesses.*') ? 'active' : '' }}">
                        <i class='bx bx-store'></i>
                        <span>Businesses</span>
                    </a>
                    <a href="{{ route('admin.business-categories.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.business-categories.*') ? 'active' : '' }}">
                        <i class='bx bx-category-alt'></i>
                        <span>Business Categories</span>
                    </a>
                    <a href="{{ route('admin.promotions.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                        <i class='bx bx-badge-check'></i>
                        <span>Promotions</span>
                    </a>
                </div>

                <div class="admin-nav-section">
                    <span class="admin-nav-section-title">Community</span>
                    <a href="{{ route('admin.reviews.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                        <i class='bx bx-message-square-detail'></i>
                        <span>Reviews</span>
                    </a>
                    <a href="{{ route('admin.suggestions.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.suggestions.*') ? 'active' : '' }}">
                        <i class='bx bx-bulb'></i>
                        <span>Suggestions</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class='bx bx-user'></i>
                        <span>Users</span>
                    </a>
                    <!-- Announcements Link Added Here -->
                    <a href="{{ route('admin.announcements.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                        <i class='bx bxs-megaphone'></i>
                        <span>Announcements</span>
                    </a>
                </div>


                <div class="admin-nav-section">
                    <span class="admin-nav-section-title">Analytics</span>
                    <a href="{{ route('admin.reports.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class='bx bx-bar-chart'></i>
                        <span>Reports</span>
                    </a>
                    <a href="{{ route('admin.activity-logs.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                        <i class='bx bx-history'></i>
                        <span>Activity Logs</span>
                    </a>
                </div>
            </nav>

            <div class="admin-sidebar-footer">
                <a href="{{ route('home') }}" class="admin-nav-link">
                    <i class='bx bx-home'></i>
                    <span>Back to Website</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="admin-nav-link admin-nav-logout">
                        <i class='bx bx-log-out'></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Bar -->
            <header class="admin-topbar">
                <div class="admin-topbar-content">
                    <div class="admin-topbar-left">
                        <button class="admin-menu-toggle" id="adminMenuToggle">
                            <i class='bx bx-menu'></i>
                        </button>
                        <h5 class="admin-topbar-title">@yield('page-title')</h5>
                    </div>
                    <div class="admin-user-info">
                        <span class="admin-user-name">{{ Auth::user()->name }}</span>
                        <div class="admin-user-avatar">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if (session('success'))
                <div data-success="{{ session('success') }}"></div>
            @endif
            @if (session('error'))
                <div data-error="{{ session('error') }}"></div>
            @endif

            <!-- Page Content -->
            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('adminMenuToggle');
            const sidebarClose = document.getElementById('adminSidebarClose');
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('adminSidebarOverlay');

            function openSidebar() {
                sidebar.classList.add('mobile-open');
                overlay.classList.add('active');
                // Don't prevent body scroll on desktop
                if (window.innerWidth <= 768) {
                    document.body.style.overflow = 'hidden';
                }
            }

            function closeSidebar() {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (menuToggle) {
                menuToggle.addEventListener('click', openSidebar);
            }

            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar on link click (mobile only)
            if (window.innerWidth <= 768) {
                const navLinks = document.querySelectorAll('.admin-nav-link');
                navLinks.forEach(link => {
                    link.addEventListener('click', closeSidebar);
                });
            }
        });
    </script>

    <!-- SweetAlert Flash Messages -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Success messages
            const successElement = document.querySelector('[data-success]');
            if (successElement) {
                const message = successElement.getAttribute('data-success');
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }

            // Error messages
            const errorElement = document.querySelector('[data-error]');
            if (errorElement) {
                const message = errorElement.getAttribute('data-error');
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message,
                    confirmButtonColor: '#1a7838'
                });
            }
        });
    </script>

    @stack('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

</body>

</html>
