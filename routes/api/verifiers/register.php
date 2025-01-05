<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['machine_id'])) {

        $ip_address = $this->data['ip_address'] ?? null;
        $headers = $this->negotiateHeaders($this->headers);
        // $ip_address = $headers['Host'] ?? null;
        
        $machine_id = $this->data['machine_id'];
        $authToken = explode(' ', $headers['Authorization'])[1];

        if (empty($machine_id) || empty($ip_address) || empty($authToken)) {
            return $this->response([
                'message' => 'Bad Request'
            ], 400);
        }

        $verifier = $this->verifierService->register($machine_id, $ip_address, $authToken);

        if ($verifier) {
            return $this->response([
                'message' => 'Verifier Registered',
                'status' => true
            ]);
        }

        return $this->response([
            'message' => 'Verifier failed to register',
            'status' => false
        ], 400);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
