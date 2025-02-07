<?php

require_once "utils/Handler.php";

class StyleAttributeHandler extends Handler{
    public function handle($content, $zip, &$images)
    {
        $content = preg_replace('/<style:style[^>]*>(.*?)<\/style:style>/', '', $content);
        $content = preg_replace('/<style:graphic-properties[^>]*>(.*?)<\/style:graphic-properties>/', '', $content);
        $content = preg_replace('/<style:paragraph-properties[^>]*>(.*?)<\/style:paragraph-properties>/', '', $content);
        $content = preg_replace('/<style:text-properties[^>]*>(.*?)<\/style:text-properties>/', '', $content);
        $content = preg_replace('/<style:table-properties[^>]*>(.*?)<\/style:table-properties>/', '', $content);
        $content = preg_replace('/<style:table-column-properties[^>]*>(.*?)<\/style:table-column-properties>/', '', $content);
        $content = preg_replace('/<style:table-row-properties[^>]*>(.*?)<\/style:table-row-properties>/', '', $content);
        return parent::handle($content, $zip, $images);
    }
}