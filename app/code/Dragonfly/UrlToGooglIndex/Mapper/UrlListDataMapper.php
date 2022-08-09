<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Mapper;

use Dragonfly\UrlToGooglIndex\Api\Data\UrlListInterface;
use Dragonfly\UrlToGooglIndex\Api\Data\UrlListInterfaceFactory;
use Dragonfly\UrlToGooglIndex\Model\UrlList;
use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Converts a collection of UrlList entities to an array of data transfer objects.
 */
class UrlListDataMapper
{
    /**
     * @var UrlListInterfaceFactory
     */
    private $entityDtoFactory;

    /**
     * @param UrlListInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        UrlListInterfaceFactory $entityDtoFactory
    )
    {
        $this->entityDtoFactory = $entityDtoFactory;
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|UrlListInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var UrlList $item */
        foreach ($collection->getItems() as $item) {
            /** @var UrlListInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->addData($item->getData());

            $results[] = $entityDto;
        }

        return $results;
    }
}
