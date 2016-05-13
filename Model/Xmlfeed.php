<?php

namespace Magefox\GoogleShopping\Model;

class Xmlfeed
{
    /**
     * XML Generator
     *
     * @var \Magento\Framework\Xml\Generator
     */
    private $generator;

    public function __construct(
        \Magento\Framework\Xml\Generator $generator
    ) {
        $this->generator = $generator;
    }

    public function getFeed()
    {
        return $this->getBody();
    }

    public function getBody()
    {

        $xml = $this->generator->getDom();

        $xml->createElement("rss");
        //create "RSS" element
        $rss = $xml->createElement("rss");
        $rss_node = $xml->appendChild($rss); //add RSS element to XML node
        $rss_node->setAttribute("version","2.0"); //set RSS version

//        $products = array(
//            'item' => array
//            (
//                'title' => "Product 1",
//                'price' => "9.99"
//            ),
//            'item' => array
//            (
//                'title' => "Product 2",
//                'price' => "4.99"
//            )
//        );
//
//        $body = array(
//            'rss' => array (
//                'channel' => array(
//                    'title' => 'Your Website',
//                    'description' => 'Your Description',
//                    'link' => 'Base URL',
//                    'items' => $products
//                )
//            )
//        );

        //$body = $this->generator->arrayToXml($body);

        return $xml->__toString();
    }


}