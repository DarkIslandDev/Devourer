<?php

namespace Gayaru\Devourer;

use DateTime;
use DateTimeZone;
use DOMDocument;
use DOMElement;
use InvalidArgumentException;
use RuntimeException;

class MaxUrlCountExceededException extends RuntimeException
{
}


abstract class AbstractSitemap
{
    const MAX_URLS = 50000;


    abstract protected function getRootNodeName();
    abstract protected function getNodeName();

    protected $xmlVersion = '1.0';
    protected $xmlEncoding = 'UTF-8';
    protected $xmlNamespaceUri = 'https://www.w3.org/2001/XMLSchema-instance';

    protected $document;
    protected $rootNode;
    protected $isFrozen = false;
    protected $urlCount = 0;


    public function __construct()
    {
        $this->document = new DOMDocument($this->xmlVersion, $this->xmlEncoding);
        $this->rootNode = $this->document->createElementNS($this->xmlNamespaceUri, $this->getRootNodeName());

        // Make the output Pretty
        $this->document->formatOutput = true;
    }


    public function freeze()
    {
        $this->document->appendChild($this->rootNode);
        $this->isFrozen = true;
    }

    public function isFrozen()
    {
        return $this->isFrozen;
    }


    public function getUrlCount()
    {
        return $this->urlCount;
    }


    public function hasMaxUrlCount()
    {
        return $this->urlCount === static::MAX_URLS;
    }


    public function toString()
    {
        return (string) $this;
    }


    public function __toString()
    {
        if (!$this->isFrozen()) {
            $this->freeze();
        }

        return $this->document->saveXML();
    }

    /**
     * Adds a URL to the document with the given array of elements.
     * @param  array $urlArray
     * @return $this
     * @throws MaxUrlCountExceededException
     */
    protected function addUrlToDocument(array $urlArray)
    {
        if ($this->hasMaxUrlCount()) {
            throw new MaxUrlCountExceededException('Maximum number of URLs has been reached, cannot add more.');
        }

        $node = $this->document->createElement($this->getNodeName());

        foreach ($urlArray as $key => $value) {
            if (is_null($value)) {
                continue;
            }
            $node->appendChild(new DOMElement($key, $value));
        }
        $this->rootNode->appendChild($node);
        $this->urlCount++;

        return $this;
    }

    /**
     * Escapes a string so it can be inserted into the Sitemap
     * @param  string $string The string to escape.
     * @return string
     */
    protected function escapeString($string)
    {
        $from = ['&', '\'', '"', '>', '<'];
        $to   = ['&amp;', '&apos;', '&quot;', '&gt;', '&lt;'];

        return str_replace($from, $to, $string);
    }

    /**
     * Takes a date as a string (or int in the case of a unix timestamp).
     * @param  string $dateString
     * @return string
     * @throws InvalidArgumentException
     */
    protected function formatDate($dateString)
    {
        try {
            // We have to handle timestamps a little differently
            if (is_numeric($dateString) && (int) $dateString == $dateString) {
                $date = DateTime::createFromFormat('U', (int) $dateString, new DateTimeZone('UTC'));
            } else {
                $date = new DateTime($dateString, new DateTimeZone('UTC'));
            }

            return $date->format(DateTime::W3C);
        } catch (\Exception $e) {
            throw new InvalidArgumentException("Malformed last modified date: {$dateString}", 0, $e);
        }
    }
}
