<?php

namespace WonderWp\Framework\API;

class Result implements \JsonSerializable
{
    /**
     * The result code (based on http codes)
     * @var int
     */
    protected $code;
    /**
     * Result data
     * @var string[]
     */
    protected $data = [];

    /**
     * @param int   $code
     * @param array $data
     */
    public function __construct($code, $data = [])
    {
        $this->code = $code;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $key
     *
     * @return array|mixed|null|string
     */
    public function getData($key = '')
    {
        if (!empty($key)) {
            return isset($this->data[$key]) ? $this->data[$key] : null;
        } else {
            return $this->data;
        }
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /** @inheritdoc */
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }
}
