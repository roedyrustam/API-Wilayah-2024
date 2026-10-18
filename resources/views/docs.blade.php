<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi API Wilayah Indonesia 2026</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
        }
        .header-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            padding: 24px 32px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        .header-title h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .header-title p {
            margin: 6px 0 0;
            font-size: 13px;
            color: #94a3b8;
        }
        .header-badge {
            display: inline-block;
            background-color: #0284c7;
            color: #ffffff;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 9999px;
            margin-left: 8px;
            text-transform: uppercase;
        }
        .header-links a {
            color: #38bdf8;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            margin-left: 16px;
            transition: color 0.2s ease;
        }
        .header-links a:hover {
            color: #7dd3fc;
            text-decoration: underline;
        }
        #swagger-ui {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 16px;
        }
        .swagger-ui .topbar {
            display: none !important;
        }
        .swagger-ui .info {
            margin: 20px 0 30px;
        }
        .swagger-ui .info .title {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
        }
    </style>
</head>
<body>
    <header class="header-banner">
        <div class="header-title">
            <h1>
                API Wilayah Indonesia 2026
                <span class="header-badge">v2.0 Kemendagri</span>
            </h1>
            <p>Master Data 38 Provinsi, 514 Kab/Kota, 7.277 Kecamatan, 83.763 Kelurahan/Desa • Dibuat oleh <a href="https://github.com/roedyrustam" target="_blank" style="color: #38bdf8; text-decoration: none; font-weight: 600;">Roedy Rustam</a></p>
        </div>
        <nav class="header-links">
            <a href="https://github.com/roedyrustam/API-Wilayah-2024" target="_blank" style="background: rgba(255, 255, 255, 0.1); color: #ffffff; padding: 6px 14px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border: 1px solid rgba(255,255,255,0.15);">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                GitHub Repo
            </a>
            <a href="/demo" style="background: #0284c7; color: #ffffff; padding: 6px 14px; border-radius: 6px; text-decoration: none; font-weight: 600;">Live Playground</a>
            <a href="/admin">Filament Admin</a>
            <a href="/json/provinsi.json" target="_blank">JSON Provinsi</a>
            <a href="/json/kabupaten.json" target="_blank">JSON Kabupaten</a>
            <a href="/json/kecamatan.json" target="_blank">JSON Kecamatan</a>
            <a href="/json/kelurahan.json" target="_blank">JSON Kelurahan</a>
            <a href="/docs/openapi.json" target="_blank">Spec OpenAPI JSON</a>
            <a href="/docs/openapi.yaml" target="_blank">Spec OpenAPI YAML</a>
        </nav>
    </header>

    <div id="swagger-ui"></div>

    <footer style="text-align: center; padding: 32px 16px; color: #64748b; font-size: 13px; border-top: 1px solid #e2e8f0; margin-top: 40px;">
        <p>&copy; 2024&ndash;2026 <strong>Roedy Rustam</strong>. <a href="https://github.com/roedyrustam/API-Wilayah-2024" target="_blank" style="color: #0284c7; text-decoration: none;">GitHub Repository</a> &bull; Lisensi Open-Source MIT &bull; Standar Kemendagri RI 2026</p>
    </footer>

    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            window.ui = SwaggerUIBundle({
                url: "/docs/openapi.json",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                displayRequestDuration: true,
                filter: true,
                tryItOutEnabled: true
            });
        };
    </script>
</body>
</html>
