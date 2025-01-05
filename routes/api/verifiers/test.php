<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['name', 'auth_token'])) {
        $name = htmlspecialchars($this->data['name'], ENT_QUOTES, 'UTF-8');
        $auth_token = $this->data['auth_token'];

        if ($this->verifierService->isValidToken($auth_token)) {
            return $this->response([
                'message' => 'Hello ' . $name,
                'status' => true
            ], 200);
        }
        
        return $this->response([
            'message' => 'Unauthorized',
            'status' => false
        ], 401);
    }

    return $this->response([
        'message' => 'Bad Request',
        'status' => false
    ], 400);
};
