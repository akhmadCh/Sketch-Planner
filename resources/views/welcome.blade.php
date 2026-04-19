<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sketch Planner Hub</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #earthquake-map {
            height: 500px;
            border-radius: 8px;
            margin-top: 16px;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }
        .gps-input-group {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        .gps-input-group input {
            flex: 1;
            min-width: 150px;
        }
        .magnitude-legend {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 16px;
            font-size: 14px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="ambient-bg"></div>

    <div class="dashboard-container">
        <!-- Header -->
        <header class="glass-panel animate-fade-up" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 32px;">
            <div style="display:flex; align-items: center; gap: 24px;">
                <h1 class="text-gradient" style="font-size: 24px; margin: 0;">Sketch Planner</h1>
                <div class="status-pill">
                    <span class="dot dot--active"></span>
                    Nexus Online
                </div>
            </div>
            
            @if (Route::has('login'))
                <div style="display: flex; gap: 16px;">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="premium-btn btn-outline">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="premium-btn btn-outline">Sign In</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="premium-btn btn-primary">Initialize Access</a>
                        @endif
                    @endauth
                </div>
            @endif
        </header>

        <!-- Main Content -->
        <main class="dashboard-main">
            <!-- Left Primary Panel -->
            <section style="display: flex; flex-direction: column; gap: 32px;">
                
                <div class="glass-panel animate-fade-up delay-1">
                    <h2 class="section-title">Seismic Zone Mapping (USGS)</h2>
                    <p style="color: var(--text-muted); margin-bottom: 16px; font-size: 15px;">
                        Enter GPS coordinates to automatically fetch earthquake data and zone seismic risk
                    </p>
                    
                    <div class="gps-input-group">
                        <input type="number" id="latitude" class="premium-input" placeholder="Latitude (e.g., -6.2)" value="-6.2" step="0.001">
                        <input type="number" id="longitude" class="premium-input" placeholder="Longitude (e.g., 106.8)" value="106.8" step="0.001">
                        <input type="number" id="radius" class="premium-input" placeholder="Radius (km)" value="200" min="10" max="1000">
                        <button id="loadMapBtn" class="premium-btn btn-primary" style="flex-shrink: 0; cursor: pointer;">Load Map</button>
                    </div>

                    <div id="earthquake-map"></div>

                    <div class="magnitude-legend">
                        <div class="legend-item">
                            <div class="legend-dot" style="background: #00ff00;"></div>
                            <span>Mag 4-5</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background: #ffaa00;"></div>
                            <span>Mag 5-6</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background: #ff6600;"></div>
                            <span>Mag 6-7</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background: #ff0000;"></div>
                            <span>Mag 7+</span>
                        </div>
                    </div>

                    <div id="riskDisplay" style="margin-top: 16px; display: none;">
                        <div style="padding: 12px; background: rgba(255, 255, 255, 0.05); border-radius: 6px; border-left: 4px solid var(--accent-primary);">
                            <div style="font-weight: 600; margin-bottom: 6px;">Seismic Risk Assessment</div>
                            <div style="font-size: 14px; color: var(--text-muted);">
                                <div>Risk Level: <span id="riskLevel" style="color: var(--accent-primary); font-weight: 600;">-</span></div>
                                <div>Earthquakes Found: <span id="eqCount" style="color: var(--accent-primary); font-weight: 600;">-</span></div>
                                <div>Max Magnitude: <span id="maxMag" style="color: var(--accent-primary); font-weight: 600;">-</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-panel animate-fade-up delay-2">
                    <h2 class="section-title">Query Terminal</h2>
                    <p style="color: var(--text-muted); margin-bottom: 24px; font-size: 15px;">Input your calibration parameters to securely sync nodes with the main server architecture.</p>
                    
                    <div style="display: flex; gap: 16px;">
                        <input type="text" class="premium-input" placeholder="Execute command...">
                        <button class="premium-btn btn-primary" style="flex-shrink: 0;">Execute</button>
                    </div>
                </div>

            </section>

            <!-- Right Sidebar Panel -->
            <aside class="glass-panel animate-fade-up delay-3" style="display:flex; flex-direction: column;">
                <h2 class="section-title">Live Telemetry</h2>
                
                <ul class="meta-list" style="flex-grow: 1; margin-bottom: 32px;">
                    <li class="meta-item">
                        <span class="metric-label">Uplink Speed</span>
                        <span style="font-family: var(--font-display); font-weight: 500;">4.2 Gbps</span>
                    </li>
                    <li class="meta-item">
                        <span class="metric-label">Node Integrity</span>
                        <div style="width: 100px; height: 6px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden;">
                            <div style="width: 85%; height: 100%; background: var(--accent-primary); border-radius: 4px; box-shadow: 0 0 10px var(--accent-primary);"></div>
                        </div>
                    </li>
                    <li class="meta-item">
                        <span class="metric-label">Data Packets</span>
                        <span style="font-family: var(--font-display); font-weight: 500; display:flex; align-items: center; gap: 8px;">
                            Syncing <span class="dot dot--warning"></span>
                        </span>
                    </li>
                    <li class="meta-item">
                        <span class="metric-label">Security Protocol</span>
                        <span style="color: #00ff88; font-weight: 500;">SEC-009 ACTIVE</span>
                    </li>
                </ul>

                <a href="{{ route('meyerhof_sementara') }}" class="premium-btn btn-outline" style="width: 100%; text-align: center;">Meyerhof Calculator (US06)</a>
            </aside>
        </main>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map = null;
        let earthquakeMarkers = [];

        // Initialize map with default location
        function initMap() {
            if (!map) {
                const defaultLat = -6.2;
                const defaultLng = 106.8;
                
                map = L.map('earthquake-map').setView([defaultLat, defaultLng], 8);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19,
                    opacity: 0.8
                }).addTo(map);

                // Add a marker for the query location
                const locationMarker = L.marker([defaultLat, defaultLng], {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                }).addTo(map);
                locationMarker.bindPopup('Query Location').openPopup();

                loadEarthquakes();
            }
        }

        // Load earthquakes from USGS API
        async function loadEarthquakes() {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);
            const radius = parseInt(document.getElementById('radius').value);

            if (isNaN(lat) || isNaN(lng)) {
                alert('Please enter valid latitude and longitude');
                return;
            }

            try {
                const response = await fetch(`/api/earthquake-data?lat=${lat}&lng=${lng}&radius=${radius}`);
                const result = await response.json();

                if (result.status === 'success') {
                    clearMarkers();
                    
                    const data = result.data;
                    const features = data.features || [];
                    
                    // Add earthquake markers
                    features.forEach(feature => {
                        const coords = feature.geometry.coordinates;
                        const props = feature.properties;
                        const mag = props.mag;
                        const place = props.place;
                        const time = new Date(props.time).toLocaleDateString();

                        // Determine color based on magnitude
                        let color = '#00ff00'; // Green
                        let magnitudeRange = 'Mag 4-5';
                        
                        if (mag >= 7) {
                            color = '#ff0000';
                            magnitudeRange = 'Mag 7+';
                        } else if (mag >= 6) {
                            color = '#ff6600';
                            magnitudeRange = 'Mag 6-7';
                        } else if (mag >= 5) {
                            color = '#ffaa00';
                            magnitudeRange = 'Mag 5-6';
                        }

                        const marker = L.circleMarker([coords[1], coords[0]], {
                            radius: Math.max(5, mag * 1.5),
                            fillColor: color,
                            color: '#fff',
                            weight: 1.5,
                            opacity: 0.8,
                            fillOpacity: 0.7
                        }).addTo(map);
                        
                        marker.bindPopup(`
                            <div style="color: #000; font-size: 12px;">
                                <strong>${magnitudeRange}</strong><br>
                                Magnitude: ${mag}<br>
                                Location: ${place}<br>
                                Date: ${time}
                            </div>
                        `);

                        earthquakeMarkers.push(marker);
                    });

                    // Update risk display
                    const riskLevel = result.risk_level;
                    const maxMag = features.length > 0 ? Math.max(...features.map(f => f.properties.mag)) : 0;
                    
                    document.getElementById('riskLevel').textContent = riskLevel;
                    document.getElementById('eqCount').textContent = features.length;
                    document.getElementById('maxMag').textContent = maxMag.toFixed(1);
                    document.getElementById('riskDisplay').style.display = 'block';

                    // Update map center
                    if (map) {
                        map.setView([lat, lng], 8);
                    }
                }
            } catch (error) {
                console.error('Error loading earthquakes:', error);
                alert('Error loading earthquake data. Please try again.');
            }
        }

        // Clear all earthquake markers
        function clearMarkers() {
            earthquakeMarkers.forEach(marker => map.removeLayer(marker));
            earthquakeMarkers = [];
        }

        // Event listeners
        document.getElementById('loadMapBtn').addEventListener('click', () => {
            if (!map) {
                initMap();
            }
            loadEarthquakes();
        });

        // Initialize map on page load
        document.addEventListener('DOMContentLoaded', () => {
            initMap();
        });
    </script>
</body>
</html>
