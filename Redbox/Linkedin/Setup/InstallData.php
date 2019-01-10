<?php

namespace Redbox\Linkedin\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class InstallData
 * @package Redbox\Linkedin\Setup
 */
class InstallData implements InstallDataInterface
{
    const LINKED_ATTR_CODE = 'linkedin_profile';

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var \Magento\Customer\Setup\CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerSetup->addAttribute(Customer::ENTITY, self::LINKED_ATTR_CODE, [
            'type' => 'varchar',
            'label' => 'LinkedIn Profile',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'validate_rules' => '{"max_text_length":250}',
            'sort_order' => 1000,
            'position' => 1000,
            'system' => 0,
        ]);

        $this->addLinkedinAttributeToAllForms($customerSetup);
    }

    /**
     * @param \Magento\Customer\Setup\CustomerSetup $customerSetup
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function addLinkedinAttributeToAllForms(\Magento\Customer\Setup\CustomerSetup $customerSetup)
    {
        $attribute = $customerSetup
            ->getEavConfig()
            ->getAttribute(Customer::ENTITY, self::LINKED_ATTR_CODE)
            ->addData([
                'used_in_forms' => [
                    'adminhtml_customer',
                    'adminhtml_checkout',
                    'customer_account_create',
                    'customer_account_edit',
                    'checkout_register',
                ],
            ]);

        $attribute->save();
    }
}
