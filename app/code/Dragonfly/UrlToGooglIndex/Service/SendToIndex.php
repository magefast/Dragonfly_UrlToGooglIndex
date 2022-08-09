<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Service;

use Dragonfly\UrlToGooglIndex\Api\Data\UrlListInterface;
use Dragonfly\UrlToGooglIndex\Api\Data\UrlProcessingInterface;
use Dragonfly\UrlToGooglIndex\Api\Data\UrlProcessingInterfaceFactory;
use Dragonfly\UrlToGooglIndex\Command\UrlProcessing\SaveCommand;
use Dragonfly\UrlToGooglIndex\Model\ResourceModel\UrlList\CollectionFactory as UrlListCollectionFactory;
use Dragonfly\UrlToGooglIndex\Model\ResourceModel\UrlProcessing;
use Dragonfly\UrlToGooglIndex\Model\ResourceModel\UrlProcessing\CollectionFactory as UrlProcessingCollectionFactory;
use Google\Exception;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;

class SendToIndex
{
    public const LIMIT_PER_DAY = 200;

    /**
     * @var array
     */
    private $candidateUrlToGooglIndex = [];

    /**
     * @var UrlListCollectionFactory
     */
    private $urlListCollectionFactory;

    /**
     * @var UrlProcessingCollectionFactory
     */
    private $urlProcessingCollectionFactory;

    /**
     * @var UrlProcessing
     */
    private $urlProcessingResourceModel;
    /**
     * @var SaveCommand
     */
    private $urlProcessingSaveCommand;
    /**
     * @var UrlProcessingInterfaceFactory
     */
    private $urlProcessingInterfaceFactory;

    /**
     * @var GoogleApi
     */
    private $googleApiService;

    /**
     * @param UrlListCollectionFactory $urlListCollectionFactory
     * @param UrlProcessingCollectionFactory $urlProcessingCollectionFactory
     * @param UrlProcessing $urlProcessingResourceModel
     * @param SaveCommand $urlProcessingSaveCommand
     * @param UrlProcessingInterfaceFactory $urlProcessingInterfaceFactory
     * @param GoogleApi $googleApiService
     */
    public function __construct(
        UrlListCollectionFactory       $urlListCollectionFactory,
        UrlProcessingCollectionFactory $urlProcessingCollectionFactory,
        UrlProcessing                  $urlProcessingResourceModel,
        SaveCommand                    $urlProcessingSaveCommand,
        UrlProcessingInterfaceFactory  $urlProcessingInterfaceFactory,
        GoogleApi                      $googleApiService
    )
    {
        $this->urlListCollectionFactory = $urlListCollectionFactory;
        $this->urlProcessingCollectionFactory = $urlProcessingCollectionFactory;
        $this->urlProcessingResourceModel = $urlProcessingResourceModel;
        $this->urlProcessingSaveCommand = $urlProcessingSaveCommand;
        $this->urlProcessingInterfaceFactory = $urlProcessingInterfaceFactory;
        $this->googleApiService = $googleApiService;
    }

    /**
     * @throws LocalizedException
     */
    public function execute()
    {
        #1 get all urls from list (url + prio)

        $allUrl = $this->getUrlList();
        $processingUrl = $this->getUrlProcessing();

        #2 check if exist url in added to index table
        foreach ($allUrl as $key => $value) {
            if (!isset($processingUrl[$key])) {
                #a if not added - add for candidate to index
                $this->candidateUrlToGooglIndex[$key] = [UrlProcessingInterface::URL => $key];
            } else {
                if ($allUrl[$key][UrlListInterface::PRIO] == 1) {
                    #b if added but prio is 1 - add for candidate to index
                    $this->candidateUrlToGooglIndex[$key] = $processingUrl[$key];
                }
            }
            #c others case - skip
        }

        #3 add to index - check limit
        $addedDate = date('U');
        $countAdded = 1;
        foreach ($this->candidateUrlToGooglIndex as $url) {
            if (self::LIMIT_PER_DAY >= $countAdded) {
                $countAdded++;
                $result = $this->addGooglIndex($url[UrlProcessingInterface::URL]);
                if ($result === true) {
                    $url[UrlProcessingInterface::ADDED_DATE] = intval($addedDate);
                    $this->add($url);
                }
            }
        }

        #4 if no url for add to index - truncate table
        if (count($this->candidateUrlToGooglIndex) == 0) {
            $this->urlProcessingResourceModel->truncateTable();
        }
    }

    /**
     * @return array
     */
    private function getUrlList(): array
    {
        $collection = $this->urlListCollectionFactory->create();
        $collection = $collection->getItems();

        $array = [];
        foreach ($collection as $c) {
            $array[$c->getUrl()] = [UrlListInterface::URL => $c->getUrl(), UrlListInterface::PRIO => $c->getPrio()];
        }
        unset($collection);

        return $array;
    }

    /**
     * @return array
     */
    private function getUrlProcessing(): array
    {
        $collection = $this->urlProcessingCollectionFactory->create();
        $collection = $collection->getItems();

        $array = [];
        foreach ($collection as $c) {
            $array[$c->getUrl()] = [
                UrlProcessingInterface::URL => $c->getUrl(),
                UrlProcessingInterface::ADDED_DATE => $c->getAddedDate(),
                UrlProcessingInterface::ENTITY_ID => $c->getEntityId(),
            ];
        }
        unset($collection);

        return $array;
    }

    /**
     * @param array $value
     */
    private function add(array $value)
    {
        try {
            /** @var UrlProcessing|DataObject $urlList */
            $urlList = $this->urlProcessingInterfaceFactory->create();
            $urlList->addData($value);
            $this->urlProcessingSaveCommand->execute($urlList);
        } catch (CouldNotSaveException $exception) {

        }
    }

    /**
     * @param string $url
     * @return bool
     * @throws LocalizedException
     * @throws Exception
     * @throws GuzzleException
     * @throws FileSystemException
     */
    private function addGooglIndex(string $url): bool
    {
        return $this->googleApiService->add($url);
    }
}
