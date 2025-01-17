<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['data'])) {
        $headers = $this->negotiateHeaders($this->headers);
        $authToken = explode(' ', $headers['Authorization'])[1] ?? null;

        // Check if 'data' is a string, if so, decode it
        $jsonData = $this->data['data'];

        if (is_string($jsonData)) {
            $jsonData = json_decode($jsonData, true); // Decode as associative array
            // Check if the 'data' contains valid JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->response([
                    'message' => 'Invalid JSON data provided.'
                ], 400);
            }
        }
        
        if (empty($authToken)) {
            return $this->response([
                'message' => 'Bad Request'
            ], 400);
        }

        $verifier = $this->verifierService->syncData($authToken, $jsonData);

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
