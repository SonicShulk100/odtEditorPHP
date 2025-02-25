<?php

/**
 * Interface pour la conversion de XML dans le fichier styles.xml en CSS.
 */
interface CSSHandler{
    /**
     * Faire passer vers un autre Handler.
     * @param CSSHandler $handler le handler récent.
     * @return CSSHandler le prochain handler.
     */
    public function setNext(CSSHandler $handler): CSSHandler;

    /**
     * Convertit une partie de XML en CSS.
     * @param SimpleXMLElement $XML une partie XML en question.
     * @param array $css le CSS convertit
     * @return void la méthode ne renvoie rien.
     */
    public function handle(SimpleXMLElement $XML, array &$css): void;
}