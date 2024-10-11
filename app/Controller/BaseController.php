<?php

namespace App\Controller;

use App\Core\View;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Request;

class BaseController
{
    public function __construct(
        private readonly View $view
    )
    {
        $this->addGlobals('appTheme', 'dark');
    }

    public function renderErrorPage(Response $response, $params = [])
    {
        $response->getBody()->write(
            (string) $this->view
                ->createPage('error', $params)
                ->render()
        );
        return $response->withStatus($params['code']);
    }
    
    public function render(Request $request, Response $response, string $viewPath, array $args)
    {
        $response->getBody()->write(
            (string) $this->view
                ->createPage($viewPath, $args)
                ->render()
        );

        return $response;
    }

    /**
     * Redirect response
     */
    public function redirect(Response $response, string $location, int $status = 302): Response
    {
        return $response->withHeader('Location', $location)->withStatus($status);
    }

    public function addGlobals(string $key, string $value)
    {
        $this->view->addGlobals($key, $value);
    }

    public function getStatic(Request $request, Response $response): Response
    {
        $type = $request->getAttribute('type');
        $fileName = $request->getAttribute('file');
        $file = get_asset_content($type, $fileName);

        $mimeType = '';
        if ($type === 'css') {
            $mimeType = 'text/css';
        } elseif ($type === 'js') {
            $mimeType = 'application/javascript';
        } else {
            // Handle other types if necessary
            $mimeType = mime_content_type($file['path']);
        }

        if (is_null($file['content'])) {
            return $response->withStatus(404);
        }

        $response->getBody()->write(
            (string) $file['content']
        );

        return $response
            ->withHeader('Content-Type', $mimeType)
            ->withHeader('Content-Length', filesize($file['path']))
            ->withHeader('Cache-Control', 'max-age=' . (60 * 60 * 24 * 365))
            ->withHeader('Expires', gmdate(DATE_RFC1123, time() + 60 * 60 * 24 * 365))
            ->withHeader('Last-Modified', gmdate(DATE_RFC1123, filemtime($file['path'])))
            ->withHeader('Pragma', '');
    }
}