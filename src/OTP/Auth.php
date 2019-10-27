<?php

namespace Arnissolle\MFA\OTP;

define(__NAMESPACE__ . '\TIME_BASED', 'totp');
define(__NAMESPACE__ . '\COUNTER_BASED', 'hotp');
define(__NAMESPACE__ . '\ALGORITHM_SHA1', 'SHA1');
define(__NAMESPACE__ . '\ALGORITHM_SHA256', 'SHA256');
define(__NAMESPACE__ . '\ALGORITHM_SHA512', 'SHA512');

/**
 * Class Auth
 *
 * @package Arnissolle\MFA\OTP
 */
class Auth
{
    public $issuer = null;
    public $type = TIME_BASED;
    public $algorithm = ALGORITHM_SHA1;
    public $counter = 0;
    public $period = 30;
    public $digits = 6;

    private function __construct() {}

    /**
     * Encoded OTP Auth Uri
     *
     * @param string $secret
     * @param string $label
     * @param callable|null $callback
     * @return string
     */
    public static function uri(string $secret, string $label, callable $callback = null): string
    {
        $auth = new Auth();

        if (isset($callback)) {
            call_user_func($callback, $auth);
        }

        $parameters = get_object_vars($auth);
        $parameters['secret'] = $secret;

        if ( ! $parameters['issuer']) {
            unset($parameters['issuer']);
        }

        if ($parameters['type'] === TIME_BASED) {
            unset($parameters['counter']);
        }

        if ($parameters['type'] === COUNTER_BASED) {
            unset($parameters['period']);
        }

        $label = rawurlencode($label);
        $query_string = http_build_query($parameters);

        return urlencode("otpauth://{$parameters['type']}/{$label}?{$query_string}");
    }

    /**
     * QR Code URL of the OTP Auth Uri
     *
     * @param string $otpAuthUri
     * @param array $params
     * @return string
     */
    public static function qrCodeUrl(string $otpAuthUri, array $params = []): string
    {
        $width = !empty($params['width']) && (int) $params['width'] > 0 ? (int) $params['width'] : 200;
        $height = !empty($params['height']) && (int) $params['height'] > 0 ? (int) $params['height'] : 200;
        $level = !empty($params['level']) && array_search($params['level'], ['L', 'M', 'Q', 'H']) !== false ? $params['level'] : 'M';

        return "https://api.qrserver.com/v1/create-qr-code/?data={$otpAuthUri}&size={$width}x{$height}&ecc={$level}";
    }
}