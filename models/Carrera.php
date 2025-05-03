<?php

namespace app\models;

use app\models\Factura;

use Yii;

/**
 * This is the model class for table "carrera".
 *
 * @property string $idCarr
 * @property string $NombCarr
 * @property string $nivelCarr
 * @property integer $StatusCarr
 * @property string $codCarr_senescyt
 * @property integer $mod_id
 * @property string $sau_id
 * @property integer $id_tc
 * @property string $inst_cod
 * @property string $idcarr_utelvt
 * @property integer $idsede
 * @property integer $idfacultad
 *
 * @property Matricula[] $matriculas
 */
class Carrera extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'carrera';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idCarr', 'StatusCarr'], 'required'],
            [['StatusCarr', 'mod_id', 'id_tc', 'idsede', 'idfacultad', 'habilitada'], 'integer'],
            [['idCarr'], 'string', 'max' => 6],
            [['NombCarr','tituloh','titulom'], 'string', 'max' => 500],
            [['nivelCarr'], 'string', 'max' => 20],
            [['codCarr_senescyt'], 'string', 'max' => 15],
            [['sau_id'], 'string', 'max' => 4],
            [['inst_cod'], 'string', 'max' => 12],
            [['idcarr_utelvt'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idCarr' => 'Código',
            'NombCarr' => 'Carrera',
	     'titulom' => 'Titulo M',
	     'tituloh' => 'Titulo H',
            'nivelCarr' => 'Nivel Carrera',
            'StatusCarr' => 'Carrera Activa',
            'codCarr_senescyt' => 'Cod Carr Senescyt',
            'mod_id' => 'Mod ID',
            'sau_id' => 'Sau ID',
            'id_tc' => 'Id Tc',
            'inst_cod' => 'Inst Cod',
            'idcarr_utelvt' => 'Idcarr Utelvt',
            'idsede' => 'Idsede',
            'idfacultad' => 'Idfacultad',
            'habilitada' => 'Habilitar Matrícula',
            'optativa' => 'Optativa',
            'culminacion' => 'Culminación',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatriculas()
    {
        return $this->hasMany(Matricula::className(), ['idCarr' => 'idCarr']);
    }

	 public function getFacultad0()
    {
        return $this->hasOne(Facultad::className(), ['idFacultad' => 'idfacultad']);
    }

	public function getNombreFacultad()
    {	
	$facultad = '';
	if ($this->facultad0) 
	{
		$facultad=$this->facultad0->facultad;
		return $facultad;
	}
    }

	public function getNombreDecano()
    {	
	$facultad = '';
	if ($this->facultad0) 
	{
		$facultad=$this->facultad0->decano;
		return $facultad;
	}
    }

	public function getNombreSecretaria()
    {	
	$facultad = '';
	if ($this->facultad0) 
	{
		$facultad=$this->facultad0->secretario;
		return $facultad;
	}
    }

	public function getNombreSiglas()
    {	
	$facultad = '';
	if ($this->facultad0) 
	{
		$facultad=$this->facultad0->siglas;
		return $facultad;
	}
    }
/**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrotitulos()
    {
        return $this->hasMany(Registrotitulos::className(), ['idcarr' => 'idCarr']);
    }
    /** 
     * @return \yii\db\ActiveQuery 
     */
    public function getFacultad(){
        return $this->hasOne(Facultad::className(), ['idfacultad' => 'idfacultad']);
    }
}
