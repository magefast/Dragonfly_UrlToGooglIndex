<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Model;

use Dragonfly\UrlToGooglIndex\Model\ResourceModel\UrlList as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class UrlList extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'url_to_googl_index_list_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
