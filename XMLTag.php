<?php
/**
 *
 * @version     PHP 5.*
 * @author      Chuehnone chuehnone@gmail.com
 * @copyright   Copyright (c) 2014 The author
 */
class XMLTag
{
    /**
     * @var string
     */
    protected $tag;

    /**
     * @var array(key => value, ...)
     */
    protected $attributes;

    /**
     * @var string|array(XMLTag, ...)
     */
    protected $value;

    public function __construct($tag = null, array $attributes = array(), $value = null)
    {
        $this->tag = $tag;
        $this->attributes = $attributes;
        $this->value = $value;
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case "tag":
                $this->tag = $value;
                break;
            case "attributes":
                if (is_array($value)) {
                    $this->attributes = $value;
                }
                break;
            case "value":
                if ($value instanceof XMLTag) {
                    if ($this == $value) {
                        $value = clone $value;
                    }

                    if (!is_array($this->value)) {
                        $this->value = array();
                    }

                    $this->value[] = $value;
                } else {
                    $this->value = $value;
                }
        }

        return $this;
    }

    public function __get($name)
    {
        switch ($name) {
            case "tag":
                return $this->tag;
            case "attributes":
                return $this->attributes;
                break;
            case "value":
                return $this->value;
        }

        return null;
    }

    public function __clone()
    {
        return new XMLTag($this->tag, $this->attributes, $this->value);
    }

    /**
     * Convert SimpleXMLElement to XMLTag
     * 
     * @param SimpleXMLElement $simpleXMLElement
     * 
     * @return this
     */
    public function loadSimpleXMLElement(SimpleXMLElement $simpleXMLElement)
    {
        $tagObj = $this->simpleXMLElementToXMLTag($simpleXMLElement);
        $this->tag = $tagObj->tag;
        $this->attributes = $tagObj->attributes;
        $this->value = $tagObj->value;

        return $this;
    }

    /**
     * Convert SimpleXMLElement to XMLTag
     * 
     * @param  SimpleXMLElement $simpleXMLElement
     * 
     * @return XMLTag
     */
    protected function simpleXMLElementToXMLTag(SimpleXMLElement $simpleXMLElement)
    {
        $tagObj = new XMLTag();
        $tagObj->tag = $simpleXMLElement->getName();

        // Get XML attributes to array
        $attributes = array();
        $sXMLElementAtr = $simpleXMLElement->attributes();
        foreach ($sXMLElementAtr as $atr) {
            $attributes[$atr->getName()] = (string)$atr;
        }
        $tagObj->attributes = $attributes;
        unset($sXMLElementAtr, $atr);

        // Get XML value or nodes
        if ($simpleXMLElement->count()) {
            $sXMLElementNode = $simpleXMLElement->children();
            foreach ($sXMLElementNode as $node) {
                $tagObj->value[] = $this->simpleXMLElementToXMLTag($node);
                unset($node);
            }
            unset($sXMLElementNode);
        } else {
            $tagObj->value = (string)$simpleXMLElement;
        }

        return $tagObj;
    }

    /**
     * To XML
     * If the path is null, it returns XML string on successs and false on error.
     * If the path is not null, it returns true if the file was written successfully and false otherwise.
     * 
     * @param string $path
     * 
     * @return bool|string
     */
    public function toXML($path = null)
    {
        $xmlObj = new SimpleXMLElement($this->tag);
    }

    protected function xmlTagToSimpleXMLElement(XMLTag $tagObj)
    {

    }

    /**
     * Append XML attribute
     * 
     * @param string $key   XML attribute name
     * @param string $value XML attribute value
     * 
     * @return this
     */
    public function appendAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * XMLTag object to array
     * 
     * @return array
     *         array(
     *             "tag" => $this->tag,
     *             "attributes" => $tthis->attributes,
     *             "value" => $this->value|$this->value->toArray()
     *         )
     */
    public function toArray()
    {
        $ary = array(
            "tag" => $this->tag,
            "attributes" => $this->attributes,
            "value" => null
        );

        if (is_array($this->value)) {
            foreach ($this->value as $value) {
                $ary["value"][] = $value->toArray();
            }
        } else {
            $ary["value"] = $this->value;
        }

        return $ary;
    }
}

?>