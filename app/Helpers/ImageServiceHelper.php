<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class ImageServiceHelper
{
    private static $instance;
    private $baseUrl;
    private $apiKey;
    
    private function __construct()
    {
        $this->baseUrl = config('services.image_service.url');
        $this->apiKey = config('services.image_service.api_key');
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
        if (!$this->baseUrl || !$this->apiKey) {
            return [
                'success' => false,
                'error' => 'Image service not configured'
            ];
        }

        $fileName = $fileName ?: time() . '_' . $this->sanitizeFileName($image->getClientOriginalName());
        
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => $image->getMimeType()
            ])->withBody(
                file_get_contents($image->getRealPath()), 
                $image->getMimeType()
            )->timeout(30)->put("{$this->baseUrl}/files/{$fileName}");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'filename' => $fileName,
                    'url' => $this->url($fileName)
                ];
            }
            
            return [
                'success' => false,
                'error' => $response->body(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
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
            return false;
        }
    }
    
    private function sanitizeFileName($filename)
    {
        // Remover caracteres especiales y espacios
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        // Limitar longitud
        $filename = substr($filename, 0, 100);
        return $filename;
    }
}