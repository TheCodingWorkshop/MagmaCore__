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

namespace MagmaCore\DataObjectLayer;

use MagmaCore\DataObjectLayer\DataLayerTrait;

class DataLayerRelationship extends DataLayerCollection
{

    use DataLayerTrait;

    /** @var string */
    protected const IDENTIFIER = 'relationships';
    protected ?Object $model = null;
    protected array $relatables = [];
    protected string $relatedKey;

    /**
     * Undocumented function
     *
     * @param Object|null $model
     * @param array $relatables
     * @param string $relatedKey
     */
    public function __construct(?Object $model = null, array $relatables = [], string $relatedKey)
    {
        $this->model = $model;
        $this->relatables = $relatables;
        $this->relatedKey = $relatedKey;
    }

    /**
     * Undocumented function
     *
     * @param Object|null $relatedTo
     * @param array $condition
     * @param array|null $args
     * @param string $index
     * @return void
     */
    public function createRelationship(
        ?Object $relatedTo = null, 
        ?array $data = null,
        array $condition = [], 
        ?array $args = null, 
        string $index
        )
    { 
        if (is_array($args)) {
            if (in_array($index, $this->relatables)) {
                $item = (isset($data[$index]) ? $data[$index] : '');
                if ($item) {
                    $this->add(new $relatedTo($item), $index);
                    $newItems = (array)$this->get($index);
                    if ($this->count()) {
                        for ($i=0; $i < count($newItems); $i++) {
                            (new DataLayerFacade(self::IDENTIFIER, $relatedTo::TABLESCHEMA, $relatedTo::TABLESCHEMAID))
                                ->getClientRepository()
                                ->validate()
                                ->save(
                                [
                                    $condition,
                                    $this->relatedKey = $newItems[$i]
                                ]
                            );
                        }
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Undocumented function
     *
     * @param Object|null $relatedTo
     * @param array|null $data
     * @param array $condition
     * @param array|null $args
     * @param string $index
     * @return void
     */
    public function updateRelationship(        
        ?Object $relatedTo = null, 
        ?array $data = null,
        array $condition = [], 
        ?array $args = null, 
        string $index
        )
    { 
        if (is_array($args)) {
            if (in_array($index, $this->relatables)) {
                $item = (isset($data[$index]) ? $data[$index] : '');
                if ($item) {
                    $storedCollection = $this->model->getRepo()->findBy();
                    $itemName = array_map('intval', $item);
                    $flat = $this->flattenArrayRecursive($storedCollection);
                    if ($flat != false) {
                        $newItems = array_diff($itemName, $flat);
                    } else {
                        $newItems = $itemName;
                    }
                    if ($newItems) {
                        $this->add(new $relatedTo($newItems), $index);
                        $additionalItems = (array)$this->get($index);
                        if ($this->count()) {
                            foreach ($additionalItems as $additionalItem) {
                                (new DataLayerFacade(self::IDENTIFIER, $relatedTo::TABLESCHEMA, $relatedTo::TABLESCHEMAID))
                                ->getClientRepository()
                                ->validate()
                                ->save(
                                [
                                    $condition,
                                    $this->relatedKey = $additionalItem
                                ]
                            );
                            }
                        }
                    } else {

                    }
                }
            }
        }
    }

    public function getRelationships()
    {
        
    }


}