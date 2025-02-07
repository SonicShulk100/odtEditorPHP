<?php

require_once "utils/Handler.php";

class FrameHandler extends Handler{
    public function handle($content, $zip, &$images)
    {
        $content = preg_replace('/<draw:frame[^>]*>(.*?)<\/draw:frame>/', '<div>$1</div>', $content);
        return parent::handle($content, $zip, $images);
    }
}