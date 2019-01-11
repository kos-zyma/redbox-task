<?php

namespace Redbox\Linkedin\Block;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\EntityManager\Hydrator;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class UserAttributes
 * @package Redbox\Linkedin\Block
 */
class UserAttributes extends \Magento\Framework\View\Element\Template
{
    /**
     * Form identifiers
     */
    const CUSTOMER_EDIT_FORM = 'customer_account_edit';
    const CUSTOMER_CREATE_FORM = 'customer_account_create';

    /**
     * @var \Magento\Eav\Model\Config
     */
    private $eavConfig;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var string
     */
    private $form;

    /**
     * @var \Magento\Framework\EntityManager\Hydrator
     */
    private $hydrator;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param CustomerRepositoryInterface $customerRepository
     * @param Session $customerSession
     * @param Hydrator $hydrator
     * @param array $data
     * @throws LocalizedException
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Eav\Model\Config $eavConfig,
        CustomerRepositoryInterface $customerRepository,
        Session $customerSession,
        Hydrator $hydrator,
        array $data = []
    ) {
        parent::__construct($context, $data);

        if (!isset($data['form'])) {
            throw new LocalizedException(__('Form is not specified'));
        }

        $this->form = $data['form'];
        $this->eavConfig = $eavConfig;
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
        $this->hydrator = $hydrator;
    }

    /**
     * @return array
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAvailableAttributes()
    {
        $attributesCollection = $this->eavConfig->getEntityType('customer')->getAttributeCollection();
        $attributesMetadata = [];

        //Currently this works only with simple: varchar and text attributes.
        foreach ($attributesCollection as $attribute) {
            if (
                $attribute->getIsVisible() &&
                in_array($attribute->getBackendType(), ['varchar', 'text']) &&
                in_array($this->form, $attribute->getUsedInForms())
            ) {
                $metadata = [
                    'label' => $attribute->getStoreLabel(),
                    'attribute_code' => $attribute->getAttributeCode()
                ];
                //If customer exists need to hydrate attribute with some value
                if ($this->form === self::CUSTOMER_EDIT_FORM) {
                    $customerData = $this->hydrator->extract($this->getCustomer());
                    $metadata['value'] = isset($customerData[$attribute->getAttributeCode()]) ? 
                        $customerData[$attribute->getAttributeCode()] : null;
                } else {
                    $metadata['value'] = null;
                }

                $attributesMetadata[] = $metadata;
            }
        }

        return $attributesMetadata;
    }

    /**
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getCustomer()
    {
        return $this->customerRepository->getById($this->customerSession->getCustomerId());
    }
}
