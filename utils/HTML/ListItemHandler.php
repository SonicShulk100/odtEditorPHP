<?php

require_once "utils/Handler.php";

class ListItemHandler extends Handler{
    public function handle($content, $zip, &$images)
    {
        $content = preg_replace('/<text:list-item[^>]*>(.*?)<\/text:list-item>/', '<li>$1</li>', $content);
        return parent::handle($content, $zip, $images);
    }
}
