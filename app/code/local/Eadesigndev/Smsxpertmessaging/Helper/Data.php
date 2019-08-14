<?php

/**
 * EaDesgin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eadesign.ro so we can send you a copy immediately.
 *
 * @category    Eadesigndev_Followup
 * @copyright   Copyright (c) 2008-2015 EaDesign by Eco Active S.R.L.
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Eadesigndev_Smsxpertmessaging_Helper_Data extends Mage_Core_Helper_Abstract
{

    private $_phone;

    public function generateXmlParams($template)
    {
        $xml = '';
        $xml .= '<PALO><HEAD>';
        $xml .= '<FROM>' . $this->getCodSender() . '</FROM>';
        $xml .= '<APP USER="' . $this->getSmsUser() . '" PASSWORD="' . $this->getSmsPass() . '">LA</APP>';
        $xml .= '<CMD>sendtextmt</CMD>';
        $xml .= '</HEAD><BODY>';
        $xml .= '<SENDER>' . $this->getDeLa() . '</SENDER>';
        $xml .= '<CONTENT><![CDATA[' . $template . ']]></CONTENT>';
        $xml .= '<DEST_LIST>';
        $xml .= '<TO>' . $this->getCodTara() . $this->_getPhone() . '</TO>';
        $xml .= '</DEST_LIST>';
        $xml .= '</BODY><OPTIONAL>';
        $xml .= '<MSG_ID>{msg id you wish to add}</MSG_ID>';
        $xml .= '</OPTIONAL></PALO>';

        $this->connectAndSend($xml);

    }

    public function connectAndSend($xml)
    {

        $xml = str_replace("%", "%25", $xml);
        $xml = str_replace(" ", "%20", $xml);
        $xml = str_replace("#", "%23", $xml);
        $xml = str_replace("&", "%26", $xml);
        $xml = str_replace("?", "%3F", $xml);
        $xml = str_replace("+", "%2B", $xml);

        $url_request = 'https://xpertmessaging.com/api/unistart5.asp';
        $curl = curl_init();
        $headers[] = "Content-type: application/x-www-form-urlencoded";
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $url_request);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "XMLString=$xml");
        curl_setopt($curl, CURLOPT_USERAGENT, 'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.26) Gecko/20120128 Firefox/3.6.26');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $ReturnSmsNotification = curl_exec($curl);
        curl_close($curl);

//        exit($ReturnSmsNotification);

    }


    public function processSmsTemplate($data)
    {

        if (!is_array($data)) {
            return;
        }

        if (!$this->getSmsNew()) {
            return;
        }

        $this->_phone = $data['customer-phone'];


        $template = Mage::getModel('core/email_template')
            ->load($this->getSmsNew())
            ->getProcessedTemplate($data);

        $this->generateXmlParams($template);

    }

    public function processSmsTemplateAwb($data)
    {

        if (!is_array($data)) {
            return;
        }

        if (!$this->getSmsAwb()) {
            return;
        }

        $this->_phone = $data['customer-phone'];


        $template = Mage::getModel('core/email_template')
            ->load($this->getSmsAwb())
            ->getProcessedTemplate($data);

        $this->generateXmlParams($template);

    }

    private function _getPhone()
    {
        return $this->_phone;
    }

    public function isActive()
    {
        return Mage::getStoreConfig('sms_settings/sms_opt/active');
    }

    public function getSmsUser()
    {
        return Mage::getStoreConfig('sms_settings/sms_opt/sms_user');
    }

    public function getSmsPass()
    {
        return Mage::getStoreConfig('sms_settings/sms_opt/sms_pass');
    }

    public function getCodSender()
    {
        return Mage::getStoreConfig('sms_settings/sms_opt/sms_code');
    }

    public function getCodTara()
    {
        return Mage::getStoreConfig('sms_settings/sms_opt/sms_cc');
    }

    public function getDeLa()
    {
        return Mage::getStoreConfig('sms_settings/sms_opt/sms_from');
    }

    public function getSmsNew()
    {
        return Mage::getStoreConfig('sms_settings/sms_opt/sms_new');
    }

    public function getSmsAwb()
    {
        return Mage::getStoreConfig('sms_settings/sms_opt/sms_complete');
    }


}