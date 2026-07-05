@extends('layouts.app')

@section('title', 'Interactive Map - Dasmariñas Tourism')

@push('styles')
    <style>
        /* Map container styles */
        .map-container {
            position: relative;
            width: 100%;
            height: 700px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            background-color: #2d6a4f;
            cursor: grab;
        }

        .map-container.dragging {
            cursor: grabbing;
        }

        .map-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            transform-origin: 0 0;
            transition: transform 0.1s ease-out;
        }

        .map-image {
            display: block;
            width: 100%;
            height: auto;
            min-width: 1000px;
            max-width: none;
            user-select: none;
            -webkit-user-drag: none;
        }

        .map-marker {
            position: absolute;
            transform: translate(-50%, -100%);
            cursor: pointer;
            z-index: 10;
            transition: filter 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 0;
        }

        .map-marker i {
            font-size: 2.5rem;
            color: #1a7838;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
            transition: color 0.2s ease, filter 0.2s ease;
            display: block;
        }

        .map-marker.hovered i {
            animation: markerBounce 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97);
            color: #f59e0b;
            filter: drop-shadow(0 6px 12px rgba(245, 158, 11, 0.55));
        }

        .map-marker.selected i {
            color: #ef4444;
            filter: drop-shadow(0 4px 10px rgba(239, 68, 68, 0.6));
        }

        .map-marker.selected.hovered i {
            color: #dc2626;
            filter: drop-shadow(0 6px 14px rgba(220, 38, 38, 0.65));
        }

        @keyframes markerBounce {
            0%   { transform: translateY(0)      scale(1);    }
            25%  { transform: translateY(-14px)  scale(1.25); }
            50%  { transform: translateY(-6px)   scale(1.1);  }
            70%  { transform: translateY(-10px)  scale(1.18); }
            85%  { transform: translateY(-3px)   scale(1.05); }
            100% { transform: translateY(0)      scale(1);    }
        }

        .zoom-controls {
            position: absolute;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 100;
        }

        .zoom-controls button {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: none;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: bold;
            transition: all 0.2s;
        }

        .zoom-controls button:hover {
            background: #f0f0f0;
            transform: scale(1.1);
        }

        .zoom-controls button:active {
            transform: scale(0.95);
        }

        /* Marker popup */
        .marker-popup {
            position: absolute;
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            min-width: 250px;
            max-width: 300px;
            display: none;
        }

        .marker-popup.active {
            display: block;
        }

        .marker-popup::before {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-top: 8px solid white;
        }

        .marker-popup-close {
            position: absolute;
            top: 8px;
            right: 8px;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #666;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .marker-popup-close:hover {
            color: #000;
        }

        .zoom-level {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background: white;
            padding: 8px 12px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            font-size: 0.9rem;
            font-weight: 500;
            z-index: 100;
        }

        /* Search suggestions */
        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: none;
        }

        .search-suggestions.active {
            display: block;
        }

        .suggestion-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }

        .suggestion-item:hover {
            background: #f8f9fa;
        }

        .suggestion-item i {
            color: #1a7838;
            font-size: 1.2rem;
        }

        .suggestion-item strong {
            color: #1a7838;
        }
    </style>
@endpush

