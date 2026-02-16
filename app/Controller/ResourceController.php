<?php

namespace App\Controller;

use App\Core\Storage;
use App\Core\View;
use App\Core\Config;
use App\Entity\FileAccessLog;
use App\Entity\Student;
use App\Entity\User;
use App\Enum\UserRole;
use App\Services\FileService;
use App\Services\JwtService;
use App\Services\SecureLinkService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ResourceController extends BaseController
{
    public function __construct(
        View $view,
        private readonly Config $config,
        private readonly FileService $fileService,
        private readonly SecureLinkService $links,
        private readonly JwtService $jwt,
        private readonly EntityManagerInterface $em,
        private readonly Storage $storage
    ) {
        parent::__construct($view);
    }

    public function access(Request $request, Response $response): Response
    {
        $token = (string) $request->getAttribute('token');
        $decoded = $this->links->parseToken($token);
        if (!$decoded) {
            return $response->withStatus(404, 'Not Found');
        }

        return match ($decoded['type']) {
            'file' => $this->serveFile($decoded['id'], $request, $response),
            'student' => $this->redirectStudent($decoded['id'], $request, $response),
            'verifier' => $this->redirectVerifier($request, $response),
            'report' => $this->redirectReport($request, $response),
            default => $response->withStatus(404, 'Not Found'),
        };
    }

    public function static(Request $request, Response $response): Response
    {
        $relative = (string) $request->getAttribute('path');
        if ($relative === '') {
            return $response->withStatus(404, 'Not Found');
        }

        $assetsPath = (string) $this->config->get('resources.assets_path', '');
        if ($assetsPath === '') {
            return $response->withStatus(404, 'Not Found');
        }

        $base = realpath($assetsPath);
        if ($base === false) {
            return $response->withStatus(404, 'Not Found');
        }

        $candidate = realpath($base . DIRECTORY_SEPARATOR . ltrim($relative, '/'));
        if ($candidate === false || !str_starts_with($candidate, $base . DIRECTORY_SEPARATOR)) {
            return $response->withStatus(404, 'Not Found');
        }

        if (!is_file($candidate) || !is_readable($candidate)) {
            return $response->withStatus(404, 'Not Found');
        }

        $ext = strtolower(pathinfo($candidate, PATHINFO_EXTENSION));
        $mimeMap = (array) $this->config->get('resources.static_mime_types', []);

        if (!array_key_exists($ext, $mimeMap)) {
            return $response->withStatus(404, 'Not Found');
        }

        $contents = file_get_contents($candidate);
        if ($contents === false) {
            return $response->withStatus(404, 'Not Found');
        }

        $response->getBody()->write($contents);

        return $response
            ->withHeader('Content-Type', $mimeMap[$ext])
            ->withHeader('Content-Length', (string) filesize($candidate))
            ->withHeader('Cache-Control', 'public, max-age=' . (60 * 60 * 24 * 365))
            ->withHeader('Expires', gmdate(DATE_RFC1123, time() + 60 * 60 * 24 * 365))
            ->withHeader('Last-Modified', gmdate(DATE_RFC1123, filemtime($candidate)))
            ->withHeader('Pragma', '');
    }

    private function serveFile(string $uuid, Request $request, Response $response): Response
    {
        $file = $this->fileService->getByUuid($uuid);
        if (!$file) {
            return $response->withStatus(404, 'Not Found');
        }

        $user = $this->resolveUser($request);
        if (!$this->fileService->canAccess($file, $user)) {
            return $response->withStatus(403, 'Forbidden');
        }

        $storagePath = $file->getStoragePath();
        if (!$this->storage->fileExists($storagePath)) {
            return $response->withStatus(404, 'File Not Found');
        }

        $this->logAccess($file, $user, $request);

        $contents = $this->storage->read($storagePath);
        if ($contents === null) {
            return $response->withStatus(404, 'File Not Found');
        }

        $fileSize = $this->storage->fileSize($storagePath) ?? strlen($contents);
        $mimeType = $file->getMimeType() ?: ($this->storage->mimeType($storagePath) ?? 'application/octet-stream');
        $lastModifiedTs = $this->storage->lastModified($storagePath) ?? time();
        $dispositionName = str_replace('"', '', $file->getOriginalName());

        $response->getBody()->write($contents);

        return $response
            ->withHeader('Content-Type', $mimeType)
            ->withHeader('Content-Length', (string) $fileSize)
            ->withHeader('Content-Disposition', 'inline; filename="' . $dispositionName . '"')
            ->withHeader('Cache-Control', 'private, max-age=' . (60 * 60 * 24 * 7))
            ->withHeader('Expires', gmdate(DATE_RFC1123, time() + 60 * 60 * 24 * 7))
            ->withHeader('Last-Modified', gmdate(DATE_RFC1123, $lastModifiedTs))
            ->withHeader('Pragma', '');
    }

    private function redirectStudent(string $studentId, Request $request, Response $response): Response
    {
        $user = $this->resolveUser($request);
        if (!$user) {
            return $response->withStatus(403, 'Forbidden');
        }

        if (UserRole::isAdministrator($user->getRole()->value)) {
            return $this->redirect($response, $this->view->urlFor('admin.manage.students.details', [
                'student_id' => (int) $studentId,
            ]));
        }

        if (UserRole::isStudent($user->getRole()->value)) {
            $student = $this->em->getRepository(Student::class)->findOneBy(['user' => $user]);
            if ($student && (string) $student->getId() === (string) $studentId) {
                return $this->redirect($response, $this->view->urlFor('student.profile'));
            }
        }

        return $response->withStatus(403, 'Forbidden');
    }

    private function redirectVerifier(Request $request, Response $response): Response
    {
        $user = $this->resolveUser($request);
        if (!$user) {
            return $response->withStatus(403, 'Forbidden');
        }

        if (UserRole::isAdministrator($user->getRole()->value)) {
            return $this->redirect($response, $this->view->urlFor('admin.manage.verifiers'));
        }

        if (UserRole::isVerifier($user->getRole()->value)) {
            return $this->redirect($response, $this->view->urlFor('verifier.dashboard'));
        }

        return $response->withStatus(403, 'Forbidden');
    }

    private function redirectReport(Request $request, Response $response): Response
    {
        $user = $this->resolveUser($request);
        if (!$user || !UserRole::isAdministrator($user->getRole()->value)) {
            return $response->withStatus(403, 'Forbidden');
        }

        return $this->redirect($response, $this->view->urlFor('admin.outpass.settings'));
    }

    private function resolveUser(Request $request): ?User
    {
        $token = $this->jwt->extractToken($request);
        if (!$token) {
            return null;
        }

        $payload = $this->jwt->decode($token);
        if (!$payload || empty($payload['sub'])) {
            return null;
        }

        return $this->em->getRepository(User::class)->find((int) $payload['sub']);
    }

    private function logAccess($file, ?User $user, Request $request): void
    {
        $log = new FileAccessLog();
        $log->setFile($file);
        $log->setUser($user);
        $log->setAction('view');
        $log->setAccessedAt(new \DateTime());
        $log->setIpAddress($request->getServerParams()['REMOTE_ADDR'] ?? null);
        $log->setUserAgent($request->getHeaderLine('User-Agent') ?: null);
        $this->em->persist($log);
        $this->em->flush();
    }
}
