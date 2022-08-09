<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Mapper;

use Dragonfly\UrlToGooglIndex\Api\Data\UrlProcessingInterface;
use Dragonfly\UrlToGooglIndex\Api\Data\UrlProcessingInterfaceFactory;
use Dragonfly\UrlToGooglIndex\Model\UrlProcessing;
use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Converts a collection of UrlProcessing entities to an array of data transfer objects.
 */
class UrlProcessingDataMapper
{
    /**
     * @var UrlProcessingInterfaceFactory
     */
    private $entityDtoFactory;

    /**
     * @param UrlProcessingInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        UrlProcessingInterfaceFactory $entityDtoFactory
    )
    {
        $this->entityDtoFactory = $entityDtoFactory;
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|UrlProcessingInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var UrlProcessing $item */
        foreach ($collection->getItems() as $item) {
            /** @var UrlProcessingInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->addData($item->getData());

            $results[] = $entityDto;
        }

        return $results;
    }
}
