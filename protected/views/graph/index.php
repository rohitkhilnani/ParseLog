<?php
/* @var $this GraphController */

$this->breadcrumbs=array(
	'Graph',
);
?>

<!-- Register javaScript scripts.  -->
<?php Yii::app()->clientScript->registerScriptFile("amcharts/amcharts.js"); ?>
<?php Yii::app()->clientScript->registerScriptFile("amcharts/serial.js"); ?>
<?php Yii::app()->clientScript->registerScriptFile("scripts/plotGraph.js"); ?>  
    
<h1><?php echo "Hits Per Location"; ?></h1>

<div id="chartdiv" style="width: 100%; height: 400px;"></div>

<h3>Note: Graph data has been hard coded for now. There are ways to make it dynamic. The intention was to demonstrate javascript usage.</h3>