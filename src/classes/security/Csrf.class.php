<?php

namespace src\classes\security;

/**
 * Gera e valida tokens CSRF vinculados à sessão do navegador.
 */
class Csrf{

    private const SESSION_KEY = 'csrf_token';

    /** Retorna o token da sessão atual, criando-o quando necessário. */
    public static function token(){
        self::startSession();

        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    /** Verifica se o token recebido pertence à sessão atual. */
    public static function validate($token){
        self::startSession();

        return is_string($token)
            && isset($_SESSION[self::SESSION_KEY])
            && hash_equals($_SESSION[self::SESSION_KEY], $token);
    }

    /** Inicia uma sessão com cookies adequados para o token CSRF. */
    private static function startSession(){
        if (session_status() !== PHP_SESSION_NONE) return;

        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);

        session_set_cookie_params([
            'httponly' => true,
            'samesite' => 'Lax',
            'secure' => $isHttps,
        ]);
        session_start();
    }

}
