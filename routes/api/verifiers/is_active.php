<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['machine_id'])) {

        $machine_id = $this->data['machine_id'];
        $headers = $this->negotiateHeaders($this->headers);
        $authToken = explode(' ', $headers['Authorization'])[1] ?? null;

        if (empty($machine_id) || empty($authToken)) {
            return $this->response([
                'message' => 'Bad Request'
            ], 400);
        }

        $verifier = $this->verifierService->isActive($authToken, $machine_id);

        if ($verifier) {
            return $this->response([
                'message' => 'Verifier is active',
                'status' => true
            ]);
        }

        return $this->response([
            'message' => 'Verifier is not active',
            'status' => false
        ], 400);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
