<?php

class TextFormattingHandler extends Handler{
    public function handle(XMLReader $request): ?string
    {
        if($request->nodeType === XMLReader::ELEMENT &&
        in_array($request->name, ["text:span", "text:p"])){

            $styleAttributes = $this->getStyleAttributes($request);

            if($request->name === "text:s"){
                return "<del>".htmlspecialchars($request->readString())."</del>";
            }

            $content = htmlspecialchars($request->readString());
            return $this->applyFormatting($content, (array)$styleAttributes);
        }
        return parent::handle($request); // TODO: Change the autogenerated stub
    }

    private function applyFormatting(string $content, array $attributes): string {
        $tags = [];

        if (isset($attributes['font-weight']) &&
            $attributes['font-weight'] === 'bold') {
            $tags[] = 'strong';
        }

        if (isset($attributes['font-style']) &&
            $attributes['font-style'] === 'italic') {
            $tags[] = 'em';
        }

        if (isset($attributes['text-decoration']) &&
            $attributes['text-decoration'] === 'underline') {
            $tags[] = 'u';
        }

        $openingTags = array_map(static function($tag) {
            return "<$tag>";
        }, $tags);

        $closingTags = array_map(static function($tag) {
            return "</$tag>";
        }, array_reverse($tags));

        return implode('', $openingTags) . $content . implode('', $closingTags);
    }

    private function getStyleAttributes(XMLReader $request)
    {
        //TODO: Modify the method to retrieve style attributes from ODT styles.xml
        return "";
    }
}
