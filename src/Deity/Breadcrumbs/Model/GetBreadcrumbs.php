<?php
declare(strict_types=1);

namespace Deity\Breadcrumbs\Model;

use Deity\BreadcrumbsApi\Api\GetBreadcrumbsInterface;
use Deity\BreadcrumbsApi\Api\UrlRewriteBreadcrumbResolverInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class GetBreadcrumbs
 *
 * @package Deity\Breadcrumbs\Model
 */
class GetBreadcrumbs implements GetBreadcrumbsInterface
{

    /**
     * @var UrlFinderInterface
     */
    private $urlFinder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var UrlRewriteBreadcrumbResolverInterface[];
     */
    private $urlRewriteResolvers;

    /**
     * GetBreadcrumbs constructor.
     * @param UrlFinderInterface $urlFinder
     * @param StoreManagerInterface $storeManager
     * @param array $urlRewriteResolvers
     * @throws InputException
     */
    public function __construct(
        UrlFinderInterface $urlFinder,
        StoreManagerInterface $storeManager,
        array $urlRewriteResolvers = []
    ) {
        $this->urlFinder = $urlFinder;
        $this->storeManager = $storeManager;

        foreach ($urlRewriteResolvers as $type => $resolver) {
            if (!$resolver instanceof UrlRewriteBreadcrumbResolverInterface) {
                throw new InputException('Url resolver must implement UrlRewriteBreadcrumbResolverInterface');
            }
            $this->urlRewriteResolvers[$type] = $resolver;
        }
    }

    /**
     * Get Breadcrumbs by Url Model
     *
     * @param UrlRewrite $urlModel
     * @return \Deity\BreadcrumbsApi\Api\Data\BreadcrumbInterface[]
     * @throws InputException
     */
    private function getBreadcrumbsByUrlModel(UrlRewrite $urlModel): array
    {
        if (!isset($this->urlRewriteResolvers[$urlModel->getEntityType()])) {
            throw new InputException('Given url type: %1 is not supported', $urlModel->getEntityType());
        }

        return $this->urlRewriteResolvers[$urlModel->getEntityType()]->getBreadcrumbsByUrlRewrite($urlModel);
    }

    /**
     * @inheritdoc
     */
    public function execute(string $url): array
    {
        return $this->getBreadcrumbsByUrlModel($this->getUrlModel($url));
    }

    /**
     * Get url model
     *
     * @param string $path
     * @return UrlRewrite
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getUrlModel(string $path)
    {
        $urlModel = $this->urlFinder->findOneByData(
            [
                UrlRewrite::REQUEST_PATH => $path,
                UrlRewrite::STORE_ID  => $this->storeManager->getStore()->getId()
            ]
        );

        if (!$urlModel) {
            throw new NoSuchEntityException(__('Requested url doesn\'t exist'));
        }

        return $urlModel;
    }
}
