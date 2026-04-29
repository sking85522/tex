<?php
namespace Core\Engine;

/**
 * Remote Database Bridge - Adaptive Edition
 * For HRITIK AI Centralized Knowledge DB
 */
class RemoteDB {
    private $api_url = "https://databasehritikai.techelevatex.us.cc/api.php";
    private $api_key;

    // Default 5MB rakha hai, ise aap badha sakte hain (e.g., 10 * 1024 * 1024)
    public $chunk_limit = 5242880;
    private $test_cookie = null;

    public function __construct() {
        $this->api_key = getenv('REMOTE_DB_API_KEY') ?: 'SACHIN_SECURE_V1_2026';
    }

    private function getBypassCookie() {
        if ($this->test_cookie !== null) return $this->test_cookie;

        $html = "";

        if (function_exists('curl_init')) {
            $ch = curl_init($this->api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $html = curl_exec($ch);
            curl_close($ch);
        } else {
            // Fallback for strict hosts
            $options = [
                "http" => [
                    "method" => "GET",
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
                ],
                "ssl" => [
                    "verify_peer" => true,
                    "verify_peer_name" => false,
                ],
            ];
            $context = stream_context_create($options);
            $html = @file_get_contents($this->api_url, false, $context);
        }

        if ($html && preg_match('/a=toNumbers\("([a-f0-9]+)"\),b=toNumbers\("([a-f0-9]+)"\),c=toNumbers\("([a-f0-9]+)"\)/', $html, $m)) {
            $a = hex2bin($m[1]);
            $b = hex2bin($m[2]);
            $c = hex2bin($m[3]);
            $decrypted = openssl_decrypt($c, 'AES-128-CBC', $a, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $b);
            $this->test_cookie = "__test=" . bin2hex($decrypted);
            return $this->test_cookie;
        }
        return null;
    }

    public function query($sql) {
        $sql_size = strlen($sql);

        // Sirf INSERT query ko chunk karenge agar wo limit se badi hai
        if (stripos($sql, 'INSERT INTO') !== false && $sql_size > $this->chunk_limit) {
            return $this->processChunkedInsert($sql);
        }

        return $this->execute($sql);
    }

    private function execute($sql) {
        $cookie = $this->getBypassCookie();
        $response = false;
        $error = '';

        $postData = ['sql' => base64_encode($sql)];

        if (function_exists('curl_init')) {
            $ch = curl_init($this->api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

            $headers = ["X-API-Key: " . $this->api_key];
            if ($cookie) {
                curl_setopt($ch, CURLOPT_COOKIE, $cookie);
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);
        } else {
            $headers = "X-API-Key: " . $this->api_key . "\r\n";
            $headers .= "Content-Type: application/x-www-form-urlencoded\r\n";
            if ($cookie) {
                $headers .= "Cookie: " . $cookie . "\r\n";
            }

            $options = [
                "http" => [
                    "method" => "POST",
                    "header" => $headers,
                    "content" => http_build_query($postData),
                    "timeout" => 30
                ],
                "ssl" => [
                    "verify_peer" => true,
                    "verify_peer_name" => false,
                ],
            ];
            $context = stream_context_create($options);
            $response = @file_get_contents($this->api_url, false, $context);
            if ($response === false) {
                $error = error_get_last()['message'] ?? 'file_get_contents failed';
            }
        }

        $decoded = json_decode((string)$response, true);
        if ($decoded === null) {
             return ['status' => 'error', 'raw_response' => $response, 'curl_error' => $error];
        }
        return $decoded;
    }

    private function processChunkedInsert($sql) {
        // SQL pattern match: INSERT INTO table (cols) VALUES (...rows...)
        if (preg_match('/^(.*?VALUES\s+)(.*)$/is', $sql, $matches)) {
            $query_prefix = $matches[1]; // "INSERT INTO table (cols) VALUES "
            $all_rows_str = rtrim(trim($matches[2]), ';');

            // Rows ko '), (' pattern se split karna
            $rows = preg_split('/\),\s*\(/', $all_rows_str);

            $total_rows = count($rows);
            $current_batch = [];
            $batch_size_counter = 0;
            $responses = [];

            foreach ($rows as $index => $row) {
                // Bracket fix karna split ke baad
                $clean_row = trim($row, '()');
                $row_string = "(" . $clean_row . ")";

                $current_batch[] = $row_string;
                $batch_size_counter += strlen($row_string);

                // Agar limit hit ho gayi ya last row hai
                if ($batch_size_counter >= $this->chunk_limit || $index == $total_rows - 1) {
                    $final_sql = $query_prefix . implode(', ', $current_batch);
                    $responses[] = $this->execute($final_sql);

                    // Reset for next batch
                    $current_batch = [];
                    $batch_size_counter = 0;
                }
            }

            return [
                "status" => "success",
                "message" => "Chunked transfer successful",
                "chunks_sent" => count($responses),
                "details" => $responses
            ];
        }

        return $this->execute($sql); // Fallback if regex fails
    }
}
