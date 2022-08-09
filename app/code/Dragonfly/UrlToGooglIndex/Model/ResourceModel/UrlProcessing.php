<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Model\ResourceModel;

use Dragonfly\UrlToGooglIndex\Api\Data\UrlProcessingInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class UrlProcessing extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'url_to_googl_index_processing_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('url_to_googl_index_processing', UrlProcessingInterface::ENTITY_ID);
        $this->_useIsObjectNew = true;
    }

    /**
     * @return UrlProcessing
     * @throws LocalizedException
     */
    public function truncateTable(): UrlProcessing
    {
        if ($this->getConnection()->getTransactionLevel() > 0) {
            $this->getConnection()->delete($this->getMainTable());
        } else {
            $this->getConnection()->truncateTable($this->getMainTable());
        }

        return $this;
    }
}
