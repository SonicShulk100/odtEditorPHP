<?php

require_once "utils/XMLHandler.php";

class ImageHandler extends XMLHandler
{
    private ZipArchive $zip;

    public function __construct(ZipArchive $zip)
    {
        $this->zip = $zip;
    }

    protected function process(string $xml): string
    {
        // Traitement des images
        preg_match_all('/<draw:frame[^>]*draw:name="([^"]+)"[^>]*>.*?<draw:image[^>]*xlink:href="([^"]+)"[^>]*>.*?<\/draw:frame>/s', $xml, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $imagePath = $this->saveImageFromZip($match[2]);
            $xml = str_replace($match[0], '<img src="' . $imagePath . '" alt="' . htmlspecialchars($match[1]) . '"/>', $xml);
        }
        return $xml;
    }

    private function saveImageFromZip(string $fileName): string
    {
        $outputDir = 'uploads/images/';
        if (!is_dir($outputDir) && !mkdir($outputDir, 0777, true) && !is_dir($outputDir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $outputDir));
        }

        $imageContent = $this->zip->getFromName($fileName);
        if (!$imageContent) {
            return '';
        }

        $newFileName = uniqid('img_', true) . '_' . basename($fileName);
        $newFilePath = $outputDir . $newFileName;

        if (file_put_contents($newFilePath, $imageContent) === false) {
            return '';
        }

        return $newFilePath;
    }
}
