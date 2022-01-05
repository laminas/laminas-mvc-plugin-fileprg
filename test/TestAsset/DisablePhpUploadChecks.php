<?php

declare(strict_types=1);

namespace Laminas\Validator\File;

/**
 * @param null|string|array $filename
 * @return bool
 */
function is_uploaded_file($filename)
{
    return true;
}
