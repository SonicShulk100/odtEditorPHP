<?php

class ParagraphHandler extends Handler{
    public function handle(XMLReader $request): ?string{
        if($request->nodeType === XMLReader::ELEMENT &&
            $request->name === "text:p"){

            $styleName = $request->getAttribute("text:style-name");
            $text = $request->readString();

            $styleAttributes = $this->getStyleAttributes($styleName);

            return "<p " . $this->buildStyleAttributes($styleAttributes) . ">".htmlspecialchars($text)."</p>";
        }

        return parent::handle($request);
    }

    private function getStyleAttributes(string $styleName): array {
        // Implementation to retrieve style attributes from ODT styles.xml
        return [];
    }

    private function buildStyleAttributes(array $attributes): string {
        $styles = [];
        foreach ($attributes as $property => $value) {
            $styles[] = "$property: $value";
        }
        return count($styles) ? 'style="' . implode('; ', $styles) . '"' : '';
    }
}
