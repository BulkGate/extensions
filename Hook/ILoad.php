<?php
namespace BulkGate\Extensions\Hook;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
interface ILoad
{
    public function customer(Variables $variables);

    public function order(Variables $variables);

    public function orderStatus(Variables $variables);

    public function returnOrder(Variables $variables);

    public function shop(Variables $variables);

    public function load(Variables $variables);
}