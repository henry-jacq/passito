<?php

namespace App\Core;

use Exception;
use App\Interfaces\ViewInterface;

class View implements ViewInterface
{
    public string $title;
    private mixed $resultView;
    private array $globals = [];
    private string $headerBlock;
    private string $footerBlock;
    private string $baseViewName;
    private string $contentsBlock;
    
    public function __construct(private readonly Config $config)
    {
        $this->title = $config->get('app.name');
        $this->baseViewName = $config->get('view.base_view');
        $this->headerBlock = $config->get('view.placeholder.header');
        $this->footerBlock = $config->get('view.placeholder.footer');
        $this->contentsBlock = $config->get('view.placeholder.contents');
    }

    /**
     * Generates the layout view
     */
    public function renderLayout(string $layoutName, array $params = [])
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

        $path = VIEW_PATH . '/layouts/' . $layoutName;

        if (file_exists($path)) {
            ob_start();
            include $path;
            $contents = ob_get_clean();
            return $contents;
        } else {
            return false;
        }
    }

    /**
     * Generates the layout view
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
     * Generates the template view
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

        $path = VIEW_PATH . '/templates/' . $template;

        if (file_exists($path)) {
            ob_start();
            include $path;
            $contents = ob_get_clean();
            if (ob_get_length() > 0) {
                ob_end_clean();
            }
            return $contents;
        } else {
            return false;
        }
    }

    /**
     * Generates the base view
     */
    public function renderBaseView(string $baseViewName, $params = [])
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        if (isset($title)) {
            $this->title = $title;
        }

        $baseViewPath = VIEW_PATH . DIRECTORY_SEPARATOR . $baseViewName;

        if (file_exists($baseViewPath)) {
            ob_start();
            include $baseViewPath;
            $contents = ob_get_clean();
            if (ob_get_length() > 0) {
                ob_end_clean();
            }
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
        if (!str_contains($this->baseViewName ,'.php')) {
            $this->baseViewName = $this->baseViewName . '.php';
        }
        $mainView = $this->renderBaseView($this->baseViewName, $params);
        $templateView = $this->renderTemplate($view, $params);
        $this->resultView = str_replace($this->contentsBlock, $templateView, $mainView);
        $this->pageFrame($params);
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

    /**
     * Render page with or without header and footer
     * 
     * It is a custom implementation, you can modify this to your needs.
     */
    public function pageFrame(array $params): View
    {
        if (null == $this->resultView) {
            throw new Exception('Page is not rendered');
        }

        $baseView = $this->resultView;

        $header = $params['header'] ?? false;
        if ($header) {
            $headerContent = $this->renderLayout('header', $params);
            $baseView = str_replace($this->headerBlock, $headerContent, $baseView);
        } else {
            $baseView = str_replace($this->headerBlock, '', $baseView);
        }

        $footer = $params['footer'] ?? false;
        if ($footer) {
            $footerContent = $this->renderLayout('footer');
            $baseView = str_replace($this->footerBlock, $footerContent, $baseView);
        } else {
            $baseView = str_replace($this->footerBlock, '', $baseView);
        }

        $this->resultView = $baseView;

        return $this;
    }

    public function isAuthenticated()
    {
        if (empty($_SESSION['user'])) {
            return false;
        }

        return true;
    }
    
}
