<?php

namespace Core;

class View
{
    protected $template;
    protected $data;

    public function __construct()
    {
    }

    /**
     * @param $template
     * @throws \Exception
     */
    public function render($template, $data = [])
    {
        $this->data = $data;
        $this->template = $template;
        if (file_exists($this->getTemplateFile())) {
            ob_start();
            require $this->getTemplateFile();
            return ob_get_clean();
        }

        throw new \Exception("Template $template not found");
    }

    protected function getTemplateFile()
    {
        return sprintf('%s/views/%s.php', dirname(__DIR__), $this->template);
    }
}