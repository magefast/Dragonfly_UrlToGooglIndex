<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Service;

use Dragonfly\UrlToGooglIndex\Api\Data\UrlListInterface;
use Dragonfly\UrlToGooglIndex\Api\Data\UrlListInterfaceFactory;
use Dragonfly\UrlToGooglIndex\Command\UrlList\SaveCommand;
use Dragonfly\UrlToGooglIndex\Model\ResourceModel\UrlList;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\CatalogInventory\Helper\Stock;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\App\EmulationFactory;
use Magento\Store\Model\StoreManagerInterface;

class ReListUrlList
{
    public const BEFORE_DAYS_FILTER = 3;

    /**
     * @var SaveCommand
     */
    private $saveCommand;

    /**
     * @var UrlListInterfaceFactory
     */
    private $factory;

    /**
     * @var CategoryCollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var Stock
     */
    private $stock;

    /**
     * @var UrlList
     */
    private $urlListResourceModel;

    /**
     * @var EmulationFactory
     */
    private $emulationFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param SaveCommand $saveCommand
     * @param UrlListInterfaceFactory $factory
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param ProductCollectionFactory $productCollectionFactory
     * @param Stock $stock
     * @param UrlList $urlListResourceModel
     * @param EmulationFactory $emulationFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        SaveCommand               $saveCommand,
        UrlListInterfaceFactory   $factory,
        CategoryCollectionFactory $categoryCollectionFactory,
        ProductCollectionFactory  $productCollectionFactory,
        Stock                     $stock,
        UrlList                   $urlListResourceModel,
        EmulationFactory          $emulationFactory,
        StoreManagerInterface     $storeManager
    )
    {
        $this->saveCommand = $saveCommand;
        $this->factory = $factory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->stock = $stock;
        $this->urlListResourceModel = $urlListResourceModel;
        $this->emulationFactory = $emulationFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @throws LocalizedException
     */
    public function reload()
    {
        //truncate table
        $this->urlListResourceModel->truncateTable();

        $websiteId = $this->storeManager->getDefaultStoreView()->getWebsiteId();
        $storeId = $this->storeManager->getWebsite($websiteId)->getDefaultStore()->getId();
        $appEmulation = $this->emulationFactory->create();
        $appEmulation->startEnvironmentEmulation($storeId);

        $categoriesUrl = $this->getCategories();
        foreach ($categoriesUrl as $value) {
            $this->add($value);
        }
        unset($categoriesUrl);

        $productsUrl = $this->getProducts();
        foreach ($productsUrl as $value) {
            $this->add($value);
        }
        unset($productsUrl);
    }

    /**
     * @return array
     */
    private function getProducts(): array
    {
        $result = [];
        $unixTimeFilterBefore = strtotime("-" . self::BEFORE_DAYS_FILTER . " day");

        $products = $this->productCollectionFactory->create();
        $products->addFieldToFilter('visibility', Visibility::VISIBILITY_BOTH)
            ->addFieldToFilter('status', Status::STATUS_ENABLED)
            ->addAttributeToFilter('type_id', Type::TYPE_SIMPLE)
            ->addAttributeToSelect('entity_id')
            ->addAttributeToSelect('updated_at')
            ->addAttributeToSelect('visibility')
            ->addAttributeToSelect('status')
            ->addAttributeToSelect('type_id')
            ->addUrlRewrite();

        $this->stock->addInStockFilterToCollection($products);

        foreach ($products as $product) {
            $unixTimeFormatUpdatedAt = strtotime($product->getUpdatedAt());
            $url = $product->getProductUrl();
            if ($unixTimeFormatUpdatedAt > $unixTimeFilterBefore) {
                $result[$url] = [UrlListInterface::URL => $url, UrlListInterface::PRIO => 1];
            } else {
                $result[$url] = [UrlListInterface::URL => $url, UrlListInterface::PRIO => 2];
            }
        }
        unset($products);

        return $result;
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    private function getCategories(): array
    {
        $result = [];
        $unixTimeFilterBefore = strtotime("-" . self::BEFORE_DAYS_FILTER . " day");

        $categories = $this->categoryCollectionFactory->create();
        $categories->addAttributeToSelect(['updated_at', 'is_active', 'level']);
        foreach ($categories as $category) {
            if ($category->getIsActive()) {
                if ($category->getLevel() == 0 || $category->getLevel() == 1) {
                    continue;
                }

                $unixTimeFormatUpdatedAt = strtotime($category->getUpdatedAt());
                $url = $category->getUrl();
                if ($unixTimeFormatUpdatedAt > $unixTimeFilterBefore) {
                    $result[$url] = [UrlListInterface::URL => $url, UrlListInterface::PRIO => 1];
                } else {
                    $result[$url] = [UrlListInterface::URL => $url, UrlListInterface::PRIO => 2];
                }
            }
        }
        unset($categories);

        return $result;
    }

    /**
     * @param array $value
     */
    private function add(array $value)
    {
        try {
            /** @var UrlListInterface|DataObject $urlList */
            $urlList = $this->factory->create();
            $urlList->addData($value);
            $this->saveCommand->execute($urlList);
        } catch (CouldNotSaveException $exception) {

        }
    }

}