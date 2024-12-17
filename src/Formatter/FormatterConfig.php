<?php

namespace Gzhegow\Calendar\Formatter;

use Gzhegow\Lib\Config\Config;


/**
 * @property bool $useIntl
 */
class FormatterConfig extends Config
{
    protected $useIntl = true;


    public function validate()
    {
        $this->useIntl = (bool) $this->useIntl;
    }
}
