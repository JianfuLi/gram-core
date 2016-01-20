<?php
namespace Gram\Object\Mapper;

/**
 * Class ObjectMapper
 * @package Gram\Object\Mapper
 */
class ObjectMapper implements MapperInterface
{
    /**
     * 忽略标记
     */
    const IGNORE_TAG = 'ignore';
    /**
     * 规则标记
     */
    const RULES_TAG = 'rules';
    /**
     * 投影标记
     */
    const PROJECTION_TAG = 'projection';
    /**
     * 类型标记
     */
    const TYPE_TAG = 'type';


    /**
     * @var array
     */
    private $metadata = [];

    /**
     * @var array
     */
    private $properties = [];

    /**
     * 映射属性
     *
     * @param string[] ...$properties
     *
     * @return self
     */
    function map(...$properties)
    {
        $this->properties = $properties;
        foreach ($this->properties as $property) {
            if (empty($this->metadata[$property])) {
                $this->metadata[$property] = [
                    self::PROJECTION_TAG => [],
                    self::IGNORE_TAG     => false,
                    self::RULES_TAG      => [],
                    self::TYPE_TAG       => null,
                ];
            }
        }
        return $this;
    }

    /**
     * 设置属性类型
     *
     * @param string $type
     */
    private function type($type)
    {
        foreach ($this->properties as $property) {
            $this->metadata[$property][self::TYPE_TAG] = $type;
        }
    }

    /**
     * 设置验证规则
     *
     * @param string $key
     * @param mixed $value
     */
    private function rule($key, $value = null)
    {
        foreach ($this->properties as $property) {
            $this->metadata[$property][self::RULES_TAG][$key] = $value;
        }
    }


    /**
     * 获取配置元数据
     *
     * @return array
     */
    function metadata()
    {
        return $this->metadata;
    }

    function projection(\Closure $assembler, \Closure $disassembler)
    {
        foreach ($this->properties as $property) {
            $this->metadata[$property][self::PROJECTION_TAG] = [$assembler, $disassembler];
        }
        return $this;
    }

    function instance($className)
    {
        $this->type($className);
        return $this;
    }

    function ignore()
    {
        foreach ($this->properties as $property) {
            $this->metadata[$property][self::IGNORE_TAG] = true;
        }
        return $this;
    }

    function arr()
    {
        $this->instance('array');
        return $this;
    }

    function bool()
    {
        $this->type('boolean');
        return $this;
    }

    function string()
    {
        $this->type('string');
        return $this;
    }

    function dateTime()
    {
        $this->type('DateTime');
        return $this;
    }

    function float()
    {
        $this->type('float');
        return $this;
    }

    function double()
    {
        $this->type('float');
        return $this;
    }


    function int()
    {
        $this->type('int');
        return $this;
    }

    function notEmpty()
    {
        $this->rule(__FUNCTION__);
        return $this;
    }

    function between($min, $max)
    {
        $this->rule(__FUNCTION__, func_get_args());
        return $this;
    }

    function email()
    {
        $this->rule(__FUNCTION__);
        return $this;
    }

    function startsWith($value)
    {
        $this->rule(__FUNCTION__, $value);
        return $this;
    }

    function endsWith($value)
    {
        $this->rule(__FUNCTION__, $value);
        return $this;
    }

    function equals($value)
    {
        $this->rule(__FUNCTION__, $value);
        return $this;
    }

    function notEquals($value)
    {
        $this->rule(__FUNCTION__, $value);
        return $this;
    }

    function file()
    {
        $this->rule(__FUNCTION__);
        return $this;
    }

    function url()
    {
        $this->rule(__FUNCTION__);
        return $this;
    }

    function in(...$params)
    {
        if (count($params) === 1 && is_array($params[0])) {
            $params = $params[0];
        }
        $this->rule(__FUNCTION__, $params);
        return $this;
    }

    function notIn(...$params)
    {
        if (count($params) === 1 && is_array($params[0])) {
            $params = $params[0];
        }
        $this->rule(__FUNCTION__, $params);
        return $this;
    }

    function length($min, $max)
    {
        $this->rule(__FUNCTION__, func_get_args());
        return $this;
    }

    function max($max)
    {
        $this->rule(__FUNCTION__, $max);
        return $this;
    }

    function min($min)
    {
        $this->rule(__FUNCTION__, $min);
        return $this;
    }

    function regex($pattern)
    {
        $this->rule(__FUNCTION__, $pattern);
        return $this;
    }
}