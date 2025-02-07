<?php
require_once "utils/Handler.php";

class SpanHandler extends Handler{
    public function handle($content, $zip, &$images)
    {
        $content = preg_replace('/<text:span[^>]*>(.*?)<\/text:span>/', '<span>$1</span>', $content);
        return parent::handle($content, $zip, $images);
    }
}
