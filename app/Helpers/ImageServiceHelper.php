<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageServiceHelper
{
    private static $instance;
    private $baseUrl;
    private $apiKey;
    
    private function __construct()
    {
        // Usar env() directamente en lugar de config()
        $this->baseUrl = env('IMAGE_SERVICE_URL');
        $this->apiKey = env('IMAGE_SERVICE_API_KEY');
        
        // Remover comillas si existen
        $this->baseUrl = trim($this->baseUrl ?? '', "'\"");
        $this->apiKey = trim($this->apiKey ?? '', "'\"");
        
        // Log para debugging
        Log::info('ImageServiceHelper initialized', [
            'base_url' => $this->baseUrl ? '***' . substr($this->baseUrl, -20) : 'NULL',
            'api_key_set' => !empty($this->apiKey)
        ]);
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function upload($image, $fileName = null)
    {
        // Validar configuración
        if (empty($this->baseUrl) || empty($this->apiKey)) {
            Log::error('Image service configuration missing', [
                'base_url_empty' => empty($this->baseUrl),
                'api_key_empty' => empty($this->apiKey)
            ]);
            
            return [
                'success' => false,
                'error' => 'Image service not configured. Check IMAGE_SERVICE_URL and IMAGE_SERVICE_API_KEY environment variables.'
            ];
        }

        $fileName = $fileName ?: time() . '_' . $this->sanitizeFileName($image->getClientOriginalName());
        
        try {
            Log::info('Starting image upload', [
                'filename' => $fileName,
                'file_size' => $image->getSize(),
                'mime_type' => $image->getMimeType()
            ]);

            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => $image->getMimeType()
            ])
            ->withBody(
                file_get_contents($image->getRealPath()), 
                $image->getMimeType()
            )
            ->timeout(30)
            ->put("{$this->baseUrl}/files/{$fileName}");
            
            Log::info('Image upload response', [
                'status_code' => $response->status(),
                'success' => $response->successful()
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'filename' => $fileName,
                    'url' => $this->url($fileName)
                ];
            }
            
            return [
                'success' => false,
                'error' => 'Server returned: ' . $response->status() . ' - ' . $response->body(),
                'status' => $response->status()
            ];
            
        } catch (\Exception $e) {
            Log::error('Image upload failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return [
                'success' => false,
                'error' => 'Upload failed: ' . $e->getMessage()
            ];
        }
    }
    
    public function url($filename, $width = null, $height = null, $quality = null)
    {
        if (!$filename || !$this->baseUrl) return null;
        
        $transform = '';
        $params = [];
        
        if ($width) $params[] = "width={$width}";
        if ($height) $params[] = "height={$height}";
        if ($quality) $params[] = "quality={$quality}";
        
        if (!empty($params)) {
            $transform = implode(',', $params) . '/';
        }
        
        return "{$this->baseUrl}/files/{$transform}{$filename}";
    }
    
    public function delete($filename)
    {
        if (!$this->baseUrl || !$this->apiKey) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey
            ])->delete("{$this->baseUrl}/files/{$filename}");
            
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Image delete failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    private function sanitizeFileName($filename)
    {
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        $filename = substr($filename, 0, 100);
        return $filename;
    }
}