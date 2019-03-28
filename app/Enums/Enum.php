<?php

namespace App\Enums;

abstract class Enum {

    /**
     * get the all enums has been declared
     *
     * @return array
     */
    public static function all(): array
    {
        try {
            return (new \ReflectionClass(static::class))->getConstants();
        } catch (\ReflectionException $e) {
            return array();
        }
    }

    /**
     * get the all enum's keys
     *
     * @return array
     */
    public static function keys(): array
    {
        return array_keys(
            static::all()
        );
    }

    /**
     * get the all enum's values
     *
     * @return array
     */
    public static function values(): array
    {
        return array_values(
            static::all()
        );
    }
}
