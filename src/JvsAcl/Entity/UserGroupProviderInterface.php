<?php

namespace JvsAcl\Entity;

/**
 * Class GroupProviderInterface
 * @package JvsAcl\src\JvsAcl\Entity
 */
interface UserGroupProviderInterface {

    /**
     * @return string
     */
    public function getUserGroup();

}