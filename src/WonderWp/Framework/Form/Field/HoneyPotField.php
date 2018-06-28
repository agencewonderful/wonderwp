<?php

namespace WonderWp\Framework\Form\Field;

class HoneyPotField extends InputField
{

    const HONEYPOT_FIELD_NAME = 'raison_societe_name';

    /** @inheritdoc */
    public function __construct($name, $value = null, array $displayRules = [], array $validationRules = [])
    {
        if(empty($displayRules['label'])){
            $displayRules['label'] = __($name.'.trad');
        }
        if(empty($displayRules['inputAttributes'])){
            $displayRules['inputAttributes'] = [];
        }
        if(empty($displayRules['inputAttributes']['class'])){
            $displayRules['inputAttributes']['class']=[];
        }
        $displayRules['inputAttributes']['class'][] = 'shouldbefilled';
        $displayRules['wrapAttributes']['style'] = 'display: none';

        parent::__construct($name, $value, $displayRules, $validationRules);
    }
}
