<?php

namespace Magefox\GoogleShopping\Helper;

class Products extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \Magento\Eav\ModelAttributeSetRepository
     */
    protected $_attributeSetRepo;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Eav\Model\AttributeSetRepository $attributeSetRepo
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_attributeSetRepo = $attributeSetRepo;
        parent::__construct($context);
    }

    public function getFilteredProducts()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->setPageSize(20);
        return $collection;
    }

    public function getAttributeSet($product)
    {
        $attributeSetId = $product->getAttributeSetId();
        $attributeSet = $this->_attributeSetRepo->get($attributeSetId);

        return $attributeSet->getAttributeSetName();

    }

    public function getGoogleCategory()
    {

    }
}