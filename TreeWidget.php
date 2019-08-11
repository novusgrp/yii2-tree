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
class TreeWidget extends \yii\base\Widget {

    /**
     * @var array
     */
    public $options = [];
    public $model;
    public $modelname;
    public $editable = false;
    public $onclick = '';
    public $keyfield = 'id';
    public $titlefield = 'id';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->registerAssets();
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets() {
        $this->modelname = $this->modelname;

        $source = empty($this->model) ? [] : $this->buildSource($this->model);
        $keys = [
            ['key' => NULL, 'title' => 'Root', 'icon' => 'glyphicon glyphicon-tree-conifer', 'expanded' => true,
                'children' => $source]
        ];

        $view = $this->getView();
        TreeAsset::register($view);
        $this->options['source'] = $keys;
        $id = 'fancyree_' . $this->id;

        if (isset($this->options['id'])) {
            $id = $this->options['id'];
            unset($this->options['id']);
        } else {
            echo Html::tag('div', '', ['id' => $id]);
        }
        $options = Json::encode($this->options);

        if ($this->editable) {
            $this->options['activate'] = new \yii\web\JsExpression('function(event, data){
            ' . $this->onclick . '(data.node.data);
        }');

            echo $view->renderFile('@vendor/novusgrp/yii2-tree/views/controls.php');
        } else {
            $this->options['activate'] = new \yii\web\JsExpression('function(event, data){ ' . $this->onclick . '(data.node.data); }');
        }
        $this->options['expanded'] = true;

        $options = Json::encode($this->options);




        $view->registerJs('$("#' . $id . '").fancytree( ' . $options . ')');
    }

    private function buildSource($model) {
        foreach ($model as $item) {
            $items[] = $item;
        }
        $model = $items;
        foreach ($model as $item) {
            $data = $this->buildAttributes($item);

            $data['key'] = $item->{$this->keyfield};
            $data['title'] = $item->{$this->titlefield};
            $data['expanded'] = true;
            $querymodel = $this->modelname . 'Query';
            $query = new $querymodel($this->modelname);
            $children = $query->where(['parent_id' => $item->id])->all();
            if ($children != NULL) {
                $data['children'] = $this->buildSource($children);
            }
            $source[] = $data;
        }

        return $source;
    }

    private function buildAttributes($object) {
        $attributes = $object->attributes();
        foreach ($attributes as $attribute) {
            $data[$attribute] = $object->{$attribute};
        }
        return $data;
    }

}
