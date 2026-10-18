/**
 * API Wilayah Indonesia 2026 - Universal Client SDK (UMD)
 * Kemendagri 2026 Compliant (38 Provinsi, 514 Kab/Kota, 7.277 Kec, 83.763 Kel)
 * 
 * Works in: Browser (<script>), Node.js (require/import), Bundlers (Webpack, Vite, Rollup)
 * Zero dependencies. Built-in Client Caching & GeoJSON Support.
 * 
 * @author Roedy Rustam <roedyrustam@gmail.com>
 * @repository https://github.com/roedyrustam/API-Wilayah-2024
 * @license MIT
 */
(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        define([], factory);
    } else if (typeof module === 'object' && module.exports) {
        module.exports = factory();
    } else {
        root.WilayahClient = factory();
    }
}(typeof self !== 'undefined' ? self : this, function () {
    'use strict';

    class WilayahClient {
        /**
         * @param {Object} [options]
         * @param {string} [options.baseUrl=''] Base URL of the API server (e.g. 'https://api.wilayah.id' or '')
         * @param {boolean} [options.cache=true] Enable in-memory caching to avoid repeated network calls
         * @param {number} [options.timeout=10000] Request timeout in milliseconds
         */
        constructor(options = {}) {
            this.baseUrl = (options.baseUrl || '').replace(/\/$/, '');
            this.useCache = options.cache !== false;
            this.timeout = options.timeout || 10000;
            this.cache = new Map();
        }

        /**
         * Low-level fetch wrapper with cache & timeout handling
         * @private
         */
        async _fetch(endpoint, options = {}) {
            const url = `${this.baseUrl}${endpoint}`;

            if (this.useCache && options.method !== 'POST' && this.cache.has(url)) {
                return this.cache.get(url);
            }

            const controller = new AbortController();
            const timer = setTimeout(() => controller.abort(), this.timeout);

            try {
                const response = await fetch(url, {
                    signal: controller.signal,
                    headers: {
                        'Accept': 'application/json',
                        ...(options.headers || {})
                    },
                    ...options
                });

                clearTimeout(timer);

                if (!response.ok) {
                    const errorBody = await response.json().catch(() => ({}));
                    throw new Error(errorBody.message || `API request failed with status ${response.status}`);
                }

                const result = await response.json();

                if (this.useCache && options.method !== 'POST') {
                    this.cache.set(url, result);
                }

                return result;
            } catch (err) {
                clearTimeout(timer);
                throw err;
            }
        }

        /**
         * Ambil daftar 38 Provinsi di Indonesia
         * @returns {Promise<Array<{id: number, code: string, name: string, latitude: number, longitude: number, timezone: string}>>}
         */
        async getProvinces() {
            const res = await this._fetch('/api/v1/provinsi');
            return res.data || [];
        }

        /**
         * Ambil detail provinsi berdasarkan ID atau kode Kemendagri (e.g. '96' untuk Papua Barat Daya)
         * @param {string|number} idOrCode
         */
        async getProvince(idOrCode) {
            const res = await this._fetch(`/api/v1/provinsi/${encodeURIComponent(idOrCode)}`);
            return res.data;
        }

        /**
         * Ambil daftar Kabupaten/Kota di bawah suatu Provinsi
         * @param {string|number} provinsiIdOrCode
         */
        async getRegencies(provinsiIdOrCode) {
            const res = await this._fetch(`/api/v1/kabupaten?provinsi_id=${encodeURIComponent(provinsiIdOrCode)}`);
            return res.data || [];
        }

        /**
         * Ambil detail Kabupaten/Kota berdasarkan ID atau full_code
         * @param {string|number} idOrCode
         */
        async getRegency(idOrCode) {
            const res = await this._fetch(`/api/v1/kabupaten/${encodeURIComponent(idOrCode)}`);
            return res.data;
        }

        /**
         * Ambil daftar Kecamatan di bawah suatu Kabupaten/Kota
         * @param {string|number} kabupatenIdOrCode
         */
        async getDistricts(kabupatenIdOrCode) {
            const res = await this._fetch(`/api/v1/kecamatan?kabupaten_id=${encodeURIComponent(kabupatenIdOrCode)}`);
            return res.data || [];
        }

        /**
         * Ambil detail Kecamatan berdasarkan ID atau full_code
         * @param {string|number} idOrCode
         */
        async getDistrict(idOrCode) {
            const res = await this._fetch(`/api/v1/kecamatan/${encodeURIComponent(idOrCode)}`);
            return res.data;
        }

        /**
         * Ambil daftar Kelurahan/Desa di bawah suatu Kecamatan
         * @param {string|number} kecamatanIdOrCode
         */
        async getVillages(kecamatanIdOrCode) {
            const res = await this._fetch(`/api/v1/kelurahan?kecamatan_id=${encodeURIComponent(kecamatanIdOrCode)}`);
            return res.data || [];
        }

        /**
         * Ambil detail Kelurahan/Desa berdasarkan ID atau full_code
         * @param {string|number} idOrCode
         */
        async getVillage(idOrCode) {
            const res = await this._fetch(`/api/v1/kelurahan/${encodeURIComponent(idOrCode)}`);
            return res.data;
        }

        /**
         * Pencarian wilayah multi-tingkat secara cepat (Provinsi, Kab/Kota, Kecamatan, Kelurahan)
         * @param {string} query Minimum 2 karakter
         */
        async search(query) {
            if (!query || query.trim().length < 2) {
                throw new Error('Kata kunci pencarian minimal 2 karakter');
            }
            const res = await this._fetch(`/api/v1/search?q=${encodeURIComponent(query.trim())}`);
            return res.data || {};
        }

        /**
         * Pencarian wilayah terdekat berdasarkan koordinat GPS (Haversine Proximity Search)
         * @param {number} latitude
         * @param {number} longitude
         * @param {Object} [options]
         * @param {number} [options.radius=50] Radius dalam kilometer
         * @param {'provinsi'|'kabupaten'|'all'} [options.level='all']
         * @param {number} [options.limit=10]
         */
        async getNearby(latitude, longitude, options = {}) {
            const params = new URLSearchParams({
                lat: latitude.toString(),
                lng: longitude.toString(),
                radius: (options.radius || 50).toString(),
                level: options.level || 'all',
                limit: (options.limit || 10).toString()
            });
            const res = await this._fetch(`/api/v1/nearby?${params.toString()}`);
            return res.data || {};
        }

        /**
         * Ambil data dalam format GeoJSON RFC 7946 untuk visualisasi Mapbox / Leaflet / QGIS
         * @param {'provinsi'|'kabupaten'} [level='provinsi']
         * @param {string|number} [id] ID/Kode spesifik (opsional)
         */
        async getGeoJson(level = 'provinsi', id = null) {
            const endpoint = id 
                ? `/api/v1/geojson/${level}/${encodeURIComponent(id)}`
                : `/api/v1/geojson/${level}`;
            return await this._fetch(endpoint);
        }

        /**
         * Dapatkan URL streaming download export (CSV / JSON)
         * @param {'provinsi'|'kabupaten'|'kecamatan'|'kelurahan'} level
         * @param {'csv'|'json'} [format='csv']
         * @returns {string} URL download langsung
         */
        getExportUrl(level, format = 'csv') {
            return `${this.baseUrl}/api/v1/export/${encodeURIComponent(level)}?format=${encodeURIComponent(format)}`;
        }

        /**
         * Bersihkan memory cache klien
         */
        clearCache() {
            this.cache.clear();
        }
    }

    return WilayahClient;
}));
