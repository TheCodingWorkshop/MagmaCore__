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

namespace MagmaCore\DataSchema;

interface DataSchemaInterface
{

    /**
     * The schema is the first method within the chain and allow overriding of the 
     * default schema set. Schema pass in will ve validated against the framework
     * define schema options and will fail validation if incorrect item in pass in
     *
     * @param array $overridingSchema
     * @return static
     */
    public function schema(array $overridingSchema = []): static;

    /**
     * The table method is the second method within the chain and takes the the 
     * object model as its only argument. From this argument we can fetch information
     * about the database object. ie database name and primary key etc...
     *
     * @param object $dataModel
     * @return static
     */
    public function table(object $dataModel): static;

    /**
     * Row is the third method called within the chain and its simple allows us to add
     * multiple rows in order to build model table accordingly. This takes in a single 
     * argument which is an array of dataSchema types. It will create an object based on the 
     * supplied array argument where the key of the array is the fully qualified namespace
     * of the schema type 
     * 
     * @param mixed $args
     * @return static
     */
    public function row(mixed $args = null): static;

    /**
     * The build method is the last method on the main chain. This renders the values from
     * the methods higher up within the chain. This method creates the SQL wrapper for all the 
     * schema type passed into the row() method and uses the schema object created earlier in the 
     * chain to call the default render method from the base schema object.
     * 
     * The build method takes a single Callable $callback argument which allows us to build 
     * an inner chain of callable method which is used to construct the finer details of the 
     * SQL query ie. primary key, unique key and any constraints being added
     *
     * @param Callable $callback
     * @return string
     */
    public function build(callable $callback): string;


}