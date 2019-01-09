<?php

namespace Redbox\Linkedin\Model\Plugin\Quote;

class SaveToQuotePlugin
{

    /**
     * Quote repository.
     *
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;


    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    )
    {
        if (!$extAttributes = $addressInformation->getShippingAddress()->getExtensionAttributes()){
            return;
        }

        $quote = $this->quoteRepository->getActive($cartId);

        $quote->setLinkedinProfile($extAttributes->getLinkedinProfile());

    }
}