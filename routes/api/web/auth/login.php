<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated()) {
        usleep(mt_rand(400000, 1300000));
        return $this->response([
            'message' => 'Already authenticated'
        ], 202);
    }
    
    if ($this->paramsExists(['email', 'password'])) {
        $authResult = $this->auth->login($this->data);
        
        if ($authResult) {
            $user = $authResult['user'];
            $token = $authResult['token'];
            $path = $this->view->urlFor('student.dashboard');

            if (UserRole::isAdministrator($user->getRole()->value)) {
                $path = $this->view->urlFor('admin.dashboard');
            }
            
            usleep(mt_rand(400000, 1300000));
            return $this->response([
                'message' => 'Authenticated',
                'redirect' => $this->getRedirect($path),
                'token' => $token,
                'token_type' => 'Bearer',
            ], 202, 'application/json', [
                'Set-Cookie' => $this->jwt->buildAuthCookieHeader($token),
            ]);
        }
        usleep(mt_rand(400000, 1300000));
        return $this->response([
            'message' => 'Unauthorized'
        ], 401);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
