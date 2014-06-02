<?php

class XMLTag
{
    protected $tag;
    protected $attributes;
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
     * @return this
     */
    public function loadSimpleXMLElement(SimpleXMLElement $simpleXMLElement)
    {

    }

    /**
     * Append XML attribute
     * 
     * @param string $key   XML attribute name
     * @param string $value XML attribute value
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