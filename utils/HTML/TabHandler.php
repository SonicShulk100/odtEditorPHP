<?php

require_once "utils/Handler.php";

class TabHandler extends Handler{
    public function handle($content, $zip, &$images)
    {
        $content = preg_replace('/<text:tab[^>]*\/>/', '', $content);
        return parent::handle($content, $zip, $images);
    }
}
