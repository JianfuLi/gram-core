<?php
namespace Gram\Core\Object;

use \DateTime;
use \ArrayObject;
use Gram\Core\Object\Mapper\ObjectMapper;
use Gram\Core\Object\Mapper\MapperInterface;
use Gram\Core\Exception\NotImplementedException;

/**
 * Class ObjectTrait
 * @package Gram\Core\Object
 */
trait ObjectTrait
{
    /**
     * @var array
     */
    private static $metadata = [];

    /**
     * @var array
     */
    private static $supportTypes = [
        'boolean',
        'int',
        'float',
        'string',
        'array',
        'ArrayObject',
        'DateTime',
    ];

    /**
     * @return string
     */
    static function dateTimeFormat()
    {
        return 'Y-m-d H:i:s';
    }

    /**
     * @return \ArrayObject
     */
    function jsonSerialize()
    {
        return static::disassemble($this);
    }

    /**
     * @param MapperInterface $m
     *
     * @throws NotImplementedException
     */
    protected static function mapping(MapperInterface $m)
    {
        throw new NotImplementedException();
    }

    /**
     * @return array
     */
    private static function metadata()
    {
        $className = static::class;
        if (!isset(static::$metadata[$className])) {
            $mapper = new ObjectMapper();
            static::mapping($mapper);
            static::$metadata[$className] = $mapper->metadata();
        }
        return static::$metadata[$className];
    }

    /**
     * 从数组组装对象
     *
     * @param array $rows
     *
     * @return static
     */
    static function assemble(array $rows)
    {
        $md = static::metadata();
        $obj = new static;

        foreach ($rows as $key => $value) {
            $m = isset($md[$key]) ? $md[$key] : null;
            if (static::matchIgnoreConditions($obj, $key, $m)) {
                continue;
            }
            if (static::matchDirectAssignment($obj, $key, $m)) {
                $obj->{$key} = $value;
                continue;
            }

            static::castValueIfHasProjection($m, $value, $rows, $key);
            static::castValueTyped($m, $value);
            static::validValue($m, $value);
            $obj->{$key} = $value;
        }
        return $obj;
    }

    /**
     * 将对象转换成数组
     *
     * @param mixed $obj
     * @param array $properties
     *
     * @return array
     */
    static function disassemble($obj, $properties = [])
    {

    }

    /**
     * 验证给定的值是否合法
     *
     * @param array $m
     * @param mixed $value
     */
    static protected function validValue($m, $value)
    {
    }

    /**
     * @param mixed $obj
     * @param string $key
     * @param array $m
     *
     * @return bool
     */
    private static function matchIgnoreConditions($obj, $key, $m)
    {
        if (!property_exists($obj, $key) || (!is_null($m) && $m[ObjectMapper::IGNORE_TAG])) {
            return true;
        }
        return false;
    }

    /**
     * @param mixed $obj
     * @param string $key
     * @param array $m
     *
     * @return bool
     */
    private static function matchDirectAssignment($obj, $key, $m)
    {
        return is_null($m);
    }


    /**
     * 有投影的情况下对给定的值进行转换
     *
     * @param array $m
     * @param mixed $value
     * @param array $rows
     * @param string $key
     */
    private static function castValueIfHasProjection($m, &$value, $rows, $key)
    {
        if (!empty($m[ObjectMapper::PROJECTION_TAG])) {
            list($assembler, $disassembler) = $m[ObjectMapper::PROJECTION_TAG];
            $value = call_user_func($assembler, $rows, $key);
        }
    }

    /**
     * 根据元数据转换类型
     *
     * @param array $m
     * @param mixed $value
     */
    private static function castValueTyped($m, &$value)
    {
        $type = $m[ObjectMapper::TYPE_TAG];
        if (is_object($value)) {
            static::castValueByObject($type, $value);
        } else {
            static::castValueByScalar($type, $value);
        }
    }

    /**
     * 转换对象类型
     *
     * @param string $type
     * @param mixed $value
     */
    private static function castValueByObject($type, &$value)
    {
        $class = get_class($value);
        if (in_array($class, self::$supportTypes)) {
            return;
        } elseif ($class == $type || is_subclass_of($value, $type)) {
            return;
        } else {
            throw new \InvalidArgumentException("错误的类型");
        }
    }

    /**
     * 转换原生类型
     *
     * @param string $type
     * @param mixed $value
     */
    private static function castValueByScalar($type, &$value)
    {
        switch ($type) {
            case 'DateTime':
                if (!is_null($value)) {
                    $value = new DateTime($value);
                }
                break;
            case 'ArrayObject':
                if (!is_array($value)) {
                    $value = [$value];
                }
                $value = new ArrayObject($value, ArrayObject::ARRAY_AS_PROPS);
                break;
            case 'boolean':
                $value = boolval($value);
                break;
            case 'int':
                $value = is_null($value) ? 0 : intval($value);
                break;
            case 'float':
                $value = is_null($value) ? 0.0 : floatval($value);
                break;
            case 'string':
                $value = is_null($value) ? null : strval($value);
                break;
            case 'array':
                $value = is_null($value) ? [] : (is_array($value) ? $value : [$value]);
                break;
            default:
                if (!is_null($value)) {
                    throw new \InvalidArgumentException("错误的类型:" . $type);
                }
        }
    }
}