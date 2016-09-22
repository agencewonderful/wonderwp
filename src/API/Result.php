<?php

namespace WonderWp\API;
    /**
     * Result class
     */
    class Result implements \JsonSerializable{

        /**
         * The result code (based on http codes)
         * @var int
         */
        protected $code;
        /**
         * Result data
         * @var string
         */
        protected $data = array();

        /**
         * Result constructor.
         * @param int $code
         * @param string $data
         */
        public function __construct($code, $data=array())
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
         * @return mixed
         */
        public function getData($key='')
        {
            if(!empty($key)){
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

        public function jsonSerialize()
        {
            $vars = get_object_vars($this);

            return $vars;
        }

        public function __toString()
        {
            return json_encode($this);
        }

    }
?>
