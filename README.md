yii2-fancytree-widget
=====================
The yii2-fancytree-widget is a Yii 2 wrapper for the [Fancytree](http://wwwendt.de/tech/fancytree/demo/). A JavaScript dynamic tree view plugin for jQuery with support for persistence, keyboard, checkboxes, tables, drag'n'drop, and lazy loading.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist novusgrp/yii2-tree "*"
```

or add

```
"novusgrp/yii2-tree": "*"
```

to the require section of your `composer.json` file.




How to use
----------

Your database
```
CREATE TABLE `categories` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) NOT NULL,
	`parent_id` INT(11) NULL DEFAULT NULL,
	`position` INT(11) NULL DEFAULT NULL,
	`type_id` INT(11) NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`deleted_at` TIMESTAMP NULL DEFAULT NULL,
	`created_by` INT(11) NULL DEFAULT NULL,
	`updated_by` INT(11) NULL DEFAULT NULL,
	`deleted_by` INT(11) NULL DEFAULT '0',
	`lock` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `FK_categories_parent_category_id` (`parent_id`),
	INDEX `FK_categories_account_id` (`account_id`),
	INDEX `FK_categories_type_id` (`type_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
```

On your Controller file:

```
    /**
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex() {
        $model = Categories::find())->all();

        return $this->render('index', [
                    'model' => $model,
        ]);
    }

```

Needed in your controller for updates.

```
    public function actionTree() {

        $data = Yii::$app->request->post('data');
        $action = Yii::$app->request->post('action');

        switch ($action) {
            case 'move':

                $subject = $this->findModel($data[0]);
                $move = $data[1];
                $relation = json_decode(json_encode(['id' => null]));
                if ($data[2] != "_1") {
                    $relation = $this->findModel($data[2]);
                }
                switch ($move) {
                    case 'over':
                        $subject->parent_id = $relation->id;
                        if (!$subject->save()) {
                            throw new \yii\web\ServerErrorHttpException();
                        }
                        return 'okay';
                        break;
                    case 'after':
                        $subject->parent_id = $relation->parent_id;
                        if (!$subject->save()) {
                            throw new \yii\web\ServerErrorHttpException();
                        }
                        return 'okay';
                        break;
                    case 'before':
                        $subject->parent_id = $relation->parent_id;
                        if (!$subject->save()) {
                            throw new \yii\web\ServerErrorHttpException();
                        }
                        return 'okay';
                        break;
                }
                break;
            case 'edit':
                $subject = $this->findModel($data[0]);
                $subject->name = $data[1];
                if (!$subject->save()) {
                    throw new \yii\web\ServerErrorHttpException();
                }
                return 'okay';
                break;
            case 'delete':
                $subject = $this->findModel($data[0]);
                if (!$subject->delete()) {
                    throw new \yii\web\ServerErrorHttpException();
                }
                return "false";
                break;
            case 'add_sibling':
                $subject = $this->findModel($data[0]);
                $type = 1;
                $model = new Categories();
                $model->parent_id = $subject->parent_id;
                $model->type_id = $type;
                $model->name = 'new';
                if (!$model->save()) {
                    throw new \yii\web\ServerErrorHttpException();
                }
                return $model->id;
                break;
            case 'add_child':
                $subject = $this->findModel($data[0]);
                $type = $data[1];
                $model = new Categories();
                $model->parent_id = $subject->id;
                $model->type_id = $type;
                $model->name = 'new';
                if (!$model->save()) {
                    throw new \yii\web\ServerErrorHttpException();
                }
                return $model->id;
                break;
        }

        throw new \yii\web\ServerErrorHttpException();
    }
```


On your view file.

```<?php

use novusgrp\TreeWidget;
use yii\web\JsExpression;

$modelclass = '\backend\models\Categories';
$controller = 'categories';
$fancytree_id = 10;
echo \novusgrp\TreeWidget::widget([
    'model' => $model,
    'modelclass' => $modelclass,
    'controller' => $controller,
    'editable' => true,
    'titlefield' => 'name',
    'keyfield' => 'id',
    'id' => 'fancyree_' . $fancytree_id,
    'options' => [
        'autoCollapse' => false,
        'extensions' => ['dnd', 'edit'],
        'dnd' => [
            'preventVoidMoves' => true,
            'preventRecursiveMoves' => true,
            'autoExpandMS' => 400,
            'dragStart' => new JsExpression('function(node, data) {
  				return true;
			}'),
            'dragEnter' => new JsExpression('function(node, data) {
				return true;
			}'),
            'dragDrop' => new JsExpression('function(node, data) {
                                datasend = [data.otherNode.key,data.hitMode,node.key];
                                action = "move";
                                if(updateTree(action,datasend).status !== 200) {
                                    return false;
                                }
				data.otherNode.moveTo(node, data.hitMode);
			}'),
            'focusOnClick' => 'true',
        ],
        'edit' => [
            'triggerStart' => ["clickActive", "dblclick", "f2", "mac+enter", "shift+click"],
            'beforeEdit' => new JsExpression('function(event, data){
                            // Return false to prevent edit mode
                          }'),
            'edit' => new JsExpression('function(event, data){
                            // Editor was opened (available as data.input)
                            $( ".fancytree-edit-input" ).width($(".fancytree-edit-input").width()*1.2);
                          }'),
            'beforeClose' => new JsExpression('function(event, data){
                            // Return false to prevent cancel/save (data.input is available)
                            if( data.originalEvent.type === "mousedown" ) {
                              // We could prevent the mouse click from generating a blur event
                              // (which would then again close the editor) and return `false` to keep
                              // the editor open:
                              //  data.originalEvent.preventDefault();
                              // return false;
                              // Or go on with closing the editor, but discard any changes:
                              //        data.save = false;
                            }
                          }
                    '),
            'save' => new JsExpression('function(event, data){
                            // Save data.input.val() or return false to keep editor open
                            
                            action = "edit";
                            datasend = [data.node.key, data.input.val()];
                            if(updateTree(action,datasend).status !== 200) {
                                node.setTitle(data.orgTitle);
                            }
                            data.input.removeClass("pending");
                            // We return true, so ext-edit will set the current user input
                            // as title
                            return true;
                          }'),
            'close' => new JsExpression('function(event, data){
                            // Editor was removed. If we started an async request, mark the node as pending
                                if( data.save ) {
                                    $(data.node.span).addClass("pending");
                                }
                            }'),
                                
        ],
    ],
]);
$this->registerJs('$.contextMenu({
    selector: "#fancyree_'.$fancytree_id.'  span.fancytree-node",
    items: {
        "edit": {name: "Edit", icon: "edit", callback: function (itemKey, opt) {
                var node = $.ui.fancytree.getNode(opt.$trigger);
                if (!node) {
                    alert("Please activate a parent node.");
                    return;
                }
                return node.editStart();
            }},
        "delete": {name: "Delete", icon: "delete", disabled: function (key, opt) {
                return false;
            },
            callback: function (itemKey, opt) {
                var node = $.ui.fancytree.getNode(opt.$trigger);
                if (!node) {
                    alert("Please activate a parent node.");
                    return;
                }
                datasend = [node.key];
                action = "delete";
                response = updateTree(action, datasend);
                if (response.status !== 200) {
                    return false;
                }
                node.remove();
            }},
        "sep1": "----",
        "add_child": {
            name: "Add Child", icon: "fa-caret-square-o-right", items: {
                "add_child": {name: "File", icon: "fa-file",
                    callback: function (key, opt) {
                        var node = $.ui.fancytree.getNode(opt.$trigger);
                        if (!node) {
                            alert("Please activate a parent node.");
                            return;
                        }
                        datasend = [node.key, 2];
                        action = "add_child";
                        response = updateTree(action, datasend);
                        if (response.status !== 200) {
                            return false;
                        }
                        node.editCreateNode("child", {title: "Node title", key: response.responseText});
                    }
                },
                "add_folder": {name: "Folder", icon: "fa-folder",
                    callback: function (key, opt) {
                        var node = $.ui.fancytree.getNode(opt.$trigger);
                        if (!node) {
                            alert("Please activate a parent node.");
                            return;
                        }
                        datasend = [node.key, 1];
                        action = "add_child";
                        response = updateTree(action, datasend);
                        if (response.status !== 200) {
                            return false;
                        }
                        node.editCreateNode("child", {title: "Node title", key: response.responseText, folder: true});
                    }
                },
            },
        },
        "add_sibling": {
            name: "Add Sibling", icon: "fa-caret-square-o-down", items: {
                "add_child": {name: "File", icon: "fa-file",
                    callback: function (key, opt) {
                        var node = $.ui.fancytree.getNode(opt.$trigger);
                        if (!node) {
                            alert("Please activate a parent node.");
                            return;
                        }
                        datasend = [node.key, 2];
                        action = "add_sibling";
                        response = updateTree(action, datasend);
                        if (response.status !== 200) {
                            return false;
                        }
                        node.editCreateNode("after", {title: "Node title", key: response.responseText});
                    }
                },
                "add_folder": {name: "Folder", icon: "fa-folder",
                    callback: function (key, opt) {
                        var node = $.ui.fancytree.getNode(opt.$trigger);
                        if (!node) {
                            alert("Please activate a parent node.");
                            return;
                        }
                        datasend = [node.key, 1];
                        action = "add_sibling";
                        response = updateTree(action, datasend);
                        if (response.status !== 200) {
                            return false;
                        }
                        node.editCreateNode("after", {title: "Node title", key: response.responseText, folder: true});
                    }
                },
            },
        },

    },
    callback: function (itemKey, opt) {
        var node = $.ui.fancytree.getNode(opt.$trigger);
        alert("select " + itemKey + " on " + node);
    }
});
');
?>

```
