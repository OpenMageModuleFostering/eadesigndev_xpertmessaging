<?php

/**
 * Created by IntelliJ IDEA.
 * User: eadesignpc
 * Date: 6/23/2015
 * Time: 11:56 AM
 */
class Eadesigndev_Smsxpertmessaging_Model_Observer
{
    public function sendSms($observer)
    {

        $helper = Mage::helper('smsxpertmessaging');

        if (!$helper->isActive()) {
            return;
        }

        $order = $observer->getOrder();

        $orderNumber = '# ' . $order->getData('increment_id');

        if ($order->getCustomerIsGuest()) {
            $name = $order->getBillingAddress()->getName();
        } else {
            $name = $order->getCustomerName();
        }

        $phoneData = explode('7', $order->getBillingAddress()->getTelephone(), 2);

        $phone = '7' . $phoneData[1];

        $data = array(
            'customer-name' => $name,
            'customer-phone' => $phone,
            'order-number' => $orderNumber,
        );

        $helper->processSmsTemplate($data);

    }

    public function sendSmsCompleate($observer)
    {

        $helper = Mage::helper('smsxpertmessaging');

        if (!$helper->isActive()) {
            return;
        }

        $order = $observer->getShipment()->getOrder();

        $trakings = $observer->getShipment()->getAllTracks();

        if(!count($trakings)){
            return;
        }

        $trackNums = array();
        $trackTitles = array();
        foreach($trakings as $tracknum)
        {
            $trackNums[]=$tracknum->getNumber();
            $trackTitles[]=$tracknum->getTitle();
        }

        $trackNum = end($trackNums);
        $trackTitle = end($trackTitles);

        $orderNumber = '# ' . $order->getData('increment_id');

        if ($order->getCustomerIsGuest()) {
            $name = $order->getBillingAddress()->getName();
        } else {
            $name = $order->getCustomerName();
        }

        $phoneData = explode('7', $order->getBillingAddress()->getTelephone(), 2);

        $phone = '7' . $phoneData[1];

        $data = array(
            'customer-name' => $name,
            'customer-phone' => $phone,
            'order-number' => $orderNumber,
            'awb-number' => $trackNum,
            'awb-title' => $trackTitle,
        );

        $helper->processSmsTemplateAwb($data);
    }
}