<?php
/**
 * @link      https://github.com/novusgrp/yii2-treewidget
 * @copyright Copyright (c) 2019 Armand Groenewald
 */

namespace novusgrp;

use yii\helpers\Html;
use yii\helpers\Json;

/**
 * The yii2-tree is a Yii 2 wrapper for the fancytree.js
 * See more: https://github.com/novusgrp/yii3-tree
 *
 * @author Armand Groenewald <armand@novusgroup.co.za>
 */
class TreeWidget extends \yii\base\Widget
{
    /**
     * @var array
     */
    public $options = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerAssets();
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        FancytreeAsset::register($view);
        $id = 'fancyree_' . $this->id;
        if (isset($this->options['id'])) {
            $id = $this->options['id'];
            unset($this->options['id']);
        } else {
           echo Html::tag('div', '', ['id' => $id]);
        }
        $options = Json::encode($this->options);
        $view->registerJs('$("#' . $id . '").fancytree( ' .$options .')');
    }
}
