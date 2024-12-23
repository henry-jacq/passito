<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['user', 'password'])) {
        $result = $this->auth->login($this->data);
        
        if ($this->auth->login($this->data)) {
            $path = "/student/dashboard";

            if ($result['role'] === 'admin') {
                $path = "/admin/dashboard";
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
