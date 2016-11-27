<?php
/**
 * The Utility file.
 */
namespace sgk\back;

/**
 * Class Utility.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class Utility
{
    /**
     * @param $value
     *
     * @throws \Exception
     */
    public static function assertIsInt($value)
    {
        if (is_int($value)) {
            return;
        } else {
            throw new \Exception('$value is not an integer.');
        }
    }

    /**
     * @param $value
     *
     * @throws \Exception
     */
    public static function assertIsString($value)
    {
        if (is_string($value)) {
            return;
        } else {
            throw new \Exception('$value is not a string.');
        }
    }
}
