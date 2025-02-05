<?php

require_once "utils/AbstractHandler.php";

class StyleHandler extends AbstractHandler
{
    /**
     * Convert ODT styles to HTML
     * @param string $xml ODT XML
     * @return string HTML
     */
    public function handle(string $xml): string
    {
        // Extract margins from styles (assuming ODT structure includes them)
        preg_match('/<style:page-layout-properties fo:margin-left="([\d.]+cm)" fo:margin-right="([\d.]+cm)" fo:margin-top="([\d.]+cm)" fo:margin-bottom="([\d.]+cm)"/', $xml, $matches);

        if ($matches) {
            $cssMargins = "margin: $matches[3] $matches[1] $matches[4] $matches[2];";
            $xml = "<div style='$cssMargins'>$xml</div>";
        }

        // Convert inline styles (bold, italic, font size)
        $xml = preg_replace('/<text:span fo:font-weight="bold">(.*?)<\/text:span>/', '<strong>$1</strong>', $xml);
        $xml = preg_replace('/<text:span fo:font-style="italic">(.*?)<\/text:span>/', '<em>$1</em>', $xml);
        $xml = preg_replace('/<text:span fo:font-size="([\d.]+pt)">(.*?)<\/text:span>/', '<span style="font-size:$1;">$2</span>', $xml);

        return parent::handle($xml);
    }
}
