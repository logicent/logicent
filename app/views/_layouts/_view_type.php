<?php

use app\enums\Type_View;
use yii\helpers\Html;
use yii\helpers\Inflector;
use Zelenin\yii\SemanticUI\Elements;

?>
<!-- inverted brown -->
<div class="compact ui inline basic small dropdown button" style="padding-right: 8.75px;">
    <div style="font-weight: normal;" class="text"><?= Yii::t('app', 'List View') ?></div>
    &ensp;<i class="sort icon"></i>
    <div class="menu">
    <?php
        foreach (Type_View::enums() as $viewType) :
            if (!in_array($this->context->action->id, $viewType['action']))
                continue;
            echo Html::a(Elements::icon($viewType['icon']) . Yii::t('app', '{viewLabel}', ['viewLabel' => $viewType['label']]),
                        [$viewType['link']],
                        [
                            'class' => 'item',
                            'data' => ['text' => $viewType['label'] .' '. Yii::t('app', 'View')]
                        ]);
        endforeach;
    ?>
    </div><!-- ./menu -->
</div>
<?php
$this->registerJs(<<<JS

$('.show-view').on('click', function(e) 
{
    e.preventDefault();

    $.ajax({
        url: $(this).attr('href'),
        type: 'get',
        data: {
        },
        success: function( response )
        {

        },
        error: function( jqXhr, textStatus, errorThrown )
        {
            console.log( errorThrown );
        }
    });
});
JS) ?>