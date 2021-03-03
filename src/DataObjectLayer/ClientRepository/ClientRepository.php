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

namespace MagmaCore\DataObjectLayer\ClientRepository;

use MagmaCore\DataObjectLayer\ClientRepository\ClientRepositoryInterface;
use MagmaCore\DataObjectLayer\EntityManager\EntityManagerInterface;
use Throwable;

class ClientRepository implements ClientRepositoryInterface
{
    /** @var EntityManagerInterface */
    protected EntityManagerInterface $em;

    /**
     * Main class constructor which requires the entity manager object. This object
     * is passed within the client repository factory.
     *
     * @param EntityManagerInterface $em
     * @return void
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritdoc
     *
     * @param array $fields
     * @param string|null $primaryKey
     * @return boolean
     */
    public function save(array $fields = [], ?string $primaryKey = null): bool
    {
        try {
            if (is_array($fields) && count($fields) > 0) {
                if ($primaryKey != null && is_string($primaryKey)) {
                    return $this->em->getCrud()->update($fields, $primaryKey);
                } elseif ($primaryKey === null) {
                    return $this->em->getCrud()->create($fields);
                }
            }
        } catch (Throwable $throw) {
            throw $throw;
        }
    }

    /**
     * @inheritdoc
     *
     * @param array $condition
     * @return boolean
     */
    public function drop(array $condition): bool
    {
        try {
            if (is_array($condition) && count($condition) > 0) {
                return $this->em->getCrud()->delete($condition);
            }
        } catch (Throwable $throw) {
            throw $throw;
        }
    }



    /**
     * @inheritdoc
     *
     * @param array $conditions
     * @return array
     */
    public function get(array $conditions = []): array
    {
        try {
            if (count($conditions) > 0) {
                return $this->em->getCrud()->read(
                    (isset($conditions['selectors']) ? $conditions['selectors'] : []),
                    (isset($conditions['condition']) ? $conditions['condition'] : []),
                );
            } else {
                return $this->em->getCrud()->read();
            }
        } catch (Throwable $throw) {
            throw $throw;
        }
    }


    public function validate(): void
    {
    }
}
