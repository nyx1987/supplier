<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
?>
 
<div id="page-wrapper">
  <div class="row">
  </div>
  <div class="row">
    <div class="col-lg-12">
      <?php echo GridView::widget([
        //设置GridView的ID
        'id' => 'myUserGridView',
        //设置数据提供器
        'dataProvider' => $dataProvider,
        //设置筛选模型
        'filterModel' => $supplierModel,
        'columns' => [
          //复选框列
          ['class' => 'yii\grid\CheckboxColumn'],
          //显示序号列
          ['class' => 'yii\grid\SerialColumn'],
          [
            //设置字段显示标题
            'label' => 'ID',
            //字段名
            'attribute' => 'id',
            //格式化
            'format' => 'raw',
            //设置单元格样式
            'headerOptions' => [
              'style' => 'width:120px;',
            ],
          ],
          [
            'label' => '姓名',
            'attribute' => 'name',
            'format' => 'raw',
          ],
          [
            'label' => 'code',
            'attribute' => 'code',
            'format' => 'raw',
          ],
          [
            'label' => 't_status',
            //设置筛选选项
            'filter' => [ "ok" => 'ok', "hold" => 'hold'],
            'attribute' => 't_status',
            'format' => 'raw',
            'value' => function ($data) {
              return ($data->t_status == 'ok') ? 'ok' : 'hold';
            }
          ],

        ],
      ]); ?>
    </div>
  </div>

    <div class="row">
    <div class="col-lg-12">
    <input type="checkbox" id="checkbox_need" value="id" checked>id
<input type="checkbox" value="name">name
<input type="checkbox" value="code">code
<input type="checkbox" value="t_status">t_status

      <button class="btn btn-primary" id="exportBtn">导出数据</button>
    </div>
  </div>
</div>
 
 
<?php $this->beginBlock('suibian') ?>
  $("#exportBtn").on("click", function () {
        var arr = [];
        $("input[type='checkbox']:checked").each(function (index, item) {//
            arr.push($(this).val());
        });
        if ($("#checkbox_need").get(0).checked) {
        }else{
            alert("ID必须选")
            return ;
        }
    window.location.href = "http://www.lovefangyuan.com/index.php?r=site/export&title="+arr;
  });
 
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['suibian']); ?>

