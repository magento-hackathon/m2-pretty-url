<?php
/**
 *
 * User: Aleksandr Yegorov (a.yegorov@ism.nl)
 * Date: 2016-01-23
 * Copyright 2016 ISM eCompany http://www.ism.nl/
 */

namespace Hackathon\PrettyUrl\Block\LayeredNavigation;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Swatches\Block\LayeredNavigation\RenderLayered;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\Layer\Filter\Item as FilterItem;
use Hackathon\PrettyUrl\Helper\Url as UrlHelper;

class RenderLayeredPlugin
{
    /** @var Attribute */
    protected $eavAttribute;

    /** @var AbstractFilter */
    protected $filter;

    /** @var UrlHelper  */
    protected $urlHelper;

    /**
     * RenderLayeredPlugin constructor.
     * @param UrlHelper $urlHelper
     */
    public function __construct(UrlHelper $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param RenderLayered $subject
     * @param AbstractFilter $filter
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSetSwatchFilter(RenderLayered $subject, AbstractFilter $filter)
    {
        $this->filter = $filter;
        $this->eavAttribute = $filter->getAttributeModel();
    }

    /**
     * @param RenderLayered $subject
     * @param \Closure $proceed
     * @param string $attributeCode
     * @param int $optionId
     * @return string
     *
     * @todo: build URL with option label
     */
    public function aroundBuildUrl(RenderLayered $subject, \Closure $proceed, $attributeCode, $optionId)
    {
        if ($this->canBeSubstituted()) {
            $result = $this->urlHelper->getOptionUrl($this->eavAttribute, $optionId);
        } else{
            $result = $proceed($attributeCode, $optionId);
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
