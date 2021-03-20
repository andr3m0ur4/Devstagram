<?php
    namespace Models;

    use \Core\Model;

    class JWT extends Model
    {
        private $secret;

        public function __construct()
        {
            global $config;
            $this->secret = $config['jwt_secret_key'];
        }

        public function create($data)
        {
            $header = json_encode([
                'typ' => 'JWT',
                'alg' => 'HS256'
            ]);

            $payload = json_encode($data);

            $header_base = $this->base64EncodeUrl($header);
            $payload_base = $this->base64EncodeUrl($payload);
            
            $signature = hash_hmac(
                'sha256',
                "{$header_base}.{$payload_base}",
                $this->secret,
                true
            );
            $signature_base = $this->base64EncodeUrl($signature);

            $jwt = "{$header_base}.{$payload_base}.{$signature_base}";

            return $jwt;
        }

        public function validate($token)
        {
            $array = [];

            $jwt_split = explode('.', $token);

            if (count($jwt_split) == 3) {
                $signature = hash_hmac(
                    'sha256',
                    "{$jwt_split[0]}.{$jwt_split[1]}",
                    $this->secret,
                    true
                );
                $signature_base = $this->base64EncodeUrl($signature);

                if ($signature_base == $jwt_split[2]) {
                    $array = json_decode($this->base64DecodeUrl($jwt_split[1]));
                    return $array;
                }
            }
            
            return false;
        }

        private function base64EncodeUrl($string) {
            return str_replace(['+','/','='], ['-','_',''], base64_encode($string));
        }
        
        private function base64DecodeUrl($string) {
            return base64_decode(str_replace(['-','_'], ['+','/'], $string));
        }
    }
    