<?php

namespace WonderWp\Forms\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class WP_Term extends AbstractRule
{
    /** @var int|null */
    protected $parentId;

    /**
     * @param int $parentId
     */
    public function __construct($parentId = null)
    {
        $this->parentId = $parentId;
    }

    /** @inheritdoc */
    public function validate($termId)
    {
        if ($termId === null) {
            return true;
        }

        $term = get_category((int)$termId);

        if (!$term instanceof \WP_Term) {
            return false;
        }

        return $this->parentId === null ? true : $term->parent === $this->parentId;
    }
}
