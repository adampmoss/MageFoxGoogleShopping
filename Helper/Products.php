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

    /**
     * @var \Magefox\GoogleShopping\Helper\Data
     */
    protected $_helper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Eav\Model\AttributeSetRepository $attributeSetRepo,
        \Magefox\GoogleShopping\Helper\Data $helper
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_attributeSetRepo = $attributeSetRepo;
        $this->_helper = $helper;
        parent::__construct($context);
    }

    public function getFilteredProducts()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->setPageSize(200);
        return $collection;
    }

    public function getAttributeSet($product)
    {
        $attributeSetId = $product->getAttributeSetId();
        $attributeSet = $this->_attributeSetRepo->get($attributeSetId);

        return $attributeSet->getAttributeSetName();

    }

    public function getProductValue($product, $attributeCode)
    {
        $attributeCodeFromConfig = $this->_helper->getConfig($attributeCode.'_attribute');
        $defaultValue = $this->_helper->getConfig('default_'.$attributeCode);

        if (!empty($attributeCodeFromConfig))
        {
            return $product->getAttributeText($attributeCodeFromConfig);
        }

        if (!empty($defaultValue))
        {
            return $defaultValue;
        }

        return false;
    }
}