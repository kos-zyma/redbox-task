<?php

namespace Redbox\Linkedin\Observer;

use Magento\Sales\Api\Data\OrderInterface;

class PlaceOrder implements \Magento\Framework\Event\ObserverInterface
{
    const LINKEDIN_PROFILE = 'linkedin_profile';

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();
        if ($order instanceof OrderInterface) {
            $order->setData(self::LINKEDIN_PROFILE, $quote->getLinkedinProfile());
        }
        return $this;
    }
}