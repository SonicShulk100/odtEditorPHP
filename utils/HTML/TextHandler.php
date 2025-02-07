<?php

require_once "utils/Handler.php";

class TextHandler extends Handler
{
    public function handle($content, $zip, &$images)
    {
        $content = preg_replace('/<text:span[^>]*>(.*?)<\/text:span>/', '<span>$1</span>', $content);
        $content = preg_replace('/<text:a[^>]*>(.*?)<\/text:a>/', '<a>$1</a>', $content);
        $content = preg_replace('/<text:line-break[^>]*>/', '<br>', $content);
        $content = preg_replace('/<text:tab[^>]*>/', '&nbsp;&nbsp;&nbsp;&nbsp;', $content);
        $content = preg_replace('/<text:s[^>]*>/', '&nbsp;', $content);
        $content = preg_replace('/<text:line-through[^>]*>(.*?)<\/text:line-through>/', '<del>$1</del>', $content);
        $content = preg_replace('/<text:underline[^>]*>(.*?)<\/text:underline>/', '<u>$1</u>', $content);
        $content = preg_replace('/<text:span[^>]*>(.*?)<\/text:span>/', '<span>$1</span>', $content);
        $content = preg_replace('/<text:span[^>]*>(.*?)<\/text:span>/', '<span>$1</span>', $content);
        $content = preg_replace('/<text:span[^>]*>(.*?)<\/text:span>/', '<span>$1</span>', $content);
        $content = preg_replace('/<text:span[^>]*>(.*?)<\/text:span>/', '<span>$1</span>', $content);
        $content = preg_replace('/<text:span[^>]*>(.*?)<\/text:span>/', '<span>$1</span>', $content);
        $content = preg_replace('/<text:span[^>]*>(.*?)<\/text:span>/', '<span>$1</span>', $content);
        $content = preg_replace('/<text:span[^>]*>(.*?)<\/text:span>/', '<span>$1</span>', $content);
        $content = preg_replace('/<text:span[^>]*>(.*?)<\/text:span>/', '<span>$1</span>', $content);
        $content = preg_replace('/<text:span[^>]*>(.*?)<\/text:span>/', '<span>$1</span>', $content);
        return parent::handle($content, $zip, $images);
    }
}