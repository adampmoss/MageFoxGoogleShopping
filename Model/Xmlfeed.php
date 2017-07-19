<?php

namespace Magefox\GoogleShopping\Model;

class Xmlfeed
{
    /**
     * General Helper
     *
     * @var \Magefox\GoogleShopping\Helper\Data
     */
    private $_helper;

    /**
     * Product Helper
     *
     * @var \Magefox\GoogleShopping\Helper\Products
     */
    private $_productFeedHelper;

    /**
     * Store Manager
     *
     * @var \Magefox\GoogleShopping\Helper\Products
     */
    private $_storeManager;

    public function __construct(
        \Magefox\GoogleShopping\Helper\Data $helper,
        \Magefox\GoogleShopping\Helper\Products $productFeedHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager

    ) {
        $this->_helper = $helper;
        $this->_productFeedHelper = $productFeedHelper;
        $this->_storeManager = $storeManager;
    }

    public function getFeed()
    {
        $xml = $this->getXmlHeader();
        $xml .= $this->getProductsXml();
        $xml .= $this->getXmlFooter();

        return $xml;
    }

    public function getXmlHeader()
    {

        header("Content-Type: application/xml; charset=utf-8");

        $xml =  '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
        $xml .= '<channel>';
        $xml .= '<title>'.$this->_helper->getConfig('google_default_title').'</title>';
        $xml .= '<link>'.$this->_helper->getConfig('google_default_url').'</link>';
        $xml .= '<description>'.$this->_helper->getConfig('google_default_description').'</description>';

        return $xml;

    }

    public function getXmlFooter()
    {
        return  '</channel></rss>';
    }

    public function getProductsXml()
    {
        $productCollection = $this->_productFeedHelper->getFilteredProducts();
        $xml = "";

        foreach ($productCollection as $product)
        {
            $xml .= "<item>".$this->buildProductXml($product)."</item>";
        }

        return $xml;
    }

    public function buildProductXml($product)
    {
        $_description = $this->fixDescription($product->getDescription());
        $xml = $this->createNode("title", $product->getName(), true);
        $xml .= $this->createNode("link", $product->getProductUrl());
        $xml .= $this->createNode("description", $_description, true);
        $xml .= $this->createNode("g:product_type", $this->_productFeedHelper->getAttributeSet($product), true);
        $xml .= $this->createNode("g:image_link", $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA, true).'catalog/product'.$product->getImage());
        $xml .= $this->createNode('g:google_product_category',
            $this->_productFeedHelper->getProductValue($product, 'google_product_category'), true);
        $xml .= $this->createNode("g:availability", 'in stock');
        $xml .= $this->createNode('g:price', number_format($product->getFinalPrice(),2,'.','').' '.$this->_productFeedHelper->getCurrentCurrencySymbol());
        if ($product->getSpecialPrice() != $product->getFinalPrice())
            $xml .= $this->createNode('g:sale_price', number_format($product->getSpecialPrice(),2,'.','').' '.$this->_productFeedHelper->getCurrentCurrencySymbol());
        $_condition = $product->getAttributeText('condition');
        if (is_array($_condition))
            $xml .= $this->createNode("g:condition", $_condition[0]);
        else
            $xml .= $this->createNode("g:condition", $_condition);
        $xml .= $this->createNode("g:gtin", $product->getAttributeText('gr_ean'));
        $xml .= $this->createNode("g:id", $product->getId());
        $xml .= $this->createNode("g:brand", $product->getAttributeText('manufacturer'));
        $xml .= $this->createNode("g:mpn", $product->getSku());

        return $xml;
    }

    public function fixDescription($data)
    {
        $description = $data;
        $encode = mb_detect_encoding($data);
        $description = mb_convert_encoding($description, 'UTF-8', $encode);

        return $description;
    }

    public function createNode($nodeName, $value, $cData = false)
    {
        if (empty($value) || empty ($nodeName))
        {
            return false;
        }

        $cDataStart = "";
        $cDataEnd = "";

        if ($cData === true)
        {
            $cDataStart = "<![CDATA[";
            $cDataEnd = "]]>";
        }

        $node = "<".$nodeName.">".$cDataStart.$value.$cDataEnd."</".$nodeName.">";

        return $node;
    }

}