@section('content')
    <!-- Page Hero -->
    <section class="page-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="page-hero-title">Interactive Map</h1>
                    <p class="page-hero-subtitle">Explore attractions and landmarks across Dasmariñas City</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <button class="btn btn-light btn-lg" onclick="resetMapView()">
                        <i class='bx bx-reset me-2'></i>Reset View
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container-fluid px-lg-5">
            <!-- Category Filter & Search Row -->
            <div class="row mb-3">
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Filter by Category</h6>
                            <div id="categoryFilters" class="d-flex flex-wrap gap-2">
                                <button class="btn btn-sm btn-primary category-filter active" data-category="all"
                                    onclick="filterByCategory('all')">
                                    <i class='bx bx-map'></i> All
                                </button>
                                <!-- Categories will be loaded dynamically -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <form class="map-search-form" onsubmit="searchMap(event)">
                        <div class="input-group position-relative">
                            <span class="input-group-text bg-white">
                                <i class='bx bx-search-alt-2'></i>
                            </span>
                            <input id="mapSearchInput" type="text" class="form-control"
                                placeholder="Search by attraction name..." autocomplete="off">
                            <button class="btn btn-primary" type="submit">
                                Search
                            </button>
                            <!-- Search suggestions dropdown -->
                            <div id="searchSuggestions" class="search-suggestions"></div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Map Container -->
            <div class="map-container" id="mapContainer">
                <div class="map-wrapper" id="mapWrapper">
                    <img src="{{ asset('assets/dasma_map.png') }}" alt="Dasmariñas Map" class="map-image" id="mapImage">
                    <div id="markersContainer"></div>
                </div>

                <!-- Zoom Controls -->
                <div class="zoom-controls">
                    <button id="zoomIn" title="Zoom In">+</button>
                    <button id="zoomOut" title="Zoom Out">−</button>
                    <button id="resetZoom" title="Reset"><i class='bx bx-reset'></i></button>
                </div>

                <!-- Zoom Level Indicator -->
                <div class="zoom-level">
                    Zoom: <span id="zoomDisplay">100%</span>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Map state
        let currentZoom = 1;
        let currentX = 0;
        let currentY = 0;
        let isDragging = false;
        let startX = 0;
        let startY = 0;
        let mapImageNaturalWidth = 0;
        let mapImageNaturalHeight = 0;
        let allMarkers = [];
        let activePopup = null;
        let activeMarker = null;

        const mapContainer = document.getElementById('mapContainer');
        const mapWrapper = document.getElementById('mapWrapper');
        const mapImage = document.getElementById('mapImage');
        const markersContainer = document.getElementById('markersContainer');
        const zoomDisplay = document.getElementById('zoomDisplay');

        // Wait for image to load to get natural dimensions
        mapImage.onload = function() {
            mapImageNaturalWidth = mapImage.naturalWidth;
            mapImageNaturalHeight = mapImage.naturalHeight;

            console.log('Map image loaded successfully:', mapImageNaturalWidth, 'x', mapImageNaturalHeight);
            console.log('Map image src:', mapImage.src);

            // Set initial zoom to 100%
            currentZoom = 1;
            currentX = 0;
            currentY = 0;
            updateMapTransform();

            // Load markers after image is ready
            loadMarkers();
        };

        // Add error handler for image loading
        mapImage.onerror = function() {
            console.error('Failed to load map image:', mapImage.src);
        };

        // Zoom controls
        document.getElementById('zoomIn').addEventListener('click', () => {
            if (currentZoom < 4) {
                currentZoom += 0.25;
                const clamped = clampPosition(currentX, currentY);
                currentX = clamped.x;
                currentY = clamped.y;
                updateMapTransform();
            }
        });

        document.getElementById('zoomOut').addEventListener('click', () => {
            if (currentZoom > 1) {
                currentZoom -= 0.25;
                const clamped = clampPosition(currentX, currentY);
                currentX = clamped.x;
                currentY = clamped.y;
                updateMapTransform();
            }
        });

        document.getElementById('resetZoom').addEventListener('click', resetMapView);

        function resetMapView() {
            currentZoom = 1;
            currentX = 0;
            currentY = 0;
            updateMapTransform();
            closeActivePopup();
        }

        function updateMapTransform() {
            mapWrapper.style.transform = `translate(${currentX}px, ${currentY}px) scale(${currentZoom})`;
            zoomDisplay.textContent = `${Math.round(currentZoom * 100)}%`;
            updateMarkersScale();
        }

        function updateMarkersScale() {
            const inverseScale = 1 / currentZoom;
            allMarkers.forEach(marker => {
                marker.element.style.transform = `translate(-50%, -100%) scale(${inverseScale})`;
            });
        }

        // Pan functionality - Mouse events
        mapContainer.addEventListener('mousedown', startDrag);
        mapContainer.addEventListener('mousemove', drag);
        mapContainer.addEventListener('mouseup', stopDrag);
        mapContainer.addEventListener('mouseleave', stopDrag);

        // Pan functionality - Touch events for mobile
        mapContainer.addEventListener('touchstart', startDragTouch, {
            passive: false
        });
        mapContainer.addEventListener('touchmove', dragTouch, {
            passive: false
        });
        mapContainer.addEventListener('touchend', stopDrag);
        mapContainer.addEventListener('touchcancel', stopDrag);

        function startDrag(e) {
            if (e.target.closest('.map-marker') || e.target.closest('.zoom-controls') || e.target.closest(
                    '.marker-popup')) {
                return;
            }
            isDragging = true;
            startX = e.clientX - currentX;
            startY = e.clientY - currentY;
            mapContainer.classList.add('dragging');
        }

        function startDragTouch(e) {
            if (e.target.closest('.map-marker') || e.target.closest('.zoom-controls') || e.target.closest(
                    '.marker-popup')) {
                return;
            }
            e.preventDefault();
            isDragging = true;
            const touch = e.touches[0];
            startX = touch.clientX - currentX;
            startY = touch.clientY - currentY;
            mapContainer.classList.add('dragging');
        }

        function clampPosition(x, y) {
            const containerW = mapContainer.offsetWidth;
            const containerH = mapContainer.offsetHeight;
            const scaledW = mapImage.offsetWidth * currentZoom;
            const scaledH = mapImage.offsetHeight * currentZoom;

            // Maximum pan: can't pull the right/bottom edge past the container edge
            const maxX = scaledW > containerW ? 0 : (containerW - scaledW) / 2;
            const minX = scaledW > containerW ? containerW - scaledW : maxX;

            const maxY = scaledH > containerH ? 0 : (containerH - scaledH) / 2;
            const minY = scaledH > containerH ? containerH - scaledH : maxY;

            return {
                x: Math.min(maxX, Math.max(minX, x)),
                y: Math.min(maxY, Math.max(minY, y))
            };
        }

        function drag(e) {
            if (!isDragging) return;
            e.preventDefault();
            const raw = { x: e.clientX - startX, y: e.clientY - startY };
            const clamped = clampPosition(raw.x, raw.y);
            currentX = clamped.x;
            currentY = clamped.y;
            updateMapTransform();
        }

        function dragTouch(e) {
            if (!isDragging) return;
            e.preventDefault();
            const touch = e.touches[0];
            const raw = { x: touch.clientX - startX, y: touch.clientY - startY };
            const clamped = clampPosition(raw.x, raw.y);
            currentX = clamped.x;
            currentY = clamped.y;
            updateMapTransform();
        }

        function stopDrag() {
            isDragging = false;
            mapContainer.classList.remove('dragging');
        }

        // Load markers from API
        function loadMarkers() {
            fetch('{{ route('map.markers') }}')
                .then(response => response.json())
                .then(markers => {
                    markersContainer.innerHTML = '';
                    allMarkers = [];

                    markers.forEach(marker => {
                        createMarker(marker);
                    });

                    // Load category filters
                    loadCategoryFilters();

                    // After loading markers, center on Dasmariñas City Hall
                    centerOnCityHall();
                })
                .catch(error => {
                    console.error('Map error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Map Error',
                        text: 'Failed to load map markers.'
                    });
                });
        }

        // Function to center map on Dasmariñas City Hall
        function centerOnCityHall() {
            // Find City Hall marker by exact attraction name
            const cityHallMarker = allMarkers.find(marker => {
                const name = marker.name;
                return name === 'dasmariñas city hall' ||
                    name === 'dasmarinas city hall' ||
                    name.includes('dasmariñas city hall') ||
                    name.includes('dasmarinas city hall');
            });

            if (cityHallMarker) {
                console.log('Found City Hall marker:', cityHallMarker.exactName);
                console.log('Marker position:', cityHallMarker.element.style.left, cityHallMarker.element.style.top);
                console.log('Marker coordinates:', cityHallMarker.data.lat, cityHallMarker.data.lng);

                // Get marker position in pixels
                const markerX = parseFloat(cityHallMarker.element.style.left);
                const markerY = parseFloat(cityHallMarker.element.style.top);

                // Get container dimensions
                const containerRect = mapContainer.getBoundingClientRect();
                console.log('Container size:', containerRect.width, 'x', containerRect.height);

                // Set zoom level to 1.0x (100%) for better initial view
                currentZoom = 1.0;

                // Calculate offset to center the marker in the viewport
                currentX = (containerRect.width / 2) - (markerX * currentZoom);
                currentY = (containerRect.height / 2) - (markerY * currentZoom);

                console.log('Centering with zoom:', currentZoom, 'offset:', currentX, currentY);

                // Update the map transform
                updateMapTransform();
            } else {
                console.log('City Hall marker not found. Available markers:', allMarkers.map(m => m.exactName));
            }
        }

        function createMarker(markerData) {
            // Calculate pixel position from coordinates (0-1000 range)
            // Use the displayed image dimensions, not natural dimensions
            const displayedWidth = mapImage.offsetWidth;
            const displayedHeight = mapImage.offsetHeight;
            const x = (markerData.lat / 1000) * displayedWidth;
            const y = (markerData.lng / 1000) * displayedHeight;

            // Create marker element
            const markerElement = document.createElement('div');
            markerElement.className = 'map-marker';
            markerElement.style.left = `${x}px`;
            markerElement.style.top = `${y}px`;
            markerElement.dataset.categoryId = markerData.attraction.category_id;
            markerElement.innerHTML =
                `<i class='bx ${markerData.attraction.category_icon}'></i>`;

            // Create popup
            const popup = document.createElement('div');
            popup.className = 'marker-popup';
            popup.innerHTML = `
                <button class="marker-popup-close" onclick="closeActivePopup()">×</button>
                <h6 class="fw-bold mb-2">${markerData.attraction.name}</h6>
                <p class="mb-2 small text-muted">${markerData.attraction.category}</p>
                ${markerData.attraction.is_historical ? '<span class="badge bg-warning mb-2">Historical</span>' : ''}
                <a href="${markerData.attraction.url}" class="btn btn-sm btn-primary w-100 mt-2">
                    <i class='bx bx-info-circle me-1'></i>View Details
                </a>
            `;

            // Position popup above marker
            popup.style.left = `${x}px`;
            popup.style.top = `${y - 20}px`;
            popup.style.transform = 'translate(-50%, -100%)';

            // Add click event to show popup
            markerElement.addEventListener('click', (e) => {
                e.stopPropagation();
                closeActivePopup();
                popup.classList.add('active');
                activePopup = popup;
                // Mark this marker as selected
                markerElement.classList.add('selected');
                activeMarker = markerElement;
            });

            // Hover: bounce animation (re-trigger by removing/re-adding class)
            markerElement.addEventListener('mouseenter', () => {
                markerElement.classList.remove('hovered');
                void markerElement.offsetWidth; // force reflow to restart animation
                markerElement.classList.add('hovered');
                markerElement.style.zIndex = 20;
            });
            markerElement.addEventListener('mouseleave', () => {
                markerElement.classList.remove('hovered');
                markerElement.style.zIndex = 10;
            });

            markersContainer.appendChild(markerElement);
            markersContainer.appendChild(popup);

            allMarkers.push({
                element: markerElement,
                popup: popup,
                data: markerData,
                name: markerData.attraction.name.toLowerCase(),
                exactName: markerData.attraction.name, // Store exact name for searching
                categoryId: markerData.attraction.category_id,
                categoryName: markerData.attraction.category,
                categoryIcon: markerData.attraction.category_icon
            });
        }

        function closeActivePopup() {
            if (activePopup) {
                activePopup.classList.remove('active');
                activePopup = null;
            }
            if (activeMarker) {
                activeMarker.classList.remove('selected');
                activeMarker = null;
            }
        }

        // Close popup when clicking on map
        mapContainer.addEventListener('click', (e) => {
            if (!e.target.closest('.map-marker') && !e.target.closest('.marker-popup')) {
                closeActivePopup();
            }
        });

        // Make closeActivePopup available globally
        window.closeActivePopup = closeActivePopup;

        // Search functionality - searches by EXACT attraction name
        function searchMap(event) {
            event.preventDefault();
            const input = document.getElementById('mapSearchInput');
            const query = input.value.trim();

            if (!query) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Empty Search',
                    text: 'Please enter an attraction name to search.'
                });
                return;
            }

            // Search for matching marker by attraction name (case-insensitive partial match)
            const queryLower = query.toLowerCase();
            const foundMarker = allMarkers.find(marker =>
                marker.exactName.toLowerCase().includes(queryLower)
            );

            if (foundMarker) {
                console.log('Search found:', foundMarker.exactName);

                // Get marker position in pixels
                const markerX = parseFloat(foundMarker.element.style.left);
                const markerY = parseFloat(foundMarker.element.style.top);

                // Get container dimensions
                const containerRect = mapContainer.getBoundingClientRect();

                // Set zoom level (not too zoomed in)
                currentZoom = 1.5;

                // Calculate offset to center the marker in the viewport
                currentX = (containerRect.width / 2) - (markerX * currentZoom);
                currentY = (containerRect.height / 2) - (markerY * currentZoom);

                // Update the map transform
                updateMapTransform();

                // Show popup after a small delay
                setTimeout(() => {
                    closeActivePopup();
                    foundMarker.popup.classList.add('active');
                    activePopup = foundMarker.popup;

                    // Highlight the marker briefly
                    foundMarker.element.style.filter = 'drop-shadow(0 4px 8px rgba(255, 65, 77, 0.6))';
                    setTimeout(() => {
                        foundMarker.element.style.filter = '';
                    }, 1500);
                }, 200);

                // Clear input
                input.value = '';

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Found!',
                    text: `Showing ${foundMarker.exactName}`,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                console.log('Search not found:', query);
                console.log('Available attractions:', allMarkers.map(m => m.exactName));

                Swal.fire({
                    icon: 'info',
                    title: 'No Match',
                    text: `No attraction named "${query}" found on the map.`,
                    footer: 'Try searching with a different keyword or check the spelling.'
                });
                input.value = '';
            }
        }

        // Reset map view function
        function resetMapView() {
            currentZoom = 1;
            currentX = 0;
            currentY = 0;
            updateMapTransform();
            closeActivePopup();
        }

        // Search autocomplete - based on EXACT attraction names
        const searchInput = document.getElementById('mapSearchInput');
        const suggestionsContainer = document.getElementById('searchSuggestions');

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            if (query.length < 2) {
                suggestionsContainer.classList.remove('active');
                suggestionsContainer.innerHTML = '';
                return;
            }

            // Filter markers based on exact attraction name (case-insensitive)
            const queryLower = query.toLowerCase();
            const matches = allMarkers.filter(marker =>
                marker.exactName.toLowerCase().includes(queryLower)
            );

            if (matches.length > 0) {
                suggestionsContainer.innerHTML = matches.slice(0, 5).map(marker => {
                    // Escape single quotes in the name for onclick
                    const escapedName = marker.exactName.replace(/'/g, "\\'");
                    return `
                        <div class="suggestion-item" onclick="selectSuggestion('${escapedName}')">
                            <i class='bx bxs-map-pin' style="color: ${marker.data.color};"></i>
                            <div>
                                <strong>${marker.exactName}</strong>
                                <br>
                                <small class="text-muted">${marker.data.attraction.category}</small>
                            </div>
                        </div>
                    `;
                }).join('');
                suggestionsContainer.classList.add('active');
            } else {
                suggestionsContainer.classList.remove('active');
                suggestionsContainer.innerHTML = '';
            }
        });

        // Select suggestion - uses exact attraction name
        function selectSuggestion(name) {
            searchInput.value = name;
            suggestionsContainer.classList.remove('active');
            suggestionsContainer.innerHTML = '';

            // Trigger search
            const event = new Event('submit', {
                bubbles: true,
                cancelable: true
            });
            searchInput.closest('form').dispatchEvent(event);
        }

        // Close suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.map-search-form')) {
                suggestionsContainer.classList.remove('active');
                suggestionsContainer.innerHTML = '';
            }
        });

        // Load category filters dynamically
        function loadCategoryFilters() {
            // Get unique categories from markers
            const categories = {};
            allMarkers.forEach(marker => {
                if (!categories[marker.categoryId]) {
                    categories[marker.categoryId] = {
                        id: marker.categoryId,
                        name: marker.categoryName,
                        icon: marker.categoryIcon
                    };
                }
            });

            // Create filter buttons
            const filterContainer = document.getElementById('categoryFilters');
            const allButton = filterContainer.querySelector('.category-filter[data-category="all"]');

            Object.values(categories).forEach(category => {
                const button = document.createElement('button');
                button.className = 'btn btn-sm btn-outline-primary category-filter';
                button.dataset.category = category.id;
                button.innerHTML = `<i class='bx ${category.icon}'></i> ${category.name}`;
                button.onclick = () => filterByCategory(category.id);
                filterContainer.appendChild(button);
            });
        }

        // Filter markers by category
        let activeCategory = 'all';

        function filterByCategory(categoryId) {
            activeCategory = categoryId;

            // Update button states
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.category == categoryId) {
                    btn.classList.add('active');
                }
            });

            // Show/hide markers based on category
            allMarkers.forEach(marker => {
                if (categoryId === 'all' || marker.categoryId == categoryId) {
                    marker.element.style.display = 'flex';
                    // Keep popup hidden unless it's the active one
                    if (marker.popup !== activePopup) {
                        marker.popup.classList.remove('active');
                    }
                } else {
                    marker.element.style.display = 'none';
                    marker.popup.classList.remove('active');
                    if (marker.popup === activePopup) {
                        activePopup = null;
                    }
                }
            });
        }

        // Update search to respect active category filter
        const originalSearchMap = searchMap;
        searchMap = function(event) {
            event.preventDefault();
            const input = document.getElementById('mapSearchInput');
            const query = input.value.trim();

            if (!query) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Empty Search',
                    text: 'Please enter an attraction name to search.'
                });
                return;
            }

            // Search only in filtered markers
            const queryLower = query.toLowerCase();
            const searchableMarkers = activeCategory === 'all' ?
                allMarkers :
                allMarkers.filter(m => m.categoryId == activeCategory);

            const foundMarker = searchableMarkers.find(marker =>
                marker.exactName.toLowerCase().includes(queryLower)
            );

            if (foundMarker) {
                console.log('Search found:', foundMarker.exactName);

                // Get marker position in pixels
                const markerX = parseFloat(foundMarker.element.style.left);
                const markerY = parseFloat(foundMarker.element.style.top);

                // Get container dimensions
                const containerRect = mapContainer.getBoundingClientRect();

                // Set zoom level (not too zoomed in)
                currentZoom = 1.5;

                // Calculate offset to center the marker in the viewport
                currentX = (containerRect.width / 2) - (markerX * currentZoom);
                currentY = (containerRect.height / 2) - (markerY * currentZoom);

                // Update the map transform
                updateMapTransform();

                // Show popup after a small delay
                setTimeout(() => {
                    closeActivePopup();
                    foundMarker.popup.classList.add('active');
                    activePopup = foundMarker.popup;
                    foundMarker.element.classList.add('selected');
                    activeMarker = foundMarker.element;

                    // Highlight the marker briefly
                    foundMarker.element.style.filter = 'drop-shadow(0 4px 8px rgba(26, 120, 56, 0.8))';
                    setTimeout(() => {
                        foundMarker.element.style.filter = '';
                    }, 1500);
                }, 200);

                // Clear input
                input.value = '';

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Found!',
                    text: `Showing ${foundMarker.exactName}`,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                console.log('Search not found:', query);

                const categoryMessage = activeCategory === 'all' ? '' : ` in the selected category`;
                Swal.fire({
                    icon: 'info',
                    title: 'No Match',
                    text: `No attraction named "${query}" found${categoryMessage}.`,
                    footer: 'Try searching with a different keyword or check the spelling.'
                });
                input.value = '';
            }
        };

        // Update autocomplete to respect category filter
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            if (query.length < 2) {
                suggestionsContainer.classList.remove('active');
                suggestionsContainer.innerHTML = '';
                return;
            }

            // Filter based on active category
            const queryLower = query.toLowerCase();
            const searchableMarkers = activeCategory === 'all' ?
                allMarkers :
                allMarkers.filter(m => m.categoryId == activeCategory);

            const matches = searchableMarkers.filter(marker =>
                marker.exactName.toLowerCase().includes(queryLower)
            );

            if (matches.length > 0) {
                suggestionsContainer.innerHTML = matches.slice(0, 5).map(marker => {
                    const escapedName = marker.exactName.replace(/'/g, "\\'");
                    return `
                        <div class="suggestion-item" onclick="selectSuggestion('${escapedName}')">
                            <i class='bx ${marker.categoryIcon}' style="color: #1a7838;"></i>
                            <div>
                                <strong>${marker.exactName}</strong>
                                <br>
                                <small class="text-muted">${marker.categoryName}</small>
                            </div>
                        </div>
                    `;
                }).join('');
                suggestionsContainer.classList.add('active');
            } else {
                suggestionsContainer.classList.remove('active');
                suggestionsContainer.innerHTML = '';
            }
        });

        // Make functions available globally
        window.searchMap = searchMap;
        window.resetMapView = resetMapView;
        window.selectSuggestion = selectSuggestion;
        window.filterByCategory = filterByCategory;
    </script>
@endpush
