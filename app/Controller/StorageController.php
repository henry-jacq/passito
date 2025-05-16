<?php

namespace App\Controller;

use App\Core\Storage;
use App\Entity\Student;
use App\Services\OutpassService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StorageController extends BaseController
{
    public function __construct(
        private readonly Storage $storage,
        private readonly OutpassService $outpassService,
    ) {
    }
    
    public function admin(Request $request, Response $response): Response
    {
        $id = (int) $request->getAttribute('id');
        $admin = $request->getAttribute('user');
        $params = $request->getAttribute('params');

        // Restrict access to matching admin ID
        if ($admin->getId() !== $id) {
            return $response->withStatus(403, 'Forbidden');
        }

        // Process and sanitize params
        if ($params !== false) {
            $params = implode(DIRECTORY_SEPARATOR, array_map('basename', explode('/', $params)));
        }

        $path = $this->storage->getFullPath($params);

        // Validate file existence and accessibility
        if (!is_file($path) || !is_readable($path)) {
            return $response->withStatus(404, 'File Not Found');
        }

        // Stream file content
        $fileSize = filesize($path);
        $mimeType = mime_content_type($path);

        $response->getBody()->write($this->storage->read($params));

        return $response
            ->withHeader('Content-Type', $mimeType)
            ->withHeader('Content-Length', $fileSize)
            ->withHeader('Cache-Control', 'max-age=' . (60 * 60 * 24 * 365))
            ->withHeader('Expires', gmdate(DATE_RFC1123, time() + 60 * 60 * 24 * 365))
            ->withHeader('Last-Modified', gmdate(DATE_RFC1123, filemtime($path)))
            ->withHeader('Pragma', '');
    }

    public function student(Request $request, Response $response): Response
    {
        $id = (int) $request->getAttribute('id');
        $student = $request->getAttribute('student');
        $params = $request->getAttribute('params');

        // Restrict access to matching student ID
        if ($student->getUser()->getId() !== $id) {
            return $response->withStatus(403, 'Forbidden');
        }

        if ($this->verifyOutpassOwner($student, $params) === false) {
            return $response->withStatus(403, 'Forbidden');
        }
        
        // Process and sanitize params
        if ($params !== false) {
            $params = implode(DIRECTORY_SEPARATOR, array_map('basename', explode('/', $params)));
        }

        $path = $this->storage->getFullPath($params);

        // Validate file existence and accessibility
        if (!is_file($path) || !is_readable($path)) {
            return $response->withStatus(404, 'File Not Found');
        }

        // Stream file content
        $fileSize = filesize($path);
        $mimeType = mime_content_type($path);

        $response->getBody()->write($this->storage->read($params));

        return $response
            ->withHeader('Content-Type', $mimeType)
            ->withHeader('Content-Length', $fileSize)
            ->withHeader('Cache-Control', 'max-age=' . (60 * 60 * 24 * 365))
            ->withHeader('Expires', gmdate(DATE_RFC1123, time() + 60 * 60 * 24 * 365))
            ->withHeader('Last-Modified', gmdate(DATE_RFC1123, filemtime($path)))
            ->withHeader('Pragma', '');
    }

    private function verifyOutpassOwner(Student $student, $filePath)
    {
        $outpasses = $this->outpassService->getOutpassByStudent($student);

        foreach ($outpasses as $outpass) {
            $qrCode = $outpass->getQrCode();
            $document = $outpass->getDocument();
            $attachment = $outpass->getAttachments();
            
            $qrCodePath = $this->storage->getFullPath("qr_codes/{$qrCode}");
            $outpassPath = $this->storage->getFullPath("outpasses/{$document}");

            if (strpos($outpassPath, $filePath) !== false) {
                return true;
            }

            if (strpos($qrCodePath, $filePath) !== false) {
                return true;
            }

            foreach ($attachment as $file) {
                $attachmentPath = $this->storage->getFullPath("attachments/{$file}");

                if (strpos($attachmentPath, $filePath) !== false) {
                    return true;
                }
            }
        }

        return false;
    }
}
