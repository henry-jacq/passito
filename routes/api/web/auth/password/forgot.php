<?php

use App\Entity\PasswordResetToken;
use App\Core\JobPayloadBuilder;
use App\Jobs\SendPasswordResetEmail;

${basename(__FILE__, '.php')} = function () {
    if (!$this->paramsExists(['email'])) {
        return $this->response([
            'message' => 'Bad Request',
            'status' => false,
        ], 400);
    }

    $email = strtolower(trim((string) ($this->data['email'] ?? '')));
    if (empty($email) || !filterEmail($email)) {
        return $this->response([
            'message' => 'Please enter a valid email address.',
            'status' => false,
        ], 400);
    }

    $user = $this->userService->getUserByEmail($email);

    // Always respond the same to avoid account enumeration.
    $genericResponse = function () {
        usleep(mt_rand(250000, 900000));
        return $this->response([
            'message' => 'If an account exists for that email, we sent a password reset link.',
            'status' => true,
        ], 202);
    };

    if (!$user) {
        return $genericResponse();
    }

    // Invalidate any previous (unconsumed) tokens for this user.
    $existing = $this->em->getRepository(PasswordResetToken::class)->findBy([
        'user' => $user,
        'consumedAt' => null,
    ]);
    foreach ($existing as $token) {
        $this->em->remove($token);
    }

    $rawToken = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    $tokenHash = hash('sha256', $rawToken);

    $reset = new PasswordResetToken();
    $reset->setUser($user);
    $reset->setTokenHash($tokenHash);
    $reset->setCreatedAt(new DateTime());
    $reset->setExpiresAt(new DateTime('+60 minutes'));

    $serverParams = $this->slimRequest->getServerParams();
    $ip = $serverParams['REMOTE_ADDR'] ?? null;
    $ua = $this->slimRequest->getHeaderLine('User-Agent') ?: null;
    $reset->setRequestIp(is_string($ip) ? $ip : null);
    $reset->setUserAgent(is_string($ua) ? substr($ua, 0, 255) : null);

    $this->em->persist($reset);
    $this->em->flush();

    // Build reset link. Uses web route name if available.
    $path = '/auth/reset?token=' . rawurlencode($rawToken);
    try {
        $path = $this->view->urlFor('auth.reset', [], ['token' => $rawToken]);
    } catch (\Throwable $e) {
        // Ignore; keep fallback.
    }
    $host = rtrim((string) $this->config->get('app.host'), '/');
    $resetLink = $host . $path;

    try {
        $jobPayload = JobPayloadBuilder::create()
            ->set('user_id', $user->getId())
            ->set('to', $user->getEmail())
            ->set('subject', 'Reset your password')
            ->set('reset_link', $resetLink)
            ->set('expires_minutes', 60);

        $this->queue->dispatch(SendPasswordResetEmail::class, $jobPayload);
    } catch (\Throwable $e) {
        // Keep response stable even if queue dispatch fails.
    }

    return $genericResponse();
};
