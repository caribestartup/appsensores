<?php

namespace app\models;

use Yii;
use app\models\Error;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "maquina".
 *
 * @property int $maquina_id
 * @property string $nombre
 * @property string $modelo
 * @property string $numero
 * @property int $local
 * @property string $imagen
 * @property double $posx
 * @property double $posy
 * @property double $ancho
 * @property double $largo
 * @property double $intervalo
 * @property string $fecha
 * @property int $estado
 * @property string $marix
 */
class Maquina extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'maquina';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'modelo', 'numero','local'], 'required'],
            [['local', 'estado'], 'integer'],
            [['posx', 'posy', 'ancho', 'largo'], 'number'],
            [['fecha', 'matrix'], 'safe'],
            [['nombre', 'modelo', 'numero'], 'string', 'max' => 50],
            [['imagen'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'maquina_id' => 'Maquina ID',
            'nombre' => 'Name',
            'modelo' => 'Model',
            'numero' => 'Number',
            'local' => 'Shed',
            'localname' => 'Shed',
            'imagen' => 'Image',
            'posx' => 'Posx',
            'posy' => 'Posy',
            'ancho' => 'Ancho',
            'largo' => 'Largo',
            'mac' => 'Mac',
            'intervalo' => 'Intervalo',
            'fecha' => 'Fecha',
            'estado' => 'Estado',
        ];
    }

    public function getAllmachines(){
        $maquina = Maquina::find()->all();
        return $maquina;
    }

    public function getRealstate()
    {
        $state = -1;
        if ($this->estado == 0) {
            $state = 0;
        }
        else if ($this->estado = 1) {
                $state = 1;
        }
       return $state;
    }

    public function pass()
    {
        $time = date_create(date('Y-m-d H:i'))->diff(date_create($this->fecha));
        return $time->format('%i');
    }

    public function todayErrors()
    {
        $query = "SELECT parciales.*, SUM(parciales.total_error) AS error, SUM(parciales.total) AS terror " . "FROM totales,parciales WHERE totales.mac='".$this->maquina_id."' and totales.hora_inicio >'".date('Y-m-d')."' and parciales.id_totales=totales.id GROUP BY parciales.ventana";
            $sql= Yii::$app->db->createCommand($query);
            $record = $sql->queryAll();

        return $record;
    }

    public function errorName($id)
    {
        return Error::find()->where(['ventana' => $id])->one()->error;
    }

    public function percentage($value1, $value2)
    {
        $percentage = 0;
        if ($value1 > 0 && $value2 > 0) {
            $percentage = ($value1 * 100) / $value2;
            $percentage = round($percentage * 100) / 100;
        }
        return $percentage;
    }

    public function getTotalerrors($sd, $ed)
    {
        $weights = [];

        $queryLast30 = "SELECT * , SUM(total_error) AS terror " . "FROM totales WHERE mac ='".$this->maquina_id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY DATE(hora_inicio) ORDER BY DATE(hora_inicio)";
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

    public function getPartialerrors($sd, $ed)
    {

        $queryLast30 = "SELECT parciales.*, SUM(parciales.total_error) AS error, SUM(parciales.total) AS terror " . "FROM totales,parciales WHERE totales.mac ='".$this->maquina_id."' and totales.hora_inicio > '".$sd."' and totales.hora_inicio < '".$ed."' and parciales.id_totales=totales.id GROUP BY totales.fecha, parciales.ventana";
        $sql= Yii::$app->db->createCommand($queryLast30);
        $recordLast30 = $sql->queryAll();

        return $recordLast30;

    }

    public function getTotalprodest($sd, $ed)
    {
        $weights = [];

        $queryLast30 = "SELECT *, SUM(temporal.programa) AS totalprev FROM (SELECT * FROM totales WHERE mac ='".$this->maquina_id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY hora_inicio ) AS temporal GROUP BY fecha";
        /*$queryLast30 = "SELECT * , programa AS totalprev " . "FROM totales WHERE mac ='".$this->maquina_id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY DATE(fecha) ORDER BY DATE(fecha)";*/
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
        $queryLast30 = "SELECT *, SUM(temporal.totals) AS totalprev FROM (SELECT *, SUM(total) AS totals FROM totales WHERE mac ='".$this->maquina_id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY hora_inicio ) AS temporal GROUP BY fecha";
        /*$queryLast30 = "SELECT * , programa - SUM(total) AS totalprev " . "FROM totales WHERE mac ='".$this->maquina_id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY DATE(hora_inicio) ORDER BY DATE(hora_inicio)";*/
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

        $queryLast30 = "SELECT * , SUM(total_error) AS totalprev " . "FROM totales WHERE mac ='".$this->maquina_id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY DATE(fecha) ORDER BY DATE(hora_inicio)";
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

    public function getTotalprodestn()
    {

        $queryLast30 = "SELECT programa AS totalprev " . "FROM totales WHERE mac ='".$this->maquina_id."' and hora_inicio > '".date('Y-m-d')."' GROUP BY DATE(fecha) ORDER BY DATE(hora_inicio)";
        $sql= Yii::$app->db->createCommand($queryLast30);
        $recordLast30 = $sql->queryAll();

        if (count($recordLast30) != 0) {
            return $recordLast30[0]["totalprev"];
        }else{
            return 0;
        }

    }

    public function getTotalprodn()
    {

        $queryLast30 = "SELECT SUM(total) AS totalprev " . "FROM totales WHERE mac ='".$this->maquina_id."' and hora_inicio > '".date('Y-m-d')."'GROUP BY DATE(fecha) ORDER BY DATE(hora_inicio)";
        $sql= Yii::$app->db->createCommand($queryLast30);
        $recordLast30 = $sql->queryAll();

        if (count($recordLast30) != 0) {
            return $recordLast30[0]["totalprev"];
        }else{
            return 0;
        }

    }

     public function getTotalprodestnall()
    {

        $queryLast30 = "SELECT programa AS totalprev " . "FROM totales WHERE  hora_inicio > '".date('Y-m-d')."' GROUP BY DATE(fecha) ORDER BY DATE(hora_inicio)";
        $sql= Yii::$app->db->createCommand($queryLast30);
        $recordLast30 = $sql->queryAll();

        if (count($recordLast30) != 0) {
            return $recordLast30[0]["totalprev"];
        }else{
            return 0;
        }

    }

    public function getTotalprodnall()
    {

        $queryLast30 = "SELECT SUM(total) AS totalprev " . "FROM totales WHERE hora_inicio > '".date('Y-m-d')."'GROUP BY DATE(fecha) ORDER BY DATE(hora_inicio)";
        $sql= Yii::$app->db->createCommand($queryLast30);
        $recordLast30 = $sql->queryAll();

        if (count($recordLast30) != 0) {
            return $recordLast30[0]["totalprev"];
        }else{
            return 0;
        }

    }

    public function getTotalerrornall()
    {

        $queryLast30 = "SELECT SUM(total_error) AS totalprev " . "FROM totales WHERE hora_inicio > '".date('Y-m-d')."'GROUP BY DATE(fecha) ORDER BY DATE(hora_inicio)";
        $sql= Yii::$app->db->createCommand($queryLast30);
        $recordLast30 = $sql->queryAll();

        if (count($recordLast30) != 0) {
            return $recordLast30[0]["totalprev"];
        }else{
            return 0;
        }

    }

    public function getLasturno()
    {
        $queryLast30 = "SELECT turno.* " . "FROM totales,turno WHERE hora_inicio > '".date('Y-m-d')."' and turno.id = totales.turno GROUP BY DATE(hora_inicio) ORDER BY DATE(hora_inicio) DESC LIMIT 1";
        $sql= Yii::$app->db->createCommand($queryLast30);
        $recordLast30 = $sql->queryAll();

        return $recordLast30;
    }

    public function getdropdownlocales(){
        $dropdown = Local::find()->asArray()->all();
        return ArrayHelper::map($dropdown, 'local_id', 'nombre');
    }

    public function getLocalname(){
        $find_turno = Local::find() ->where(['local_id' => $this->local])->one();
        return $find_turno->nombre;
    }

    public function getAviableMachines()
    {
        // $query = "SELECT * " . "FROM turno_usuario_maquina
        //                                 join user_turno on turno_usuario_maquina.turno_usuario_id = user_turno.id
        //                                 join turno on user_turno.turno = turno.id
        //                                 WHERE turno.inicio < '".date('H:i:s')."' and turno.fin > '".date('H:i:s')."'
        //                                 and  turno_usuario_maquina.fecha = '".date('Y-m-d')."'";
        // $sql= Yii::$app->db->createCommand($query);
        // $record = $sql->queryAll();
        //
        //
        // print_r($record);
        //
        // $maquinas = Maquina::find()->all();
        // $libres = array();
        //
        // foreach ($maquinas as $key => $maquina) {
        //
        //
        // }



        return Maquina::find()->all();
    }



}
