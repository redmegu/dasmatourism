<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="Explore Dasmariñas City's historical sites, tourist destinations, and local businesses through our interactive digital tourism platform.">
    <meta name="keywords" content="Dasmariñas, Tourism, Cavite, Philippines, Tourist Attractions, Historical Sites">
    <meta name="author" content="Dasmariñas City Tourism Office">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', 'Dasmariñas Tourism Information System')">
    <meta property="og:description"
        content="Discover the beauty of Dasmariñas City through our interactive tourism platform.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/dasma-logo.png') }}">

    <title>@yield('title', 'Dasmariñas Tourism Information System')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/dasma-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/dasma-logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
        
    

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ========================================
           USER PAGINATION STYLES (GLOBAL)
           ======================================== */
        .user-pagination-nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            padding: 0;
        }

        @media (min-width: 640px) {
            .user-pagination-nav {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .user-pagination-info {
            color: #64748b;
            font-size: 0.875rem;
        }

        .user-pagination-info p {
            margin: 0;
        }

        .user-pagination-info .font-semibold {
            font-weight: 600;
            color: #1e293b;
        }

        .user-pagination {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
            justify-content: center;
        }

        .user-page-item {
            display: inline-flex;
        }

        .user-page-link {
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

        .user-page-link:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .user-page-item.active .user-page-link {
            background: linear-gradient(135deg, #1a7838, #27a345);
            border-color: #1a7838;
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(26, 120, 56, 0.25);
        }

        .user-page-item.disabled .user-page-link {
            color: #cbd5e1;
            background: #f8fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
            pointer-events: none;
        }

        .user-page-link i {
            font-size: 1.125rem;
        }
    </style>

    @stack('styles')
    
    <script>
        // Define the function globally before any links can call it
        window.showPolicyModal = function(type) {
            const titleElement = document.getElementById('policyModalTitle');
            const bodyContainer = document.getElementById('policyModalBody');
            
            // Re-query the modal instance inside the function to avoid null errors on repeated open/close
            const modalElement = document.getElementById('policyModal');
            let policyModalInstance = null;
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                 // Use getInstance or create new one if needed, but for simplicity, recreate instance if null
                 policyModalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
            }
            
            let contentSourceElement;
            let titleText;

            // 1. Identify which content source element and title to use
            if (type === 'privacy') {
                contentSourceElement = document.getElementById('privacyContent');
                titleText = 'Privacy Policy';
            } else if (type === 'terms') {
                contentSourceElement = document.getElementById('termsContent');
                titleText = 'Terms of Use';
            } else if (type === 'faq') {
                contentSourceElement = document.getElementById('faqContent');
                titleText = 'FAQ & Support';
            } else {
                return;
            }
            
            // 2. Set the Title and Inject the content 
            if (contentSourceElement && bodyContainer) {
                titleElement.textContent = titleText;
                // Copy the inner HTML (the fully rendered policy content)
                bodyContainer.innerHTML = contentSourceElement.innerHTML; 
            } else {
                // Fallback for missing elements
                titleElement.textContent = 'Error Loading Content';
                bodyContainer.innerHTML = '<div class="alert alert-danger">Policy content failed to load (Source element missing).</div>';
            }

            // 3. Open the modal 
            if (policyModalInstance) {
                policyModalInstance.show();
            } else {
                 console.error("Bootstrap Modal Instance not initialized.");
            }
        }
    </script>
    
    
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('assets/dasma-logo.png') }}" alt="Dasmariñas City" class="navbar-logo">
                <span>Dasmariñas Tourism</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class='bx bx-home-alt'></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('attractions.*') ? 'active' : '' }}"
                            href="{{ route('attractions.index') }}">
                            <i class='bx bx-map-pin'></i> Attractions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('businesses.*') ? 'active' : '' }}"
                            href="{{ route('businesses.index') }}">
                            <i class='bx bx-store'></i> Businesses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('map.*') ? 'active' : '' }}"
                            href="{{ route('map.index') }}">
                            <i class='bx bx-map'></i> Map
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('story-mode.*') ? 'active' : '' }}"
                            href="{{ route('story-mode.index') }}">
                            <i class='bx bx-book-reader'></i> Story Mode
                        </a>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class='bx bx-log-in'></i> Login
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                            <a class="btn btn-primary btn-mobile-nav" href="{{ route('register') }}">
                                <i class='bx bx-user-plus'></i> Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown ms-lg-2">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class='bx bx-user-circle'></i>
                                <span class="d-none d-lg-inline">{{ Str::limit(Auth::user()->name, 15) }}</span>
                                <span class="d-lg-none">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (Auth::user()->isAdministrator())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class='bx bx-dashboard'></i> Admin Dashboard
                                        </a>
                                    </li>
                                @elseif(Auth::user()->isBusinessOwner())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('business-owner.dashboard') }}">
                                            <i class='bx bx-dashboard'></i> Business Dashboard
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.profile.show') }}">
                                        <i class='bx bx-user'></i> Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.suggestions.index') }}">
                                        <i class='bx bx-bulb'></i> My Suggestions
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class='bx bx-log-out'></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages (Hidden elements for SweetAlert) -->
    @if (session('success'))
        <div data-success="{{ session('success') }}" style="display: none;"></div>
    @endif
    @if (session('error'))
        <div data-error="{{ session('error') }}" style="display: none;"></div>
    @endif
    @if (session('info'))
        <div data-info="{{ session('info') }}" style="display: none;"></div>
    @endif
    @if (session('warning'))
        <div data-warning="{{ session('warning') }}" style="display: none;"></div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Enhanced Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <!-- About Section -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand mb-4">
                        <img src="{{ asset('assets/dasma-logo.png') }}" alt="Dasmariñas" class="footer-logo">
                        <h5 class="mb-0">Dasmariñas Tourism</h5>
                    </div>
                    <p class="footer-text">
                        Explore the rich history and vibrant culture of Dasmariñas City through our digital tourism
                        platform.
                    </p>
                    <div class="footer-badge">
                        <i class='bx bx-map'></i>
                        <span>Province of Cavite, Philippines</span>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="footer-title">Quick Links</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('attractions.index') }}"><i class='bx bx-chevron-right'></i>
                                Attractions</a></li>
                        <li><a href="{{ route('businesses.index') }}"><i class='bx bx-chevron-right'></i>
                                Businesses</a></li>
                        <li><a href="{{ route('map.index') }}"><i class='bx bx-chevron-right'></i> Interactive
                                Map</a></li>
                        <li><a href="{{ route('story-mode.index') }}"><i class='bx bx-chevron-right'></i> Story
                                Mode</a></li>
                        <li><a href="#" onclick="showPolicyModal('privacy')" 
                           data-bs-toggle="modal" data-bs-target="#policyModal"><i class='bx bx-chevron-right'></i>Privacy Policy</a></li>
                        <li><a href="#" onclick="showPolicyModal('terms')" 
                           data-bs-toggle="modal" data-bs-target="#policyModal"><i class='bx bx-chevron-right'></i>Terms of Use</a></li>
                        <li>
                            <a href="#" onclick="showPolicyModal('faq')" 
                               data-bs-toggle="modal" data-bs-target="#policyModal">
                               <i class='bx bx-chevron-right'></i> FAQ & Support
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- For Visitors -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-title">For Visitors</h6>
                    <ul class="footer-links">
                        @auth
                            <li><a href="{{ route('user.profile.show') }}"><i class='bx bx-chevron-right'></i> My
                                    Profile</a></li>
                            <li><a href="{{ route('user.suggestions.create') }}"><i class='bx bx-chevron-right'></i>
                                    Suggest a Place</a></li>
                        @else
                            <li><a href="{{ route('login') }}"><i class='bx bx-chevron-right'></i> Login</a></li>
                            <li><a href="{{ route('register') }}"><i class='bx bx-chevron-right'></i> Create Account</a>
                            </li>
                        @endauth
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-title">Contact Us</h6>
                    <ul class="footer-contact">
                        <li>
                            <i class='bx bx-map'></i>
                            <span>Dasmariñas City, Cavite</span>
                        </li>
                        <li>
                            <i class='bx bx-envelope'></i>
                            <a href="mailto:tourism@dasmarinas.gov.ph">tourism@dasmarinas.gov.ph</a>
                        </li>
                        <li>
                            <i class='bx bx-phone'></i>
                            <a href="tel:+6346416093">(046) 416-0931</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <hr class="footer-divider">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="footer-copyright mb-0">
                        &copy; {{ date('Y') }} Dasmariñas Tourism Information System. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="footer-made-with mb-0">
                        Made with <i class='bx bxs-heart'></i> for Dasmariñas City
                    </p>
                </div>
            </div>
        </div>
    </footer>
    {{-- ⬅️ Policy Modal Structure --}}
    <div class="modal fade" id="policyModal" tabindex="-1" aria-labelledby="policyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header bg-primary text-white" style="border-radius: 12px 12px 0 0;">
                    <h5 class="modal-title fw-bold" id="policyModalTitle"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="policyModalBody" style="line-height: 1.7; color: #475569;">
                        <!-- Content will be injected here by JS -->
                    </div>
                    
                    {{-- ⬅️ Hidden Content Includes (CRUCIAL: Includes FAQ) --}}
                    <div id="privacyContent" class="d-none"> 
                        @include('policy_content.privacy_policy')
                    </div>
                    <div id="termsContent" class="d-none"> 
                        @include('policy_content.terms_of_use')
                    </div>
                    <div id="faqContent" class="d-none"> 
                        @include('policy_content.faq')
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    @stack('scripts')
    
    {{-- ⬅️ Global JavaScript for Modal and Utility --}}
    <script>
        // Store modal instance globally (Bootstrap 5)
        let policyModalInstance = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap modal instance once
            const modalElement = document.getElementById('policyModal');
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                 policyModalInstance = new bootstrap.Modal(modalElement);
            }
        });


        

    <!-- Scroll to Top Button -->
    <button id="scrollTopBtn" class="scroll-top" style="display: none;" aria-label="Scroll to top">
        <i class='bx bx-chevron-up'></i>
    </button>

    @stack('scripts')

    <!-- Scroll to Top Script -->
    <script>
        // Scroll to Top Button
        const scrollTopBtn = document.getElementById('scrollTopBtn');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollTopBtn.style.display = 'flex';
            } else {
                scrollTopBtn.style.display = 'none';
            }
        });

        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
    
</body>

</html>
