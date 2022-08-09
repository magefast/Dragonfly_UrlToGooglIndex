<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Model\ResourceModel\UrlList;

use Dragonfly\UrlToGooglIndex\Model\ResourceModel\UrlList as ResourceModel;
use Dragonfly\UrlToGooglIndex\Model\UrlList as Model;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'url_to_googl_index_list_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
