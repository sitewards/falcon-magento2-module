<?php
declare(strict_types=1);

namespace Deity\UrlRewrite\Model;

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
        $commandsPerEntityType = []
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

        $this->commandsPerEntityType = $commandsPerEntityType;
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
        /**
         * @TODO provide relevant canonical URL that can be already used on the page
         */
        $urlData->setCanonicalUrl($urlModel->getTargetPath());

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
