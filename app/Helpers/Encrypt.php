<?php

class Encrypter implements EncrypterContract, StringEncrypter
{
    protected $key;
    protected $cipher;

    private static $supportedCiphers = [
        'aes-128-cbc' => ['size' => 16, 'aead' => false],
        'aes-256-cbc' => ['size' => 32, 'aead' => false],
        'aes-128-gcm' => ['size' => 16, 'aead' => true],
        'aes-256-gcm' => ['size' => 32, 'aead' => true],
    ];

    public function __construct($key, $cipher = 'aes-256-cbc')
    {
        $key = (string) $key;

        if (! static::supported($key, $cipher)) {
            $ciphers = implode(', ', array_keys(self::$supportedCiphers));

            throw new RuntimeException("Chiper tidak didukung atau panjang kunci salah. Chiper yang didukung : {$ciphers}.");
        }

        $this->key = $key;
        $this->cipher = $cipher;
    }

    public static function supported($key, $cipher)
    {
        if (! isset(self::$supportedCiphers[strtolower($cipher)])) {
            return false;
        }

        return mb_strlen($key, '8bit') === self::$supportedCiphers[strtolower($cipher)]['size'];
    }

    public static function generateKey($cipher)
    {
        return random_bytes(self::$supportedCiphers[strtolower($cipher)]['size'] ?? 32);
    }

    public function encrypt($value, $serialize = true)
    {
        $iv = random_bytes(openssl_cipher_iv_length(strtolower($this->cipher)));

        $value = \openssl_encrypt(
            $serialize ? serialize($value) : $value,
            strtolower($this->cipher), $this->key, 0, $iv, $tag
        );

        if ($value === false) {
            throw new EncryptException('Tidak dapat mengenkripsi data.');
        }

        $iv = base64_encode($iv);
        $tag = base64_encode($tag ?? '');

        $json = json_encode(compact('iv', 'value', 'mac', 'tag'), JSON_UNESCAPED_SLASHES);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new EncryptException('Tidak dapat mengenkripsi data.');
        }

        return base64_encode($json);
    }

    public function decrypt($payload, $unserialize = true)
    {
        $payload = $this->getJsonPayload($payload);

        $iv = base64_decode($payload['iv']);

        $this->ensureTagIsValid(
            $tag = empty($payload['tag']) ? null : base64_decode($payload['tag'])
        );

        // Di sini kita akan mendekripsi valuenya. Jika kita berhasil mendekripsinya
        // kita akan menghapus serialnya dan mengembalikannya ke pemanggil. Jika kita
        // tidak dapat mendekripsi value ini, kita akan membuang pesan pengecualian.
        $decrypted = \openssl_decrypt(
            $payload['value'], strtolower($this->cipher), $this->key, 0, $iv, $tag ?? ''
        );

        if ($decrypted === false) {
            throw new DecryptException('Tidak dapat mendekripsi data.');
        }

        return $unserialize ? unserialize($decrypted) : $decrypted;
    }

    protected function hash($iv, $value)
    {
        return hash_hmac('sha256', $iv.$value, $this->key);
    }
    
    protected function getJsonPayload($payload)
    {
        $payload = json_decode(base64_decode($payload), true);

        // Jika muatannya bukan JSON yang valid atau tidak memiliki set kunci yang tepat, kita akan
        // menganggapnya tidak valid dan keluar dari rutinitas karena kita tidak akan bisa 
        // mendekripsi nilai yang diberikan. Kita juga akan memeriksa MAC untuk enkripsi ini.
        if (! $this->validPayload($payload)) {
            throw new DecryptException('Payload tidak valid.');
        }

        if (! self::$supportedCiphers[strtolower($this->cipher)]['aead'] && ! $this->validMac($payload)) {
            throw new DecryptException('MAC tidak valid.');
        }

        return $payload;
    }

    protected function validPayload($payload)
    {
        if (! is_array($payload)) {
            return false;
        }

        foreach (['iv', 'value', 'mac'] as $item) {
            if (! isset($payload[$item]) || ! is_string($payload[$item])) {
                return false;
            }
        }

        if (isset($payload['tag']) && ! is_string($payload['tag'])) {
            return false;
        }

        return strlen(base64_decode($payload['iv'], true)) === openssl_cipher_iv_length(strtolower($this->cipher));
    }

    protected function validMac(array $payload)
    {
        return hash_equals(
            $this->hash($payload['iv'], $payload['value']), $payload['mac']
        );
    }

    protected function ensureTagIsValid($tag)
    {
        if (self::$supportedCiphers[strtolower($this->cipher)]['aead'] && strlen($tag) !== 16) {
            throw new DecryptException('Tidak dapat mendekripsi data.');
        }

        if (! self::$supportedCiphers[strtolower($this->cipher)]['aead'] && is_string($tag)) {
            throw new DecryptException('Tidak dapat menggunakan tag karena algoritma cipher tidak mendukung AEAD.');
        }
    }

    public function getKey()
    {
        return $this->key;
    }
}
