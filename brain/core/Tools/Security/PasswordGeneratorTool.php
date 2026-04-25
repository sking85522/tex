<?php
namespace Core\Tools\Security;

/**
 * PasswordGeneratorTool
 * Generates secure, high-entropy random passwords.
 */
class PasswordGeneratorTool {
    
    public function run($params = []) {
        $length = $params['length'] ?? 16;
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
        
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return [
            'password' => $password,
            'entropy' => 'High',
            'length' => $length,
            'message' => "Sir, aapka naya secure password taiyar hai!"
        ];
    }
}
