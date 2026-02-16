<?php

namespace App\Services;

use DateTime;
use App\Entity\User;
use App\Entity\LoginSession;
use App\Enum\UserRole;
use Doctrine\ORM\EntityManagerInterface;

class LoginSessionService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function createAdminSession(User $user, ?string $ipAddress, ?string $userAgent, int $ttl): ?LoginSession
    {
        if (!UserRole::isAdministrator($user->getRole()->value)) {
            return null;
        }

        $createdAt = new DateTime();
        $expiresAt = (clone $createdAt)->modify('+' . max(1, $ttl) . ' seconds');

        $session = new LoginSession();
        $session->setUser($user);
        $session->setTokenId(bin2hex(random_bytes(32)));
        $session->setIpAddress($ipAddress);
        $session->setUserAgent($userAgent);
        $session->setIsActive(true);
        $session->setCreatedAt($createdAt);
        $session->setExpiresAt($expiresAt);

        $this->em->persist($session);
        $this->em->flush();

        return $session;
    }

    public function getActiveSessionByToken(string $tokenId): ?LoginSession
    {
        if ($tokenId === '') {
            return null;
        }

        $session = $this->em->getRepository(LoginSession::class)->findOneBy(['tokenId' => $tokenId]);
        if (!$session instanceof LoginSession) {
            return null;
        }

        if (!$session->getIsActive()) {
            return null;
        }

        if ($session->getExpiresAt() <= new DateTime()) {
            $this->revokeByToken($tokenId);
            return null;
        }

        return $session;
    }

    public function revokeByToken(string $tokenId): bool
    {
        $session = $this->em->getRepository(LoginSession::class)->findOneBy(['tokenId' => $tokenId]);
        if (!$session instanceof LoginSession) {
            return false;
        }

        if (!$session->getIsActive()) {
            return true;
        }

        $session->setIsActive(false);
        $session->setRevokedAt(new DateTime());
        $this->em->persist($session);
        $this->em->flush();

        return true;
    }

    public function revokeForUser(User $user, string $tokenId): bool
    {
        $session = $this->em->getRepository(LoginSession::class)->findOneBy([
            'user' => $user,
            'tokenId' => $tokenId,
        ]);

        if (!$session instanceof LoginSession) {
            return false;
        }

        if (!$session->getIsActive()) {
            return true;
        }

        $session->setIsActive(false);
        $session->setRevokedAt(new DateTime());
        $this->em->persist($session);
        $this->em->flush();

        return true;
    }

    public function getUserSessions(User $user): array
    {
        return $this->em->getRepository(LoginSession::class)->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );
    }

    public function deleteForUser(User $user, string $tokenId): bool
    {
        $session = $this->em->getRepository(LoginSession::class)->findOneBy([
            'user' => $user,
            'tokenId' => $tokenId,
        ]);

        if (!$session instanceof LoginSession) {
            return false;
        }

        $this->em->remove($session);
        $this->em->flush();

        return true;
    }
}
