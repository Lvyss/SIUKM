<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class CloudinaryService
{
    protected $cloudName;
    protected $apiKey;
    protected $apiSecret;
    protected $uploadPreset;

    public function __construct()
    {
        $this->cloudName = config('cloudinary.cloud_name');
        $this->apiKey = config('cloudinary.api_key');
        $this->apiSecret = config('cloudinary.api_secret');
        $this->uploadPreset = config('cloudinary.upload_preset', 'siukm_preset');
    }

    public function upload(UploadedFile $file, $folder = 'siukm')
    {
        try {
            Log::info('Starting Cloudinary upload', [
                'cloud_name' => $this->cloudName,
                'folder' => $folder,
                'file_name' => $file->getClientOriginalName()
            ]);

            // Method 1: Try with Upload Preset (Unsigned)
            $url = $this->uploadWithPreset($file, $folder);
            
            if ($url) {
                Log::info('Cloudinary upload successful with preset', ['url' => $url]);
                return $url;
            }

            // Method 2: Try with Signed Upload
            $url = $this->uploadWithSignature($file, $folder);
            
            if ($url) {
                Log::info('Cloudinary upload successful with signature', ['url' => $url]);
                return $url;
            }

            throw new \Exception('All Cloudinary upload methods failed');

        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed', [
                'error' => $e->getMessage(),
                'fallback_to_local' => true
            ]);
            
            // Fallback to local storage
            return $this->uploadToLocal($file, $folder);
        }
    }

    protected function uploadWithPreset(UploadedFile $file, $folder)
    {
        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload";

        $response = Http::timeout(30)->asMultipart()->post($url, [
            'file' => fopen($file->getRealPath(), 'r'),
            'upload_preset' => $this->uploadPreset,
            'folder' => $folder,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['secure_url'] ?? $data['url'] ?? null;
        }

        Log::warning('Upload with preset failed', [
            'status' => $response->status(),
            'response' => $response->body()
        ]);

        return null;
    }

    protected function uploadWithSignature(UploadedFile $file, $folder)
    {
        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload";
        
        $timestamp = time();
        $params = [
            'folder' => $folder,
            'timestamp' => $timestamp,
        ];
        
        // Generate signature
        $signature = $this->generateSignature($params);
        
        $response = Http::timeout(30)->asMultipart()->post($url, [
            'file' => fopen($file->getRealPath(), 'r'),
            'folder' => $folder,
            'timestamp' => $timestamp,
            'api_key' => $this->apiKey,
            'signature' => $signature,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['secure_url'] ?? $data['url'] ?? null;
        }

        Log::warning('Upload with signature failed', [
            'status' => $response->status(),
            'response' => $response->body()
        ]);

        return null;
    }

    protected function generateSignature($params)
    {
        $signatureData = [];
        foreach ($params as $key => $value) {
            $signatureData[] = "{$key}={$value}";
        }
        
        sort($signatureData);
        $stringToSign = implode('&', $signatureData);
        $signature = sha1($stringToSign . $this->apiSecret);
        
        return $signature;
    }

    protected function uploadToLocal(UploadedFile $file, $folder)
    {
        $path = $file->store("public/{$folder}");
        $publicPath = str_replace('public/', 'storage/', $path);
        
        Log::info('File saved to local storage', ['path' => $publicPath]);
        
        return asset($publicPath);
    }

 public function delete($url)
    {
        Log::info('Attempting to delete file', ['url' => $url]);

        // Jika URL local storage, delete dari local
        if ($this->isLocalUrl($url)) {
            return $this->deleteLocal($url);
        }

        // Jika URL Cloudinary, delete dari Cloudinary
        $publicId = $this->extractPublicId($url);
        
        if (!$publicId) {
            Log::warning('Cannot extract public_id from URL', ['url' => $url]);
            return false;
        }

        try {
            $timestamp = time();
            $signature = sha1("public_id={$publicId}&timestamp={$timestamp}{$this->apiSecret}");

            Log::info('Sending delete request to Cloudinary', [
                'public_id' => $publicId,
                'timestamp' => $timestamp,
                'signature' => $signature
            ]);

            $response = Http::post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy", [
                'public_id' => $publicId,
                'timestamp' => $timestamp,
                'api_key' => $this->apiKey,
                'signature' => $signature,
            ]);

            $success = $response->successful();
            
            Log::info('Cloudinary delete response', [
                'public_id' => $publicId,
                'success' => $success,
                'status_code' => $response->status(),
                'response_body' => $response->body()
            ]);

            return $success;

        } catch (\Exception $e) {
            Log::error('Cloudinary delete failed', [
                'error' => $e->getMessage(),
                'public_id' => $publicId
            ]);
            return false;
        }
    }

    /**
     * Extract public_id from Cloudinary URL - FIXED VERSION
     */
    protected function extractPublicId($url)
    {
        Log::info('Extracting public_id from URL', ['url' => $url]);

        // Parse URL untuk mendapatkan path
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'] ?? '';
        
        // Pattern untuk Cloudinary URL:
        // /upload/version/folder/filename.jpg
        // /upload/folder/filename.jpg  
        $pattern = '/\/upload\/(?:v\d+\/)?(.+?)(?:\.[^\.]+)?$/';
        
        preg_match($pattern, $path, $matches);
        
        if (isset($matches[1])) {
            $publicId = $matches[1];
            Log::info('Public ID extracted', ['public_id' => $publicId]);
            return $publicId;
        }

        Log::warning('No public_id found in URL', ['url' => $url, 'path' => $path]);
        return null;
    }

    /**
     * Delete local file
     */
    protected function deleteLocal($url)
    {
        try {
            // Extract path from URL: http://localhost/storage/ukm-logos/image.jpg
            $baseUrl = url('');
            $path = str_replace($baseUrl . '/storage/', 'public/', $url);
            
            Log::info('Attempting local file deletion', ['path' => $path]);

            if (Storage::exists($path)) {
                Storage::delete($path);
                Log::info('Local file deleted successfully', ['path' => $path]);
                return true;
            }
            
            Log::warning('Local file not found', ['path' => $path]);
            return false;
            
        } catch (\Exception $e) {
            Log::error('Local delete failed', [
                'error' => $e->getMessage(),
                'url' => $url
            ]);
            return false;
        }
    }

    /**
     * Check if URL is from Cloudinary
     */
    public function isCloudinaryUrl($url)
    {
        return str_contains($url, 'cloudinary.com');
    }

    /**
     * Check if URL is from local storage
     */
    public function isLocalUrl($url)
    {
        return str_contains($url, url(''));
    }

    /**
     * Test connection to Cloudinary
     */
    public function testConnection()
    {
        try {
            $response = Http::get("https://api.cloudinary.com/v1_1/{$this->cloudName}/ping");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}