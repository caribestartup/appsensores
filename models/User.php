<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $avatar
 * @property string $name
 * @property string $surname
 * @property integer $role
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    public $file;
    
    public function attributeLabels(){

        return[
            'username' => Yii::t('app','User'),
            'email' => Yii::t('app','email'),
            'password' => Yii::t('app','Password'),
            'password_repeat' => Yii::t('app','Rewrite password'),
            'file' => Yii::t('app', 'Avatar'), 
            'name' => Yii::t('app', 'Name'),
            'surname' => Yii::t('app', 'Surname'), 
            'role' => Yii::t('app', 'Permission'), 
            'status' => Yii::t('app', 'Active'), 
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
     
     /*preguntar si el usuario esta activo*/
    public static function isActive()
    {
     return Yii::$app->user->identity->status == self::STATUS_ACTIVE;
    }
    
     public static function roleInArray($arr_role)
    {
        $find_role = Role::find() ->where(['id' => Yii::$app->user->identity->role])->one();
        $role = $find_role->role;
    return in_array($role, $arr_role);
    }
    
    public function getRole(){
        $find_role = Role::find() ->where(['id' => Yii::$app->user->identity->role])->one();
        return $find_role->role;
    }
    
    public function getR(){
        $find_role = Role::find() ->where(['id' => $this->role])->one();
        return $find_role->role;
    }

    public function todayErrors()
    {
         $query = "SELECT parciales.*" . "FROM trabajo,maquina,totales,parciales WHERE trabajo.operador='".$this->getId()."' and trabajo.inicio > '".date('Y-m-d')."' and maquina.maquina_id = trabajo.maquina and totales.mac= maquina.mac and totales.hora_inicio >'".date('Y-m-d')."' and parciales.id_totales=totales.id";
            $sql= Yii::$app->db->createCommand($query);
            $record = $sql->queryAll();

        return $record;
    }

    /*public function getTurnos()
    {               
        $query = "SELECT turno.*"."FROM user_turno,turno WHERE user_turno.user=".$this->id." and turno.id=user_turno.turno";
        $sql = Yii::$app->db->createCommand($query);
        return $sql->queryAll();
    }*/

    public function getTurnos()
    {
        $searchModel = new UserTurnoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['user' => $this->id]);
        return $dataProvider;
    }
    
    public function turnosint(){
        $turno =[];
        $turnos = UserTurno::find()->where(['user' => $this->id])->all();
        foreach ($turnos as $model){
            $turno[] = $model->turno;
        }
        
        return $turno;
    }
    
    public function turnoinarray($turno, $arr){
        
        $exists = FALSE;
        foreach ($arr as $model){
            if (in_array($model, $turno)) {
                $exists = TRUE;
            }
        }
        return $exists;
    }

    public function getTotalerrors($sd, $ed)
    {
        $weights = [];

        $queryLast30 = "SELECT * , SUM(total_error) AS terror " . "FROM totales WHERE operario ='".$this->id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY DATE(fecha) ORDER BY DATE(hora_inicio)";
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
    /*Consulta obtener suma de programa agrupando por fecha y sumando los que tengan distinta maquina
    //SELECT SUM(temporal.programa) AS suma FROM (SELECT fecha,mac,programa, COUNT(*) AS contador FROM totales GROUP BY hora_inicio,mac ) AS temporal GROUP BY fecha
    */
    
    public function getTotalprodest($sd, $ed)
    {
        $weights = [];
        $queryLast30 = "SELECT *, SUM(temporal.programa) AS totalprev FROM (SELECT * FROM totales WHERE operario ='".$this->id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY hora_inicio,mac ) AS temporal GROUP BY fecha";
        /*$queryLast30 = "SELECT * , programa AS totalprev " . "FROM totales WHERE operario ='".$this->id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY DATE(hora_inicio) ORDER BY DATE(hora_inicio)";*/
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

        $queryLast30 = "SELECT *, SUM(temporal.totals) AS totalprev FROM (SELECT *, SUM(total) AS totals FROM totales WHERE operario ='".$this->id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY hora_inicio,mac ) AS temporal GROUP BY fecha";
        /*$queryLast30 = "SELECT * , programa - SUM(total) AS totalprev " . "FROM totales WHERE operario ='".$this->id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY DATE(hora_inicio) ORDER BY DATE(hora_inicio)";*/
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

        $queryLast30 = "SELECT * , SUM(total_error) AS totalprev " . "FROM totales WHERE operario ='".$this->id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY DATE(fecha) ORDER BY DATE(hora_inicio)";
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

        $queryLast30 = "SELECT parciales.*, SUM(parciales.total_error) AS error, SUM(parciales.total) AS terror " . "FROM totales,parciales WHERE totales.operario ='".$this->id."' and totales.hora_inicio > '".$sd."' and totales.hora_inicio < '".$ed."' and parciales.id_totales=totales.id GROUP BY totales.fecha, parciales.ventana";
        $sql= Yii::$app->db->createCommand($queryLast30);
        $recordLast30 = $sql->queryAll();
                
        return $recordLast30;

    }
    
    /*
     * consulta para obtener detalles de operario por maquina
     * SELECT mac,fecha,SUM(temporal.programa) AS suma,SUM(total) AS totals,SUM(temporal.programa)-SUM(total) AS realp FROM (SELECT * FROM totales WHERE operario = 2 AND mac=3 GROUP BY hora_inicio) AS temporal GROUP BY mac,fecha ORDER BY fecha
     * */
    
    public function getTotalprodestmac($sd, $ed, $mac)
    {
        $weights = [];
        
        $queryLast30 = "SELECT *, SUM(temporal.programa) AS totalprev FROM (SELECT * FROM totales WHERE operario ='".$this->id."' and mac ='".$mac."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY hora_inicio ) AS temporal GROUP BY fecha ORDER BY fecha";
        /*$queryLast30 = "SELECT * , programa - SUM(total) AS totalprev " . "FROM totales WHERE operario ='".$this->id."' and hora_inicio > '".$sd."' and hora_inicio < '".$ed."' GROUP BY DATE(hora_inicio) ORDER BY DATE(hora_inicio)";*/
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
   



  

}
