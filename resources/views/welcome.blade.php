<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sketch Planner Hub</title>
    @vite(['resources/css/app.css'])
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
                    <h2 class="section-title">System Overview</h2>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
                        <div>
                            <div class="metric-label">Active Deployments</div>
                            <div class="metric-value">1,402</div>
                        </div>
                        <div>
                            <div class="metric-label">Network Efficiency</div>
                            <div class="metric-value">99.8%</div>
                        </div>
                        <div>
                            <div class="metric-label">Processing Load</div>
                            <div class="metric-value" style="color: var(--accent-primary);">12ms</div>
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
</body>
</html>
