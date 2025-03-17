<?php

/**
 * Classe abstraite HTMLHandler
 * @subclasses ParagraphHTMLHandler, ListHTMLHandler, TableHTMLHandler, HeaderFooterHandler, ImageHTMLHandler, StyleHTMLHandler, FontHandler, MarginHandler
 */

abstract class HTMLHandler {
    protected ?HTMLHandler $nextHandler = null;

    public function setNext(HTMLHandler $handler): HTMLHandler {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle($content, ZipArchive $zip, &$images) {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($content, $zip, $images);
        }
        return $content;
    }
}
