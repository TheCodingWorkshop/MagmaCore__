<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MagmaCore\Queuing;

use MagmaCore\Queuing\QueuingInteface;

class Queuing implements QueuingInteface
{

    /** @var array - the que data */
    protected array $queue;
    /** @var int - counter for item ids */
    protected int $id;

    /**
     * Class constructor which starts working with the que
     * 
     * @return void
     */
    public function __construct()
    {
        $this->queue = array();
        $this->id = 0;
    }

    /**
     * Add a item and stored it directly within the queue
     *
     * @param array $data
     * @return void
     */
    public function create(array $data) : void
    {
        $item = new \StdClass();
        $item->itemID = $this->id++;
        $item->data = $data;
        $item->created = time();
        $item->expires = 0;
        $this->queue[$item->itemID] = $item;

    }

    /**
     * Returns the number of items within the queue
     *
     * @return int
     */
    public function quantity()
    {
        return count($this->queue);
    }

    /**
     * Claim an item in the queue for processing for a specific time.
     *
     * @param integer $leaseTime
     * @return mixed - returns an object or boolean
     */
    public function claim(int $leaseTime = 3600)
    {
        foreach ($this->queue as $key => $item) {
            if ($item->expire == 0) {
                $item->expire = time() + $leaseTime;
                $this->queue[$key] = $item;
                return $item;
            }
        }
        return false;
    }

    /**
     * Delete a finish item from the queue
     *
     * @param Object $item
     * @return void
     */
    public function delete(Object $item) : void
    {
        unset($this->queue[$item->itemID]);
    }

    /**
     * Release an item that the worker could not process, so another worker
     * can come in and process it before the timeout expires
     *
     * @param Object $item
     * @return boolean
     */
    public function release(Object $item) : bool
    {
        if (isset($this->queue[$item->itemID]) && $this->queue[$item->itemID]->expire !=0) {
            $this->queue[$item->itemID]->expire = 0;
            return true;
        }
        return false;
    }

    /**
     * Create a que
     *
     * @return void
     */
    public function createQue()
    {

    }

    /**
     * Delete a que
     *
     * @return void
     */
    public function deleteQue()
    {
        $this->queue = [];
        $this->id = 0;
    }

}