<?php

declare(strict_types=1);

namespace Laminas\Filter\File;

use function rename;

/**
 * @param string $source
 * @param string $dest
 * @return bool
 */
function move_uploaded_file($source, $dest)
{
    return rename($source, $dest);
}
