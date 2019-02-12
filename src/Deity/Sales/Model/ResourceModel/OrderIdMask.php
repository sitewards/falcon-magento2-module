<?php
declare(strict_types=1);

namespace Deity\Sales\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * OrderIdMask Resource model
 */
class OrderIdMask extends AbstractDb
{

    /**#@+
     * Constants related to specific db layer
     */
    const TABLE_NAME_SOURCE_ITEM = 'sales_order_id_mask';
    const ID_FIELD_NAME = 'entity_id';
    /**#@-*/

    const ORDER_ID_FIELD_NAME = 'order_id';

    const MASKED_ID_FIELD_NAME = 'masked_id';

    /**
     * Main table and field initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME_SOURCE_ITEM, self::ID_FIELD_NAME);
    }
}
