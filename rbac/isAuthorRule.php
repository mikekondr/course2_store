<?php

namespace app\rbac;

use yii\rbac\Rule;

class isAuthorRule extends Rule
{
    public $name = 'isAuthor';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params) : bool
    {
        return isset($params['order']) ? $params['order']['created_by'] == $user : false;
    }
}