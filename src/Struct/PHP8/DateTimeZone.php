<?php

namespace Gzhegow\Calendar\Struct\PHP8;

use Gzhegow\Calendar\Exception\LogicException;


class DateTimeZone extends \DateTimeZone implements
    \JsonSerializable
{
    /**
     * @return static
     */
    public static function createFromInstance($object)
    {
        if (is_a($object, static::class)) {
            return clone $object;
        }

        if (! is_a($object, \DateTimeZone::class)) {
            throw new LogicException(
                [
                    'The `object` should be instance of: ' . \DateTimeZone::class,
                    $object,
                ]
            );
        }

        (function ($state) {
            foreach ( $state as $key => $value ) {
                $this->{$key} = $value;
            }
        })->call($tz = new static('UTC'), (array) $object);

        return $tz;
    }


    public function jsonSerialize() : mixed
    {
        // var_dump($tz, $var = json_encode($tz));
        //
        // > string(72) "{"timezone_type":3,"timezone":"UTC"}"
        //
        // vs
        //
        // > string(29) "UTC"

        return $this->getName();
    }
}
