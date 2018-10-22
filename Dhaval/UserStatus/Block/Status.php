<?php

namespace Dhaval\UserStatus\Block;

use Dhaval\UserStatus\Model\Config\Source\Options;

class Status extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Options 
     */
    public $statusOptions;
    
    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    protected $sessionFactory;
    
    /**
     * @var customer 
     */
    protected $customer;
    
    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Customer\Model\SessionFactory $sessionFactory
     * @param \Magento\Customer\Model\Customer $customer
     * @param Options $statusOptions
     * @param array $data
     */ 
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Customer\Model\SessionFactory $sessionFactory,
        \Magento\Customer\Model\Customer $customer,
        Options $statusOptions,
        array $data = []
    ) {
        $this->statusOptions = $statusOptions;
        $this->sessionFactory = $sessionFactory;
        $this->customer = $customer;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve form action url and set "secure" param to avoid confirm
     * message when we submit form from secure page to unsecure
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        return $this->getUrl('userstatus/status/save', ['_secure' => true]);
    }
    
    /**
     * get available optiolns
     */
    public function getStatusOptions()
    {   
        return $this->statusOptions->getAllOptions();
    }
    
    /**
     * Get customer status value
     * @return int|null
     */
    public function getStatusValue()
    {
        $sessionModel = $this->sessionFactory->create();
        $customerId = $sessionModel->getCustomer()->getId();
        $customerData = $this->customer->load($customerId);
        return $customerData->getUserStatus();
    }
    
    /**
     * check customer logged or not
     * @return boolean
     */
    public function isCustomerLoggedIn()
    {
        $sessionModel = $this->sessionFactory->create();
        
        return (int)$sessionModel->isLoggedIn();
    }
}