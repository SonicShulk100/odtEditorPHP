<?php

require_once "utils/AbstractHandler.php";

class TableHandler extends AbstractHandler {
    public function handle(string $xml): string {
        // Convert tables
        $xml = str_replace(array('<table:table>', '<table:table-row>', '<table:table-cell>', '</table:table>', '</table:table-row>', '</table:table-cell>'), array('<table>', '<tr>', '<td>', '</table>', '</tr>', '</td>'), $xml);
        return parent::handle($xml);
    }
}
