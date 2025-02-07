<?php

require_once "utils/Handler.php";

class FontFaceDeclsHandler extends Handler{
    public function handle($content, $zip, &$images)
    {
        $content = preg_replace('/<style:font-face-decls[^>]*>(.*?)<\/style:font-face-decls>/', '', $content);
        return parent::handle($content, $zip, $images);
    }
}
