<?php
/**
 *
 * User: Aleksandr Yegorov (a.yegorov@ism.nl)
 * Date: 2016-01-23
 * Copyright 2016 ISM eCompany http://www.ism.nl/
 */

namespace Hackathon\PrettyUrl\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Eav\Model\Entity\Attribute;

class Url extends AbstractHelper
{
    const DELIMITER = '-';
    const SPECIAL_CHAR = '_';

    /**
     * @param Attribute $attribute
     * @param int $optionId
     * @return null|string
     * @toso: add mapping/caching
     */
    public function getOptionText($attribute, $optionId)
    {
        $result = null;
        /** @var \Magento\Eav\Api\Data\AttributeOptionInterface $option */
        foreach ($attribute->getOptions() as $option) {
            if ($option->getValue() == $optionId) {
                $result = $option->getLabel();
            }
        }

        return $result;
    }

    public function getOptionUrl($attribute, $optionId)
    {
        $label = $this->getOptionText($attribute, $optionId);

        $label = $this->createKey($label);

        $url = $this->_urlBuilder->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);

        $data = parse_url($url);
        // TODO: remove substituted values from URL
        $data['path'] = rtrim($data['path'], '/');
        $delimiter = ($this->shouldAddDelimiter($data['path'])) ? $this->getDelimiterChar() . '/' : '';
        $data['path'] .= '/' . $delimiter . $label . '/';
        // TODO: use \Magento\Framework\Url\Helper\Data::removeRequestParam()
        $url = $this->unparseUrl($data);
        $result = $this->_urlBuilder->getRouteUrl($url);

        return $result;
    }

    // todo: parse code=option_id from url path
    public function getUrlParametersFromPath($urlPath)
    {
        return [
            'blue' => ['color' => 65],

        ];
    }

    public function createKey($label)
    {
        $specialCharReplacement = $this->getSpecialCharReplacement();
        $key = preg_replace('/[^0-9a-z,]+/i', $specialCharReplacement, $label);
        $key = strtolower($key);
        $key = trim($key, $specialCharReplacement);

        return $key;
    }

    public function unparseUrl($parsed_url) {
        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    public function getSpecialCharReplacement()
    {
        return self::SPECIAL_CHAR;
    }

    /**
     * @return string
     */
    public function getDelimiterChar()
    {
        return self::DELIMITER;
    }

    public function shouldAddDelimiter($url)
    {
        return false === strpos($url, '/' . $this->getDelimiterChar() . '/');
    }
}
