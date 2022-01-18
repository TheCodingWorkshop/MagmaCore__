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

namespace MagmaCore\Base\Domain\Actions;

use MagmaCore\Base\Domain\DomainActionLogicInterface;
use MagmaCore\Base\Domain\DomainTraits;

/**
 * Class which handles the domain logic when adding a new item to the database
 * items are sanitize and validated before persisting to database. The class will
 * also dispatched any validation error before persistence. The logic also implements
 * event dispatching which provide usable data for event listeners to perform other
 * necessary tasks and message flashing
 */
class SearchAction implements DomainActionLogicInterface
{

    use DomainTraits;

    /** @var bool */
    protected bool $isRestFul = false;

    /**
     * execute logic for adding new items to the database(). Post data is returned as a collection
     *
     * @param Object $controller - The controller object implementing this object
     * @param string|null $entityObject
     * @param string|null $eventDispatcher - the eventDispatcher for the current object
     * @param string|null $objectSchema
     * @param string $method - the name of the method within the current controller object
     * @param array $rules
     * @param array $additionalContext - additional data which can be passed to the event dispatcher
     * @return NewAction
     */
    public function execute(
        object $controller,
        ?string $entityObject,
        ?string $eventDispatcher,
        ?string $objectSchema,
        string $method,
        array $rules = [],
        array $additionalContext = [],
        mixed $optional = null
    ): self {

        $this->controller = $controller;
        $this->method = $method;
        $this->schema = $objectSchema;
        $formBuilder = $controller->formBuilder;

        if (isset($formBuilder) && $formBuilder->isFormValid($this->getSubmitValue())) :
            $formData = ($this->isRestFul === true) ? $controller->formBuilder->getJson() : $controller->formBuilder->getData();
            unset($formData[$this->getSubmitValue()]);
            if ($formData) {
                $this->dispatchSingleActionEvent(
                    $controller,
                    $eventDispatcher,
                    $method,
                    $formData,
                    $additionalContext
                );
            }
        endif;
        return $this;
    }

//    function searchAllDB($search){
//        global $mysqli;
//
//        $out = Array();
//
//        $sql = "show tables";
//        $rs = $mysqli->query($sql);
//        if($rs->num_rows > 0){
//            while($r = $rs->fetch_array()){
//                $table = $r[0];
//                $sql_search = "select * from `".$table."` where ";
//                $sql_search_fields = Array();
//                $sql2 = "SHOW COLUMNS FROM `".$table."`";
//                $rs2 = $mysqli->query($sql2);
//                if($rs2->num_rows > 0){
//                    while($r2 = $rs2->fetch_array()){
//                        $column = $r2[0];
//                        $sql_search_fields[] = "`".$column."` like('%".$mysqli->real_escape_string($search)."%')";
//                    }
//                    $rs2->close();
//                }
//                $sql_search .= implode(" OR ", $sql_search_fields);
//                $rs3 = $mysqli->query($sql_search);
//                $out[$table] = $rs3->num_rows."\n";
//                if($rs3->num_rows > 0){
//                    $rs3->close();
//                }
//            }
//            $rs->close();
//        }
//
//        return $out;
//    }
}


