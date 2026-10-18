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
            <p>Master Data 38 Provinsi, 514 Kab/Kota, 7.277 Kecamatan, 83.763 Kelurahan/Desa</p>
        </div>
        <nav class="header-links">
            <a href="/admin">Filament Admin</a>
            <a href="/json/provinsi.json" target="_blank">JSON Provinsi</a>
            <a href="/json/kabupaten.json" target="_blank">JSON Kabupaten</a>
            <a href="/json/kecamatan.json" target="_blank">JSON Kecamatan</a>
            <a href="/json/kelurahan.json" target="_blank">JSON Kelurahan</a>
            <a href="/docs/openapi.yaml" target="_blank">Spec OpenAPI YAML</a>
        </nav>
    </header>

    <div id="swagger-ui"></div>

    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            window.ui = SwaggerUIBundle({
                url: "/docs/openapi.yaml",
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
