<?php

namespace App\Controller;

use Closure;
use App\Core\View;
use App\Core\Session;
use App\Core\Storage;
use App\Services\SMSService;
use App\Services\AuthService;
use App\Services\MailService;
use App\Services\UserService;
use InvalidArgumentException;
use App\Services\AdminService;
use App\Services\OutpassService;
use App\Services\FacilityService;
use App\Services\VerifierService;
use App\Services\ParentVerificationService;
use App\Utils\CsvProcessor;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ApiController
{
    protected $data;
    protected $files;
    protected $params;
    protected $headers;
    protected $resource;
    protected $namespace;
    protected $attributes;
    private $current_call;
    protected $slimRequest;
    protected $slimResponse;
    private const CONTENT_TYPE = 'application/json';
    private const ALLOWED_CONTENT_TYPES = [
        'application/json',
        'application/zip',
        'application/xml',
        'application/xhtml',
        'text/html'
    ];
    private const ALLOWED_HEADERS = [
        'Content-Type', 'Content-Length',
        'Content-Disposition', 'Pragma', 'Host',
        'Cache-Control', 'Expires', 'Last-Modified', 'Authorization'
    ];
    private const API_ROUTE = ROUTES_PATH . DIRECTORY_SEPARATOR . 'api';

    public function __construct(
        private readonly View $view,
        private readonly Session $session,
        private readonly AuthService $auth,
        private readonly MailService $mail,
        private readonly SMSService $sms,
        private readonly Storage $storage,
        private readonly UserService $userService,
        private readonly AdminService $adminService,
        private readonly OutpassService $outpassService,
        private readonly VerifierService $verifierService,
        private readonly FacilityService $facilityService,
        private readonly CsvProcessor $csvProcessor,
        private readonly ParentVerificationService $verificationService
    )
    {
    }
    
    /**
     * Process API request
     */
    public function process(Request $request, Response $response): Response
    {
        $this->slimRequest = $request;
        $this->slimResponse = $response;
        $this->files = $request->getUploadedFiles();
        $this->params = $request->getServerParams();
        $this->attributes = $request->getAttributes();
        $this->headers = $request->getHeaders();

        $get = $this->cleanInputs($request->getQueryParams());
        $post = $this->cleanInputs($request->getParsedBody() ?? []);
        $this->data = array_merge($get, $post);

        // $this->negotiateHeaders($request->getHeaders());
        // $this->negotiateContentType($request->getHeader('Accept'));
        
        $resource = trim($request->getAttribute('resource'));
        $namespace = trim($request->getAttribute('namespace'));
        $this->namespace = strtolower($namespace);
        $this->resource = strtolower(basename($resource, '.php'));
        
        return $this->handle();
    }

    /**
     * Return API file path if it exists
     */
    protected function fileExists()
    {
        $apiPath = self::API_ROUTE . DIRECTORY_SEPARATOR;
        $filePath = implode(DIRECTORY_SEPARATOR, [$this->namespace, $this->resource]) . '.php';

        $params = $this->handleParams();

        if ($params !== false) {
            $filePath = implode(DIRECTORY_SEPARATOR, [$this->namespace, $this->resource, $params]) . '.php';
        }

        $fullPath = $apiPath . $filePath;
        if (file_exists($fullPath)) {
            return $fullPath;
        }
        return false;
    }

    /**
     * Return the optional route path
     */
    public function handleParams()
    {
        $params = $this->getAttribute('params');
        if ($params !== false) {
            $params = explode('/', $params);
            $params = implode(DIRECTORY_SEPARATOR, $params);
        }
        return $params;
    }

    /**
     * Handle API
     */
    protected function handle()
    {
        $func = $this->resource;
        $params = $this->handleParams();
        if ($params !== false) {
            // $func = implode(DIRECTORY_SEPARATOR, [$this->resource, $params]);
            $func = basename($params);
        }
        if ($this->fileExists() !== false) {
            include_once $this->fileExists();
            $this->current_call = Closure::bind(${$func}, $this, get_class($this));
            return $this->$func();
        } else {
            return $this->response([
                'error' => 'method_not_found',
                'resource' => $this->resource
            ], 404);
        }
    }

    /**
     * Check if parameter exists in parsed body
     */
    public function paramsExists(array $params)
    {
        $exists = true;
        if ($this->data == null) {
            return false;
        }
        foreach ($params as $param) {
            if (!array_key_exists($param, $this->data) || empty($this->data[$param])) {
                $exists = false;
            }
        }
        return $exists;
    }

    /**
     * Clean request inputs
     */
    private function cleanInputs($data)
    {
        $clean_input = array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->cleanInputs($v);
            }
        } else {
            if (!is_null($data)) {
                $data = strip_tags((string) $data); // Cast to string, safely removes null
                $clean_input = trim($data);
            } else {
                $clean_input = ''; // or null, based on how you want to handle empty inputs
            }
        }
        return $clean_input;
    }

    public function isXhr()
    {
        $request = $this->slimRequest;
        if ($request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
            return true;
        }
        return false;
    }

    /**
     * Get redirect URL
     */
    public function getRedirect($default = "/")
    {
        $redirect = $this->session->get('_redirect', $default);
        $this->session->forget('_redirect');
        return $redirect;
    }

    private function packData(array $data, string $contentType)
    {
        switch ($contentType) {
            case 'text/html':
                return $data['html'];
            case 'application/json':
                return packJson($data);
            case 'application/zip':
                $zipFile = $data['zipFile'];
                if (file_exists($zipFile)) {
                    return file_get_contents($zipFile);
                } else {
                    return packJson(['error' => 'File not found'], 'application/json');
                }
            case 'text/csv':
                $csvFile = $data['csvFile'];
                if (file_exists($csvFile)) {
                    $fileContents = file_get_contents($csvFile);
                    unlink($csvFile);
                    return $fileContents;
                } else {
                    return packJson(['error' => 'File not found'], 'application/json');
                }
            default:
                return packJson($data);
        }
    }

    // Not used
    private function negotiateContentType(array $acceptedContentTypes): string
    {
        foreach (self::ALLOWED_CONTENT_TYPES as $allowedContentType) {
            if (in_array($allowedContentType, $acceptedContentTypes)) {
                return $allowedContentType;
            }
        }

        throw new InvalidArgumentException('Unsupported Content Type');
    }

    public function negotiateHeaders(array $requestHeaders): array
    {
        $negotiatedHeaders = [];
        foreach ($requestHeaders as $header => $values) {
            $header = ucwords(strtolower($header));
            if (in_array($header, self::ALLOWED_HEADERS)) {
                $negotiatedHeaders[$header] = implode(', ', $values);
            }
        }

        return $negotiatedHeaders;
    }

    public function die()
    {
        $data = [
            "type" => "death"
        ];
        return $this->response($data, 400);
    }

    public function __call($method, $args)
    {
        if (is_callable($this->current_call)) {
            return call_user_func_array($this->current_call, $args);
        } else {
            $error = [
                'error' => 'Method not Allowed',
                'method' => $method
            ];
            $this->response($error, 405);
        }
    }

    /**
     * Return request method
     */
    public function getMethod(): string
    {
        return $this->slimRequest->getMethod();
    }

    /**
     * Check request method
     */
    public function isMethod(string $method): bool
    {
        return strtoupper($this->getMethod()) == strtoupper($method);
    }

    /**
     * Get request attribute
     */
    public function getAttribute(string $attribute)
    {
        if (array_key_exists($attribute, $this->attributes) && !empty($this->attributes[$attribute])) {
            return $this->attributes[$attribute];
        }
        return false;
    }

    /**
     * Check if user authenticated or not
     */
    public function isAuthenticated(): bool
    {
        return $this->auth->isAuthenticated();
    }

    /**
     * Get user role
     */
    public function getRole(): string
    {
        return $this->session->get('role');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->auth->isAdmin();
    }

    /**
     * Return user Id from session
     */
    public function getUserId(): string
    {
        return $this->session->get('user');
    }
    
    /**
     * Return JSON Response
     */
    public function response(
        array $payload = [], int $statusCode = 200, 
        $contentType = self::CONTENT_TYPE, array $headers = []
    )
    {
        $this->slimResponse->getBody()->write(
            $this->packData($payload, $contentType)
        );

        if (!empty($headers)) {
            foreach ($headers as $header => $value) {
                $this->slimResponse = $this->slimResponse->withHeader($header, $value);
            }
        }
        
        return $this->slimResponse
            ->withHeader('Content-Type', $contentType)
            ->withStatus($statusCode);
    }
}
