<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Command\UrlList;

use Dragonfly\UrlToGooglIndex\Api\Data\UrlListInterface;
use Dragonfly\UrlToGooglIndex\Model\ResourceModel\UrlList as UrlListResource;
use Dragonfly\UrlToGooglIndex\Model\UrlList;
use Dragonfly\UrlToGooglIndex\Model\UrlListFactory;
use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;

/**
 * Save UrlList Command.
 */
class SaveCommand
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UrlListFactory
     */
    private $modelFactory;

    /**
     * @var UrlListResource
     */
    private $resource;

    /**
     * @param LoggerInterface $logger
     * @param UrlListFactory $modelFactory
     * @param UrlListResource $resource
     */
    public function __construct(
        LoggerInterface     $logger,
        UrlListFactory $modelFactory,
        UrlListResource     $resource
    )
    {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * Save UrlList.
     *
     * @param UrlListInterface $urlList
     *
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(UrlListInterface $urlList): int
    {
        try {
            /** @var UrlList $model */
            $model = $this->modelFactory->create();
            $model->addData($urlList->getData());
            $model->setHasDataChanges(true);

            if (!$model->getData(UrlListInterface::ENTITY_ID)) {
                $model->isObjectNew(true);
            }
            $this->resource->save($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save UrlList. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(__('Could not save UrlList.'));
        }

        return (int)$model->getData(UrlListInterface::ENTITY_ID);
    }
}
