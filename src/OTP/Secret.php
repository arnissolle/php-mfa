<?php

namespace Arnissolle\MFA\OTP;

/**
 * Class Secret
 *
 * @package Arnissolle\MFA\OTP
 */
class Secret
{
    /**
     * The Base 32 Alphabet (rfc3548)
     *
     * @var array
     */
    public static $base32Alphabet = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', // 0-7
        'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 8-15
        'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 16-23
        'Y', 'Z', '2', '3', '4', '5', '6', '7', // 24-31
        '=', // pad
    ];

    /**
     * Create new secret.
     * 16 characters (default), randomly chosen from the allowed base32 characters.
     *
     * @param int $secretLength
     * @return string
     * @throws \Exception
     */
    public static function create(int $secretLength = 16): string
    {
        if ($secretLength < 16 || $secretLength > 128) {
            throw new \Exception('Bad secret length');
        }

        $secret = '';
        $randomBytes = random_bytes($secretLength);

        for ($i = 0; $i < $secretLength; ++$i) {
            $index = ord($randomBytes[$i]) & 31;
            $secret .= self::$base32Alphabet[$index];
        }

        return $secret;
    }

    /**
     * Helper class to decode base32.
     *
     * @param string $secret
     * @return bool|string
     */
    public static function base32Decode(string $secret)
    {
        if ( ! $secret) {
            return false;
        }

        $base32chars = self::$base32Alphabet;
        $base32charsFlipped = array_flip($base32chars);

        $paddingCharCount = substr_count($secret, $base32chars[32]);
        $allowedValues = array(6, 4, 3, 1, 0);
        if (!in_array($paddingCharCount, $allowedValues)) {
            return false;
        }
        for ($i = 0; $i < 4; ++$i) {
            if ($paddingCharCount == $allowedValues[$i] &&
                substr($secret, -($allowedValues[$i])) != str_repeat($base32chars[32], $allowedValues[$i])) {
                return false;
            }
        }
        $secret = str_replace('=', '', $secret);
        $secret = str_split($secret);
        $binaryString = '';
        for ($i = 0; $i < count($secret); $i = $i + 8) {
            $x = '';
            if (!in_array($secret[$i], $base32chars)) {
                return false;
            }
            for ($j = 0; $j < 8; ++$j) {
                $x .= str_pad(base_convert(@$base32charsFlipped[@$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
            }
            $eightBits = str_split($x, 8);
            for ($z = 0; $z < count($eightBits); ++$z) {
                $binaryString .= (($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48) ? $y : '';
            }
        }

        return $binaryString;
    }
}
