<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "curso_ofertado".
 *
 * @property integer $id
 * @property integer $idper
 * @property integer $iddetallemalla
 * @property string $iddocente
 * @property string $paralelo
 * @property integer $cupo
 * @property integer $idhorario
 * @property integer $estado
 */
class CursoOfertado extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $idcarr;
	public $idmalla;
	public $nivel;
	public $asignaturamalla;

    public static function tableName()
    {
        return 'curso_ofertado';
    }

    /**
     * @inheritdoc  , 'max' => 45 ,'min' => 45
     */
    public function rules()
    {
        return [
            [['idper', 'iddetallemalla', 'cupo', 'paralelo'], 'required'],
            [['idper', 'iddetallemalla', 'cupo', 'idhorario', 'estado', 'restringido','Matriculados','nhoras','SubidoH1','ImpresionH1','SubidoH2','ImpresionT'], 'integer'],
            [['idper', 'iddetallemalla', 'cupo', 'idhorario', 'estado', 'restringido','Matriculados','nhoras','SubidoH1','ImpresionH1','SubidoH2','ImpresionT'], 'integer'],
	    [['NReprobados','NAprobados','AutoEval','CoevalPar','CoevalEstu','CoevalCoor','EvaluarPar','NArticulo','NLibro','NCursos'], 'integer'],
 	    [['NAsistenciaH1','NTutoriasClaseH1','NTutoriaCompleH1','NAsistenciaOtrosH1','NAsistenciaFueraH1','NAsistenciaH2','NTutoriasClaseH2','NTutoriaCompleH2','NAsistenciaOtrosH2','NAsistenciaFueraH2'], 'integer'],
	    [['cupo'], 'integer' ],
	    [['iddocente'], 'string', 'max' => 20],
            [['paralelo'], 'string', 'max' => 2],
	    [['idcarr', 'idmalla'], 'safe'],
	    [['fecha_inicio', 'fecha_fin','silabo','modulo','fechacreacion'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'idcurso',
            'idper' => 'Idper',
            'iddetallemalla' => 'Iddetallemalla',
            'iddocente' => 'Id docente',
            'paralelo' => 'Paralelo',
            'cupo' => 'Cupo',
            'idhorario' => 'Horario',
            'estado' => 'Estado',
	    'Matriculados' => 'Matri',
	    'nhoras' => 'NHoras',
	    'SubidoH1' => 'SubH1','SubidoH2' => 'SubH2','ImpresionH1' => 'ImpH1','ImpresionT' => 'ImpT',
	    'NReprobados' => 'NReprobados','NAprobados' => 'NAprobados',
	    'AutoEval' => 'AutoEval','CoevalPar' => 'CoevalPar', 'CoevalEstu' => 'CoevalEstu','CoevalCoor' => 'CoevalCoor','EvaluarPar' => 'EvaluarPar',
	    'NArticulo' => 'NArticulo','NLibro' => 'NLibro', 'NCursos' => 'NCursos',
	    'NAsistenciaH1' => 'NAsistenciaH1','NTutoriasClaseH1' => 'NTutoriasClaseH1', 'NTutoriaCompleH1' => 'NTutoriaCompleH1', 'NAsistenciaOtrosH1' => 'NAsistenciaOtrosH1', 'NAsistenciaFueraH1' => 'NAsistenciaFueraH1',
	    'NAsistenciaH2' => 'NAsistenciaH2','NTutoriasClaseH2' => 'NTutoriasClaseH2', 'NTutoriaCompleH2' => 'NTutoriaCompleH2', 'NAsistenciaOtrosH2' => 'NAsistenciaOtrosH2', 'NAsistenciaFueraH2' => 'NAsistenciaFueraH2',
	    'fechacreacion' => 'FechaReg',
	    'restringido' => 'Restr',
	    'idcarr' => 'Carrera',
	    'idmalla' => 'Malla',
	    'silabo' => 'silabo',
	    'modulo' => 'modulo',

        ];
    }

	/**
     * @return \yii\db\ActiveQuery
     */
	public function getPeriodo()
    {
        return $this->hasOne(Periodolectivo::className(), ['idPer' => 'idper']);
    }
    public function getDetallemalla()
    {
        return $this->hasOne(DetalleMalla::className(), ['id' => 'iddetallemalla']);
    }
	public function getDocente()
    {
        return $this->hasOne(InformacionpersonalD::className(), ['CIInfPer' => 'iddocente']);
    }
	public function getNombreDocente()
    {
		$model=$this->docente;
		return $model?($model->ApellInfPer . ' ' . $model->ApellMatInfPer . ' ' . $model->NombInfPer):'';
    }
	public function getCupos()
    {
		#echo var_dump(count($this->getDetallematricula()) ); exit;
		$matriculados = DetalleMatricula::find()
							->where(['idcurso' => $this->id, 'estado'=> 1])->all();

		$total_matriculados = $matriculados?count($matriculados):0;
		return $this->cupo - $total_matriculados;
    }
public function getMatriculados()
    {
		#echo var_dump(count($this->getDetallematricula()) ); exit;
		$matriculados = DetalleMatricula::find()
							->where(['idcurso' => $this->id])->all();
			#->andFilterWhere(['>', 'fecha', '2021-04-18'])->all();
			#->andFilterWhere(['>', 'costo', '5'])->all();
		$total_matriculados = $matriculados?count($matriculados):0;
		return $total_matriculados;
    }
public function getNHoras()
    {
		#echo var_dump(count($this->getDetallematricula()) ); exit;
		$Horas= DetalleHorario::find()
							->where(['idcurso' => $this->id])->all();

		$total_Horas= $Horas?count($Horas):0;
		return $total_Horas;
    }
 public function getReprobados()
    {
               $matriculados = DetalleMatricula::find() 
				->joinWith(['notasalumno'])
			        ->where(['idcurso' => $this->id])
				->andWhere(['<', 'CalifFinal', 7])->all();


		$total_reprobados = $matriculados?count($matriculados):0;
       		return $total_reprobados;
    }

 public function getAprobados()
    {
               $matriculados = DetalleMatricula::find() 
				->joinWith(['notasalumno'])
			        ->where(['idcurso' => $this->id])
				->andWhere(['>=', 'CalifFinal', 7])->all();


		$total_aprobados = $matriculados?count($matriculados):0;
       		return $total_aprobados ;
    }

 public function getEvaDocenteAuto()
    {
               $matriculados = Evaldocente::find() 
			        ->where(['cedula_docente' => $this->iddocente])
				->andWhere(['=', 'materia_id', 1])
				->andWhere(['tipo' =>  null])
				->andWhere(['periodo_id' => $this->idper])->all();
				#->andWhere(['<', 'CalifFinal', 7])->all();


		$total_reprobados = $matriculados?count($matriculados):0;
       		return $total_reprobados;
    }
 public function getEvaDocenteCoePar()
    {
               $matriculados = Evaldocente::find() 
			        ->where(['cedula_docente' => $this->iddocente])
				->andWhere(['=', 'materia_id', 1])
				->andWhere(['tipo' =>  'PAR'])
				->andWhere(['periodo_id' => $this->idper])->all();
				#->andWhere(['<', 'CalifFinal', 7])->all();


		$total_reprobados = $matriculados?count($matriculados):0;
       		return $total_reprobados;
    }
public function getEvaDocenteCoeCoor()
    {
               $matriculados = Evaldocente::find() 
			        ->where(['cedula_docente' => $this->iddocente])
				->andWhere(['=', 'materia_id', 1])
				->andWhere(['tipo' =>  'COORDINADOR'])
				->andWhere(['periodo_id' => $this->idper])->all();
				#->andWhere(['<', 'CalifFinal', 7])->all();


		$total_reprobados = $matriculados?count($matriculados):0;
       		return $total_reprobados;
    }
 public function getEvaDocenteCoeEst()
    {
               $matriculados = Evaldocente::find() 
			        ->where(['cedula_docente' => $this->iddocente])
				->andWhere(['!=', 'materia_id', 1])
				->andWhere(['tipo' =>  null])
				->andWhere(['periodo_id' => $this->idper])->all();
				#->andWhere(['<', 'CalifFinal', 7])->all();


		$total_reprobados = $matriculados?count($matriculados):0;
       		return $total_reprobados;
    }

 public function getEvaDocenteEvaPar()
    {
               $matriculados = Evaldocente::find() 
			        ->where(['cedula_docente_evaluador' => $this->iddocente])
				->andWhere(['=', 'materia_id', 1])
				->andWhere(['tipo' =>  'PAR'])
				->andWhere(['periodo_id' => $this->idper])->all();
				#->andWhere(['<', 'CalifFinal', 7])->all();


		$total_Evaldocente = $matriculados?count($matriculados):0;
       		return $total_Evaldocente;
    }
/*
 			0 => 'ASISTENCIA DOCENTE - CLASES',
                        1 => 'TUTORIAS DE CLASES',
                        2 => 'OTRAS ACTIVIDADES',
                        3 => 'TUTORIAS DE COMPLEXIVO',
                        4 => 'TUTORIAS DE TRABAJO DE INTEGRACION CURRICULAR',
*/
public function getLecciDocente($HemiN,$TutoriaN)
    {
                $totalfirmas = LeccionarioDocente::find()
                            ->where([
                                'iddocente' => $this->iddocente,
			        'idcurso'=> $this->id,
                                'idper' => $this->idper,
                                'tutoria_docente' => $TutoriaN,
                                'hemisemestre' => $HemiN, 
                            ])
                            ->andWhere(['IS NOT', 'ip_fin', null]) 
                            ->all();

		$total_Leccionario =  $totalfirmas?count($totalfirmas):0;
       		return $total_Leccionario;
    }
public function getLecciDocenteO($HemiN,$TutoriaN)
    {
                $totalfirmas = LeccionarioDocente::find()
                            ->where([
                                'iddocente' => $this->iddocente,
			    //    'idcurso'=> $this->id,
                                'idper' => $this->idper,
                                'tutoria_docente' => $TutoriaN,
                                'hemisemestre' => $HemiN, 
                            ])
                          //  ->andWhere(['IS NOT', 'ip_fin', null]) 
                            ->all();

		$total_Leccionario =  $totalfirmas?count($totalfirmas):0;
       		return $total_Leccionario;
    }
public function getLecciDocenteFuera($HemiN,$TutoriaN)
    {
                $totalfirmasI = LeccionarioDocente::find()
                            ->where([
                                'iddocente' => $this->iddocente,
			        'idcurso'=> $this->id,
                                'idper' => $this->idper,
                                'tutoria_docente' => $TutoriaN,
                                'hemisemestre' => $HemiN, 
                            ])
                            ->andWhere(['ip_ingreso' => 'FUERA DEL CAMPUS'])
                            ->all();
 		$totalfirmasF = LeccionarioDocente::find()
                            ->where([
                                'iddocente' => $this->iddocente,
			        'idcurso'=> $this->id,
                                'idper' => $this->idper,
                                'tutoria_docente' => $TutoriaN,
                                'hemisemestre' => $HemiN, 
                            ])
                            ->andWhere(['ip_fin' => 'FUERA DEL CAMPUS'])
                            ->all();

		$total_LeccionarioI =  $totalfirmasI?count($totalfirmasI):0;
		$total_LeccionarioF =  $totalfirmasF?count($totalfirmasF):0;
		$total_Leccionario = $total_LeccionarioI+$total_LeccionarioF;
       		return $total_Leccionario;
    }
	public function getSubirHemi($hemi)
    {  
		$matriculados = DetalleMatricula::find()
							->where(['idcurso' => $this->id, 'estado'=> 1])->all();
		$subida =0;
		$cont=0;
	
                if($matriculados)
		{
				foreach($matriculados as $matri) {
					
					        
						$subida += $matri?$matri->getPromedionotasAudi($hemi):0;
						
					$cont+=1;	
					if($cont>=3)
						if($subida!=0)
							break;
	
					if($cont==20)
						break;

				}
			
		}

			if($subida>0)
				$estado=1;
			else
				$estado=0;

               		return  $estado;
    }
	public function getSubirHemiSNNA()
    {  
		$matriculados = DetalleMatricula::find()
							->where(['idcurso' => $this->id, 'estado'=> 1])->all();
		$subida =0;
		$cont=0;
	
                if($matriculados)
		{
				foreach($matriculados as $matri) {
					
					        
						$subida += $matri?$matri->getPromedionotasAudiSNNA():0;
						
					$cont+=1;	
						
					if($cont>=2)
						if($subida!=0)
							break;

					if($cont==10)
						break;
				}
			
		}

			if($subida>0)
				$estado=1;
			else
				$estado=0;

               		return  $estado;
    }

public function getSubirNotasAlumno()
    {  
		$matriculados = DetalleMatricula::find()
							->where(['idcurso' => $this->id, 'estado'=> 1])->all();
		$subida =0;
		$cont=0;
		$matriculados = DetalleMatricula::find()
							->where(['idcurso' => $this->id, 'estado'=> 1])->all();

		$total_matriculados = $matriculados?count($matriculados):0;

                if($matriculados)
		{
				foreach($matriculados as $matri) {
					
					$notaH = Notasalumnoasignatura::find()
							->where(['iddetalle' => $matri->id])->one();
					if($notaH)
					    $cont+=1;	
						
					if($cont>=5)
						break;


									
				}
			
		}

			if($cont>=5)
				$estado=1;
			else
				$estado=0;


					if($cont<=$total_matriculados)
						$estado=1;

               		return  $estado;
    }



}
