<?php

namespace Hatimeria\Reagento\Helper;

use Magento\Catalog\Model\Category as MagentoCategory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context as AppContext;

class Category extends AbstractHelper
{
    /** @var \Magento\Framework\View\ConfigInterface */
    private $viewConfig;

    /** @var \Magento\Store\Model\StoreManagerInterface */
    private $storeManager;

    /** @var \Magento\Framework\Filesystem */
    private $filesystem;

    /** @var \Magento\Framework\Image\AdapterFactory */
    private $imageFactory;

    /**
     * @param AppContext $context
     * @param \Magento\Framework\Image\AdapterFactory $imageFactory
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\View\ConfigInterface $viewConfig
     */
    public function __construct(AppContext $context,
                                \Magento\Framework\Image\AdapterFactory $imageFactory,
                                \Magento\Framework\Filesystem $filesystem,
                                \Magento\Store\Model\StoreManagerInterface $storeManager,
                                \Magento\Framework\View\ConfigInterface $viewConfig)
    {
        parent::__construct($context);
        $this->viewConfig = $viewConfig;
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
        $this->imageFactory = $imageFactory;
    }

    /**
     * @param MagentoCategory $category
     * @param string $size
     */
    public function addImageAttribute($category, $size = 'category_page_grid')
    {
        $sizeValues = $this->viewConfig->getViewConfig()->getMediaAttributes('Magento_Catalog', 'images', $size);
        $imageName = $category->getData('image');
        if(!$imageName || !$sizeValues) {
            return;
        }

        $height = $sizeValues['height'];
        $width = $sizeValues['width'];
        // TODO try do not use hardcoded paths
        $categorySubPath = 'catalog/category/';

        $absolutePath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath($categorySubPath);
        $resizedImagePath = "cache/{$width}x{$height}/{$imageName}";
        $resizedImage = $absolutePath . $resizedImagePath;

        if(!file_exists($resizedImage)) {
            $imageResize = $this->imageFactory->create();
            $imageResize->open($absolutePath . $imageName);
            $imageResize->constrainOnly(TRUE);
            $imageResize->keepTransparency(TRUE);
            $imageResize->keepFrame(FALSE);
            $imageResize->keepAspectRatio(TRUE);
            $imageResize->resize($width, $height);
            $imageResize->save($resizedImage);
        }

        $url = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
            . $categorySubPath . $resizedImagePath;

        $category->setData('image', $url);
    }
}