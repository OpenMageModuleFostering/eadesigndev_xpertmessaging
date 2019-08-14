<?php

class Eadesigndev_Smsxpertmessaging_Model_System_Smslist
{
    public function toOptionArray()
    {
        $result = array();
        $collection = Mage::getResourceModel('core/email_template_collection')
            ->load();
        $options = $collection->toOptionArray();
        $defOptions = Mage_Core_Model_Email_Template::getDefaultTemplatesAsOptionsArray();
        foreach ($defOptions as $v) {
            $options[] = $v;
        }
        foreach ($options as $v) {
            $result[$v['value']] = $v['label'];
        }

        $options = array();
        $options[] = array('value' => '', 'label' => '---------Choose Email Template---------');
        foreach ($result as $k => $v) {
            if (!is_int($k))
                continue;
            $options[] = array('value' => $k, 'label' => $v);
        }

        $result = $options;

        return $result;
    }
}