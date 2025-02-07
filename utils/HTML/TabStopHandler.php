<?php

require_once "utils/Handler.php";

class TabStopHandler extends Handler{
    public function handle($content, $zip, &$images)
    {
        $content = preg_replace('/<style:tab-stops[^>]*>(.*?)<\/style:tab-stops>/', '', $content);
        $content = preg_replace('/<style:tab-stop[^>]*>(.*?)<\/style:tab-stop>/', '', $content);
        return parent::handle($content, $zip, $images);
    }
}
