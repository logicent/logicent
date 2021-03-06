<?php

use crudle\setup\enums\Type_Role;
use crudle\_sample\enums\Type_Menu_Group;

$this->params['menuGroupClass'] = Type_Menu_Group::class;

return [
    [
        'route' => '/_sample',
        'label' => 'Sample',
        // 'group' => Type_Menu_Group::Content,
        'visible' => Yii::$app->user->can(Type_Role::SystemManager),
    ],
];
?>