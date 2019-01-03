<?php

namespace Redbox\Linkedin\Model\Plugin;

use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Customer\Api\Data\CustomerExtensionInterface;
use Magento\Framework\App\ObjectManager;

class CustomerPlugin
{
    /**
     * @var ExtensionAttributesFactory
     */
    private $extensionFactory;

    /**
     * @var array
     */
    private $customerSubscriptionStatus = [];


    /**
     * @param ExtensionAttributesFactory|null $extensionFactory
     */
    public function __construct(
        ExtensionAttributesFactory $extensionFactory = null
    ) {
        $this->extensionFactory = $extensionFactory
            ?: ObjectManager::getInstance()->get(ExtensionAttributesFactory::class);
    }

    /**
     * @param CustomerRepository $subject
     * @param CustomerInterface $result
     * @param CustomerInterface $customer
     * @return CustomerInterface
     */
    public function afterSave(CustomerRepository $subject, CustomerInterface $result, CustomerInterface $customer)
    {

        return $result;
    }

    /**
     * @param CustomerRepository $subject
     * @param callable $deleteCustomerById
     * @param $customerId
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function aroundDeleteById(
        CustomerRepository $subject,
        callable $deleteCustomerById,
        $customerId
    ) {
        $customer = $subject->getById($customerId);
        $result = $deleteCustomerById($customerId);

        return $result;
    }

    /**
     * @param CustomerRepository $subject
     * @param $result
     * @param CustomerInterface $customer
     * @return mixed
     */
    public function afterDelete(CustomerRepository $subject, $result, CustomerInterface $customer)
    {

        return $result;
    }

    /**
     * @param CustomerRepository $subject
     * @param CustomerInterface $customer
     * @return CustomerInterface
     */
    public function afterGetById(CustomerRepository $subject, CustomerInterface $customer)
    {
        $extensionAttributes = $customer->getExtensionAttributes();

        return $customer;
    }


}
