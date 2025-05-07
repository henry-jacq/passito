<?php

namespace App\Core;

use App\Enum\UserRole;
use Slim\Routing\RouteParser;
use App\Core\Storage;

class View
{
    public string $title;
    public string $appName;
    public string $appDesc;
    private mixed $resultView;
    private array $globals = [];
    private string $baseViewName;
    private string $contentsBlock;
    private RouteParser $router;

    // Cache for templates and components
    private array $viewCache = [];

    public function __construct(
        private readonly Config $config,
        private readonly Storage $storage,
        private readonly Session $session,
        RouteParser $router
    ) {
        $this->router = $router;
        $this->title = $config->get('app.name');
        $this->contentsBlock = $config->get('view.placeholder.contents');
    }

    /**
     * Generates a URL for a given named route
     */
    public function urlFor(string $routeName, array $params = [], array $queryParams = []): string
    {
        return $this->router->urlFor($routeName, $params, $queryParams);
    }

    /**
     * Generates the layout view
     */
    public function renderLayout(string $layoutName, array $params = [])
    {
        // Escape all string values in the data array
        $safeParams = $this->escapeData($params);

        // Safely extract variables to avoid overwriting
        extract($safeParams, EXTR_SKIP);

        // Inserting global variables
        extract($this->getGlobals(), EXTR_SKIP);

        if (!str_contains($layoutName, '.php')) {
            $layoutName = $layoutName . '.php';
        }

        $path = VIEW_PATH . "/layouts/" . $layoutName;

        if (file_exists($path)) {
            ob_start();
            include $path;
            $contents = ob_get_clean();
            // Store in cache
            $this->viewCache[$path] = $contents;
            return $contents;
        } else {
            return false;
        }
    }

    /**
     * Generates the component view
     */
    public function getComponent($component, $params = []): void
    {
        // Escape all string values in the data array
        $safeParams = $this->escapeData($params);

        // Safely extract variables to avoid overwriting
        extract($safeParams, EXTR_SKIP);

        // Inserting global variables
        extract($this->getGlobals(), EXTR_SKIP);

        if (!str_contains($component, '.php')) {
            $component = $component . '.php';
        }

        $path = VIEW_PATH . '/components/' . $component;

        // Cache check
        if (isset($this->viewCache[$path])) {
            echo $this->viewCache[$path];  // Output cached component
            return;
        }

        if (file_exists($path)) {
            ob_start();
            include $path;
            $contents = ob_get_clean();
            // Store in cache
            $this->viewCache[$path] = $contents;
            echo $contents;
        } else {
            return;
        }
    }

    /**
     * Render template view
     */
    public function renderTemplate($template, $params = [])
    {
        // Escape all string values in the data array
        $safeParams = $this->escapeData($params);

        // Safely extract variables to avoid overwriting
        extract($safeParams, EXTR_SKIP);

        // Inserting global variables
        extract($this->getGlobals(), EXTR_SKIP);

        if (!str_contains($template, '.php')) {
            $template = $template . '.php';
        }

        $path = VIEW_PATH . "/templates/" . DIRECTORY_SEPARATOR . $template;

        // Cache check
        if (isset($this->viewCache[$path])) {
            // Return cached template
            return $this->viewCache[$path];
        }

        if (file_exists($path)) {
            ob_start();
            include $path;
            $contents = ob_get_clean();
            // Store in cache
            $this->viewCache[$path] = $contents;
            return $contents;
        } else {
            return false;
        }
    }

    /**
     * Creates a page with the template name and params
     */
    public function createPage(string $view, $params = []): View
    {
        // Clear previous resultView to avoid multiple render cycles
        $this->resultView = null;

        // Get the user role
        $role = $this->session->get('role') ?? UserRole::USER->value;
        // Get the layout config
        $layoutConfig = $this->config->get('view.layouts');

        if (is_array($layoutConfig[$role])) {
            $this->baseViewName = $layoutConfig[$role][dirname($view)] ?? $layoutConfig[UserRole::USER->value];
        } else {
            $this->baseViewName = $layoutConfig[$role] ?? $layoutConfig[UserRole::USER->value];
        }

        if (!str_contains($this->baseViewName, '.php')) {
            $this->baseViewName = $this->baseViewName . '.php';
        }

        if (!empty($params['title'])) {
            $this->title = $params['title'];
        }

        $mainView = $this->renderLayout($this->baseViewName, $params);
        $templateView = $this->renderTemplate($view, $params);
        $this->resultView = str_replace($this->contentsBlock, $templateView, $mainView);
        return $this;
    }

    /**
     * Render email template
     */
    public function renderEmail(string $view, $params = []): string
    {
        // Escape all string values in the data array
        $safeParams = $this->escapeData($params);

        // Safely extract variables to avoid overwriting
        extract($safeParams, EXTR_SKIP);

        // Inserting global variables
        extract($this->getGlobals(), EXTR_SKIP);

        if (!str_contains($view, '.php')) {
            $view = $view . '.php';
        }

        $path = VIEW_PATH . "/emails/" . $view;

        if (file_exists($path)) {
            ob_start();
            include $path;
            $contents = ob_get_clean();
            return $contents;
        } else {
            throw new \Exception("Email template not found: " . $view);
        }
    }

    /**
     * Render the page
     */
    public function render(): void
    {
        echo $this->resultView;
        // To handle multiple render cycles
        $this->resultView = null;
    }

    /**
     * Add global variables
     */
    public function addGlobals(string $key, $value, bool $overwrite = false): void
    {
        if (!array_key_exists($key, $this->globals) || $overwrite) {
            $this->globals[$key] = $value;
        }
    }

    /**
     * Return the saved globals
     */
    public function getGlobals(): array
    {
        if (null !== $this->globals) {
            return $this->escapeData($this->globals);
        }

        return [];
    }

    public function isAuthenticated($key = 'user', $role = null): bool
    {
        if (empty($_SESSION[$key])) {
            return false;
        }

        if ($role && $_SESSION[$key]['role'] !== $role) {
            return false;
        }

        return true;
    }

    /**
     * Recursively escape all string values in the data array using htmlspecialchars.
     *
     * @param array $data
     * @return array
     */
    private function escapeData(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            } elseif (is_array($value)) {
                // Recursively apply htmlspecialchars to arrays
                $data[$key] = $this->escapeData($value);
            }
            // Objects and other types are left unchanged
        }

        return $data;
    }

    public function clearCache(): void
    {
        $this->viewCache = [];
    }

    public function clearCacheIfDev(): void
    {
        if ($this->config->get('app.env') === 'development') {
            $this->clearCache();
        }
    }
}
