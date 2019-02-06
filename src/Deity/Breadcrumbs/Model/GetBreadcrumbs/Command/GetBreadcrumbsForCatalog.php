<?php
declare(strict_types=1);

namespace Deity\Breadcrumbs\Model\GetBreadcrumbs\Command;

use Deity\BreadcrumbsApi\Api\Data\BreadcrumbInterface;
use Deity\BreadcrumbsApi\Api\Data\BreadcrumbInterfaceFactory;
use Deity\BreadcrumbsApi\Api\UrlRewriteBreadcrumbResolverInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class GetBreadcrumbsForCatalog
 *
 * @package Deity\Breadcrumbs\Model\GetBreadcrumbs\Command
 */
class GetBreadcrumbsForCatalog implements UrlRewriteBreadcrumbResolverInterface
{

    /**
     * @var UrlFinderInterface
     */
    private $urlFinder;

    /**
     * @var BreadcrumbInterfaceFactory
     */
    private $breadcrumbFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * GetBreadcrumbsForCatalog constructor.
     * @param UrlFinderInterface $urlFinder
     * @param BreadcrumbInterfaceFactory $breadcrumbFactory
     * @param StoreManagerInterface $storeManager
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        UrlFinderInterface $urlFinder,
        BreadcrumbInterfaceFactory $breadcrumbFactory,
        StoreManagerInterface $storeManager,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->urlFinder = $urlFinder;
        $this->breadcrumbFactory = $breadcrumbFactory;
        $this->storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get breadcrumbs for product
     *
     * @param UrlRewrite $urlRewrite
     * @return \Deity\BreadcrumbsApi\Api\Data\BreadcrumbInterface[]
     * @throws NoSuchEntityException
     */
    private function getBreadcrumbsForProduct(UrlRewrite $urlRewrite): array
    {
        $metaData = $urlRewrite->getMetadata();

        $categoryId = $metaData['category_id'] ?? 0;

        if ($categoryId == 0) {
            return [];
        }

        return $this->getBreadcrumbsForCategory((int)$categoryId, false);
    }

    /**
     * Get breadcrumbs for category
     *
     * @param int $leafCategoryId
     * @param bool $excludeLeafCategory
     * @return \Deity\BreadcrumbsApi\Api\Data\BreadcrumbInterface[]
     * @throws NoSuchEntityException
     */
    private function getBreadcrumbsForCategory(int $leafCategoryId, $excludeLeafCategory = true): array
    {
        $leafCategoryObject = $this->categoryRepository->get($leafCategoryId);
        if ($leafCategoryObject->getLevel() <= 2 && $excludeLeafCategory === true) {
            //1 level category
            return [];
        }

        // Omit root categories
        $categoryIds = array_slice(explode('/', $leafCategoryObject->getPath()), 2);
        // Drop the leaf category
        array_pop($categoryIds);

        $result = [];
        foreach ($categoryIds as $currentCategoryId) {
            $categoryObject = $this->categoryRepository->get($currentCategoryId);
            $categoryPath = $this->getPathByCategoryId((int)$currentCategoryId);

            $result[] = $this->breadcrumbFactory->create(
                [
                    BreadcrumbInterface::NAME => $categoryObject->getName(),
                    BreadcrumbInterface::URL_PATH => $categoryPath
                ]
            );
        }

        if ($excludeLeafCategory === false) {
            $result[] = $this->breadcrumbFactory->create(
                [
                    BreadcrumbInterface::NAME => $leafCategoryObject->getName(),
                    BreadcrumbInterface::URL_PATH => $this->getPathByCategoryId((int)$leafCategoryId)
                ]
            );
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getBreadcrumbsByUrlRewrite(UrlRewrite $urlRewrite): array
    {

        if ($urlRewrite->getEntityType() == CategoryUrlRewriteGenerator::ENTITY_TYPE) {
            return $this->getBreadcrumbsForCategory((int)$urlRewrite->getEntityId());
        }

        return $this->getBreadcrumbsForProduct($urlRewrite);
    }

    /**
     * Get Path By Category Id
     *
     * @param int $categoryId
     * @return string
     * @throws NoSuchEntityException
     */
    private function getPathByCategoryId(int $categoryId): string
    {
        $urlModel = $this->urlFinder->findOneByData(
            [
                UrlRewrite::ENTITY_TYPE => CategoryUrlRewriteGenerator::ENTITY_TYPE,
                UrlRewrite::ENTITY_ID => $categoryId,
                UrlRewrite::STORE_ID  => $this->storeManager->getStore()->getId()
            ]
        );

        if (!$urlModel) {
            throw new NoSuchEntityException(__('Requested url doesn\'t exist'));
        }

        return $urlModel->getRequestPath();
    }
}
