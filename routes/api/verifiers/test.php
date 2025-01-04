<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['name']) && !empty($this->data['name'])) {
        $name = htmlspecialchars($this->data['name'], ENT_QUOTES, 'UTF-8');
        
        return $this->response([
            'message' => 'Hello ' . $name,
            'status' => true
        ], 200);
    }

    return $this->response([
        'message' => 'Bad Request',
        'status' => false
    ], 400);
};
