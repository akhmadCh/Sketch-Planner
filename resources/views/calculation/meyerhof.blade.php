<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meyerhof Calculator - Sketch Planner</title>
    @vite(['resources/css/app.css'])
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Extracting any missing custom classes from welcome.blade just in case */
        .ambient-bg {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at 50% 0%, rgba(0, 197, 253, 0.15), transparent 60%), #0a0e17;
            z-index: -1;
        }
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
            color: #fff;
            font-family: 'Inter', sans-serif;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 24px;
            backdrop-filter: blur(10px);
        }
        .premium-input {
            width: 100%;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .premium-input:focus {
            outline: none;
            border-color: #00C5FD;
        }
        .table-input {
            width: 100%;
            background: transparent;
            border: none;
            color: #fff;
            text-align: right;
        }
        .table-input:focus {
            outline: none;
            color: #00C5FD;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 8px 12px;
            text-align: right;
        }
        th {
            background: rgba(255, 255, 255, 0.05);
            text-align: center;
        }
        .text-center { text-align: center !important; }
        .btn-primary {
            background: #00C5FD;
            color: #fff !important;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
        }
        .btn-outline {
            background: transparent;
            color: #00C5FD;
            border: 1px solid #00C5FD;
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
        }
        .text-gradient {
            background: linear-gradient(90deg, #fff, #00C5FD);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .status-LAYAK { color: #00ff88; text-shadow: 0 0 10px rgba(0,255,136,0.5); }
        .status-MARGIN { color: #f5a623; text-shadow: 0 0 10px rgba(245,166,35,0.5); }
        .status-BAHAYA { color: #ff3366; text-shadow: 0 0 10px rgba(255,51,102,0.5); }
    </style>
</head>
<body class="bg-[#0a0e17] text-white">
    <div class="ambient-bg"></div>

    <div class="dashboard-container" x-data="meyerhofCalculator()">
        <header class="glass-panel" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
            <div>
                <h1 class="text-gradient" style="font-size: 24px; margin: 0; font-weight: 600;">Meyerhof Bearing Capacity</h1>
                <p style="color: #888; font-size: 14px; margin-top: 4px;">Dynamic Calculation Terminal for Lahan Gambut (US06)</p>
            </div>
            <a href="{{ url('/') }}" class="btn-outline" style="text-decoration:none;">&larr; Back to Nexus</a>
        </header>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
            <!-- Left Panel: Input Form -->
            <div class="glass-panel">
                <h2 style="font-size: 18px; margin-bottom: 24px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 12px;">Foundation Parameters</h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-size: 13px; color: #ccc; margin-bottom: 8px;">Point Label</label>
                        <input type="text" x-model="pointLabel" class="premium-input">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; color: #ccc; margin-bottom: 8px;">Safety Factor (SF)</label>
                        <input type="number" step="0.1" x-model="safetyFactor" class="premium-input">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; color: #ccc; margin-bottom: 8px;">Pile Diameter (m)</label>
                        <input type="number" step="0.05" x-model="pileDiameter" class="premium-input">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; color: #ccc; margin-bottom: 8px;">Pile Depth (m)</label>
                        <input type="number" step="0.2" x-model="pileDepth" class="premium-input" @change="autoPopulateMocks()">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; color: #ccc; margin-bottom: 8px;">Depth Interval (m)</label>
                        <input type="number" step="0.1" x-model="depthInterval" class="premium-input" @change="autoPopulateMocks()">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; color: #ccc; margin-bottom: 8px;">Required Load (kN)</label>
                        <input type="number" step="10" x-model="requiredLoad" class="premium-input">
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2 style="font-size: 18px; margin: 0;">Sondir (CPT) Data</h2>
                    <button @click="autoPopulateMocks()" class="btn-outline" style="padding: 6px 12px; font-size: 12px;">Auto Generate Mocks</button>
                </div>

                <div style="max-height: 400px; overflow-y: auto; background: rgba(0,0,0,0.2); border-radius: 8px;">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 80px;">Depth (m)</th>
                                <th>qc (kgf/cm²)</th>
                                <th>fs (kgf/cm²)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(row, index) in sondirRows" :key="index">
                                <tr>
                                    <td class="text-center" style="color: #aaa;" x-text="(index * depthInterval).toFixed(2)"></td>
                                    <td><input type="number" step="0.1" x-model="row.qc" class="table-input"></td>
                                    <td><input type="number" step="0.01" x-model="row.fs" class="table-input"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                
                <button @click="calculate()" class="btn-primary" style="width: 100%; margin-top: 24px; display: flex; justify-content: center; gap: 8px; font-weight: bold; background: #00C5FD; color: #111;">
                    <span x-show="isLoading" style="font-style: italic;">⚡ Validating Architecture...</span>
                    <span x-show="!isLoading">Execute Meyerhof Calculation</span>
                </button>

                <template x-if="errorMsg">
                    <div style="margin-top: 16px; padding: 12px; background: rgba(255,51,102,0.1); border: 1px solid #ff3366; border-radius: 8px; color: #ff3366; font-size: 14px;" x-text="errorMsg"></div>
                </template>
            </div>

            <!-- Right Panel: Telemetry & Results -->
            <div class="glass-panel" style="display: flex; flex-direction: column;">
                <h2 style="font-size: 18px; margin-bottom: 24px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 12px;">Telemetry Output</h2>
                
                <div style="flex-grow: 1; display:flex; flex-direction:column; justify-content:center; align-items:center; opacity: 0.5;" x-show="!result && !isLoading">
                    <p style="font-size: 14px;">Awaiting Execution...</p>
                </div>

                <template x-if="result">
                    <div style="display: flex; flex-direction: column; gap: 24px;" class="animate-fade-up">
                        <div style="text-align: center; padding: 24px; background: rgba(0,0,0,0.3); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                            <div style="font-size: 14px; color: #aaa; margin-bottom: 8px;">Foundation Status</div>
                            <div style="font-size: 36px; font-weight: 700; font-family: var(--font-display);" :class="'status-' + result.status" x-text="result.status"></div>
                            
                            <template x-if="result.peat_warning">
                                <div style="margin-top: 12px; display: inline-flex; align-items: center; background: rgba(255,51,102,0.15); padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: bold; color: #ff4775; border: 1px solid rgba(255,51,102,0.4); box-shadow: 0 0 10px rgba(255,51,102,0.2);">
                                    ⚠️ PEAT SOIL DETECTED (Rf &gt; 5%)
                                </div>
                            </template>
                        </div>

                        <div>
                            <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <span style="color: #aaa; font-size: 14px;">Ultimate Capacity (Qu)</span>
                                <strong x-text="result.qu_kn + ' kN'" style="font-size: 15px;"></strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <span style="color: #aaa; font-size: 14px;">Allowable Capacity (Qa)</span>
                                <strong style="color: #00C5FD; font-size: 15px;" x-text="result.qa_kn + ' kN'"></strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <span style="color: #aaa; font-size: 14px;">Applied Safety Factor</span>
                                <strong x-text="result.safety_factor" style="font-size: 15px;"></strong>
                            </div>
                        </div>

                        <div style="background: rgba(255,255,255,0.03); padding: 16px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                            <h3 style="font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px;">Calculation Detail</h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 13px;">
                                <div><b style="color: #aaa; font-weight: normal;">qc avg:</b> <br><span x-text="result.calculation_detail.qc_avg + ' kgf/cm²'" style="font-weight: 600; color: #fff;"></span></div>
                                <div><b style="color: #aaa; font-weight: normal;">fs avg:</b> <br><span x-text="result.calculation_detail.fs_avg + ' kgf/cm²'" style="font-weight: 600; color: #fff;"></span></div>
                                <div><b style="color: #aaa; font-weight: normal;">Area Tip:</b> <br><span x-text="result.calculation_detail.Ap + ' m²'" style="font-weight: 600; color: #fff;"></span></div>
                                <div><b style="color: #aaa; font-weight: normal;">Area Skin:</b> <br><span x-text="result.calculation_detail.As + ' m²'" style="font-weight: 600; color: #fff;"></span></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- View Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('meyerhofCalculator', () => ({
                projectId: 1,
                pointLabel: 'S-1',
                depthInterval: 0.2,
                pileDiameter: 0.4,
                pileDepth: 12.0,
                requiredLoad: 800,
                safetyFactor: 2.5,
                sondirRows: [],
                isLoading: false,
                result: null,
                errorMsg: null,

                init() {
                    this.autoPopulateMocks();
                },

                autoPopulateMocks() {
                    this.sondirRows = [];
                    let rowCount = Math.floor(parseFloat(this.pileDepth) / parseFloat(this.depthInterval)) + 1;
                    
                    for (let i = 0; i < rowCount; i++) {
                        // simulate standard clay to stiff clay profile
                        let qc = Math.min(150, Math.max(8, i * 2 + Math.random() * 20));
                        let fs = (qc * 0.015) + (Math.random() * 0.1); 
                        
                        this.sondirRows.push({
                            qc: qc.toFixed(1),
                            fs: fs.toFixed(2),
                        });
                    }
                },

                async calculate() {
                    this.isLoading = true;
                    this.errorMsg = null;
                    this.result = null;

                    let qc_values = this.sondirRows.map(r => parseFloat(r.qc || 0));
                    let fs_values = this.sondirRows.map(r => parseFloat(r.fs || 0));

                    const payload = {
                        point_label: this.pointLabel,
                        depth_interval: parseFloat(this.depthInterval),
                        pile_diameter: parseFloat(this.pileDiameter),
                        pile_depth: parseFloat(this.pileDepth),
                        required_load: parseFloat(this.requiredLoad),
                        safety_factor: parseFloat(this.safetyFactor),
                        qc_values: qc_values,
                        fs_values: fs_values
                    };

                    try {
                        const response = await fetch('/api/projects/' + this.projectId + '/calculate/meyerhof', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await response.json();
                        
                        if (response.ok) {
                            this.result = data.data;
                        } else {
                            this.errorMsg = data.message || "An error occurred during calculation.";
                            if(data.errors) {
                                this.errorMsg += " " + JSON.stringify(data.errors);
                            }
                        }
                    } catch (err) {
                        this.errorMsg = "Failed to connect to Nexus logic core.";
                        console.error(err);
                    } finally {
                        this.isLoading = false;
                    }
                }
            }));
        });
    </script>
</body>
</html>
