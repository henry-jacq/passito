<?php

namespace App\Core;

use App\Core\Session;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class Request
{
    public function __construct(private readonly Session $session)
    {        
    }
    
    public function getReferer(ServerRequestInterface $request): string
    {
        $referer = $request->getHeader('referer')[0] ?? '';

        if (!$referer) {
            return $this->session->get('previousUrl');
        }

        $refererHost = parse_url($referer, PHP_URL_HOST);

        if ($refererHost !== $request->getUri()->getHost()) {
            $referer = $this->session->get('previousUrl');
        }

        return $referer;        
    }

    public function isXhr(ServerRequestInterface $request): bool
    {
        return $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
    }

    public function shouldStoreRedirect(ServerRequestInterface $request): bool
    {
        if (strtoupper($request->getMethod()) !== 'GET') {
            return false;
        }

        if ($this->isXhr($request)) {
            return false;
        }

        $path = $request->getUri()->getPath();
        if (str_starts_with($path, '/api')) {
            return false;
        }

        if ($path === '/auth/login') {
            return false;
        }

        return true;
    }

    public function normalizeRedirectUri(UriInterface $uri): string
    {
        $path = $uri->getPath() ?: '/';
        $query = $uri->getQuery();
        if ($query !== '') {
            return $path . '?' . $query;
        }

        return $path;
    }
}
