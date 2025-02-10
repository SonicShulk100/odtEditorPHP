<?php

require_once "utils/Handler.php";

class AutomaticStylesHandler extends Handler{
    public function handle($content, $zip, &$images)
    {
        $content = preg_replace('/<style:style[^>]*>(.*?)<\/style:style>/', '', $content);
        return parent::handle($content, $zip, $images);
    }
}
