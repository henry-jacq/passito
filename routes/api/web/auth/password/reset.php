<?php

use App\Entity\PasswordResetToken;

${basename(__FILE__, '.php')} = function () {
    if (!$this->paramsExists(['token', 'password'])) {
        return $this->response([
            'message' => 'Bad Request',
            'status' => false,
        ], 400);
    }

    $rawToken = (string) ($this->data['token'] ?? '');
    $password = (string) ($this->data['password'] ?? '');
    $confirm = (string) ($this->data['password_confirmation'] ?? $this->data['confirm_password'] ?? '');

    if ($rawToken === '' || strlen($rawToken) < 20) {
        return $this->response([
            'message' => 'Invalid or expired reset token.',
            'status' => false,
        ], 400);
    }

    if (strlen($password) < 8) {
        return $this->response([
            'message' => 'Password must be at least 8 characters.',
            'status' => false,
        ], 400);
    }

    if ($confirm !== '' && !hash_equals($password, $confirm)) {
        return $this->response([
            'message' => 'Passwords do not match.',
            'status' => false,
        ], 400);
    }

    $tokenHash = hash('sha256', $rawToken);
    $reset = $this->em->getRepository(PasswordResetToken::class)->findOneBy([
        'tokenHash' => $tokenHash,
    ]);

    $now = new DateTime();
    if (
        !$reset ||
        $reset->getConsumedAt() !== null ||
        $reset->getExpiresAt() < $now
    ) {
        usleep(mt_rand(250000, 900000));
        return $this->response([
            'message' => 'Invalid or expired reset token.',
            'status' => false,
        ], 400);
    }

    $user = $reset->getUser();
    $user->setPassword(password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]));
    $reset->setConsumedAt($now);

    // Remove any other outstanding tokens for this user.
    $others = $this->em->getRepository(PasswordResetToken::class)->findBy([
        'user' => $user,
        'consumedAt' => null,
    ]);
    foreach ($others as $other) {
        if ($other->getId() !== $reset->getId()) {
            $this->em->remove($other);
        }
    }

    $this->em->flush();

    usleep(mt_rand(250000, 900000));
    return $this->response([
        'message' => 'Password updated. You can now sign in.',
        'status' => true,
    ], 200);
};
