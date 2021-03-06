<?php
/**
 *
 * User: Aleksandr Yegorov (a.yegorov@ism.nl)
 * Date: 2016-01-23
 * Copyright 2016 ISM eCompany http://www.ism.nl/
 */

namespace Hackathon\PrettyUrl\Model\Layer\Filter;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\Layer\Filter\FilterInterface;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\Layer\Filter\Item as FilterItem;
use Hackathon\PrettyUrl\Helper\Url as UrlHelper;

class ItemPlugin
{
    /** @var Attribute */
    protected $eavAttribute;

    /** @var UrlHelper  */
    protected $urlHelper;

    /** @var FilterInterface */
    protected $filter;

    /**
     * RenderLayeredPlugin constructor.
     * @param UrlHelper $urlHelper
     */
    public function __construct(UrlHelper $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param FilterItem $subject
     * @param \Closure $proceed
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @see \Magento\Catalog\Model\Layer\Filter\Item::getUrl()
     */
    public function aroundGetUrl(FilterItem $subject, \Closure $proceed)
    {
        $this->filter = $subject->getFilter();
        $this->eavAttribute = $this->filter->getAttributeModel();

        if ($this->canBeSubstituted()) {
            $result = $this->urlHelper->getOptionUrl($this->eavAttribute, $subject->getValue());
        } else{
            $result = $proceed();
        }

        return $result;
    }

    /**
     * @return bool
     * @todo: add real check
     */
    public function canBeSubstituted()
    {
        return $this->eavAttribute instanceof ProductAttributeInterface;
    }
}
