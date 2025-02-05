<?php

class ListHandler extends Handler{
    public function handle(XMLReader $request)
    {
        if($request->nodeType === XMLReader::ELEMENT &&
            ($request->name === "text:list" ||
                $request->name === "text:list-item")){
            $listType = $this->determineListType($request);

            $htmlContent = [];

            if($listType === 'ordered'){
                $htmlContent[] = "<ol>";
            }
            else{
                $htmlContent[] = "<ul>";
            }

            while ($request->next() &&
                ($request->name !== 'text:list' ||
                    $request->nodeType !== XMLReader::END_ELEMENT)) {
                if($request->name === "text:list-item"){
                    $htmlContent[] = "<li>".$this->processListItem($request)."</li>";
                }
            }

            $htmlContent = $listType === 'ordered' ? array_merge($htmlContent, ["</ol>"]) : array_merge($htmlContent, ["</ul>"]);
            return implode("\n", $htmlContent);
        }
        return parent::handle($request);
    }

    private function determineListType(XMLReader $reader): string {
        $parent = $reader->lookupNamespace('text');
        $listStyleName = $reader->getAttribute('text:style-name');
        // Determine if ordered or unordered based on style name
        return str_contains($listStyleName, 'OL') ? 'ordered' : 'unordered';
    }

    private function processListItem(XMLReader $reader): string {
        $itemContent = '';
        while ($reader->next() &&
            ($reader->name !== 'text:list-item' ||
                $reader->nodeType !== XMLReader::END_ELEMENT)) {
            if ($reader->nodeType === XMLReader::TEXT) {
                $itemContent .= htmlspecialchars($reader->value);
            }
        }
        return $itemContent;
    }
}
