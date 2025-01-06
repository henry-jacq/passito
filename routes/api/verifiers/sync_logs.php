<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['machine_id', 'data'])) {

        $machine_id = $this->data['machine_id'];
        $headers = $this->negotiateHeaders($this->headers);
        $authToken = explode(' ', $headers['Authorization'])[1] ?? null;

        if (empty($machine_id) || empty($authToken)) {
            return $this->response([
                'message' => 'Bad Request'
            ], 400);
        }

        $verifier = $this->verifierService->syncData($authToken, $machine_id);

        if ($verifier) {
            return $this->response([
                'message' => 'Data is synced',
                'status' => true
            ]);
        }

        return $this->response([
            'message' => 'Failed to sync data',
            'status' => false
        ], 400);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
