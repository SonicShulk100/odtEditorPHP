<?php

/**
 * Interface HTMLHandler
 * @package handlers
 * @subpackage html
 */
interface HTMLHandler
{
    /**
     * Setting the next Handler
     * @param HTMLHandler $handler the next handler
     * @return HTMLHandler the next handler
     */
    public function setNext(HTMLHandler $handler): HTMLHandler;

    /**
     * Handle the request
     * @param string $request the Request
     * @param ZipArchive $zip the ZipArchive
     * @param array $images images
     * @return string the response
     */
    public function handle(string $request, ZipArchive $zip, array $images): string;
}
