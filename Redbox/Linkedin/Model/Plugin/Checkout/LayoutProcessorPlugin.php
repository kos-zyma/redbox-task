<?php

namespace Redbox\Linkedin\Model\Plugin\Checkout;

/**
 * Class LayoutProcessorPlugin
 * @package Redbox\Linkedin\Model\Plugin\Checkout
 */
class LayoutProcessorPlugin
{
    /**
     * Customer session
     *
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->customerSession = $customerSession;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ) {
        if (!$this->customerSession->isLoggedIn()) {
            $customAttributeCode = 'linkedin_profile';
            $customField = [
                'component' => 'Magento_Ui/js/form/element/abstract',
                'config' => [
                    'customScope' => 'shippingAddress.custom_attributes',
                    'customEntry' => null,
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/input',
                    'tooltip' => [
                        'description' => 'this is what the field is for',
                    ],
                ],
                'dataScope' => 'shippingAddress.custom_attributes' . '.' . $customAttributeCode,
                'label' => 'Linkedin Profile',
                'provider' => 'checkoutProvider',
                'sortOrder' => 0,
                'validation' => [
                    'required-entry' => true
                ],
                'options' => [],
                'filterBy' => null,
                'customEntry' => null,
                'visible' => true,
            ];

            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children'][$customAttributeCode] = $customField;
        }

        return $jsLayout;
    }
}
