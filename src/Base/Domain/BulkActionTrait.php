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

namespace MagmaCore\Base\Domain;

trait BulkActionTrait
{

    public function resolveBulkAction(object $controller, array $formData, string $schemaID)
    {
        foreach (array_map('intval', $formData[$schemaID]) as $itemID) {
            if ($itemID !==null) {
                $itemObject = $controller->repository
                ->getRepo()
                ->findObjectBy(
                    [$schemaID => $itemID], 
                    $controller->repository->getClonableKeys()
                );
                $itemObjectToArray = $controller->toArray($itemObject);

                /* new clone modified firstname, lastname and email strings */
                $modifiedArray = array_map(
                    fn($item) => $this->resolvedCloning($item),
                    $itemObjectToArray
                );

                $baseArray = $controller->repository
                ->getRepo()
                ->findOneBy([$schemaID => $itemID]);

                /* merge the modifiedArray with the baseArray overriding any key from the baseArray */
                $newCloneArray = array_map(
                    fn($array) => array_merge($array, $modifiedArray), 
                    $baseArray
                );
                $newClone = $this->flattenArray($newCloneArray);
                /* We want the id to auto incremented so we will remove the id key from the array */
                $_newClone = $controller->repository->unsetCloneKeys($newClone);

                /* Now lets imsert the clone data within the database */
                $controller->repository
                ->getRepo()
                ->getEm()
                ->getCrud()
                ->create($_newClone);
                        
            }
        }

    }

    /**
     * Returns a modified clone array modifying the selected elements within the item object
     * which was return by concatinating the a clone string to create a clone but unique item 
     * which will be re-inserted within the database.
     *
     * @param string $value
     * @return string
     */
    private function resolvedCloning(string $value): string
    {
        $suffix = '-clone';
        if (str_contains($value, '@')) { /* check if the argument contains an @ symbol */
            $ex = explode('@', $value); /* explode the argument by the @ symbol */
            if (is_array($ex)) {
                /* safely get the first and last index of the array */
                return $ex[array_key_first($ex)] . $suffix . '-' . $ex[array_key_last($ex)];
            }
        } else {
            return $value . $suffix;
        }
    }


}
