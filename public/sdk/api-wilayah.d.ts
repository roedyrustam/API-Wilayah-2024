/**
 * TypeScript Type Definitions for API Wilayah Indonesia 2026 Client SDK
 * 
 * @author Roedy Rustam <roedyrustam@gmail.com>
 * @repository https://github.com/roedyrustam/API-Wilayah-2024
 * @license MIT
 */

export interface ClientOptions {
    baseUrl?: string;
    cache?: boolean;
    timeout?: number;
}

export interface Provinsi {
    id: number;
    code: string;
    name: string;
    latitude: number | null;
    longitude: number | null;
    timezone: 'WIB' | 'WITA' | 'WIT';
}

export interface Kabupaten {
    id: number;
    provinsi_id: number;
    type: 'Kabupaten' | 'Kota';
    name: string;
    code: string;
    full_code: string;
    latitude: number | null;
    longitude: number | null;
    timezone?: 'WIB' | 'WITA' | 'WIT';
}

export interface Kecamatan {
    id: number;
    kabupaten_id: number;
    name: string;
    code: string;
    full_code: string;
}

export interface Kelurahan {
    id: number;
    kecamatan_id: number;
    name: string;
    code: string;
    full_code: string;
    pos_code: string | null;
}

export interface SearchResults {
    query: string;
    total_matches: number;
    provinsi: Provinsi[];
    kabupaten: Kabupaten[];
    kecamatan: Kecamatan[];
    kelurahan: Kelurahan[];
}

export interface NearbyItem {
    id: number;
    name: string;
    code: string;
    level: string;
    latitude: number;
    longitude: number;
    distance_km: number;
}

export interface NearbyResults {
    origin: {
        latitude: number;
        longitude: number;
    };
    radius_km: number;
    level: string;
    total_found: number;
    data: NearbyItem[];
}

export interface GeoJsonGeometry {
    type: 'Point';
    coordinates: [number, number]; // [longitude, latitude]
}

export interface GeoJsonFeature {
    type: 'Feature';
    geometry: GeoJsonGeometry;
    properties: Record<string, any>;
}

export interface GeoJsonFeatureCollection {
    type: 'FeatureCollection';
    features: GeoJsonFeature[];
}

export declare class WilayahClient {
    constructor(options?: ClientOptions);
    getProvinces(): Promise<Provinsi[]>;
    getProvince(idOrCode: string | number): Promise<Provinsi>;
    getRegencies(provinsiIdOrCode: string | number): Promise<Kabupaten[]>;
    getRegency(idOrCode: string | number): Promise<Kabupaten>;
    getDistricts(kabupatenIdOrCode: string | number): Promise<Kecamatan[]>;
    getDistrict(idOrCode: string | number): Promise<Kecamatan>;
    getVillages(kecamatanIdOrCode: string | number): Promise<Kelurahan[]>;
    getVillage(idOrCode: string | number): Promise<Kelurahan>;
    getKodePos(postalCode: string): Promise<{ pos_code: string; total_villages: number; villages: any[] }>;
    searchKodePos(query: string, options?: { limit?: number }): Promise<any>;
    getCascaderProvinsi(provinsiIdOrCode: string | number): Promise<any>;
    getCascaderKabupaten(kabupatenIdOrCode: string | number): Promise<any>;
    search(query: string): Promise<SearchResults>;
    getNearby(latitude: number, longitude: number, options?: { radius?: number; level?: 'provinsi' | 'kabupaten' | 'all'; limit?: number }): Promise<NearbyResults>;
    getGeoJson(level?: 'provinsi' | 'kabupaten', id?: string | number | null): Promise<GeoJsonFeatureCollection>;
    getExportUrl(level: 'provinsi' | 'kabupaten' | 'kecamatan' | 'kelurahan', format?: 'csv' | 'json'): string;
    clearCache(): void;
}

export default WilayahClient;
