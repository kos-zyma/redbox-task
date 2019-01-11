<?php

namespace Redbox\Linkedin\Model\Plugin\Quote;

/**
 * Class SaveToQuotePlugin
 * @package Redbox\Linkedin\Model\Plugin\Quote
 */
class SaveToQuotePlugin
{
    /**
     * Quote repository.
     *
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        /** @var \Magento\Quote\Api\Data\AddressExtension $customAttributes */
        $customAttributes = $addressInformation->getShippingAddress()->getCustomAttributes();

        if (!$customAttributes) {
            return;
        }
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        $quote->setData('linkedin_profile', $customAttributes->getLinkedinProfile());
    }
}
