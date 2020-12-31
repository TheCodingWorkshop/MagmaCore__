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

namespace MagmaCore\Auth\Model;

use MagmaCore\Auth\RememberedLoginInterface;
use MagmaCore\Base\AbstractBaseModel;
use MagmaCore\Utility\Token;
use Throwable;

class RememberedLoginModel extends AbstractBaseModel implements RememberedLoginInterface
{ 

    /** @var string */
    protected const TABLESCHEMA = 'remembered_logins';
    /** @var string */
    protected const TABLESCHEMAID = 'id';

    /**
     * Main constructor class which passes the relevant information to the 
     * base model parent constructor. This allows the repsitory to fetch the
     * correct information from the database based on the model/entity
     * 
     * @throws BaseInvalidArgumentException
     * @return void
     */
    public function __construct()
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID);
    }

    /**
     * Guard these IDs from being deleted etc..
     *
     * @return array
     */
    public function guardedID() : array
    {
        return [];
    }

    /**
     * @inheritdoc
     *
     * @param string $token
     * @return void
     */
    public function findByToken(string $token) : Object
    { 
        try {
            $token = new Token($token);
            $tokenHash = $token->getHash();
            $tokenUser = $this->getRepo()->findObjectBy(['token_hash' => $tokenHash], []);
            if ($tokenUser !=null) {
                return $tokenUser;
            }
        }catch(Throwable $th) {
            throw $th;
        }
    }

    /**
     * @inheritdoc
     *
     * @param string $expires
     * @return boolean True if the token has expired, false otherwise
     */
    public function hasExpired(string $expires) : bool
    { 
        if (!empty($expires)) {
            return strtotime($expires) < time();
        }
    }

    /**
     * @inheritdoc
     *
     * @param string $tokenHash
     * @return boolean
     */
    public function destroy(string $tokenHash) : bool
    { 
        try {
            $destroy = $this
            ->getRepo()
            ->getEm() /* Access entity manager object */
            ->getCrud()
            ->delete(['token_hash' => $tokenHash]);
            if ($destroy) {
                return $destroy;
            }
            return false;
        } catch(Throwable $th) {
            throw $th;
        }
    }

    /**
     * @inheritdoc
     *
     * @param integer $userID
     * @return void
     */
    public function getUser(int $userID) : Object
    { 
        if (!empty($userID)) {
            return $this->getRepo()->findObjectBy([], ['id' => $userID]);
        }
    }

    /**
     * @inheritdoc
     *
     * @param int $userID - the ID of the user to remember
     * @return array True if the login was remembered successfully, false otherwise
     * @throws Exception
     */
    public function rememberedLogin(int $userID) : array
    { 
        $token = new Token();
        $tokenHash = $token->getHash();
        $tokenValue = $token->getValue();
        $timestampExpiry = time() + 60 * 60 * 24 * 30; // 30 days from now

        $fields = [
            'token_hash' => $tokenHash,
            'expires_at' => date('Y-m-d H:i:s', $timestampExpiry),
            'id' => $userID
        ];

        $persisted = $this
        ->getRepo()
        ->getEm()
        ->getCrud()
        ->create($fields);
        if ($persisted) {
            return [
                $tokenValue,
                $timestampExpiry
            ];
        }
    }

}
