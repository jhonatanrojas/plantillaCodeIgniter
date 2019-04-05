<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proyectos extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct(){

		parent::__construct();
	
		$this->load->library('session');
		$this->load->model('proyectomodel');
		$this->load->model('requerimientosmodel');
		$this->load->model('profesionModel');
		$this->load->model('datospersonasmodel');
		$this->load->model('personasmodel');
		if(!is_logged_in()){
			redirect('index.php/login');
			
		}
	}


	public function index()
	{
		$nombreUsuario = $this->session->userdata('user_data');
		$this->load->view('layout/header');
		$this->load->view('layout/nav');
		$User['nombreUser']=$nombreUsuario['nombre'];
		$this->load->view('layout/navar',$User);
		
		$this->load->view('layout/scriptjs');
		$this->load->view('requerimiento/requerimientoview');
		$this->load->view('layout/footer');
	}




public function registrar()
{
    $nombreUsuario = $this->session->userdata('user_data');
    $this->load->view('layout/header');
    $this->load->view('layout/nav');
    $User['nombreUser']=$nombreUsuario['nombre'];
    $this->load->view('layout/navar',$User);
    
    $this->load->view('layout/scriptjs');
    $this->load->view('proyectos/registrar');

}


public function getProfesion(){

	$result=$this->profesionModel->getProfesion($this->input->post_get('codigo'));

	$this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($result));

}

public function getDatosPersonasJSON(){

	if(!empty($this->input->post_get('cedula'))) { 

		//ver si tine prroyectos
		$resultProyectos=$this->proyectomodel->findProyectosPersonas($this->input->post('cedula'));
		if(!$resultProyectos['result']){

			$obj=$this->datospersonasmodel->getDataPersona($this->input->post_get('cedula'));

		}else{
			$obj = new stdClass;
			$obj->response=array(
                "status"=>"ok",
                "http_code"=>404
            );
           $obj->data=array(
         
          );
          $obj->error=array("code"=>"","message"=>"");
          $obj->comments="Esta persona ya posee un proyecto registrado";

             

               $obj;
		}

	$this->output
        ->set_content_type('application/json')
		->set_output(json_encode($obj));
		
	}

}

