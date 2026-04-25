<?php
namespace Core\Tools\Network;

/**
 * IPLookupTool
 * Fetches geolocation and metadata for an IP address.
 */
class IPLookupTool {
    
    public function run($params = []) {
        $ip = $params['ip'] ?? $_SERVER['REMOTE_ADDR'] ?? '8.8.8.8';
        if ($ip === '::1') $ip = '103.21.159.1'; // Mock for localhost

        $url = "http://ip-api.com/json/{$ip}";
        
        try {
            $response = @file_get_contents($url);
            if (!$response) return "Network Error: Could not connect to IP Database.";
            
            $data = json_decode($response, true);
            if ($data['status'] === 'fail') return "Error: " . ($data['message'] ?? 'Invalid IP');

            return [
                'ip' => $ip,
                'location' => "{$data['city']}, {$data['regionName']}, {$data['country']}",
                'isp' => $data['isp'],
                'lat_lon' => "{$data['lat']}, {$data['lon']}",
                'message' => "Sir, is IP ki details mil gayi hain ({$ip})."
            ];
        } catch (\Exception $e) {
            return "IP Lookup Failed.";
        }
    }
}
