<?php

namespace Dhaval\UserStatus\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;

class Options extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{ 

    /**
     * generate options
     * @return array
     */
    public function getAllOptions()
    {
        /* status Attribute options list*/
        $this->_options = [
            ['label'=>'Select Options', 'value'=>''],
            ['label'=>'Online', 'value'=>'0'],
            ['label'=>'Away', 'value'=>'1'],
            ['label'=>'Do Not Distrub', 'value'=>'2'],
            ['label'=>'Invisible', 'value'=>'3'],
            ['label'=>'Offline', 'value'=>'4']
        ];

        return $this->_options;
    }
    
    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string|bool
     */
    public function getOptionText($value)
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }
    
    /**
     * Retrieve flat column definition
     *
     * @return array
     */
    public function getFlatColumns()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        return [
            $attributeCode => [
                'unsigned' => false,
                'default' => null,
                'extra' => null,
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Custom Attribute Options  ' . $attributeCode . ' column',
            ],
        ];
    }

}