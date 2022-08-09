<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Command\UrlProcessing;

use Dragonfly\UrlToGooglIndex\Api\Data\UrlProcessingInterface;
use Dragonfly\UrlToGooglIndex\Model\ResourceModel\UrlProcessing as ResourceUrlProcessing;
use Dragonfly\UrlToGooglIndex\Model\UrlProcessing;
use Dragonfly\UrlToGooglIndex\Model\UrlProcessingFactory;
use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;

/**
 * Save UrlProcessing Command.
 */
class SaveCommand
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UrlProcessingFactory
     */
    private $modelFactory;

    /**
     * @var ResourceUrlProcessing
     */
    private $resource;

    /**
     * @param LoggerInterface $logger
     * @param UrlProcessingFactory $modelFactory
     * @param ResourceUrlProcessing $resource
     */
    public function __construct(
        LoggerInterface       $logger,
        UrlProcessingFactory  $modelFactory,
        ResourceUrlProcessing $resource
    )
    {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * Save UrlProcessing.
     *
     * @param UrlProcessingInterface $urlProcessing
     *
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(UrlProcessingInterface $urlProcessing): int
    {
        try {
            /** @var UrlProcessing $model */
            $model = $this->modelFactory->create();
            $model->addData($urlProcessing->getData());
            $model->setHasDataChanges(true);

            if (!$model->getData(UrlProcessingInterface::ENTITY_ID)) {
                $model->isObjectNew(true);
            }
            $this->resource->save($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save UrlProcessing. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(__('Could not save UrlProcessing.'));
        }

        return (int)$model->getData(UrlProcessingInterface::ENTITY_ID);
    }
}
