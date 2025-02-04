<?php
interface XMLReader{
    /**
     * Opens an XML file for reading
     *
     * @param string $uri URI/Path to XML file
     * @return bool True on success, false on failure
     */
    public function open(string $uri): bool;

    /**
     * Reads the next node in the XML stream
     *
     * @return bool True on success, false on failure/end of file
     */
    public function read(): bool;

    /**
     * Gets the current node type
     *
     * @return int One of the XMLReader node types
     */
    public function getAttributeNo(): int;

    /**
     * Gets the current node name
     *
     * @return string Name of current element
     */
    public function getName(): string;

    /**
     * Gets the current node value
     *
     * @return string Value of current node
     */
    public function getValue(): string;

    /**
     * Gets an attribute value by name
     *
     * @param string $name Name of attribute to retrieve
     * @return string|null Attribute value or null if not found
     */
    public function getAttribute(string $name): ?string;

    /**
     * Closes the XML reader
     *
     * @return void
     */
    public function close(): void;
}