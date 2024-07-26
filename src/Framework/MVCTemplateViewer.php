<?php 

namespace Framework;

class MVCTemplateViewer implements TemplateViewerInterface
{
    public function render(string $template, array $data = []): string
    {

        $views_dir = dirname(__DIR__, 2) . "/views/";

        $code = file_get_contents($views_dir . $template);

        if(preg_match('#^{%\s*extends "(?<template>.+)"\s*%}#', $code, $matches) === 1){

                $base = file_get_contents($views_dir . $matches["template"]);

                $blocks = $this->getBlocks($code);

                $code = $this->replaceYield($base, $blocks);
                
        }

        $code = $this->loadIncludes($views_dir, $code);
        $code = $this->replaceVariables($code);
        $code = $this->replacePHP($code);

        extract($data, EXTR_SKIP);

        ob_start();
        
        eval("?>$code");

        return ob_get_clean();
    }

    private function loadIncludes(string $dir, string $code): string 
    {
        preg_match_all('#{%\s*include "(?<template>.*?)"\s*%}#', $code, $matches, PREG_SET_ORDER);

        foreach($matches as $match){

            $template = $match["template"];

            $contents = file_get_contents($dir . $template);

            $code = preg_replace("#{%\s*include \"$template\"\s*%}#", $contents, $code);

        }

        return $code;
    }

    private function replaceYield(string $code, array $blocks): string 
    {
        preg_match_all("#{%\s*yield (?<name>\w+)\s*%}#", $code, $matches, PREG_SET_ORDER);

        foreach($matches as $match){

            $name = $match["name"];

            $block = $blocks[$name];

            $code = preg_replace("#{%\s*yield $name\s*%}#", $block, $code);

        }

        return $code;
    }

    private function getBlocks(string $code): array 
    {
        preg_match_all("#{%\s*block (?<name>\w+)\s*%}(?<content>.*?){%\s*endblock\s*%}#s", $code, $matches, PREG_SET_ORDER);

        $blocks = [];

        foreach($matches as $match){

            $blocks[$match["name"]] = $match["content"];
        }

        return $blocks;
    }

    private function replaceVariables(string $code): string 
    {
        return preg_replace("#{{\s*(\S+)\s*}}#", "<?= htmlspecialchars(\$$1 ?? '') ?>", $code);
    }

    private function replacePHP(string $code): string 
    {
        return preg_replace("#{%\s*(.+)\s*%}#", "<?php $1 ?>", $code);
    }
}