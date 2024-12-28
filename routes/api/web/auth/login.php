<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->auth->isAuthenticated()) {
        usleep(mt_rand(400000, 1300000));
        return $this->response([
            'message' => 'Already authenticated'
        ], 202);
    }
    
    if ($this->paramsExists(['email', 'password'])) {
        $user = $this->auth->login($this->data);
        
        if ($user) {
            $path = $this->view->urlFor('student.dashboard');

            if (UserRole::isAdministrator($user->getRole())) {
                $path = $this->view->urlFor('admin.dashboard');
            }
            
            usleep(mt_rand(400000, 1300000));
            return $this->response([
                'message' => 'Authenticated',
                'redirect' => $this->getRedirect($path)
            ], 202);
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
