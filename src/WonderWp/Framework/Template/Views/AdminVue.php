<?php

namespace WonderWp\Framework\Template\Views;

class AdminVue
{
    protected $frags = [];

    /**
     * @param VueFrag $frag
     *
     * @return $this
     */
    public function addFrag(VueFrag $frag)
    {
        $this->frags[] = $frag;

        return $this;
    }

    /**
     * @param array  $frags
     *
     * @return $this
     */
    public function registerFrags($frags = [])
    {
        if (!empty($frags)) {
            foreach ($frags as $vueFrag) {
                $this->addFrag($vueFrag);
            }
        }

        return $this;
    }

    /**
     * @param array $params
     *
     * @return $this
     */
    public function render($params = [])
    {
        if (!empty($this->frags)) {
            foreach ($this->frags as $frag) {
                /** @var VueFrag $frag */
                $frag->render($params);
            }
        }

        return $this;
    }
}
