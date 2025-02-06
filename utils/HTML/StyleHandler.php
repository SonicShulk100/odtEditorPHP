<?php

require_once 'utils/Handler.php';

class StyleHandler extends Handler {
    public function handle($content, $zip, &$images) {
        // Handle style conversion
        // Convert styles (bold, italic, underline, center, left, right) from the ODT content to HTML
        $patterns = [
            '/<text:span[^>]+text:style-name="[^"]*bold[^"]*"[^>]*>(.*?)<\/text:span>/' => '<strong>$1</strong>',
            '/<text:span[^>]+text:style-name="[^"]*italic[^"]*"[^>]*>(.*?)<\/text:span>/' => '<em>$1</em>',
            '/<text:span[^>]+text:style-name="[^"]*underline[^"]*"[^>]*>(.*?)<\/text:span>/' => '<u>$1</u>',
            '/<text:p[^>]+text:align="center"[^>]*>(.*?)<\/text:p>/' => '<p style="text-align:center;">$1</p>',
            '/<text:p[^>]+text:align="left"[^>]*>(.*?)<\/text:p>/' => '<p style="text-align:left;">$1</p>',
            '/<text:p[^>]+text:align="right"[^>]*>(.*?)<\/text:p>/' => '<p style="text-align:right;">$1</p>',
        ];

        // Apply each pattern to the content
        foreach ($patterns as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }

        //Appel de la méthode handle de la classe mère.
        return parent::handle($content, $zip, $images) ?? $content;
    }
}
