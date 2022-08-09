<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Model\ResourceModel\UrlProcessing;

use Dragonfly\UrlToGooglIndex\Model\ResourceModel\UrlProcessing as ResourceUrlProcessing;
use Dragonfly\UrlToGooglIndex\Model\UrlProcessing;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'url_to_googl_index_processing_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(UrlProcessing::class, ResourceUrlProcessing::class);
    }
}