public function  regitrarPaso1(){

	if(empty($_SESSION['cedula'])){

	 $_SESSION['cedula']= $this->input->post('cedula');
	}


	$resultProyectos=$this->proyectomodel->findProyectosPersonas($this->input->post('cedula'));
	if(!$resultProyectos['result']){

	//consultar si esta en la tabla personas
	$resultPersonas=$this->personasmodel->find($this->input->post('cedula'));

	//Actualizar
	if($resultPersonas['result']){

		
		$datos = array(
			'nacionaliidad' 	=> $this->input->post('nacionaliidad'),
			'nombres' 			=> $this->input->post('nombres'),
			'apellidos' 		=> $this->input->post('apellidos'),
			'email' 			=> $this->input->post('email'),
			'cedula'			=> $this->input->post('cedula'),
			'sexo' 				=> $this->input->post('sexo'),
			'direccion'			=> $this->input->post('direccion'),
			'estado_id' 		=> $this->input->post('estado_id'),
			'municipio_id' 		=> $this->input->post('municipio_id'),
			'parroquia_id' 		=> $this->input->post('parroquia_id'),
			'v_carnet' 			=> $this->input->post('v_carnet'),
			'v_social' 			=> $this->input->post('v_social'),
			'fecha_nac' 		=> $this->input->post('fecha_nac'), 
			'posee_carnet'		=> $this->input->post('posee_carnet'), 
			'telefono'			=> $this->input->post('telefono'), 
			'telefono2'			=> $this->input->post('telefono2'), 
			'profesion' 		=> $this->input->post('profesion'), 
			'institucion_id'	=> 0,
			'principal'			=> true
			);

			
		$result=$this->personasmodel->actualizar($datos,$resultPersonas['data']->id);

		if($result){

			$response=array(
				"result"	=>true,
				"mensaje"	=>"Se guardaron los cambios exitosamente"
			);


		}else{

			$response=array(
				"result"	=>false,
				"mensaje"	=>"Ocurrio un error, no se logro Guardar"
			);
		}
	
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($response));

	}else{
	//insertar

	$datos = array(
		'nacionaliidad' 	=> $this->input->post('nacionaliidad'),
		'nombres' 			=> $this->input->post('nombres'),
		'apellidos' 		=> $this->input->post('apellidos'),
		'email' 			=> $this->input->post('email'),
		'cedula'			=> $this->input->post('cedula'),
		'sexo' 				=> $this->input->post('sexo'),
		'direccion'			=> $this->input->post('direccion'),
		'estado_id' 		=> $this->input->post('estado_id'),
		'municipio_id' 		=> $this->input->post('municipio_id'),
		'parroquia_id' 		=> $this->input->post('parroquia_id'),
		'v_carnet' 			=> $this->input->post('v_carnet'),
		'v_social' 			=> $this->input->post('v_social'),
		'fecha_nac' 		=> $this->input->post('fecha_nac'), 
		'posee_carnet'		=> $this->input->post('posee_carnet'), 
		'telefono'			=> $this->input->post('telefono'), 
		'telefono2'			=> $this->input->post('telefono2'), 
		'profesion' 		=>$this->input->post('profesion'), 
		'institucion_id'	=>0,
		'principal'			=> true
		);

					$result=$this->personasmodel->registrar($datos);



					$response=array(
					"result"	=>true,
					"mensaje"	=>"Se guardaron los cambios Exitosamente",
					);



					$this->output
					->set_content_type('application/json')
					->set_output(json_encode($response));

	}


			}else{
			//tiene proyectos
			$response=array(
			"result"	=>false,
			"mensaje"	=>"Ya existe un requerimiento registrado con esta cedula".$this->input->post('cedula') ,
			);

			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
			}

		}

		public function  regitrarPaso2(){

		$actualizar=false;
		
		$datatemp=$this->session->tempdata('requerimiento_id');
		if(isset($datatemp))
		{
			 $actualizar=true;
		}else{
			$actualizar=false;

		}
	
		$user=	$this->session->userdata('user_data');

		$actualizar=false;
		if(!empty($_SESSION['requerimiento_id'])){
		
			$actualizar=true;
			
		   }
		   if($actualizar){

			$datos = array(
				'descripcion'           =>$this->input->post('descripcion'),
				'categoria_id'         =>$this->input->post('categoria_id'),
				'sub_categoria_id'      =>$this->input->post('sub_categoria_id'),
				'user_id'           =>$user['id']						


		);

		//Actualizo paso
		$this->proyectomodel->update(array("nombre"=>$this->input->post('nombrep')),
		
		$_SESSION['proyecto_id']);


			$Urequerimiento=$this->requerimientosmodel->update($datos,$_SESSION['requerimiento_id']);
			if($Urequerimiento){
				$response=array(
					"result"	=>true,
					"mensaje"	=>"Se Actualizaron los datos exitosamente",
			
					);
				}

			else{

				$response=array(
					"result"	=>false,
					"mensaje"	=>"Ocurrio un Error al intentar actualizar",
			
					);

			}

			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));

		    }else{


		  	   
			$datos = array(
						'descripcion'           =>$this->input->post('descripcion'),
						'categoria_id'         =>$this->input->post('categoria_id'),
						'sub_categoria_id'      =>$this->input->post('sub_categoria_id'),
						'user_id'           =>$user['id']						
		
	 
				);

				
			$idrequerimiento=$this->requerimientosmodel->registrar($datos);

			$resultpersonas=$this->personasmodel->find($_SESSION['cedula']);
			$idpersonas=$resultpersonas['data']->id;


			//aqui se relaciona las persona con el requerimiento
			$this->requerimientosmodel->requerimientoPersona(
				array(
				'requerimiento_id'=>$idrequerimiento,
				'persona_id'=>$idpersonas

			));

			

			if($idrequerimiento>0){

				$idproyecto=$this->proyectomodel->registrar(
					array("nombre"=>$this->input->post('nombrep'))
					);
				

			}

			//creo session temporal para los id
			$_SESSION['requerimiento_id']=$idrequerimiento;
			$_SESSION['proyecto_id']=$idproyecto;
			$_SESSION['persona_id']=$idpersonas;
			

			if($idproyecto>0){

			$response=array(
				"result"	=>true,
				"mensaje"	=>"Se guardaron los datos exitosamente",
				"idproyecto"=>$idproyecto,
				"idrequerimiento"=>$idrequerimiento
				);
			}

			
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
		}
		}


		
		public function  regitrarPaso3(){

			$datos = array(
				
				'municipio_id'         =>$this->input->post('municipio_id'),
				'parroquia_id'      =>$this->input->post('parroquia_id'),
				'estado_id'      =>$this->input->post('estado_id'),
				"ente_id"=>   0
				// $this->input->post('inst_responsable')
								


		);

		
		$idrequerimiento=$this->requerimientosmodel->update($datos,$_SESSION['requerimiento_id']);

		$datos=		array(
			"codrif"=>$this->input->post('rif'),
			"numero_rif"=>$this->input->post('numerorif'),
			"nombre_empresa"=>$this->input->post('nombrerazonsocial'),
			"empresa_registrada"=>$this->input->post('registrada'),
			"codigo_situr"=>$this->input->post('codigo_situr'),
			"codigo_sunagro"=>$this->input->post('codigo_sunagro'),
			
		);
		$idproyecto=$this->proyectomodel->update($datos,$_SESSION['proyecto_id']);
	
			
			if($idproyecto>0){
				$response=array(
					"result"	=>true,
					"mensaje"	=>"Se guardaron los datos exitosamente",
					"idproyecto"=>$idproyecto,
					"idrequerimiento"=>$idrequerimiento
					);

			}else{

				$response=array(
					"result"	=>false,
					"mensaje"	=>"Ocurrio un Error al intentar actualizar",
			
					);	
			}

						
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	
		}



		public function getCategoria(){
			$response=	$this->requerimientosmodel->categoriaGet();

			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
			
		
		}

		public function getSubCategoria(){
			$response=	$this->requerimientosmodel->getSubCategoria($this->input->get('id'));

			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
			
		
		}


		
		public function getEstatusProyecto(){
			$response=	$this->proyectomodel->getEstatusProyecto();

			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
			
		
		}
}

