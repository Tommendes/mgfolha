<?php

/**
 * Description of StatsTileWidget
 *
 * @author TomMe
 */

namespace common\components;

use rmrevin\yii\fontawesome\component\Icon;
use yii\base\Widget;
use yii\helpers\Html;

class StatsTile extends Widget {

    public $options = ['class' => 'tile-stats'];
    public $icon;
    public $header;
    public $headerOptions = [];
    public $text;
    public $number;

    public function run() {
        echo Html::beginTag('div', $this->options);
        if (empty($this->icon) === false) {
            echo Html::tag('div', new Icon($this->icon), ['class' => 'icon']);
        }
        echo Html::tag('div', $this->number, ['class' => 'count']);
        echo Html::tag('h3', $this->header, $this->headerOptions);
        echo Html::tag('p', $this->text);
        echo Html::endTag('div');
    }

}
