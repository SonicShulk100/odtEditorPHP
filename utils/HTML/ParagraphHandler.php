<?php

require_once "utils/Handler.php";

class ParagraphHandler extends Handler{
    public function handle($content, ZipArchive $zip, &$images)
    {
        $pattern = '/<text:p text:style-name="([^"]*)">(.*?)<\/text:p>/s';

        $replacement = '<p class="p-$1">$2</p>';

        $content = preg_replace($pattern, $replacement, $content);

        $content = preg_replace(
            "/<text:p>(.*?)<\/text:p>/s",
            "<p>$1</p>",
            $content
        );

        return parent::handle($content, $zip, $images);
    }
}
