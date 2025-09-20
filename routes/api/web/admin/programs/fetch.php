<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && UserRole::isAdministrator($this->getRole())) {
        $programs = [];

        foreach ($this->academicService->getPrograms() as $program) {
            $program = $program->toArray();
            $programs[] = $program;
        }

        if (empty($programs)) {
            return $this->response([
                'message' => 'Programs have not been created!',
                'status' => false,
            ], 200);
        }

        return $this->response([
            'message' => 'Programs fetched',
            'status' => true,
            'data' => [
                'programs' => $programs
            ]
        ]);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
