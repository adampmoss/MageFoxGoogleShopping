<?php

namespace Magefox\GoogleShopping\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const GS_CONFIG_PATH = 'magefoxgoogleshopping/settings/';

    public function getConfig($configNode)
    {
        return $this->scopeConfig->getValue(
            self::GS_CONFIG_PATH.$configNode,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}