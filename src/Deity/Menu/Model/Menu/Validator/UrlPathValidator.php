<?php
declare(strict_types=1);

namespace Deity\Menu\Model\Menu\Validator;

use Deity\MenuApi\Api\Data\MenuInterface;
use Deity\MenuApi\Model\MenuValidatorInterface;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;

/**
 * Class UrlPathValidator
 *
 * @package Deity\Menu\Model\Menu\Validator
 */
class UrlPathValidator implements MenuValidatorInterface
{

    /**
     * @var ValidationResultFactory
     */
    private $validationResultFactory;

    /**
     * UrlPathValidator constructor.
     *
     * @param ValidationResultFactory $validationResultFactory
     */
    public function __construct(ValidationResultFactory $validationResultFactory)
    {
        $this->validationResultFactory = $validationResultFactory;
    }

    /**
     * Validate given menu interface
     *
     * @param MenuInterface $menu
     * @return ValidationResult
     */
    public function validate(MenuInterface $menu): ValidationResult
    {
        $errors = [];
        if ('' === trim($menu->getUrlPath())) {
            $errors[] = __(
                'Menu item for category "%categoryId": "%field" can not be empty.',
                [
                    'categoryId' => $menu->getId(),
                    'field' => MenuInterface::URL_PATH
                ]
            );
        }

        return $this->validationResultFactory->create(['errors' => $errors]);
    }
}
