<?php
/**
 *
 * User: Aleksandr Yegorov (a.yegorov@ism.nl)
 * Date: 2016-01-23
 * Copyright 2016 ISM eCompany http://www.ism.nl/
 */

namespace Hackathon\PrettyUrl\Model\Layer\Filter;

use Magento\Catalog\Model\Layer\Filter\FilterInterface;
use Magento\Catalog\Model\Layer\Filter\Item;

class ItemPlugin
{
    public function aroundGetUrl(Item $subject, \Closure $proceed)
    {
        $filter = $subject->getFilter();

        if ($this->canBeSubstituted($filter)) {

        }
    }

    public function canBeSubstituted(FilterInterface $filter)
    {
        // TODO: check if we can work with this attribute
        return ($filter->getAttributeModel());
    }
}
