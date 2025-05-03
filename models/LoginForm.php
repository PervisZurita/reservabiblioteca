<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use app\models\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username'], 'required', 'message' => '{attribute} no puede estar vacío.'],
            [['password'], 'required', 'message' => '{attribute} no puede estar vacía.'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Usuario',
            'password' => 'Contraseña',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Usuario o contraseña incorrecta.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
    /*if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
            //VERIFICAR SI THIS->USER TIENE ALGUN USUARIO, O ENCONTRO EL USUARIO
            //EN TAL CASO DE NO TENER USUARIOS CONSULTAR A LA TABLA DE ESTUDIANTES INFPERSONAL. BUSCAR POR CEDULA EN LA TABLA
            //CASO CONTRARIO QUE NO ENCUENTRE USUARIOS BUSCAR EN LA TABLA DOCENTE
            //EN CASO DE ENCONTRAR EL USUARIO EN LA TABLA DE ESTUDIANTES O DOCENTES AUTOMATICAMENTE CREAR EL USUARIO CON LOS DATOS OBTENIDOS
        }*/


        if ($this->_user === false) {
            // Intenta encontrar al usuario por nombre de usuario en la tabla de Usuarios
            $this->_user = User::findByUsername($this->username);

            // Si no se encuentra al usuario en la tabla de Usuarios, busca en otras tablas
            if ($this->_user === null) {
                // Buscar en la tabla de Estudiantes por cédula
                $student = Informacionpersonal::findByCedula($this->username);

                // Buscar en la tabla de Docentes por cédula si no se encuentra en Estudiantes
                if ($student === null) {
                    $teacher = InformacionpersonalD::findByCedula($this->username);

                    // Si se encuentra en la tabla de Docentes, crea un nuevo usuario con los datos de Docentes
                    if ($teacher !== null) {
                        $this->_user = new User();
                        $this->_user->username = $teacher->CIInfPer; // Utiliza la cédula como nombre de usuario
                        $now = \Yii::$app->formatter;
                        $this->_user->tipo_usuario = 18;
                        //$this->_user->setPassword($teacher->ClaveUsu);
 			$this->_user->password=$teacher->ClaveUsu;
                        $this->_user->Created_at = $now->asDatetime(new \DateTime(), 'php:Y-m-d H:i:s');
                        $this->_user->Auth_key = \Yii::$app->security->generateRandomString();
                        // Guarda el usuario
                        $this->_user->save();
                    }
                } else {
                    // Si se encuentra en la tabla de Estudiantes, crea un nuevo usuario con los datos de Estudiantes
                    $this->_user = new User();
                    $this->_user->username = $student->CIInfPer; // Utiliza la cédula como nombre de usuario
                    $now = \Yii::$app->formatter;
                   // $this->_user->setPassword($student->codigo_dactilar);
		    $this->_user->password=$student->codigo_dactilar;
                    $this->_user->tipo_usuario = 13;
                    $this->_user->Created_at = $now->asDatetime(new \DateTime(), 'php:Y-m-d H:i:s');
                    $this->_user->Auth_key = \Yii::$app->security->generateRandomString();
                    // Guarda el usuario
                    $this->_user->save();
                }
            }
        }

        return $this->_user;
    }
}
