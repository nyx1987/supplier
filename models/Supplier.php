<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "supplier".
 *
 * @property int $id
 * @property string $name
 * @property string|null $code
 * @property string $t_status
 */
class Supplier extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['t_status'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 3],
            [['id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            't_status' => 'T Status',
        ];
    }

    public function search($params)
     {
         $query = Supplier::find();
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                'pageSize' => 10, //每页显示条数
                ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
       // $query->andFilterWhere([
       //         'population' => $this->population,
      //  ]);

    //增加过滤条件
    $query->andFilterWhere(['id' => $this->id])
      ->andFilterWhere(['like', 'name', $this->name])
      ->andFilterWhere(['like', 'code', $this->code])
      ->andFilterWhere(['t_status' => $this->t_status]);
        return $dataProvider;
     }

}
