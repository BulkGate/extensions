<?php
namespace BulkGate\Extensions\Database;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
interface IDatabase
{
    /**
     * @param string $sql
     * @return Result
     */
    public function execute($sql);

    /**
     * @return mixed
     */
    public function lastId();

    /**
     * @param string $string
     * @return string
     */
    public function escape($string);

    /**
     * @return string
     */
    public function prefix();

    /**
     * @return array
     */
    public function getSqlList();
}