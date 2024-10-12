<?php

namespace App\Core;

class View
{
    public string $title;
    public string $appName;
    public string $appDesc;
    private mixed $resultView;
    private array $globals = [];
    private string $baseViewName;
    private string $contentsBlock;

    public function __construct(private readonly Config $config)
    {
        $this->title = $config->get('app.name');
        $this->appName = $config->get('app.name');
        $this->appDesc = $config->get('app.desc');
        $this->baseViewName = $config->get('view.base_view');
        $this->contentsBlock = $config->get('view.placeholder.contents');
    }

    /**
     * Generates the layout view
     */
    public function getLayout(string $layoutName, array $params = [])
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        // Inserting global variables
        foreach ($this->globals as $key => $value) {
            $$key = $value;
        }

        if (!str_contains($layoutName, '.php')) {
            $layoutName = $layoutName . '.php';
        }

        $path = VIEW_PATH . "/layouts/" . $layoutName;

        if (file_exists($path)) {
            ob_start();
            include_once $path;
            $contents = ob_get_clean();
            return $contents;
        } else {
            return false;
        }
    }

    /**
     * Generates the component view
     */
    public function renderComponent($component, $params = [])
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        if (!str_contains($component, '.php')) {
            $component = $component . '.php';
        }

        // Inserting global variables
        foreach ($this->globals as $key => $value) {
            $$key = $value;
        }

        $path = VIEW_PATH . '/components/' . $component;

        if (file_exists($path)) {
            include $path;
        } else {
            return false;
        }
    }

    /**
     * Render template view
     */
    public function renderTemplate($template, $params = [])
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        if (!str_contains($template, '.php')) {
            $template = $template . '.php';
        }

        // Inserting global variables
        foreach ($this->globals as $key => $value) {
            $$key = $value;
        }

        $path = VIEW_PATH . "/templates/" . DIRECTORY_SEPARATOR . $template;

        if (file_exists($path)) {
            ob_start();
            include $path;
            $contents = ob_get_clean();
            if (ob_get_length() > 0) {
                ob_end_clean();
            }
            return $contents;
        } else {
            throw new \Exception("Template not found: " . $template);
        }
    }

    /**
     * Creates a page with the template name and params
     */
    public function createPage(string $view, $params = []): View
    {
        if (!str_contains($this->baseViewName, '.php')) {
            $this->baseViewName = $this->baseViewName . '.php';
        }

        if (!empty($params['title'])) {
            $this->title = $params['title'];
        }

        $mainView = $this->getLayout($this->baseViewName, $params);
        $templateView = $this->renderTemplate($view, $params);
        $this->resultView = str_replace($this->contentsBlock, $templateView, $mainView);
        return $this;
    }

    /**
     * Render the page
     */
    public function render(): void
    {
        echo($this->resultView);
    }

    /**
     * Add global variables
     */
    public function addGlobals(string $key, $value): void
    {
        if (!array_key_exists($key, $this->globals)) {
            $this->globals[$key] = $value;
        }
    }

    /**
     * Return the saved globals
     */
    public function getGlobals(): array
    {
        if (null !== $this->globals) {
            return $this->globals;
        }

        return [];
    }

    public function isAuthenticated()
    {
        if (empty($_SESSION['user'])) {
            return false;
        }

        return true;
    }

}
