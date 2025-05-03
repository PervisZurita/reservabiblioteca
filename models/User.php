<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $auth_key
 * @property int $status
 * @property int $tipo_usuario
 * @property string $created_at
 * @property string $updated_at
 * @property InformacionpersonalD $informacionpersonalD
 * @property Informacionpersonal $informacionpersonal
 * @property Personaldata $personaldata
 * 
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const TYPE_EXTERNO = 1;
    const TYPE_ESTUDIANTE = 13;
    const TYPE_DOCENTE = 18;
    const TYPE_PERSONALB = 21;
    const TYPE_GERENTE = 7;
    const TYPE_ADMIN = 8;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'Auth_key', 'Created_at'], 'required'],
            [['Status', 'tipo_usuario'], 'integer'],
            [['Created_at', 'Updated_at', 'Tempralpasswordtime'], 'safe'],
            [['username'], 'string', 'max' => 15],
            [['password'], 'string', 'max' => 255],
            [['Auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Usuario',
            'password' => 'Contraseña',
            'Auth_key' => 'Auth Key',
            'Status' => 'Estado',
            'tipo_usuario' => 'Tipo de Usuario',
            'Created_at' => 'Creación',
            'Updated_at' => 'Actualización',
        ];
    }


    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }


    /**
     * Gets query for [[Informacionpersonal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInformacionpersonal()
    {
        return $this->hasOne(Informacionpersonal::class, ['CIInfPer' => 'username']);
    }

    /**
     * Gets query for [[Informacionpersonal_d]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInformacionpersonalD()
    {
        return $this->hasOne(InformacionpersonalD::class, ['CIInfPer' => 'username']);
    }


    /**
     * Gets query for [[Personaldata]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersonaldata()
    {
        return $this->hasOne(Personaldata::class, ['Ci' => 'username']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'Status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Implementa la búsqueda por token aquí, si es necesario.
        return null;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'Status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->Auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->Auth_key === $authKey;
    }
    
    public function setPassword($password)
    {
       // $this->password = Yii::$app->security->generatePasswordHash($password);
$this->password =md5($password);
    }

    public function validatePassword($password)
    {
       // return Yii::$app->security->validatePassword($password, $this->password);
		return $this->password === md5($password);
    }

    public function validateCurrentPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'La contraseña actual es incorrecta.');
            }
        }
    }

	public function savePassword($password)
    {
		echo var_dump($this->ClaveUsu); exit;
		$this->ClaveUsu = md5($password);
        if (self::save())
            	return true;

		return false;
    }
    
}