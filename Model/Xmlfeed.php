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

    public function __construct(
        \Magefox\GoogleShopping\Helper\Data $helper,
        \Magefox\GoogleShopping\Helper\Products $productFeedHelper

    ) {
        $this->_helper = $helper;
        $this->_productFeedHelper = $productFeedHelper;
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
        $xml .= '<title>Store Name</title>';
        $xml .= '<link>Base URL</link>';
        $xml .= '<description>Store Name</description>';

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
        $xml = $this->createNode("title", $product->getName(), true);
        $xml .= $this->createNode("link", $product->getProductUrl());
        $xml .= $this->createNode("description", $product->getDescription(), true);
        $xml .= $this->createNode("g:product_type", $this->_productFeedHelper->getAttributeSet($product));


        /*$xml .= "<g:image_link>".$product->getId()."</g:image_link>";
        $xml .= "<g:availability>".$product->getId()."</g:availability>";
        $xml .= "<g:condition>".$product->getId()."</g:condition>";
        $xml .= "<g:id>".$product->getSku()."</g:id>";
        $xml .= "<g:brand>".$product->getId()."</g:brand>";
        $xml .= "<g:mpn>".$product->getId()."</g:mpn>";
        $xml .= "<g:tax></g:tax>";*/
        
        return $xml;
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