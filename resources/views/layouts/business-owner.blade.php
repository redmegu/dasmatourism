<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Business Dashboard') - Dasmariñas Tourism</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/dasma-logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        .nav-link-disabled {
            opacity: 0.45;
            cursor: not-allowed;
            pointer-events: none;
        }

        .nav-link-disabled .lock-note {
            font-size: 0.75rem;
            color: #fbbf24;
            margin-left: 2.2rem;
        }
        /* Loading overlay */
        .page-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .page-loading.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f1f5f9;
            border-top-color: var(--dasma-green);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* User dropdown */
        .user-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            width: 240px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .user-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-header {
            padding: 1.25rem;
            background: linear-gradient(135deg, var(--dasma-green), var(--dasma-blue));
            color: white;
        }

        .user-dropdown-name {
            font-weight: 700;
            font-size: 1rem;
            margin: 0 0 0.25rem 0;
        }

        .user-dropdown-email {
            font-size: 0.8125rem;
            opacity: 0.9;
            margin: 0;
        }

        .user-dropdown-menu {
            padding: 0.5rem;
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            color: var(--dark-color);
            text-decoration: none;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.9375rem;
        }

        .user-dropdown-item:hover {
            background: #f8fafc;
            color: var(--dasma-green);
        }

        .user-dropdown-item i {
            font-size: 1.25rem;
            width: 24px;
        }

        .user-dropdown-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 0.5rem 0;
        }

        .user-dropdown-item.logout {
            color: #ef4444;
        }

        .user-dropdown-item.logout:hover {
            background: rgba(239, 68, 68, 0.1);
        }
    </style>
</head>

