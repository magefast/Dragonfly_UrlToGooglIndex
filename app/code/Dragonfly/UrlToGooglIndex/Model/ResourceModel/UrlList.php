<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Model\ResourceModel;

use Dragonfly\UrlToGooglIndex\Api\Data\UrlListInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class UrlList extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'url_to_googl_index_list_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('url_to_googl_index_list', UrlListInterface::ENTITY_ID);
        $this->_useIsObjectNew = true;
    }

    /**
     * Truncate table
     *
     * @return $this
     * @throws LocalizedException
     */
    public function truncateTable(): UrlList
    {
        if ($this->getConnection()->getTransactionLevel() > 0) {
            $this->getConnection()->delete($this->getMainTable());
        } else {
            $this->getConnection()->truncateTable($this->getMainTable());
        }

        return $this;
    }
}
