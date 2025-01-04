<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['machine_id'])) {
        $machine_id = $this->data['machine_id'];
        $headers = $this->negotiateHeaders($this->headers);
        $host = $headers['Host'];
        $authToken = explode(' ', $headers['Authorization'])[1];

        if (empty($machine_id) || empty($host) || empty($authToken)) {
            return $this->response([
                'message' => 'Bad Request'
            ], 400);
        }

        $verifier = $this->verifierService->register($machine_id, $host, $authToken);

        if ($verifier) {
            return $this->response([
                'message' => 'Verifier registered successfully',
                'status' => true
            ]);
        }

        return $this->response([
            'message' => 'Verifier registration failed',
            'status' => false
        ], 400);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