<body class="business-owner-body">
    <!-- Page Loading -->
    <div class="page-loading" id="pageLoading">
        <div class="spinner"></div>
    </div>

    <div class="business-owner-layout">
        
        <!-- Enhanced Sidebar -->
        <aside class="business-sidebar" id="businessSidebar">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <div class="brand-icon">
                        <i class='bx bx-store'></i>
                    </div>
                    <div class="brand-text">
                        <h4>Business Panel</h4>
                        <p>Dasmariñas Tourism</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <span class="nav-section-title">Main Menu</span>

                    <a href="{{ route('business-owner.dashboard') }}"
                        class="nav-link {{ request()->routeIs('business-owner.dashboard') ? 'active' : '' }}">
                        <div class="nav-link-icon">
                            <i class='bx bxs-dashboard'></i>
                        </div>
                        <span>Dashboard</span>
                        @if (request()->routeIs('business-owner.dashboard'))
                            <div class="nav-link-indicator"></div>
                        @endif
                    </a>

                    <a href="{{ route('business-owner.profile.show') }}"
                        class="nav-link {{ request()->routeIs('business-owner.profile.*') ? 'active' : '' }}">
                        <div class="nav-link-icon">
                            <i class='bx bxs-store-alt'></i>
                        </div>
                        <span>My Business</span>
                        @if (request()->routeIs('business-owner.profile.*'))
                            <div class="nav-link-indicator"></div>
                        @endif
                    </a>

                    @php
                        $business = auth()->user()->business;
                        $isApproved = $business && $business->status === 'approved';
                    @endphp
                    
                    @if ($isApproved)
                        <a href="{{ route('business-owner.promotions.index') }}"
                            class="nav-link {{ request()->routeIs('business-owner.promotions.*') ? 'active' : '' }}">
                            <div class="nav-link-icon">
                                <i class='bx bxs-badge-check'></i>
                            </div>
                            <span>Promotions</span>
                            @if (request()->routeIs('business-owner.promotions.*'))
                                <div class="nav-link-indicator"></div>
                            @endif
                        </a>
                    @else
                        <div class="nav-link nav-link-disabled">
                            <div class="nav-link-icon">
                                <i class='bx bx-lock'></i>
                            </div>
                            <span>Promotions</span>
                            <div class="lock-note">
                                Awaiting admin approval
                            </div>
                        </div>
                    @endif

                    </a>

                    <a href="{{ route('business-owner.analytics') }}"
                        class="nav-link {{ request()->routeIs('business-owner.analytics') ? 'active' : '' }}">
                        <div class="nav-link-icon">
                            <i class='bx bxs-bar-chart-alt-2'></i>
                        </div>
                        <span>Analytics</span>
                        @if (request()->routeIs('business-owner.analytics'))
                            <div class="nav-link-indicator"></div>
                        @endif
                    </a>
                </div>

                <div class="nav-section">
                    <span class="nav-section-title">Quick Links</span>

                    <a href="{{ route('home') }}" class="nav-link nav-link-secondary">
                        <div class="nav-link-icon">
                            <i class='bx bx-home-alt'></i>
                        </div>
                        <span>Back to Website</span>
                    </a>
                </div>

                <!-- User Section -->
                <div class="sidebar-user">
                    <div class="user-info">
                        <div class="user-avatar">
                            @if (Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                    alt="{{ Auth::user()->name }}">
                            @else
                                <i class='bx bx-user'></i>
                            @endif
                        </div>
                        <div class="user-details">
                            <h6>{{ Str::limit(Auth::user()->name, 18) }}</h6>
                            <p>Business Owner</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="logout-form" id="logoutForm">
                        @csrf
                        <button type="button" class="logout-btn" title="Logout" onclick="confirmLogout()">
                            <i class='bx bx-log-out'></i>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="business-main">
            <!-- Top Navigation Bar -->
            <header class="business-topbar">
                <div class="topbar-content">
                    <div class="topbar-left">
                        <button class="mobile-menu-toggle" id="mobileMenuToggle">
                            <i class='bx bx-menu'></i>
                        </button>
                        <h1 class="page-title">@yield('page-title')</h1>
                    </div>
                    <div class="topbar-right">
                        <!-- User Profile -->
                        <div style="position: relative;">
                            <div class="topbar-user" id="userProfileBtn">
                                <span class="user-name d-none d-md-inline">{{ Auth::user()->name }}</span>
                                <div class="user-avatar-small">
                                    @if (Auth::user()->profile_picture)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                            alt="{{ Auth::user()->name }}">
                                    @else
                                        <i class='bx bx-user'></i>
                                    @endif
                                </div>
                            </div>
                            <div class="user-dropdown" id="userDropdown">
                                <div class="user-dropdown-header">
                                    <p class="user-dropdown-name">{{ Auth::user()->name }}</p>
                                    <p class="user-dropdown-email">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="user-dropdown-menu">
                                    <a href="{{ route('user.profile.show') }}" class="user-dropdown-item">
                                        <i class='bx bx-user'></i>
                                        <span>My Profile</span>
                                    </a>
                                    <a href="{{ route('business-owner.profile.show') }}" class="user-dropdown-item">
                                        <i class='bx bx-store'></i>
                                        <span>Business Profile</span>
                                    </a>
                                    <div class="user-dropdown-divider"></div>
                                    <a href="#" class="user-dropdown-item logout"
                                        onclick="confirmLogout(); return false;">
                                        <i class='bx bx-log-out'></i>
                                        <span>Logout</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="business-content">
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="business-footer">
                <div class="footer-content">
                    <p>&copy; {{ date('Y') }} Dasmariñas Tourism. All rights reserved.</p>
                    <div class="footer-links">
                        <a href="#" onclick="showHelp(); return false;">Help</a>
                        <a href="#">Privacy</a>
                        <a href="#">Terms</a>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    @stack('scripts')

    <!-- Enhanced Scripts -->
    <script>
        // Page loading
        window.addEventListener('load', function() {
            document.getElementById('pageLoading').classList.add('hidden');
        });

        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('businessSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleMobileMenu() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            document.body.classList.toggle('sidebar-open');
        }

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', toggleMobileMenu);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleMobileMenu);
        }

        // Close sidebar when clicking nav link on mobile
        const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    toggleMobileMenu();
                }
            });
        });

        // User dropdown
        const userProfileBtn = document.getElementById('userProfileBtn');
        const userDropdown = document.getElementById('userDropdown');

        userProfileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            userDropdown.classList.remove('show');
        });

        // Logout confirmation
        function confirmLogout() {
            Swal.fire({
                title: 'Logout Confirmation',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--dasma-green)',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }

        // Help function
        function showHelp() {
            Swal.fire({
                title: 'Need Help?',
                html: `
                    <p>Contact our support team:</p>
                    <p><strong>Email:</strong> support@dasmatourism.com</p>
                    <p><strong>Phone:</strong> (046) 123-4567</p>
                `,
                icon: 'info',
                confirmButtonColor: 'var(--dasma-green)',
                confirmButtonText: 'Got it!'
            });
        }

        // Flash messages with SweetAlert
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: 'var(--dasma-green)'
            });
        @endif

        @if (session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Information',
                text: '{{ session('info') }}',
                confirmButtonColor: 'var(--dasma-green)'
            });
        @endif
    </script>
</body>

</html>
