<?php
declare(strict_types=1);

namespace Deity\MenuApi\Model;

use Deity\MenuApi\Api\Data\MenuInterface;
use Magento\Framework\Validation\ValidationResult;

/**
 * Interface MenuValidatorInterface
 *
 * @package Deity\MenuApi\Model
 */
interface MenuValidatorInterface
{
    /**
     * Validate given menu interface
     *
     * @param MenuInterface $menu
     * @return ValidationResult
     */
    public function validate(MenuInterface $menu): ValidationResult;
}
