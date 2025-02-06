<?php

require_once 'utils/Handler.php';

class ListHandler extends Handler {
    public function handle($content, $zip, &$images) {
        // Handle list conversion
        // Convert lists (unordered and ordered) and their sublist from the ODT content to HTML
        $content = preg_replace('/<text:list[^>]*>(.*?)<\/text:list>/', '<ul>$1</ul>', $content);
        $content = preg_replace('/<text:list-item[^>]*>(.*?)<\/text:list-item>/', '<li>$1</li>', $content);
        $content = preg_replace('/<text:ordered-list[^>]*>(.*?)<\/text:ordered-list>/', '<ol>$1</ol>', $content);

        // Call the handle method of the parent class
        return parent::handle($content, $zip, $images) ?? $content;
    }
}
