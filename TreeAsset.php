<?php

/**
 * @link      https://github.com/novusgrp/yii2-treewidget
 * @copyright Copyright (c) 2019 Armand Groenewald
 */

namespace novusgrp;

/**
 * Asset bundle for fancytree Widget
 *
 * @author Armand Groenewald <armand@novusgroup.co.za>
 */
class TreeAsset extends \yii\web\AssetBundle {

    public $sourcePath = '@bower/fancytree';
    public $skin = 'dist/skin-lion/ui.fancytree';

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
    

    /**
     * Set up CSS and JS asset arrays based on the base-file names
     * @param string $type whether 'css' or 'js'
     * @param array $files the list of 'css' or 'js' basefile names
     */
    protected function setupAssets($type, $files = []) {
        $srcFiles = [];
        $minFiles = [];
        foreach ($files as $file) {
            $srcFiles[] = "{$file}.{$type}";
            $minFiles[] = "{$file}.min.{$type}";
        }
        if (empty($this->$type)) {
            $this->$type = $srcFiles;
        }
    }

    /**
     * @inheritdoc
     */
    public function init() {
        $this->setupAssets('css', [$this->skin]);
        $this->setupAssets('js', ['dist/jquery.fancytree-all']);
        $this->setupAssets('js', ['@nmp-asset/jquery-contextmenu/dist/jquery.contextMenu']);
        $this->setupAssets('css', ['@nmp-asset/jquery-contextmenu/dist/jquery.contextMenu']);
        parent::init();
    }

}
