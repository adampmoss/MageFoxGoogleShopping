<?php

namespace Magefox\GoogleShopping\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    /** @var \Magefox\GoogleShopping\Model\Xmlfeed  */
    protected $xmlFeed;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magefox\GoogleShopping\Model\Xmlfeed $xmlFeed
    ) {
        $this->xmlFeed = $xmlFeed;
        parent::__construct($context);
    }
    
    public function execute()
    {
        echo $this->xmlFeed->getFeed();
    }
}