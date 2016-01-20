<?php
namespace Gram\Object\Mapper;

/**
 * Interface MapperInterface
 * @package Gram\Object\Mapper
 */
interface MapperInterface
{
    /**
     * 映射属性
     *
     * @param string[] ...$properties
     *
     * @return MapperInterface
     */
    function map(...$properties);

    /**
     * 获取配置元数据
     *
     * @return \ArrayObject
     */
    function metadata();

    /**
     * 设置属性的投影转换函数
     *
     * @param \Closure $assembler
     * @param \Closure $disassembler
     *
     * @return MapperInterface
     */
    function projection(\Closure $assembler, \Closure $disassembler);

    /**
     * 设置属性类型的完整类名
     *
     * @param string $className
     *
     * @return MapperInterface
     */
    function instance($className);

    /**
     * 设置为忽略的属性
     *
     * @return MapperInterface
     */
    function ignore();

    /**
     * 设置属性类型为数组类型
     *
     * @return MapperInterface
     */
    function arr();

    /**
     * 设置属性类型为布尔值类型
     *
     * @return MapperInterface
     */
    function bool();

    /**
     * 设置属性类型为字符串类型
     *
     * @return MapperInterface
     */
    function string();

    /**
     * 设置属性类型为时间类型
     *
     * @return MapperInterface
     */
    function dateTime();

    /**
     * 设置属性类型为浮点型
     *
     * @return MapperInterface
     */
    function float();

    /**
     * 设置属性类型为双精度类型
     *
     * @return MapperInterface
     */
    function double();

    /**
     * 设置属性类型为整型
     *
     * @return MapperInterface
     */
    function int();

    /**
     * 设置属性值不能为空
     *
     * @return MapperInterface
     */
    function notEmpty();

    /**
     * 设置属性值在一个区间
     *
     * @param int $min
     * @param int $max
     *
     * @return MapperInterface
     */
    function between($min, $max);

    /**
     * 设置属性值格式为email
     *
     * @return MapperInterface
     */
    function email();

    /**
     * 设置属性值以给定的字符串开始
     *
     * @param string $value
     *
     * @return MapperInterface
     */
    function startsWith($value);

    /**
     * 设置属性值以给定的字符串结束
     *
     * @param string $value
     *
     * @return MapperInterface
     */
    function endsWith($value);

    /**
     * 设置属性值等于给定的值
     *
     * @param $value
     *
     * @return MapperInterface
     */
    function equals($value);

    /**
     * 设置属性值不等于给定的值
     *
     * @param $value
     *
     * @return MapperInterface
     */
    function notEquals($value);

    /**
     * 设置属性值格式为文件
     *
     * @return MapperInterface
     */
    function file();

    /**
     * 设置属性值格式为url
     *
     * @return MapperInterface
     */
    function url();

    /**
     * 设置属性值在给定的值里
     *
     * @param array ...$params
     *
     * @return MapperInterface
     */
    function in(...$params);

    /**
     * 设置属性值不在给定的值里
     *
     * @param array ...$params
     *
     * @return MapperInterface
     */
    function notIn(...$params);

    /**
     * 设置属性值长度在给定的区间里
     *
     * @param $min
     * @param $max
     *
     * @return MapperInterface
     */
    function length($min, $max);

    /**
     * 设置属性值最大值为给定的值
     *
     * @param $max
     *
     * @return MapperInterface
     */
    function max($max);

    /**
     * 设置属性值最小值为给定的值
     *
     * @param $min
     *
     * @return MapperInterface
     */
    function min($min);

    /**
     * 设置属性值所符合的正则表达式
     *
     * @param $pattern
     *
     * @return MapperInterface
     */
    function regex($pattern);
}