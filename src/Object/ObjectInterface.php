<?php
namespace Gram\Core\Object;

/**
 * Interface EntityInterface
 * @package Gram\Core\Object
 */
interface ObjectInterface extends \JsonSerializable
{
    /**
     * @return null
     */
    static function mapping();

    /**
     * @param array $arr
     *
     * @return ObjectInterface
     */
    static function assemble(array $arr);

    /**
     * @param ObjectInterface $obj
     * @param array           $properties
     *
     * @return mixed
     */
    static function disassemble($obj, $properties = []);
}