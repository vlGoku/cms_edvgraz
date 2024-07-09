<?php

namespace EdvGraz\CMS;

class Session {
    public string $id;
    public string $forename;
    public string $role;

    public function __construct() {
        session_start();
        $this->id = $_SESSION['id'] ?? 0;
        $this->forname = $_SESSION['forename'] ?? '';
        $this->role = $_SESSION['role'] ?? 'visitor';
    }

    public function updateSession(array $user): void {
        $this->createSession($user);
    }

    public function createSession(array $user): void {
        \session_regenerate_id(true);
        $_SESSION['id'] = $user['id'];
        $_SESSION['forename'] = $user['forename'];
        $_SESSION['role'] = $user['role'];
    }

    public function destroySession(): void {
        $_SESSION = [];
        $cookie_data = session_get_cookie_params();
        setcookie(Session_name(), '', time() - 42000, $cookie_data['path'], $cookie_data['secure'], $cookie_data['httppnly']);
        session_destroy();
    }
}