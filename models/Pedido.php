<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "turno".
 *
 * @property integer $id
 * @property string $identificador
 * @property string $inicio
 * @property string $fin
 */
class Pedido extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pedido';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identificador'], 'required'],
            // [['inicio', 'fin'], 'safe'],
            [['identificador'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'identificador' => Yii::t('app', 'Identifier')
            // 'inicio' => Yii::t('app', 'Begin'),
            // 'fin' => Yii::t('app', 'End'),
        ];
    }

    public function getTotalerrors($sd, $ed)
    {
        $weights = [];

        $queryLast30 = "SELECT * , SUM(total_error) AS terror " . "FROM totales WHERE turno ='".$this->id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY DATE(fecha) ORDER BY DATE(hora_inicio)";
        $sql= Yii::$app->db->createCommand($queryLast30);
        $recordLast30 = $sql->queryAll();

        $fechas = $this::fechas($sd, $ed);

        foreach ($fechas as $fecha) {
                        $weights[] = 0 ;
                    }
        foreach($recordLast30 as $weight)
                {

                //Sets weight rounded 2 points
                    $index = array_search(substr($weight['hora_inicio'], 0,10), $fechas);
                    $weights[$index] = $weight["terror"];
                }

        return $weights;

    }

    public function getTotalprodest($sd, $ed)
    {
        $weights = [];
        $queryLast30 = "SELECT *, SUM(temporal.programa) AS totalprev FROM (SELECT * FROM totales WHERE turno ='".$this->id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY hora_inicio, mac ) AS temporal GROUP BY fecha";
        /*$queryLast30 = "SELECT * , programa AS totalprev " . "FROM totales WHERE turno ='".$this->id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY DATE(hora_inicio) ORDER BY DATE(hora_inicio)";*/
        $sql= Yii::$app->db->createCommand($queryLast30);
        $recordLast30 = $sql->queryAll();

        $fechas = $this::fechas($sd, $ed);

        foreach ($fechas as $fecha) {
                        $weights[] = 0 ;
                    }
        foreach($recordLast30 as $weight)
                {

                //Sets weight rounded 2 points
                    $index = array_search(substr($weight['hora_inicio'], 0,10), $fechas);
                    $weights[$index] = $weight["totalprev"];
                }

        return $weights;

    }

    public function getTotalprod($sd, $ed)
    {
        $weights = [];
        $queryLast30 = "SELECT *, SUM(temporal.totals) AS totalprev FROM (SELECT *, SUM(total) AS totals FROM totales WHERE turno ='".$this->id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY hora_inicio, mac ) AS temporal GROUP BY fecha";
        /*$queryLast30 = "SELECT * , programa - SUM(total) AS totalprev " . "FROM totales WHERE turno ='".$this->id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY DATE(hora_inicio) ORDER BY DATE(hora_inicio)";*/
        $sql= Yii::$app->db->createCommand($queryLast30);
        $recordLast30 = $sql->queryAll();

        $fechas = $this::fechas($sd, $ed);

        foreach ($fechas as $fecha) {
                        $weights[] = 0 ;
                    }
        foreach($recordLast30 as $weight)
                {

                //Sets weight rounded 2 points
                    $index = array_search(substr($weight['hora_inicio'], 0,10), $fechas);
                    $weights[$index] = $weight["totalprev"];
                }

        return $weights;

    }

    public function getTotalrech($sd, $ed)
    {
        $weights = [];

        $queryLast30 = "SELECT * , SUM(total_error) AS totalprev " . "FROM totales WHERE turno ='".$this->id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY DATE(fecha) ORDER BY DATE(hora_inicio)";
        $sql= Yii::$app->db->createCommand($queryLast30);
        $recordLast30 = $sql->queryAll();

        $fechas = $this::fechas($sd, $ed);

        foreach ($fechas as $fecha) {
                        $weights[] = 0 ;
                    }
        foreach($recordLast30 as $weight)
                {

                //Sets weight rounded 2 points
                    $index = array_search(substr($weight['hora_inicio'], 0,10), $fechas);
                    $weights[$index] = $weight["totalprev"];
                }

        return $weights;

    }

    public function getPartialerrors($sd, $ed)
    {

        $queryLast30 = "SELECT parciales.*, SUM(parciales.total_error) AS error, SUM(parciales.total) AS terror " . "FROM totales,parciales WHERE totales.turno ='".$this->id."' and totales.hora_inicio > '".$sd."' and totales.hora_inicio < '".$ed."' and parciales.id_totales=totales.id GROUP BY totales.fecha, parciales.ventana";
        $sql= Yii::$app->db->createCommand($queryLast30);
        $recordLast30 = $sql->queryAll();

        return $recordLast30;

    }

    public function fechas($start, $end) {
        $range = array();

        if (is_string($start) === true) $start = strtotime($start);
        if (is_string($end) === true ) $end = strtotime($end);

        if ($start > $end) return createDateRangeArray($end, $start);

        do {
            $range[] = date('Y-m-d', $start);
            $start = strtotime("+ 1 day", $start);
        } while($start <= $end);

        return $range;
    }
}
