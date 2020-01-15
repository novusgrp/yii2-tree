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

On your Controller file:

```
    /**
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex() {
        $model = Categories::find()->andWhere(['parent_id' => null])->all();

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
        }

        throw new \yii\web\ServerErrorHttpException();
    }
```


On your view file.

```<?php
// Example of data.
$modelclass = '\backend\models\Categories';
$controller = 'categories';
echo \novusgrp\TreeWidget::widget([
    'model' => $model,
    'modelclass' => $modelclass,
    'controller' => $controller,
    'editable' => true,
    'titlefield' => 'name',
    'keyfield' => 'id',
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
                                if(updateTree(action,datasend) !== 200) {
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
                            console.log(event.type, event, data);
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
                            
                            setTimeout(function(){
                              $(data.node.span).removeClass("pending");
                              data.node.setTitle(data.node.title);
                            }, 2000);
                            // We return true, so ext-edit will set the current user input
                            // as title
                            return true;
                          }'),
            'close' => new JsExpression('function(event, data){
                            // Editor was removed
                            if( data.save ) {
                              // Since we started an async request, mark the node as preliminary
                              $(data.node.span).addClass("pending");
                              action = "edit";
                              datasend = [data.node.key, data.node.title];
                              if(updateTree(action,datasend) !== 200) {
                                    return false;
                              }
                            }
                          }'),
        ],
    ],
]);
?>

```
