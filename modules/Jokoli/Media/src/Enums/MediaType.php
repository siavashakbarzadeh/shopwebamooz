<?php

namespace Jokoli\Media\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Image()
 * @method static static Video()
 * @method static static Audio()
 * @method static static Zip()
 * @method static static Doc()
 */
class MediaType extends Enum implements LocalizedEnum
{
    const Image = 0;
    const Video = 1;
    const Audio = 2;
    const Zip = 3;
    const Doc = 4;
}
