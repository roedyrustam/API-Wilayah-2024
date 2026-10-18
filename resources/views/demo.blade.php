<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Playground & Demo — API Wilayah Indonesia 2026</title>
    <meta name="description" content="Playground interaktif untuk menguji Cascading Dropdown, Global Search, dan Nearby Radius API Wilayah Indonesia Standar Kemendagri 2026.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base: #07090e;
            --bg-card: rgba(15, 23, 42, 0.75);
            --bg-card-hover: rgba(30, 41, 59, 0.85);
            --border: rgba(255, 255, 255, 0.08);
            --border-focus: rgba(56, 189, 248, 0.5);
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --primary: #0284c7;
            --primary-hover: #0369a1;
            --cyan: #38bdf8;
            --emerald: #10b981;
            --amber: #f59e0b;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-base);
            color: var(--text-main);
            font-family: var(--font-sans);
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* Top Navigation Bar */
        .navbar {
            background: rgba(10, 15, 26, 0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 16px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }

        .brand-logo {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, #0284c7, #06b6d4);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 16px;
            color: #ffffff;
            box-shadow: 0 0 15px rgba(2, 132, 199, 0.4);
        }

        .brand-title {
            font-size: 17px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .brand-badge {
            background: rgba(56, 189, 248, 0.15);
            border: 1px solid rgba(56, 189, 248, 0.3);
            color: var(--cyan);
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 9999px;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: color 0.15s ease;
        }

        .nav-link:hover {
            color: #ffffff;
        }

        .btn-doc {
            background: var(--primary);
            color: #ffffff;
            padding: 7px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: background 0.15s ease;
        }

        .btn-doc:hover {
            background: var(--primary-hover);
        }

        /* Container */
        .container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 32px 20px 80px;
        }

        /* Hero */
        .hero {
            text-align: center;
            margin-bottom: 36px;
        }

        .hero h1 {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #ffffff 30%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            color: var(--text-muted);
            font-size: 15px;
            max-width: 680px;
            margin: 0 auto;
        }

        /* Tab Bar */
        .tabs {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 28px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 12px;
        }

        .tab-btn {
            background: transparent;
            border: 1px solid transparent;
            color: var(--text-muted);
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab-btn:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.04);
        }

        .tab-btn.active {
            color: #ffffff;
            background: rgba(2, 132, 199, 0.2);
            border-color: rgba(56, 189, 248, 0.4);
            box-shadow: 0 0 12px rgba(2, 132, 199, 0.2);
        }

        /* Tab Content */
        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
            animation: fadeIn 0.25s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Layout Grid */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        @media (max-width: 900px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .card-header {
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .form-select, .form-input {
            width: 100%;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 14px;
            color: var(--text-main);
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: all 0.15s ease;
        }

        .form-select:hover, .form-input:hover {
            border-color: rgba(255, 255, 255, 0.2);
        }

        .form-select:focus, .form-input:focus {
            border-color: var(--cyan);
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }

        .form-select:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Quick chip badges */
        .chip-container {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }

        .chip {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 12px;
            padding: 3px 10px;
            border-radius: 9999px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .chip:hover {
            color: #ffffff;
            border-color: var(--cyan);
            background: rgba(56, 189, 248, 0.1);
        }

        /* Result Panel */
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 13.5px;
        }

        .info-label {
            color: var(--text-muted);
        }

        .info-val {
            font-weight: 600;
            color: #ffffff;
            font-variant-numeric: tabular-nums;
        }

        .badge-tz {
            background: rgba(16, 185, 129, 0.15);
            color: var(--emerald);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-code {
            background: rgba(56, 189, 248, 0.15);
            color: var(--cyan);
            padding: 2px 8px;
            border-radius: 4px;
            font-family: var(--font-mono);
            font-size: 12px;
        }

        /* Code snippets */
        .snippet-box {
            background: #05070a;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 14px;
            margin-top: 18px;
            position: relative;
        }

        .snippet-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .snippet-lang {
            font-size: 11px;
            font-weight: 700;
            color: var(--cyan);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-copy {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--border);
            color: var(--text-main);
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-copy:hover {
            background: rgba(255, 255, 255, 0.16);
        }

        pre {
            font-family: var(--font-mono);
            font-size: 12.5px;
            color: #cbd5e1;
            overflow-x: auto;
            white-space: pre-wrap;
            word-break: break-all;
        }

        /* Results table list */
        .results-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 480px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .result-card {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.15s ease;
        }

        .result-card:hover {
            border-color: rgba(56, 189, 248, 0.4);
            background: rgba(30, 41, 59, 0.6);
            transform: translateX(2px);
        }

        .result-main h4 {
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
        }

        .result-main p {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .badge-level {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .level-provinsi { background: rgba(56, 189, 248, 0.15); color: #38bdf8; }
        .level-kabupaten { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .level-kecamatan { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
        .level-kelurahan { background: rgba(168, 85, 247, 0.15); color: #c084fc; }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-top-color: var(--cyan);
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Export & SDK Styles */
        .btn-export {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: linear-gradient(135deg, #0284c7, #0369a1);
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-export:hover {
            background: linear-gradient(135deg, #0369a1, #075985);
            box-shadow: 0 0 12px rgba(2, 132, 199, 0.4);
        }
        .btn-export-secondary {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--border);
            color: var(--text-main);
        }
        .btn-export-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
        }
        .code-box {
            background: #05070b;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px;
            font-family: var(--font-mono);
            font-size: 13px;
            color: #38bdf8;
            overflow-x: auto;
            white-space: pre;
            line-height: 1.6;
        }
        .export-card {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .export-card h4 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .export-card p {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header class="navbar">
        <a href="/demo" class="brand">
            <div class="brand-logo">ID</div>
            <div>
                <span class="brand-title">API Wilayah Indonesia</span>
                <span class="brand-badge">2026 Kemendagri</span>
            </div>
        </a>
        <nav class="nav-links">
            <a href="https://github.com/roedyrustam/API-Wilayah-2024" target="_blank" class="nav-link" style="display:flex;align-items:center;gap:6px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                GitHub
            </a>
            <a href="/api/documentation" class="btn-doc">Swagger Spec</a>
            <a href="/admin" class="nav-link">Filament Admin</a>
            <a href="/json/provinsi.json" target="_blank" class="nav-link">JSON Raw</a>
        </nav>
    </header>

    <main class="container">
        <!-- Hero Header -->
        <section class="hero">
            <h1>Developer Playground & Testing Console</h1>
            <p>Uji coba langsung Cascading Dropdown 4-Level, Pencarian Autocomplete Global, dan Proximity GPS Radius Search dengan respons live dari API Wilayah Indonesia 2026.</p>
        </section>

        <!-- Feature Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('tab-cascading', this)">Cascading Dropdown</button>
            <button class="tab-btn" onclick="switchTab('tab-search', this)">Global Autocomplete</button>
            <button class="tab-btn" onclick="switchTab('tab-nearby', this)">Nearby Radius (GPS)</button>
            <button class="tab-btn" onclick="switchTab('tab-geojson', this)">GeoJSON RFC 7946</button>
            <button class="tab-btn" onclick="switchTab('tab-kodepos-cascader', this)">Kode Pos & Cascader (v3.3)</button>
            <button class="tab-btn" onclick="switchTab('tab-export-sdk', this)">Export & Client SDK</button>
        </div>

        <!-- TAB 1: CASCADING DROPDOWN -->
        <section id="tab-cascading" class="tab-panel active">
            <div class="grid-2">
                <!-- Dropdowns Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pilih Wilayah Administratif</h3>
                        <span id="loading-indicator" style="display:none;"><span class="spinner"></span></span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="sel-provinsi">1. Provinsi</label>
                        <select id="sel-provinsi" class="form-select" onchange="onProvinceChange()">
                            <option value="">-- Pilih Provinsi --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="sel-kabupaten">2. Kabupaten / Kota</label>
                        <select id="sel-kabupaten" class="form-select" onchange="onKabupatenChange()" disabled>
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="sel-kecamatan">3. Kecamatan / Distrik</label>
                        <select id="sel-kecamatan" class="form-select" onchange="onKecamatanChange()" disabled>
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="sel-kelurahan">4. Kelurahan / Desa</label>
                        <select id="sel-kelurahan" class="form-select" onchange="onKelurahanChange()" disabled>
                            <option value="">-- Pilih Kelurahan/Desa --</option>
                        </select>
                    </div>

                    <div class="chip-container">
                        <span class="info-label" style="font-size:12px; align-self:center;">Contoh Cepat:</span>
                        <button class="chip" onclick="quickSelect(26, 'Papua Barat Daya')">Papua Barat Daya (DOB 96)</button>
                        <button class="chip" onclick="quickSelect(6, 'DKI Jakarta')">DKI Jakarta</button>
                        <button class="chip" onclick="quickSelect(9, 'Jawa Barat')">Jawa Barat</button>
                        <button class="chip" onclick="quickSelect(2, 'Bali')">Bali</button>
                    </div>
                </div>

                <!-- Live Details & Code Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Wilayah Terpilih</h3>
                        <span id="detail-tz" class="badge-tz">WIB</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Provinsi</span>
                        <span class="info-val" id="detail-prov">-</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kabupaten / Kota</span>
                        <span class="info-val" id="detail-kab">-</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kecamatan</span>
                        <span class="info-val" id="detail-kec">-</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kelurahan / Desa</span>
                        <span class="info-val" id="detail-kel">-</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kode Resmi Kemendagri</span>
                        <span class="info-val" id="detail-code"><span class="badge-code">-</span></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kode Pos</span>
                        <span class="info-val" id="detail-pos">-</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Koordinat Centroid</span>
                        <span class="info-val" id="detail-coords">-</span>
                    </div>

                    <!-- Code Snippet Box -->
                    <div class="snippet-box">
                        <div class="snippet-header">
                            <span class="snippet-lang">Integration Code (Fetch API)</span>
                            <button class="btn-copy" onclick="copySnippet()">Salin Kode</button>
                        </div>
                        <pre id="snippet-code">// Pilih wilayah di sebelah kiri untuk melihat kode integrasi otomatis...</pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- TAB 2: GLOBAL SEARCH -->
        <section id="tab-search" class="tab-panel">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pencarian Autocomplete Simultan (4-Level)</h3>
                    <span id="search-count" class="badge-code">0 hasil</span>
                </div>

                <div class="form-group">
                    <input type="text" id="search-input" class="form-input" placeholder="Ketik minimal 2 karakter (contoh: Sorong, Bandung, Menteng, 98415)..." oninput="onSearchInput(this.value)">
                    <div class="chip-container">
                        <span class="info-label" style="font-size:12px; align-self:center;">Pencarian Populer:</span>
                        <button class="chip" onclick="triggerSearch('Sorong')">Sorong</button>
                        <button class="chip" onclick="triggerSearch('Raja Ampat')">Raja Ampat</button>
                        <button class="chip" onclick="triggerSearch('Bandung')">Bandung</button>
                        <button class="chip" onclick="triggerSearch('Menteng')">Menteng</button>
                        <button class="chip" onclick="triggerSearch('98415')">98415 (Kode Pos)</button>
                    </div>
                </div>

                <div id="search-results" class="results-list">
                    <p style="color:var(--text-muted); font-size:14px; text-align:center; padding:30px;">Ketik kata kunci untuk memulai pencarian...</p>
                </div>
            </div>
        </section>

        <!-- TAB 3: NEARBY RADIUS -->
        <section id="tab-nearby" class="tab-panel">
            <div class="grid-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Parameter Koordinat GPS</h3>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="nearby-lat">Latitude</label>
                        <input type="number" step="any" id="nearby-lat" class="form-input" value="-6.208763">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="nearby-lng">Longitude</label>
                        <input type="number" step="any" id="nearby-lng" class="form-input" value="106.845599">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="nearby-radius">Radius (Kilometer): <span id="radius-val" style="color:var(--cyan); font-weight:700;">100 km</span></label>
                        <input type="range" id="nearby-radius" min="10" max="500" step="10" value="100" style="width:100%; accent-color:var(--cyan);" oninput="document.getElementById('radius-val').innerText = this.value + ' km'">
                    </div>

                    <div style="display:flex; gap:10px; margin-top:16px;">
                        <button class="btn-doc" style="flex:1; cursor:pointer;" onclick="runNearbySearch()">Cari Wilayah Terdekat</button>
                        <button class="btn-copy" style="cursor:pointer;" onclick="useCurrentLocation()">Gunakan GPS Saya</button>
                    </div>

                    <div class="chip-container" style="margin-top:16px;">
                        <span class="info-label" style="font-size:12px; align-self:center;">Preset Lokasi:</span>
                        <button class="chip" onclick="setPreset(-6.208763, 106.845599)">Jakarta Pusat</button>
                        <button class="chip" onclick="setPreset(-7.257472, 112.752090)">Surabaya</button>
                        <button class="chip" onclick="setPreset(-0.876200, 131.255800)">Sorong (Papua Barat Daya)</button>
                        <button class="chip" onclick="setPreset(-8.409518, 115.188916)">Bali</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Wilayah Dalam Radius Terdekat</h3>
                        <span id="nearby-count" class="badge-code">0 wilayah</span>
                    </div>

                    <div id="nearby-results" class="results-list">
                        <p style="color:var(--text-muted); font-size:14px; text-align:center; padding:30px;">Klik 'Cari Wilayah Terdekat' untuk memproses...</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- TAB 4: GEOJSON RFC 7946 -->
        <section id="tab-geojson" class="tab-panel">
            <div class="grid-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">GeoJSON Explorer (RFC 7946)</h3>
                        <span id="geojson-loading" style="display:none;"><span class="spinner"></span></span>
                    </div>
                    <p style="font-size:13px; color:var(--text-muted); margin-bottom:16px;">
                        Endpoint GeoJSON standar RFC 7946 dengan format FeatureCollection. Dapat langsung digunakan di Leaflet, Mapbox, Google Maps, OpenLayers, atau software SIG (QGIS/ArcGIS).
                    </p>

                    <div class="form-group">
                        <label class="form-label" for="geojson-level">Tingkat Wilayah</label>
                        <select id="geojson-level" class="form-select" onchange="onGeoJsonLevelChange()">
                            <option value="provinsi">Seluruh Provinsi (38 Fitur Point)</option>
                            <option value="kabupaten">Kabupaten / Kota Terpilih</option>
                        </select>
                    </div>

                    <div class="form-group" id="geojson-prov-group" style="display:none;">
                        <label class="form-label" for="geojson-prov-id">Pilih Provinsi Parent</label>
                        <select id="geojson-prov-id" class="form-select" onchange="loadGeoJson()">
                            <option value="">-- Pilih Provinsi --</option>
                        </select>
                    </div>

                    <div class="chip-container" style="margin-top:16px;">
                        <span class="info-label" style="font-size:12px; align-self:center;">Contoh Cepat:</span>
                        <button class="chip" onclick="quickGeoJson('provinsi', null)">Semua 38 Provinsi</button>
                        <button class="chip" onclick="quickGeoJson('provinsi', 26)">Papua Barat Daya (96)</button>
                        <button class="chip" onclick="quickGeoJson('provinsi', 6)">DKI Jakarta (31)</button>
                        <button class="chip" onclick="quickGeoJson('kabupaten', 26)">6 Kab/Kota PBD</button>
                    </div>

                    <div style="margin-top:20px; display:flex; gap:10px;">
                        <button class="btn-export" onclick="copyGeoJson()">Salin GeoJSON</button>
                        <a id="geojson-raw-link" href="/api/v1/geojson/provinsi" target="_blank" class="btn-export btn-export-secondary">Buka Raw URL</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Live GeoJSON Payload</h3>
                        <span id="geojson-summary" class="badge-code">0 Features</span>
                    </div>
                    <pre id="geojson-output" class="code-box" style="max-height: 480px; overflow-y: auto;">// Klik salah satu contoh di sebelah kiri untuk memuat GeoJSON...</pre>
                </div>
            </div>
        </section>

        <!-- TAB 5: EXPORT & CLIENT SDK -->
        <section id="tab-export-sdk" class="tab-panel">
            <h3 style="font-size:18px; font-weight:700; margin-bottom:14px;">1. Streaming Exporter (CSV & JSON)</h3>
            <p style="font-size:13.5px; color:var(--text-muted); margin-bottom:20px;">
                Download seluruh master data per tingkat wilayah secara efisien tanpa memory limit (streaming generator). File CSV dilengkapi UTF-8 BOM sehingga langsung terbaca rapi di Microsoft Excel.
            </p>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:16px; margin-bottom:32px;">
                <div class="export-card">
                    <div>
                        <h4>Provinsi</h4>
                        <p>38 Provinsi lengkap dengan kode Kemendagri, koordinat GIS, dan zona waktu.</p>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <a href="/api/v1/export/provinsi?format=csv" class="btn-export" style="flex:1;">CSV</a>
                        <a href="/api/v1/export/provinsi?format=json" class="btn-export btn-export-secondary" style="flex:1;">JSON</a>
                    </div>
                </div>

                <div class="export-card">
                    <div>
                        <h4>Kabupaten / Kota</h4>
                        <p>514 Kab/Kota terverifikasi, tipe (Kab/Kota), dan relasi ke provinsi parent.</p>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <a href="/api/v1/export/kabupaten?format=csv" class="btn-export" style="flex:1;">CSV</a>
                        <a href="/api/v1/export/kabupaten?format=json" class="btn-export btn-export-secondary" style="flex:1;">JSON</a>
                    </div>
                </div>

                <div class="export-card">
                    <div>
                        <h4>Kecamatan / Distrik</h4>
                        <p>7.277 Kecamatan dengan kodifikasi Kemendagri 6-digit.</p>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <a href="/api/v1/export/kecamatan?format=csv" class="btn-export" style="flex:1;">CSV</a>
                        <a href="/api/v1/export/kecamatan?format=json" class="btn-export btn-export-secondary" style="flex:1;">JSON</a>
                    </div>
                </div>

                <div class="export-card">
                    <div>
                        <h4>Kelurahan / Desa</h4>
                        <p>83.763 Desa & Kelurahan beserta kode pos resmi Indonesia.</p>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <a href="/api/v1/export/kelurahan?format=csv" class="btn-export" style="flex:1;">CSV</a>
                        <a href="/api/v1/export/kelurahan?format=json" class="btn-export btn-export-secondary" style="flex:1;">JSON</a>
                    </div>
                </div>
            </div>

            <h3 style="font-size:18px; font-weight:700; margin-bottom:14px;">2. Universal Client SDK & Komponen Blade</h3>
            <div class="grid-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Blade Cascading Component</h3>
                    </div>
                    <p style="font-size:13px; color:var(--text-muted); margin-bottom:12px;">
                        Komponen siap pakai untuk project Laravel Blade dengan styling otomatis dan JavaScript scoped:
                    </p>
                    <div class="code-box">&lt;!-- Dropdown 4-Tingkat Lengkap --&gt;
&lt;x-wilayah-cascade 
    name-provinsi="provinsi_id"
    name-kabupaten="kabupaten_id"
    name-kecamatan="kecamatan_id"
    name-kelurahan="kelurahan_id"
    :show-postal-code="true"
/&gt;</div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">JavaScript SDK (UMD / ESM)</h3>
                    </div>
                    <p style="font-size:13px; color:var(--text-muted); margin-bottom:12px;">
                        Gunakan SDK tanpa dependensi di browser atau bundler (Vite, React, Vue, Next.js):
                    </p>
                    <div class="code-box">&lt;script src="/sdk/api-wilayah.js"&gt;&lt;/script&gt;
&lt;script&gt;
  const api = new WilayahClient();
  const provinces = await api.getProvinces();
  const regencies = await api.getRegencies('96'); // PBD
  const nearby = await api.getNearby(-0.8762, 131.2558);
&lt;/script&gt;</div>
                </div>
            </div>
        </section>

        <!-- TAB 6: KODE POS & SINGLE-TRIP CASCADER -->
        <section id="tab-kodepos-cascader" class="tab-panel">
            <div class="grid-2">
                <!-- Postal Code Lookup Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lookup Kode Pos 5-Digit</h3>
                        <span class="badge" style="background:rgba(16,185,129,0.2); color:#34d399;">GET /api/v1/kodepos/{code}</span>
                    </div>
                    <p style="font-size:13px; color:var(--text-muted); margin-bottom:16px;">
                        Masukkan 5 digit kode pos resmi Indonesia untuk mengambil hierarki lengkap (Kelurahan, Kecamatan, Kab/Kota, Provinsi, dan Zona Waktu) secara instan.
                    </p>
                    <div class="form-group">
                        <label class="form-label">Kode Pos (5 Digit)</label>
                        <div style="display:flex; gap:8px;">
                            <input type="text" id="demo-pos-input" class="form-select" placeholder="Contoh: 10110 atau 98547" value="10110" style="flex:1;">
                            <button type="button" class="btn-primary" onclick="lookupKodePos()">Cari Kode Pos</button>
                        </div>
                    </div>
                    <div style="margin-top:12px; display:flex; gap:6px; flex-wrap:wrap;">
                        <span style="font-size:11px; color:var(--text-muted); align-self:center;">Contoh Cepat:</span>
                        <button type="button" class="btn-copy" onclick="quickPostal('10110')">10110 (Gambir)</button>
                        <button type="button" class="btn-copy" onclick="quickPostal('40115')">40115 (Bandung)</button>
                        <button type="button" class="btn-copy" onclick="quickPostal('60271')">60271 (Surabaya)</button>
                        <button type="button" class="btn-copy" onclick="quickPostal('98547')">98547 (Papua)</button>
                    </div>
                    <div id="postal-result-container" style="margin-top:20px;">
                        <!-- Results will appear here -->
                    </div>
                </div>

                <!-- Single-Trip Cascader Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Single-Trip Cascader Hierarki</h3>
                        <span class="badge" style="background:rgba(56,189,248,0.2); color:#38bdf8;">1 Network Trip</span>
                    </div>
                    <p style="font-size:13px; color:var(--text-muted); margin-bottom:16px;">
                        Ambil seluruh cabang anak Provinsi (hingga Kecamatan) dalam satu kali request jaringan untuk dropdown frontend berperforma tinggi.
                    </p>
                    <div class="form-group">
                        <label class="form-label">Pilih Provinsi untuk Cascader</label>
                        <div style="display:flex; gap:8px;">
                            <select id="cascader-prov-id" class="form-select" style="flex:1;"></select>
                            <button type="button" class="btn-primary" onclick="loadCascaderTree()">Muat Pohon</button>
                        </div>
                    </div>
                    <div id="cascader-benchmark" style="font-size:12px; color:var(--cyan); margin-top:8px; display:none;"></div>
                    <div style="margin-top:16px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <span style="font-size:12px; font-weight:600; color:var(--text-muted);">Pohon Hierarki JSON</span>
                            <button type="button" class="btn-copy" onclick="copyCascaderJson()">Salin JSON</button>
                        </div>
                        <div class="code-box" id="cascader-output" style="max-height:300px; font-size:12px;">// Klik tombol "Muat Pohon" untuk menguji hierarki cascader</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer style="margin-top: 60px; padding: 32px 20px; border-top: 1px solid var(--border); text-align: center; color: var(--text-muted); font-size: 13.5px;">
            <p>&copy; 2024&ndash;2026 <strong>Roedy Rustam</strong>. Dibuat dengan standar resmi Kemendagri Republik Indonesia.</p>
            <p style="margin-top: 8px; display: flex; align-items: center; justify-content: center; gap: 16px; flex-wrap: wrap;">
                <a href="https://github.com/roedyrustam/API-Wilayah-2024" target="_blank" style="color: var(--cyan); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    github.com/roedyrustam/API-Wilayah-2024
                </a>
                <span>&bull;</span>
                <a href="/api/documentation" style="color: var(--cyan); text-decoration: none; font-weight: 500;">Swagger OpenAPI Documentation</a>
                <span>&bull;</span>
                <a href="https://github.com/roedyrustam" target="_blank" style="color: var(--cyan); text-decoration: none; font-weight: 500;">Author: Roedy Rustam</a>
            </p>
        </footer>
    </main>

    <script>
        // Tab switching
        function switchTab(tabId, el) {
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            el.classList.add('active');
        }

        // Global State
        let provincesCache = [];
        let selectedState = {
            prov: null,
            kab: null,
            kec: null,
            kel: null
        };

        // Initialize Provinces on load
        async function init() {
            try {
                const res = await fetch('/api/v1/provinsi');
                const json = await res.json();
                if (json.status && json.data) {
                    provincesCache = json.data;
                    const sel = document.getElementById('sel-provinsi');
                    const geoSel = document.getElementById('geojson-prov-id');
                    provincesCache.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id;
                        opt.textContent = `${p.name} (${p.code})`;
                        sel.appendChild(opt);

                        if (geoSel) {
                            const optGeo = document.createElement('option');
                            optGeo.value = p.id;
                            optGeo.textContent = `${p.name} (${p.code})`;
                            geoSel.appendChild(optGeo);
                        }

                        const cascSel = document.getElementById('cascader-prov-id');
                        if (cascSel) {
                            const optCasc = document.createElement('option');
                            optCasc.value = p.id;
                            optCasc.textContent = `${p.name} (${p.code})`;
                            cascSel.appendChild(optCasc);
                        }
                    });
                }
            } catch (e) {
                console.error('Error fetching provinces:', e);
            }
        }

        window.addEventListener('DOMContentLoaded', init);

        async function onProvinceChange() {
            const provId = document.getElementById('sel-provinsi').value;
            resetDownFrom('kabupaten');

            if (!provId) {
                selectedState.prov = null;
                updateDetails();
                return;
            }

            selectedState.prov = provincesCache.find(p => p.id == provId);
            updateDetails();

            showLoading(true);
            try {
                const res = await fetch(`/api/v1/provinsi/${provId}/kabupaten`);
                const json = await res.json();
                const sel = document.getElementById('sel-kabupaten');
                sel.disabled = false;
                sel.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
                json.data.forEach(k => {
                    const opt = document.createElement('option');
                    opt.value = k.id;
                    opt.textContent = `${k.type} ${k.name} (${k.full_code})`;
                    opt.dataset.item = JSON.stringify(k);
                    sel.appendChild(opt);
                });
            } catch (e) {
                console.error(e);
            } finally {
                showLoading(false);
            }
        }

        async function onKabupatenChange() {
            const kabSel = document.getElementById('sel-kabupaten');
            const kabId = kabSel.value;
            resetDownFrom('kecamatan');

            if (!kabId) {
                selectedState.kab = null;
                updateDetails();
                return;
            }

            const opt = kabSel.selectedOptions[0];
            selectedState.kab = JSON.parse(opt.dataset.item);
            updateDetails();

            showLoading(true);
            try {
                const res = await fetch(`/api/v1/kabupaten/${kabId}/kecamatan`);
                const json = await res.json();
                const sel = document.getElementById('sel-kecamatan');
                sel.disabled = false;
                sel.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                json.data.forEach(kc => {
                    const opt = document.createElement('option');
                    opt.value = kc.id;
                    opt.textContent = `${kc.name} (${kc.full_code})`;
                    opt.dataset.item = JSON.stringify(kc);
                    sel.appendChild(opt);
                });
            } catch (e) {
                console.error(e);
            } finally {
                showLoading(false);
            }
        }

        async function onKecamatanChange() {
            const kecSel = document.getElementById('sel-kecamatan');
            const kecId = kecSel.value;
            resetDownFrom('kelurahan');

            if (!kecId) {
                selectedState.kec = null;
                updateDetails();
                return;
            }

            const opt = kecSel.selectedOptions[0];
            selectedState.kec = JSON.parse(opt.dataset.item);
            updateDetails();

            showLoading(true);
            try {
                const res = await fetch(`/api/v1/kecamatan/${kecId}/kelurahan`);
                const json = await res.json();
                const sel = document.getElementById('sel-kelurahan');
                sel.disabled = false;
                sel.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
                json.data.forEach(kl => {
                    const opt = document.createElement('option');
                    opt.value = kl.id;
                    opt.textContent = `${kl.name} (Pos: ${kl.pos_code || '-'})`;
                    opt.dataset.item = JSON.stringify(kl);
                    sel.appendChild(opt);
                });
            } catch (e) {
                console.error(e);
            } finally {
                showLoading(false);
            }
        }

        function onKelurahanChange() {
            const kelSel = document.getElementById('sel-kelurahan');
            if (!kelSel.value) {
                selectedState.kel = null;
            } else {
                selectedState.kel = JSON.parse(kelSel.selectedOptions[0].dataset.item);
            }
            updateDetails();
        }

        function resetDownFrom(level) {
            if (level === 'kabupaten') {
                const k = document.getElementById('sel-kabupaten');
                k.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
                k.disabled = true;
                selectedState.kab = null;
                resetDownFrom('kecamatan');
            } else if (level === 'kecamatan') {
                const kc = document.getElementById('sel-kecamatan');
                kc.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                kc.disabled = true;
                selectedState.kec = null;
                resetDownFrom('kelurahan');
            } else if (level === 'kelurahan') {
                const kl = document.getElementById('sel-kelurahan');
                kl.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
                kl.disabled = true;
                selectedState.kel = null;
            }
        }

        function updateDetails() {
            document.getElementById('detail-prov').innerText = selectedState.prov ? `${selectedState.prov.name} (${selectedState.prov.code})` : '-';
            document.getElementById('detail-kab').innerText = selectedState.kab ? `${selectedState.kab.type} ${selectedState.kab.name} (${selectedState.kab.full_code})` : '-';
            document.getElementById('detail-kec').innerText = selectedState.kec ? `${selectedState.kec.name} (${selectedState.kec.full_code})` : '-';
            document.getElementById('detail-kel').innerText = selectedState.kel ? selectedState.kel.name : '-';

            const activeItem = selectedState.kel || selectedState.kec || selectedState.kab || selectedState.prov;
            const code = activeItem ? (activeItem.full_code || activeItem.code) : '-';
            document.getElementById('detail-code').innerHTML = `<span class="badge-code">${code}</span>`;

            document.getElementById('detail-pos').innerText = selectedState.kel ? (selectedState.kel.pos_code || '-') : '-';

            const tz = selectedState.prov ? (selectedState.prov.timezone || 'WIB') : 'WIB';
            document.getElementById('detail-tz').innerText = tz;

            const lat = selectedState.prov ? selectedState.prov.latitude : null;
            const lng = selectedState.prov ? selectedState.prov.longitude : null;
            document.getElementById('detail-coords').innerText = (lat && lng) ? `${lat.toFixed(4)}, ${lng.toFixed(4)}` : '-';

            // Generate code snippet
            let snippet = '';
            if (selectedState.kel) {
                snippet = `// Mengambil detail lengkap Kelurahan/Desa: ${selectedState.kel.name}\n` +
                          `fetch('/api/v1/kelurahan/${selectedState.kel.id}')\n` +
                          `  .then(res => res.json())\n` +
                          `  .then(data => console.log(data));`;
            } else if (selectedState.kec) {
                snippet = `// Mengambil daftar kelurahan di Kecamatan ${selectedState.kec.name}\n` +
                          `fetch('/api/v1/kecamatan/${selectedState.kec.id}/kelurahan')\n` +
                          `  .then(res => res.json())\n` +
                          `  .then(data => console.log(data.data));`;
            } else if (selectedState.kab) {
                snippet = `// Mengambil daftar kecamatan di ${selectedState.kab.name}\n` +
                          `fetch('/api/v1/kabupaten/${selectedState.kab.id}/kecamatan')\n` +
                          `  .then(res => res.json())\n` +
                          `  .then(data => console.log(data.data));`;
            } else if (selectedState.prov) {
                snippet = `// Mengambil daftar kabupaten di Provinsi ${selectedState.prov.name}\n` +
                          `fetch('/api/v1/provinsi/${selectedState.prov.id}/kabupaten')\n` +
                          `  .then(res => res.json())\n` +
                          `  .then(data => console.log(data.data));`;
            } else {
                snippet = `// Silakan pilih salah satu wilayah di dropdown untuk membuat kode request`;
            }
            document.getElementById('snippet-code').innerText = snippet;
        }

        function showLoading(show) {
            document.getElementById('loading-indicator').style.display = show ? 'inline-block' : 'none';
        }

        function copySnippet() {
            const text = document.getElementById('snippet-code').innerText;
            navigator.clipboard.writeText(text);
            const btn = document.querySelector('.btn-copy');
            const original = btn.innerText;
            btn.innerText = 'Tersalin!';
            setTimeout(() => { btn.innerText = original; }, 1500);
        }

        function quickSelect(id, name) {
            document.getElementById('sel-provinsi').value = id;
            onProvinceChange();
        }

        // TAB 2: Search Autocomplete
        let searchTimer = null;
        function onSearchInput(val) {
            clearTimeout(searchTimer);
            if (!val || val.trim().length < 2) {
                document.getElementById('search-results').innerHTML = '<p style="color:var(--text-muted); font-size:14px; text-align:center; padding:30px;">Ketik minimal 2 karakter untuk memulai...</p>';
                document.getElementById('search-count').innerText = '0 hasil';
                return;
            }
            searchTimer = setTimeout(() => {
                fetchSearch(val.trim());
            }, 250);
        }

        function triggerSearch(val) {
            document.getElementById('search-input').value = val;
            fetchSearch(val);
        }

        async function fetchSearch(q) {
            document.getElementById('search-results').innerHTML = '<div style="text-align:center; padding:30px;"><span class="spinner"></span></div>';
            try {
                const res = await fetch(`/api/v1/search?q=${encodeURIComponent(q)}`);
                const json = await res.json();
                const container = document.getElementById('search-results');
                if (!json.status || !json.data || json.data.total_matches === 0) {
                    container.innerHTML = `<p style="color:var(--text-muted); font-size:14px; text-align:center; padding:30px;">Tidak ditemukan wilayah yang cocok dengan "${q}".</p>`;
                    document.getElementById('search-count').innerText = '0 hasil';
                    return;
                }

                document.getElementById('search-count').innerText = `${json.data.total_matches} hasil`;
                let html = '';

                // Combine items
                const levels = ['provinsi', 'kabupaten', 'kecamatan', 'kelurahan'];
                levels.forEach(lvl => {
                    const items = json.data.results[lvl] || [];
                    items.forEach(item => {
                        let sub = '';
                        let code = item.full_code || item.code;
                        if (lvl === 'kabupaten') sub = `${item.type} • Provinsi: ${item.provinsi ? item.provinsi.name : '-'}`;
                        else if (lvl === 'kecamatan') sub = `Kab/Kota: ${item.kabupaten ? item.kabupaten.name : '-'}`;
                        else if (lvl === 'kelurahan') sub = `Kecamatan: ${item.kecamatan ? item.kecamatan.name : '-'} • Kode Pos: ${item.pos_code || '-'}`;
                        else sub = `Kode Kemendagri: ${code} • Zona: ${item.timezone || 'WIB'}`;

                        html += `
                            <div class="result-card">
                                <div class="result-main">
                                    <h4>${item.name}</h4>
                                    <p>${sub}</p>
                                </div>
                                <div>
                                    <span class="badge-level level-${lvl}">${lvl}</span>
                                    <span class="badge-code" style="margin-left:6px;">${code}</span>
                                </div>
                            </div>
                        `;
                    });
                });

                container.innerHTML = html;
            } catch (e) {
                console.error(e);
            }
        }

        // TAB 3: Nearby Radius
        function setPreset(lat, lng) {
            document.getElementById('nearby-lat').value = lat;
            document.getElementById('nearby-lng').value = lng;
            runNearbySearch();
        }

        function useCurrentLocation() {
            if (!navigator.geolocation) {
                alert('Geolocation tidak didukung oleh browser Anda.');
                return;
            }
            navigator.geolocation.getCurrentPosition(pos => {
                document.getElementById('nearby-lat').value = pos.coords.latitude.toFixed(6);
                document.getElementById('nearby-lng').value = pos.coords.longitude.toFixed(6);
                runNearbySearch();
            }, err => {
                alert('Gagal mengambil lokasi GPS: ' + err.message);
            });
        }

        async function runNearbySearch() {
            const lat = document.getElementById('nearby-lat').value;
            const lng = document.getElementById('nearby-lng').value;
            const radius = document.getElementById('nearby-radius').value;
            const container = document.getElementById('nearby-results');

            container.innerHTML = '<div style="text-align:center; padding:30px;"><span class="spinner"></span></div>';
            try {
                const res = await fetch(`/api/v1/nearby?lat=${lat}&lng=${lng}&radius=${radius}&level=provinsi&limit=15`);
                const json = await res.json();
                if (!json.status || !json.data || json.data.total_found === 0) {
                    container.innerHTML = `<p style="color:var(--text-muted); font-size:14px; text-align:center; padding:30px;">Tidak ada wilayah dalam radius ${radius} km.</p>`;
                    document.getElementById('nearby-count').innerText = '0 wilayah';
                    return;
                }

                document.getElementById('nearby-count').innerText = `${json.data.total_found} wilayah`;
                let html = '';
                json.data.data.forEach(item => {
                    html += `
                        <div class="result-card">
                            <div class="result-main">
                                <h4>${item.name}</h4>
                                <p>Kode: ${item.code} • Zona: ${item.timezone || 'WIB'}</p>
                            </div>
                            <div style="text-align:right;">
                                <span class="badge-tz" style="background:rgba(2,132,199,0.2); color:var(--cyan); font-size:12px;">${item.distance_km} km</span>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            } catch (e) {
                console.error(e);
            }
        }

        // TAB 4: GeoJSON RFC 7946 Explorer
        let currentGeoJson = null;

        function onGeoJsonLevelChange() {
            const lvl = document.getElementById('geojson-level').value;
            const provGroup = document.getElementById('geojson-prov-group');
            if (lvl === 'kabupaten') {
                provGroup.style.display = 'block';
            } else {
                provGroup.style.display = 'none';
            }
            loadGeoJson();
        }

        async function loadGeoJson() {
            const lvl = document.getElementById('geojson-level').value;
            const provId = document.getElementById('geojson-prov-id').value;
            const loading = document.getElementById('geojson-loading');
            const output = document.getElementById('geojson-output');
            const summary = document.getElementById('geojson-summary');
            const rawLink = document.getElementById('geojson-raw-link');

            let endpoint = `/api/v1/geojson/${lvl}`;
            if (lvl === 'kabupaten' && provId) {
                endpoint = `/api/v1/geojson/provinsi/${provId}`;
            }

            rawLink.href = endpoint;
            loading.style.display = 'inline-block';
            output.innerText = '// Memuat data GeoJSON...';

            try {
                const res = await fetch(endpoint);
                const data = await res.json();
                currentGeoJson = data;

                const count = data.features ? data.features.length : 0;
                summary.innerText = `${count} Features (${data.type})`;

                output.innerText = JSON.stringify(data, null, 2);
            } catch (err) {
                output.innerText = '// Terjadi kesalahan memuat GeoJSON: ' + err.message;
            } finally {
                loading.style.display = 'none';
            }
        }

        function quickGeoJson(level, id) {
            document.getElementById('geojson-level').value = level;
            const provGroup = document.getElementById('geojson-prov-group');
            if (level === 'kabupaten' || id) {
                provGroup.style.display = 'block';
                if (id) {
                    document.getElementById('geojson-prov-id').value = id;
                }
            } else {
                provGroup.style.display = 'none';
            }
            loadGeoJson();
        }

        function copyGeoJson() {
            if (!currentGeoJson) {
                alert('Silakan muat GeoJSON terlebih dahulu.');
                return;
            }
            navigator.clipboard.writeText(JSON.stringify(currentGeoJson, null, 2))
                .then(() => alert('GeoJSON RFC 7946 berhasil disalin ke clipboard!'))
                .catch(err => alert('Gagal menyalin: ' + err.message));
        }
    </script>
</body>
</html>
