<?php
namespace BulkGate\Extensions\Hook;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
interface ILoad
{
    /**
     * @param Variables $variables
     * @return void
     */
    public function customer(Variables $variables);

    /**
     * @param Variables $variables
     * @return void
     */
    public function order(Variables $variables);

    /**
     * @param Variables $variables
     * @return void
     */
    public function orderStatus(Variables $variables);

    /**
     * @param Variables $variables
     * @return void
     */
    public function returnOrder(Variables $variables);

    /**
     * @param Variables $variables
     * @return void
     */
    public function shop(Variables $variables);

    /**
     * @param Variables $variables
     * @return void
     */
    public function load(Variables $variables);
}