<?php
namespace Dhaval\UserStatus\Controller\Status;

use Magento\Framework\App\Action;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Controller\ResultFactory;
use Magento\Customer\Model\Session;

class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * @var  \Magento\Framework\View\Result\Page 
     */
    protected $resultPageFactory;
    
    /**
     * @var Session 
     */
    protected $customerSession;
    
    /**
     * @var customer 
     */
    protected $customer;
    
    /**
     * @var \Psr\Log\LoggerInterface 
     */
    protected $logger;
    
    /**
     * @var customerFactory 
     */ 
    protected $customerFactory; 
    
    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param Session $customerSession
     * @param \Magento\Customer\Model\Customer $customer
     * @param \Magento\Customer\Model\ResourceModel\CustomerFactory $customerFactory
     * @param \Psr\Log\LoggerInterface $logger
     */ 
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        Session $customerSession,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Customer\Model\ResourceModel\CustomerFactory $customerFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        $this->customer = $customer;
        $this->customerFactory = $customerFactory;
        $this->logger = $logger;
        parent::__construct($context);
    }
    
    /**
     * Save customer Status
     */
    public function execute()
    {
        if ($this->customerSession->isLoggedIn()) {
            try {
                $customerId = $this->customerSession->getCustomer()->getId();
                $customer = $this->customer->load($customerId);
                $postStatus = $this->getRequest()->getParam('user_status');
                $customerData = $customer->getDataModel();
                $customerData->setCustomAttribute('user_status',$postStatus);
                $customer->updateData($customerData);
                $customerResource = $this->customerFactory->create();
                $customerResource->saveAttribute($customer, 'user_status');
                $this->messageManager->addSuccessMessage(__('Status saved successfully.'));
                $this->_redirect('*/*/');
            } catch (\Exception $e) {
                $this->logger->log('ERROR', $e->getMessage());
                $this->messageManager->addErrorMessage(__('Customer could not save. Try again..'));
                $this->_redirect('*/*/');
            }
        } else {
             $this->messageManager->addErrorMessage(__('Please login as a customer.'));   
             $this->_redirect('/');    
        }
    }
}