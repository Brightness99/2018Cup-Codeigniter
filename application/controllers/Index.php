<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

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
	
	function __construct() {
        parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model("Common_model");
		$current_company = $this->uri->segment(1);
		
		if($current_company != ''){
			$this->getCompanyProfile($current_company);
		}
		
		if ($this->session->userdata('user_id') != null) {
            $this->getUserProfile($this->session->userdata('user_id'));
        } 
		
		$_SESSION['current_device'] = $_SERVER['HTTP_USER_AGENT'];
	}
	
	public function index(){
		if ($this->session->userdata('user_id') != null) {
			redirect(base_url().$_SESSION['company']->url.'/bienvenida');
		}
		if($this->uri->segment(1) == ''){
			$data = array();

			$this->home_template->set('title', 'Set Company');
			$this->home_template->load('home_template', 'contents' , 'index/index', $data);
		}else{
			$data = array();

			$this->login_template->set('companyDetails', $_SESSION['company']);			
			$this->login_template->set('Login', 'Login');
			$this->login_template->load('login_template', 'contents' , 'login/index', $data);
		}
	}
	
	public function login()
	{
		if ($this->session->userdata('user_id') != null) {
			redirect(base_url().$_SESSION['company']->url.'/bienvenida');
		}else{
			if($this->uri->segment(1) == ''){
				$data = array();

				$this->home_template->set('title', 'Set Company');
				$this->home_template->load('home_template', 'contents' , 'index/index', $data);
			}else{
				$data = array();
				$condiciones			= $this->Common_model->getLoginCondtionText();
				$data['condiciones1'] 	= $condiciones->rules;
				$data['condiciones2'] 	= $_SESSION['company']->bases_condiciones;
		
				$this->login_template->set('companyDetails', $_SESSION['company']);			
				$this->login_template->set('Login', 'Login');
				
				$data['condition_text'] = $this->Common_model->getLoginCondtionText();
				$this->login_template->load('login_template', 'contents' , 'login/index', $data);
			}
		}
	}
	
	public function doLogin()
	{
		if($this->input->post()){
			$user_name 	= $this->input->post('user_name');
			$user_pass 	= md5($this->input->post('user_pass'));
			
			$loginDo = $this->Common_model->loginCheck($user_name,$user_pass);
			
			if($loginDo){
				if($loginDo->acepto_bases == 1){
					$this->session->set_userdata('user_id', $loginDo->id_empleado);
					$_SESSION['logged_in_company'] = $_SESSION['company']->url;
					
					$userDetails = $this->Common_model->getUserDetails($this->session->userdata('user_id'));
					
					echo json_encode(array('status' => 1,'message' => 'Ingreso correcto...','acepto_bases' => $userDetails->acepto_bases));
				}else{
					$userDetails = $this->Common_model->getUserDetails($this->session->userdata('user_id'));
					
					echo json_encode(array('status' => 1,'message' => 'Ingreso correcto...','acepto_bases' => $userDetails->acepto_bases,'user_id' => $loginDo->id_empleado));
				}				
			}else{
				echo json_encode(array('status' => 0,'message' => 'Nombre de usuario o contraseña incorrectos.'));
			}
		}else{
			echo json_encode(array('status' => 0,'message' => 'Something went wrong.'));
		}
		
		die;
	}
	
	public function bienvenida(){
		$this->loginCheck();
		
		$data = array();
		
		$this->front_template->set('title', 'Bienvenida');
		$userDetails = $this->Common_model->getUserDetails($userId);
		$data['companyDetails'] = $this->Common_model->getCompanyDetails($_SESSION['logged_in_company']);
		$data['userDetails'] 	= $this->Common_model->getUserDetails($this->session->userdata('user_id'));
		
		$data['trivia'] 		= $this->Common_model->getTrivia();
		$data['user_trivia_answer'] = array();
		
		//$selectedAns = array();
		
		
		if($data['trivia']){
			$data['has_trivia'] = true;
			
			foreach($data['trivia'] as $tri){
				$triviaId = $tri['id_trivia'];
				if($rc = $this->Common_model->getTriviaUserRelation($triviaId)){
					
					$data['trivia_user_record'] = true;
				}else{
					$data['trivia_user_record'] = false;
					break;
				}
			}
		}else{
			$data['has_trivia'] = false;
			$data['trivia_user_record'] = false;
		}
		
		$this->load->view('index/bienvenida', $data);
	}
	
	public function pronosticos(){
		$this->loginCheck();
		
		$data = array();
		
		$this->front_template->set('title', 'Pronósticos');
		
		$group = $this->uri->segment(3);
		
		if($group == 'fase'){
			$group = array(1,2,3,4,5,6,7,8);
		}
		elseif($group == 'octavos'){
			$group = array(9);
		}
		elseif($group == 'cuartos'){
			$group = array(10);
		}
		elseif($group == 'semi-final'){
			$group = array(11);
		}
		elseif($group == 'final'){
			$group = array(12,13);
		}
		else{
			redirect(base_url().$this->uri->segment(1).'/pronosticos/fase');
		}
		
		$data['userDetails'] 	= $this->Common_model->getUserDetails($this->session->userdata('user_id'));
		$data['matchList'] 		= $this->Common_model->getMatchListByGroup($group);
		$data['user_point'] 	= $this->Common_model->getUserTotalPoints($this->session->userdata('user_id'));
		$data['user_rank'] 		= $this->Common_model->getUserRank($this->session->userdata('user_id'));
		$data['group'] 			= $group;
		
		$data['jugadores'] 			= $this->Common_model->getJugadores();
		$data['equipos'] 			= $this->Common_model->getEquipos();
		$data['first_match_time'] 	= $this->Common_model->getFirstMatchTime();
		$data['jugadores_answer'] 	= $this->Common_model->getJugadoresAnswerTime($this->session->userdata('user_id'));
		$data['equipos_answer'] 	= $this->Common_model->getEquiposAnswerTime($this->session->userdata('user_id'));

		if($data['matchList']){
			foreach($data['matchList'] as $k => $match){
				$data['matchList'][$k]['user_prediction'] = $this->Common_model->getUserPrediction($match['match_id'],$this->session->userdata('user_id'));
			}
		}
		
		$data['trivia'] 		= $this->Common_model->getTrivia();
		$data['user_trivia_answer'] = array();
		
		//$selectedAns = array();
		
		if($data['trivia']){
			$data['has_trivia'] = true;
			
			foreach($data['trivia'] as $tri){
				$triviaId = $tri['id_trivia'];
				if($rc = $this->Common_model->getTriviaUserRelation($triviaId)){
					
					$data['trivia_user_record'] = true;
				}else{
					$data['trivia_user_record'] = false;
					break;
				}
			}
		}else{
			$data['has_trivia'] = false;
			$data['trivia_user_record'] = false;
		}
		
		$data['companyDetails'] = $this->Common_model->getCompanyDetails($_SESSION['logged_in_company']);
		
		$this->load->view('index/pronosticos', $data);
	}
	
	
	public function ranking(){
		$this->loginCheck();
		
		$data = array();
		
		$this->front_template->set('title', 'Ranking');
		$userDetails = $this->Common_model->getUserDetails($userId);
		$data['companyDetails'] = $this->Common_model->getCompanyDetails($_SESSION['logged_in_company']);
		
		$data['groups_point'] 		= $this->Common_model->getUserRankByGroup(array(1,2,3,4,5,6,7,8));
		$data['octavos_point'] 		= $this->Common_model->getUserRankByGroup(array(9));
		$data['cuartos_point'] 		= $this->Common_model->getUserRankByGroup(array(10));
		$data['semi_final_point'] 	= $this->Common_model->getUserRankByGroup(array(11));
		$data['final_point'] 		= $this->Common_model->getUserRankByGroup(array(12,13));
		
		$data['groups_point_trivias']		= $this->Common_model->getUserRankByTrivia(array(1));
		$data['octavos_point_trivias']		= $this->Common_model->getUserRankByTrivia(array(2));
		$data['cuartos_point_trivias']		= $this->Common_model->getUserRankByTrivia(array(3));
		$data['semi_final_point_trivias']	= $this->Common_model->getUserRankByTrivia(array(4));
		$data['final_point_trivias']		= $this->Common_model->getUserRankByTrivia(array(5));

		
		$data['user_rank'] 			= $this->Common_model->getUserRank($this->session->userdata('user_id'));
		$data['user_point'] 		= $this->Common_model->getUserTotalPoints($this->session->userdata('user_id'));
		$data['userDetails'] 		= $this->Common_model->getUserDetails($this->session->userdata('user_id'));
		
		$data['jugadores'] 			= $this->Common_model->getJugadores();
		$data['equipos'] 			= $this->Common_model->getEquipos();
		$data['first_match_time'] 	= $this->Common_model->getFirstMatchTime();
		$data['jugadores_answer'] 	= $this->Common_model->getJugadoresAnswerTime($this->session->userdata('user_id'));
		$data['equipos_answer'] 	= $this->Common_model->getEquiposAnswerTime($this->session->userdata('user_id'));
		
		$data['general_group_ranking'] 	= $this->Common_model->getGeneralRanking(null);
		
		
		$data['trivia'] 		= $this->Common_model->getTrivia();
		$data['user_trivia_answer'] = array();
		
		//$selectedAns = array();
		
		if($data['trivia']){
			$data['has_trivia'] = true;
			
			foreach($data['trivia'] as $tri){
				$triviaId = $tri['id_trivia'];
				if($rc = $this->Common_model->getTriviaUserRelation($triviaId)){
					
					$data['trivia_user_record'] = true;
				}else{
					$data['trivia_user_record'] = false;
					break;
				}
			}
		}else{
			$data['has_trivia'] = false;
			$data['trivia_user_record'] = false;
		}
		
		$this->load->view('index/ranking', $data);
	}
	
	
	public function trivias(){
		$this->loginCheck();
		
		$data = array();
		
		$this->front_template->set('title', 'Trivias');
		$data['companyDetails'] = $this->Common_model->getCompanyDetails($_SESSION['logged_in_company']);
		$data['userDetails'] 	= $this->Common_model->getUserDetails($this->session->userdata('user_id'));
		$data['trivia'] 		= $this->Common_model->getTrivia();
		
		$data['user_trivia_answer'] = array();
		
		$selectedAns = array();
		
		if($data['trivia']){
			$triviaId = $data['trivia'][0]['id_trivia'];
			$data['trivia_user_record'] = $this->Common_model->getTriviaUserRelation($triviaId);
			
			if($data['trivia_user_record']){
				$data['user_trivia_answer'] = $this->Common_model->getUserTriviaAnswer();
				
				if($data['user_trivia_answer']){
					foreach($data['user_trivia_answer'] as $selectedAnswer){
						$selectedAns[] = $selectedAnswer['id_respuesta'];
					}
				}
				
				$data['user_trivia_answer'] = $selectedAns;
			}
		}
		
		$data['group']  = null;
		
		if($data['trivia']){
			foreach($data['trivia'] as $trivia){
				$group = $trivia['id_fase'];
				
				if(in_array($group,array(1,2,3,4,5,6,7,8))){
					$data['group'] = 'Fase';
				}
				elseif(in_array($group,array(9))){
					$data['group'] = 'Octavos';
				}
				elseif(in_array($group,array(10))){
					$data['group'] = 'Cuartos';
				}
				elseif(in_array($group,array(11))){
					$data['group'] = 'Semi-final';
				}
				elseif(in_array($group,array(12,13))){
					$data['group'] = 'Final';
				}
				
				break;
			}
		}
		
		$data['jugadores'] 			= $this->Common_model->getJugadores();
		$data['equipos'] 			= $this->Common_model->getEquipos();
		$data['first_match_time'] 	= $this->Common_model->getFirstMatchTime();
		$data['jugadores_answer'] 	= $this->Common_model->getJugadoresAnswerTime($this->session->userdata('user_id'));
		$data['equipos_answer'] 	= $this->Common_model->getEquiposAnswerTime($this->session->userdata('user_id'));
		
		$data['trivia'] 		= $this->Common_model->getTrivia();
		$data['user_trivia_answer'] = array();
		
		$selectedAns = array();
		
		if($data['trivia']){
			$triviaId = $data['trivia'][0]['id_trivia'];
			$data['trivia_user_record'] = $this->Common_model->getTriviaUserRelation($triviaId);
			
			if($data['trivia_user_record']){
				$data['user_trivia_answer'] = $this->Common_model->getUserTriviaAnswer();
				
				if($data['user_trivia_answer']){
					foreach($data['user_trivia_answer'] as $selectedAnswer){
						$selectedAns[] = $selectedAnswer['id_respuesta'];
					}
				}
				
				$data['user_trivia_answer'] = $selectedAns;
			}
		}
		
		if($data['trivia']){
			$data['has_trivia'] = true;
			
			foreach($data['trivia'] as $tri){
				$triviaId = $tri['id_trivia'];
				if($rc = $this->Common_model->getTriviaUserRelation($triviaId)){
					
					$data['trivia_user_record'] = true;
				}else{
					$data['trivia_user_record'] = false;
					break;
				}
			}
		}else{
			$data['has_trivia'] = false;
			$data['trivia_user_record'] = false;
		}

		$this->load->view('index/trivias', $data);
	}
	
	
	public function respuestas_anteriores(){
		$this->loginCheck();
		
		$data = array();
		
		$this->front_template->set('title', 'Trivias');
		$userDetails = $this->Common_model->getUserDetails($userId);
		$data['companyDetails'] 	= $this->Common_model->getCompanyDetails($_SESSION['logged_in_company']);
		$data['userDetails'] 		= $this->Common_model->getUserDetails($this->session->userdata('user_id'));
		$data['triviaList'] 		= $this->Common_model->getUserTriviaRecord($this->session->userdata('user_id'));
		
		$data['user_all_answer'] 	= $this->Common_model->getUserTriviaAnswer();
		
		$data['user_trivia_answer'] = array();
		
		if($data['user_all_answer']){
			foreach($data['user_all_answer'] as $selectedAnswer){
				$selectedAns[] = $selectedAnswer['id_respuesta'];
			}
			
			$data['user_trivia_answer'] = $selectedAns;
		}
		
		$data['jugadores'] 			= $this->Common_model->getJugadores();
		$data['equipos'] 			= $this->Common_model->getEquipos();
		$data['first_match_time'] 	= $this->Common_model->getFirstMatchTime();
		$data['jugadores_answer'] 	= $this->Common_model->getJugadoresAnswerTime($this->session->userdata('user_id'));
		$data['equipos_answer'] 	= $this->Common_model->getEquiposAnswerTime($this->session->userdata('user_id'));
		
		if($data['trivia']){
			$data['has_trivia'] = true;
			
			foreach($data['trivia'] as $tri){
				$triviaId = $tri['id_trivia'];
				if($rc = $this->Common_model->getTriviaUserRelation($triviaId)){
					
					$data['trivia_user_record'] = true;
				}else{
					$data['trivia_user_record'] = false;
					break;
				}
			}
		}else{
			$data['has_trivia'] = false;
			$data['trivia_user_record'] = false;
		}

		
		$this->load->view('index/respuestas_anteriores', $data);
	}

	
	public function change_profile(){
		$this->loginCheck();
		
		if($this->input->post()){
			$image_path = null;
			
			if($_FILES){
				$path_parts = pathinfo($_FILES["user_image"]["name"]);
				$filepath = $_FILES['user_image']['tmp_name'];
				$image = imagecreatefromstring(file_get_contents($filepath));
				
				// Rotate image correctly!
				$exif = exif_read_data($filepath);
				if (!empty($exif['Orientation'])) {
					switch ($exif['Orientation']) {
						case 1: // nothing
							break;
						case 2: // horizontal flip
							imageflip($image, IMG_FLIP_HORIZONTAL);
							break;
						case 3: // 180 rotate left
							$image = imagerotate($image, 180, 0);
							break;
						case 4: // vertical flip
							imageflip($image, IMG_FLIP_VERTICAL);
							break;
						case 5: // vertical flip + 90 rotate right
							imageflip($image, IMG_FLIP_VERTICAL);
							$image = imagerotate($image, -90, 0);
							break;
						case 6: // 90 rotate right
							$image = imagerotate($image, -90, 0);
							break;
						case 7: // horizontal flip + 90 rotate right
							imageflip($image, IMG_FLIP_HORIZONTAL);
							$image = imagerotate($image, -90, 0);
							break;
						case 8:    // 90 rotate left
							$image = imagerotate($image, 90, 0);
							break;
					}
				}
				
				imagejpeg($image, $_FILES['user_image']['tmp_name'], 40);
				
				//$image_path = 'd_'.time() . "." . $path_parts['extension'];
				//$move_result = move_uploaded_file($_FILES['user_image']['tmp_name'], '../img/results/' . $image_path);
				//
				//
				$fileName 	= md5(strtotime(@date('y-m-d h:i:s')) . '_' . rand(111111, 999999));
				$fileType 	= pathinfo($_FILES['user_image']["name"], PATHINFO_EXTENSION);

	
				if (move_uploaded_file($_FILES['user_image']["tmp_name"], 'img/empleadosPerfil/' . $fileName . '.' . $fileType)) {
					$image_path = $fileName.'.'.$fileType;
				}
			}
			
			if($image_path != null){
				$array = array(
							   'imagen_perfil' 	=> $image_path
							   );
				
				$this->Common_model->updateUser($array);
				$this->session->set_flashdata('item','Tu perfil fue actualizado.' );
			}
			
			if($_POST['password'] != '' && ($_POST['password'] == $_POST['cpassword'])){
				$array = array(
						   'pass' 	=> md5($_POST['password']),
						   );
			
				$this->Common_model->updateUser($array);
				$this->session->set_flashdata('item','Tu perfil fue actualizado.' );
			}
				
			
			redirect(base_url().$this->uri->segment(1).'/edit-profile');
		}else{
			redirect(base_url().$this->uri->segment(1));
		}
	}
	
	public function accept_login_condition(){
		$array = array(
					'acepto_bases' 	=> $_POST['accept'],
					);
		
		if($this->Common_model->updateUserCondition($array,$_POST['user_id'])){
			$this->session->set_userdata('user_id', $_POST['user_id']);
			$_SESSION['logged_in_company'] = $_SESSION['company']->url;
			
			echo json_encode(array('status' => 1));
			
		}else{
			echo json_encode(array('status' => 0));
		}
		
		die; 
	}
	
	
	public function get_ajax_ranking(){
		$this->loginCheck();
		
		$data = array();

		$group = $_POST['group'];
		
		if($group == 'group'){
			$group = array(1,2,3,4,5,6,7,8);
		}
		elseif($group == 'octavos'){
			$group = array(9);
		}
		elseif($group == 'cuartos'){
			$group = array(10);
		}
		elseif($group == 'semi'){
			$group = array(11);
		}
		elseif($group == 'final'){
			$group = array(12,13);
		}
		elseif($group == 'general'){
			$group = null;
		}
		
		$data['group_ranking'] 	= $this->Common_model->getGeneralRanking($group);
		
		$this->load->view('index/get_ajax_ranking', $data);
	}
	
	public function get_ajax_ranking_mobile(){
		$this->loginCheck();
		
		$data = array();

		$group = $_POST['group'];
		
		if($group == 'group'){
			$group = array(1,2,3,4,5,6,7,8);
		}
		elseif($group == 'octavos'){
			$group = array(9);
		}
		elseif($group == 'cuartos'){
			$group = array(10);
		}
		elseif($group == 'semi'){
			$group = array(11);
		}
		elseif($group == 'final'){
			$group = array(12,13);
		}
		elseif($group == 'general'){
			$group = null;
		}
		
		$data['group_ranking'] 	= $this->Common_model->getGeneralRanking($group);
		
		$this->load->view('index/get_ajax_ranking_mobile', $data);
	}
	
	public function edit_profile(){
		$this->loginCheck();
		
		$data = array();
		
		$data['userDetails'] 	= $this->Common_model->getUserDetails($this->session->userdata('user_id'));
		$data['companyDetails'] = $this->Common_model->getCompanyDetails($_SESSION['logged_in_company']);
		
		$data['jugadores'] 			= $this->Common_model->getJugadores();
		$data['equipos'] 			= $this->Common_model->getEquipos();
		$data['first_match_time'] 	= $this->Common_model->getFirstMatchTime();
		$data['jugadores_answer'] 	= $this->Common_model->getJugadoresAnswerTime($this->session->userdata('user_id'));
		$data['equipos_answer'] 	= $this->Common_model->getEquiposAnswerTime($this->session->userdata('user_id'));
		
		$data['trivia'] 		= $this->Common_model->getTrivia();
		$data['user_trivia_answer'] = array();
		
		//$selectedAns = array();
		
		if($data['trivia']){
			$data['has_trivia'] = true;
			
			foreach($data['trivia'] as $tri){
				$triviaId = $tri['id_trivia'];
				if($rc = $this->Common_model->getTriviaUserRelation($triviaId)){
					
					$data['trivia_user_record'] = true;
				}else{
					$data['trivia_user_record'] = false;
					break;
				}
			}
		}else{
			$data['has_trivia'] = false;
			$data['trivia_user_record'] = false;
		}
		
		$this->load->view('index/edit-profile', $data);
	}
	
	public function save_guardar(){
		if($this->input->post()){
			if($_POST['pList'] != ''){
				$player_data = array(
								'empleado_id' 	=> $this->session->userdata('user_id'),
								'jugador_id'	=> $_POST['pList'],
								'wwhen'			=> @date('Y-m-d')
								);
			
				$this->Common_model->savePlayerPrediction($player_data);
				$this->Common_model->savePlayerPredictionLog($player_data);
			}
			
			if($_POST['cList'] != ''){
				$country_data = array(
									'empleado_id' 	=> $this->session->userdata('user_id'),
									'equipo_id'		=> $_POST['cList'],
									'wwhen'			=> @date('Y-m-d')
									);
				
				$this->Common_model->saveCountryPrediction($country_data);
				$this->Common_model->saveCountryPredictionLog($country_data);
			}
			
			echo 1;
		}else{
			
		}
		
		die;
	}
	
	public function save_prediction(){
		if($this->input->post()){
			foreach($_POST['home_predection'] as $k => $home_prediction){
				if($home_prediction != '' && $_POST['away_predection'][$k] != ''){
					$this->Common_model->saveUserPrediction($k,$home_prediction,$_POST['away_predection'][$k],$this->session->userdata('user_id'));
					$this->Common_model->saveUserPredictionLog($k,$home_prediction,$_POST['away_predection'][$k],$this->session->userdata('user_id'));
				}
			}
			
			echo 1;
		}else{
			
		}
		
		die;
	}
	
	public function premios(){
		$this->loginCheck();
		
		$data = array();
		
		$data['userDetails'] 	= $this->Common_model->getUserDetails($this->session->userdata('user_id'));
		$data['companyDetails'] = $this->Common_model->getCompanyDetails($_SESSION['logged_in_company']);
		$data['premios_pc'] 	= $this->Common_model->getPremios($_SESSION['company']->id_empresa,'21');
		$data['premios_mb'] 	= $this->Common_model->getPremios($_SESSION['company']->id_empresa,'22');
		
		$data['jugadores'] 			= $this->Common_model->getJugadores();
		$data['equipos'] 			= $this->Common_model->getEquipos();
		$data['first_match_time'] 	= $this->Common_model->getFirstMatchTime();
		$data['jugadores_answer'] 	= $this->Common_model->getJugadoresAnswerTime($this->session->userdata('user_id'));
		$data['equipos_answer'] 	= $this->Common_model->getEquiposAnswerTime($this->session->userdata('user_id'));
		
		$data['trivia'] 		= $this->Common_model->getTrivia();
		$data['user_trivia_answer'] = array();
		
		//$selectedAns = array();
		
		if($data['trivia']){
			$data['has_trivia'] = true;
			
			foreach($data['trivia'] as $tri){
				$triviaId = $tri['id_trivia'];
				if($rc = $this->Common_model->getTriviaUserRelation($triviaId)){
					
					$data['trivia_user_record'] = true;
				}else{
					$data['trivia_user_record'] = false;
					break;
				}
			}
		}else{
			$data['has_trivia'] = false;
			$data['trivia_user_record'] = false;
		}
		
		$this->load->view('index/premios', $data);
	}
	
	public function bases_condiciones(){
		$this->loginCheck();
		
		$data = array();
		
		$data['userDetails'] 	= $this->Common_model->getUserDetails($this->session->userdata('user_id'));
		$data['companyDetails'] = $this->Common_model->getCompanyDetails($_SESSION['logged_in_company']);
		$condiciones			= $this->Common_model->getLoginCondtionText();
		$data['condiciones1'] 	= $condiciones->rules;
		$data['condiciones2'] 	= $_SESSION['company']->bases_condiciones;

		$data['jugadores'] 			= $this->Common_model->getJugadores();
		$data['equipos'] 			= $this->Common_model->getEquipos();
		$data['first_match_time'] 	= $this->Common_model->getFirstMatchTime();
		$data['jugadores_answer'] 	= $this->Common_model->getJugadoresAnswerTime($this->session->userdata('user_id'));
		$data['equipos_answer'] 	= $this->Common_model->getEquiposAnswerTime($this->session->userdata('user_id'));
		
		$data['trivia'] 		= $this->Common_model->getTrivia();
		$data['user_trivia_answer'] = array();
		
		//$selectedAns = array();
		
		if($data['trivia']){
			$data['has_trivia'] = true;
			
			foreach($data['trivia'] as $tri){
				$triviaId = $tri['id_trivia'];
				if($rc = $this->Common_model->getTriviaUserRelation($triviaId)){
					
					$data['trivia_user_record'] = true;
				}else{
					$data['trivia_user_record'] = false;
					break;
				}
			}
		}else{
			$data['has_trivia'] = false;
			$data['trivia_user_record'] = false;
		}
		
		$this->load->view('index/bases_condiciones', $data);
	}
	
	public function logout(){
		$this->session->unset_userdata('user_id');
		session_unset($_SESSION['company']);
		
		redirect(base_url().$this->uri->segment(1));
	}
	
	function loginCheck(){
		if ($this->session->userdata('user_id') == null) {
			redirect(base_url().$_SESSION['company']->url);
        }else{
			return true;
		}
	}
	
	function getCompanyProfile($company){
		if(isset($_SESSION['logged_in_company'])){
			if($_SESSION['logged_in_company'] != $company){
				redirect(base_url().$_SESSION['logged_in_company']);
			}
		}else{
			$companyDetails = $this->Common_model->getCompanyDetails($company);
		
			if($companyDetails){
				$_SESSION['company'] = $companyDetails;
				return true;
			}else{
				redirect(base_url());
			}
		}
	}
	
	public function save_trivia(){
		if($this->input->post()){
			$triviaData = array(
								'user_id' 	=> $this->session->userdata('user_id'),
								'id_trivia' => $_POST['id_trivia'],
								'wwhen'		=> @date('Y-m-d h:i:s')
								);
			
			$this->Common_model->saveTriviaLog($triviaData);
			
			$answerIds = $_POST['id_pregunta'];
			
			unset($_POST['id_trivia']);
			unset($_POST['id_pregunta']);
			
			$k = 0;
			foreach($_POST as $post){
				$answerData = array(
									'id_respuesta' 	=> $post,
									'id_empleado'	=> $this->session->userdata('user_id'),
									'wwhen'			=> @date('Y-m-d h:i:s')
									);
				
				$this->Common_model->saveAnswerLog($answerData);
				
				$k++;
			}
			
			echo json_encode(array('status' => 1,'message' => 'Saved'));
		}else{
			echo json_encode(array('status' => 0,'message' => 'Something went wrong.'));
		}
		
		die;
	}
	
	function getUserProfile($userId){
		$userDetails = $this->Common_model->getUserDetails($userId);
		
		if($userDetails){
			$this->front_template->set('id_empleado', $userDetails->id_empleado);
			$this->front_template->set('user', $userDetails->user);
			$this->front_template->set('nombre', $userDetails->nombre);
			$this->front_template->set('apellido', $userDetails->apellido);
			$this->front_template->set('imagen_perfil', $userDetails->imagen_perfil);
		}else{
			redirect(base_url());
		}
	}
}
