<?php
declare(strict_types=1);

namespace Deity\UrlRewrite\Model;

use Deity\UrlRewriteApi\Api\CanonicalUrlProviderInterface;
use Deity\UrlRewriteApi\Api\ConvertEntityIdToUniqueKeyInterface;
use Deity\UrlRewriteApi\Api\Data\UrlRewriteInterface;
use Deity\UrlRewriteApi\Api\Data\UrlRewriteInterfaceFactory;
use Deity\UrlRewriteApi\Api\GetUrlRewriteInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * @package Deity\UrlRewrite\Model
 */
class GetUrlRewrite implements GetUrlRewriteInterface
{
    /**
     * @var UrlFinderInterface
     */
    private $urlFinder;

    /**
     * @var UrlRewriteInterfaceFactory
     */
    private $urlRewriteFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ConvertEntityIdToUniqueKeyInterface[]
     */
    private $commandsPerEntityType;

    /**
     * @var array
     */
    private $canonicalUrlProviders;

    /**
     * Url constructor.
     *
     * @param UrlFinderInterface $urlFinder
     * @param UrlRewriteInterfaceFactory $urlRewriteFactory
     * @param StoreManagerInterface $storeManager
     * @param array $commandsPerEntityType
     * @throws LocalizedException
     */
    public function __construct(
        UrlFinderInterface $urlFinder,
        UrlRewriteInterfaceFactory $urlRewriteFactory,
        StoreManagerInterface $storeManager,
        $commandsPerEntityType = [],
        $canonicalUrlProviders = []
    ) {
        $this->urlFinder = $urlFinder;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->storeManager = $storeManager;

        foreach ($commandsPerEntityType as $command) {
            if (!$command instanceof ConvertEntityIdToUniqueKeyInterface) {
                throw new LocalizedException(
                    __(
                        'Convert Entity class must implement %interface.',
                        ['interface' => ConvertEntityIdToUniqueKeyInterface::class]
                    )
                );
            }
        }

        foreach ($canonicalUrlProviders as $urlProvider) {
            if (!$urlProvider instanceof CanonicalUrlProviderInterface) {
                throw new LocalizedException(
                    __(
                        'Canonical URL provider class must implement %interface.',
                        ['interface' => CanonicalUrlProviderInterface::class]
                    )
                );
            }
        }

        $this->commandsPerEntityType = $commandsPerEntityType;
        $this->canonicalUrlProviders = $canonicalUrlProviders;
    }

    /**
     * @param string $url
     * @return UrlRewriteInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(string $url): UrlRewriteInterface
    {
        $urlModel = $this->getUrlModel($url);

        /**
         * @var UrlRewriteInterface $urlData
         */
        $urlData = $this->urlRewriteFactory->create();
        $urlData->setEntityType($this->sanitizeType($urlModel->getEntityType()));
        $urlData->setEntityId((string)$urlModel->getEntityId());

        //Set canonical URL
        if (array_key_exists($urlModel->getEntityType(), $this->canonicalUrlProviders)) {
            $urlData->setCanonicalUrl(
                $this->canonicalUrlProviders[$urlModel->getEntityType()]->getCanonicalUrl($urlModel)
            );
        } else {
            //Use default if entity type is not specified in canonicalUrlProviders di argument.
            $urlData->setCanonicalUrl($this->canonicalUrlProviders['base']->getCanonicalUrl($urlModel));
        }

        if (isset($this->commandsPerEntityType[$urlModel->getEntityType()])) {
            $this->commandsPerEntityType[$urlModel->getEntityType()]->execute($urlData);
        }

        return $urlData;
    }

    /**
     * @param $path
     * @return UrlRewrite
     * @throws NoSuchEntityException
     */
    private function getUrlModel($path)
    {
        $urlModel = $this->urlFinder->findOneByData(
            [
                'request_path' => $path,
                'store_id'  => $this->storeManager->getStore()->getId()
            ]
        );

        if (!$urlModel) {
            throw new NoSuchEntityException(__('Requested url doesn\'t exist'));
        }

        return $urlModel;
    }

    /**
     * Sanitize the type to fit schema specifications
     *
     * @param  string $type
     * @return string
     */
    private function sanitizeType(string $type) : string
    {
        return strtoupper(str_replace('-', '_', $type));
    }
}
