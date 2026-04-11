<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sketch Planner - Meyerhof Calculation</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="ambient-bg"></div>

    <div class="dashboard-container">
        <!-- Header -->
        <header class="glass-panel animate-fade-up" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 32px;">
            <div style="display:flex; align-items: center; gap: 24px;">
                <h1 class="text-gradient" style="font-size: 24px; margin: 0;">Meyerhof Calculator</h1>
                <div class="status-pill">
                    <span class="dot dot--active"></span>
                    US06 Active
                </div>
            </div>
            
            <a href="{{ url('/') }}" class="premium-btn btn-outline">Back to Dashboard</a>
        </header>

        <!-- Main Content -->
        <main class="dashboard-main">
            <!-- Left Primary Panel (Form) -->
            <section class="glass-panel animate-fade-up delay-1" style="display: flex; flex-direction: column; gap: 32px;">
                <h2 class="section-title">Pile & Soil Parameters Input</h2>
                
                <form onsubmit="event.preventDefault();" style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <!-- Pile Properties -->
                    <div style="grid-column: 1 / -1;">
                        <h3 class="metric-label" style="margin-bottom: 12px; color: var(--accent-primary);">1. Pile Geometry</h3>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label class="metric-label">Pile Diameter (m)</label>
                        <input type="number" class="premium-input" placeholder="e.g. 0.4" step="0.01">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label class="metric-label">Pile Length (m)</label>
                        <input type="number" class="premium-input" placeholder="e.g. 12" step="0.1">
                    </div>

                    <!-- Soil Properties -->
                    <div style="grid-column: 1 / -1; margin-top: 16px;">
                        <h3 class="metric-label" style="margin-bottom: 12px; color: var(--accent-primary);">2. N-SPT Soil Values</h3>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label class="metric-label">N-SPT Tip (Bottom/Base)</label>
                        <input type="number" class="premium-input" placeholder="e.g. 40">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label class="metric-label">Average N-SPT (Shaft)</label>
                        <input type="number" class="premium-input" placeholder="e.g. 15">
                    </div>

                    <div style="grid-column: 1 / -1; margin-top: 24px;">
                        <button type="submit" class="premium-btn btn-primary" style="width: 100%;">Calculate Bearing Capacity</button>
                    </div>
                </form>
            </section>

            <!-- Right Sidebar Panel (Results) -->
            <aside class="glass-panel animate-fade-up delay-2" style="display:flex; flex-direction: column;">
                <h2 class="section-title">Capacity Output</h2>
                <p style="color: var(--text-muted); margin-bottom: 32px; font-size: 14px;">Results simulated using automated Meyerhof methodology.</p>
                
                <ul class="meta-list" style="flex-grow: 1; margin-bottom: 32px;">
                    <li class="meta-item" style="flex-direction: column; align-items: flex-start; gap: 8px; padding-bottom: 24px;">
                        <span class="metric-label">End Bearing Capacity (Qp)</span>
                        <div style="font-family: var(--font-display); font-weight: 700; font-size: 32px; color: var(--text-primary);">
                            --- <span style="font-size: 16px; color: var(--text-muted);">kN</span>
                        </div>
                    </li>
                    <li class="meta-item" style="flex-direction: column; align-items: flex-start; gap: 8px; padding-bottom: 24px;">
                        <span class="metric-label">Friction Capacity (Qs)</span>
                        <div style="font-family: var(--font-display); font-weight: 700; font-size: 32px; color: var(--text-primary);">
                            --- <span style="font-size: 16px; color: var(--text-muted);">kN</span>
                        </div>
                    </li>
                    <li class="meta-item" style="flex-direction: column; align-items: flex-start; gap: 8px; border-bottom: none;">
                        <span class="metric-label" style="color: var(--accent-primary);">Ultimate Bearing Capacity (Qu)</span>
                        <div style="font-family: var(--font-display); font-weight: 700; font-size: 42px; color: var(--accent-primary); text-shadow: 0 0 16px rgba(0, 240, 255, 0.4);">
                            --- <span style="font-size: 20px; color: var(--text-muted);">kN</span>
                        </div>
                    </li>
                </ul>

                <button class="premium-btn btn-outline" style="width: 100%;">Export Report (Locked)</button>
            </aside>
        </main>
    </div>
</body>
</html>
