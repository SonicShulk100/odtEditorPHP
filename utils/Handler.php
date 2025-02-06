<?php

/**
 * Classe abstraite Handler
 * @subclasses ParagraphHandler, ListHandler, TableHandler, HeaderFooterHandler, ImageHandler, StyleHandler, FontHandler, MarginHandler
 */

abstract class Handler {
    protected $nextHandler;

    public function setNext(Handler $handler): Handler {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle($content, $zip, &$images) {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($content, $zip, $images);
        }
        return $content;
    }
}
