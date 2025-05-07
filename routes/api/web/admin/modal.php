<?php

${basename(__FILE__, '.php')} = function () {
    
    if ($this->auth->isAuthenticated() && $this->paramsExists(['template'])) {

        $templateName = $this->data['template'];
        $modalBody = $this->view->renderTemplate('modals/'.$templateName, $this->data);

        if ($modalBody == false) {
            return $this->response([
                'message' => 'Modal Template Not Found'
            ], 404);
        }
        
        return $this->response([
            'message' => $templateName,
            'html' => $modalBody
        ], 200, 'text/html');
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
