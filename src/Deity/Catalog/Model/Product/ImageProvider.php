<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Product;

use Deity\CatalogApi\Api\ProductImageProviderInterface;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Helper\ImageFactory;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\Area;
use Magento\Store\Model\App\Emulation;

/**
 * Class ImageProvider
 * @package Deity\Catalog\Model\Product
 */
class ImageProvider implements ProductImageProviderInterface
{
    /**
     * @var Emulation
     */
    private $appEmulation;

    /**
     * @var ImageFactory
     */
    private $imageHelperFactory;

    /**
     * ImageProvider constructor.
     * @param Emulation $appEmulation
     * @param ImageFactory $imageHelperFactory
     */
    public function __construct(Emulation $appEmulation, ImageFactory $imageHelperFactory)
    {
        $this->appEmulation = $appEmulation;
        $this->imageHelperFactory = $imageHelperFactory;
    }

    /**
     * @param Product $product
     * @param string $imageId
     * @param string imageFile
     * @return string
     */
    public function getProductImageTypeUrl(Product $product, string $imageId, string $imageFile = ''): string
    {
        try {
            $this->appEmulation->startEnvironmentEmulation($product->getStoreId(), Area::AREA_FRONTEND, true);
            /** @var Image $imageObject */
            $imageObject = $this->imageHelperFactory->create()->init($product, $imageId);
            if ($imageFile != '') {
                $imageObject->setImageFile($imageFile);
            }

            $imageUrl = $imageObject->getUrl();
        } catch (\Exception $e) {
            $imageUrl = '';
        } finally {
            $this->appEmulation->stopEnvironmentEmulation();
        }

        return $imageUrl;
    }
}
