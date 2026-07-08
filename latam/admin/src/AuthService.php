<?php

class AuthService
{
    const SESSION_FLAG = 'admin_authenticated';
    const SESSION_USER = 'admin_user';

    private $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function isAuthenticated()
    {
        return !empty($_SESSION[self::SESSION_FLAG]);
    }

    public function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            header('Location: index.php');
            exit;
        }
    }

    public function attempt($username, $password, $ip)
    {
        if ($this->limiter->isBlocked($ip)) {
            error_log('MSL admin auth: rate limited');
            return ['ok' => false, 'error' => 'blocked'];
        }

        $expectedUser = Env::get('ADMIN_USER', '');
        $hash = Env::get('ADMIN_PASSWORD_HASH', '');

        $userOk = is_string($username) && $expectedUser !== '' && hash_equals($expectedUser, $username);
        $passOk = is_string($password) && $hash !== '' && password_verify($password, $hash);

        if (!$userOk || !$passOk) {
            $this->limiter->registerFailure($ip);
            if ($this->limiter->isBlocked($ip)) {
                error_log('MSL admin auth: rate limited');
                return ['ok' => false, 'error' => 'blocked'];
            }
            error_log('MSL admin auth: failed login');
            return ['ok' => false, 'error' => 'invalid'];
        }

        $this->limiter->reset($ip);
        session_regenerate_id(true);
        $_SESSION[self::SESSION_FLAG] = true;
        $_SESSION[self::SESSION_USER] = $expectedUser;

        return ['ok' => true, 'error' => null];
    }

    public function logout()
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
