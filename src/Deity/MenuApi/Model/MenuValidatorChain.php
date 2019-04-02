<?php
declare(strict_types=1);

namespace Deity\MenuApi\Model;

use Deity\MenuApi\Api\Data\MenuInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;

/**
 * Class MenuValidatorChain
 *
 * @package Deity\MenuApi\Model
 */
class MenuValidatorChain implements MenuValidatorInterface
{

    /**
     * @var ValidationResultFactory
     */
    private $validationResultFactory;

    /**
     * @var MenuValidatorInterface[]
     */
    private $validators;

    /**
     * MenuValidatorChain constructor.
     * @param ValidationResultFactory $validationResultFactory
     * @param MenuValidatorInterface[] $validators
     * @throws LocalizedException
     */
    public function __construct(ValidationResultFactory $validationResultFactory, array $validators = [])
    {
        $this->validationResultFactory = $validationResultFactory;

        foreach ($validators as $validator) {
            if (!$validator instanceof MenuValidatorInterface) {
                throw new LocalizedException(
                    __('Menu Validator must implement MenuValidatorInterface.')
                );
            }
        }
        $this->validators = $validators;
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
        foreach ($this->validators as $validator) {
            $validationResult = $validator->validate($menu);

            if (!$validationResult->isValid()) {
                $errors = array_merge($errors, $validationResult->getErrors());
            }
        }

        return $this->validationResultFactory->create(['errors' => $errors]);
    }
}
