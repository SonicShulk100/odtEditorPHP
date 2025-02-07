<?php

require_once "utils/Handler.php";

class BodyHandler extends Handler{
    public function handle($content, $zip, &$images)
    {
        $content = preg_replace('/<office:body[^>]*>(.*?)<\/office:body>/', '<body>$1</body>', $content);
        return parent::handle($content, $zip, $images);
    }
}
