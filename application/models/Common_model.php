<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends CI_Model {
	
    function __construct()
    {
        parent::__construct(); 
    }
	
	function saveData($data){
		if($this->db->insert('forms', $data)){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}
	
	function saveTriviaLog($data){
		if($this->db->insert('user_trivia_log', $data)){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}
	
	function savePlayerPrediction($data){
		$this->db->delete('pronosticos_goleador', array('empleado_id' => $this->session->userdata('user_id')));

		if($this->db->insert('pronosticos_goleador', $data)){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}
    
    function savePlayerPredictionLog($data){
		if($this->db->insert('pronosticos_goleador_log', $data)){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}
	
	function getJugadoresAnswerTime($id){
		$query = $this->db->select("pronosticos_goleador.*")
					->from("pronosticos_goleador")
					->where('empleado_id',$this->session->userdata('user_id'))
					->get();

		if($query->num_rows() > 0){
			return $query->row();
		}
	}
	
	function saveCountryPrediction($data){
		$this->db->delete('pronosticos_campeon', array('empleado_id' => $this->session->userdata('user_id')));
		
		if($this->db->insert('pronosticos_campeon', $data)){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}
    
    function saveCountryPredictionLog($data){		
		if($this->db->insert('pronosticos_campeon_log', $data)){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}
	
	function getEquiposAnswerTime($id){
		$query = $this->db->select("pronosticos_campeon.*")
					->from("pronosticos_campeon")
					->where('empleado_id',$this->session->userdata('user_id'))
					->get();

		if($query->num_rows() > 0){
			return $query->row();
		}
	}
	
	function saveAnswerLog($data){
		if($this->db->insert('trivias_respuestas_empleados', $data)){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}
	
	function loginCheck($user,$pass){
		if(isset($_SESSION['company']->id_empresa)){
			$query = $this->db->select("empleados.*")
						->from("empleados")
						->join('empresas E', 'E.id_empresa = empleados.id_empresa', 'inner')
						->where('empleados.user',$user)
						->where('empleados.pass',$pass)
						->where('E.id_empresa',$_SESSION['company']->id_empresa)
						->where('empleados.state','1')
						->get();
						
			if($query->num_rows() > 0){
				$userDetails =  $query->row();
				
				$log_data = array(
								'user_id' => $userDetails->id_empleado,
								'login_time' => @date('Y-m-d h:i:s')
								);
				
				$this->db->insert('user_log_history', $log_data);
				
				return $userDetails;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	function getCompanyDetails($company){
		$query = $this->db->select("empresas.*")
					->from("empresas")
					->where('empresas.url',$company)
					->get();

		if($query->num_rows() > 0){
			$result = $query->row();
			
			$queryImages = $this->db->select("empresas_imagenes.*")
					->from("empresas_imagenes")
					->where('empresas_imagenes.id_empresa',$result->id_empresa)
					->get();
			
			if($queryImages->num_rows() > 0){
				$images =  $queryImages->result_array();
				
				$pc_slider = array();
				$mobile_slider = array();
				
				foreach($images as $img){					
					if($img['tipo_imagen'] == 11){
						$result->pc_logo = base_url().'img/'.$company.'/'.$img['nombre_archivo'];
					}
					
					if($img['tipo_imagen'] == 12){
						$result->mobile_logo = base_url().'img/'.$company.'/'.$img['nombre_archivo'];
					}
					
					if(($img['tipo_imagen'] >= 41 && $img['tipo_imagen'] <= 81) && $img['tipo_imagen']%2 != 0 ){
						$pc_slider[] = base_url().'img/'.$company.'/'.$img['nombre_archivo'];
					}
					
					if(($img['tipo_imagen'] >= 42 && $img['tipo_imagen'] <= 82) && $img['tipo_imagen']%2 == 0 ){
						$mobile_slider[] = base_url().'img/'.$company.'/'.$img['nombre_archivo'];
					}
				}
				
				$result->pc_slider = $pc_slider;
				$result->mobile_slider = $mobile_slider;
			}
			
			return $result;
		}else{
			return false;
		}
	}
	
	function getUserDetails($user){
		$query = $this->db->select("empleados.*")
					->from("empleados")
					->where('empleados.id_empleado',$user)
					->get();

		if($query->num_rows() > 0){
			return $query->row();
		}else{
			return false;
		}
	}
	
	function getMatchListByGroup($group){
		$query = $this->db->select("partidos.*,EQ.name as home_team_name,EQ.country as home_team_country,EQU.name as away_team_name,EQU.country as away_team_country")
					->from("partidos")
					->join('equipos EQ', 'EQ.team_id = partidos.home_team_id', 'inner')
					->join('equipos EQU', 'EQU.team_id = partidos.away_team_id', 'inner')
					->where_in('partidos.stage_id',$group)
					->get();

		if($query->num_rows() > 0){
			$result =  $query->result_array();
			
			foreach($result as $k => $match){
				$query = $this->db->select("puntos_empleados.puntos_empleado_valor")
					->from("puntos_empleados")
					->where('puntos_empleados.empleado_id',$this->session->userdata('user_id'))
					->where('puntos_empleados.partido_id',$match['match_id'])
					->get();

				if($query->num_rows() > 0){
					$result[$k]['point'] = $query->row();
				}else{
					$result[$k]['point'] = null;
				}
			}

			return $result;
		}else{
			return false;
		}
	}
	
	public function getUserTotalPoints($userId){
		$query = $this->db->select("SUM(puntos_empleados.puntos_empleado_valor) as total_point")
					->from("puntos_empleados")
					->where('puntos_empleados.empleado_id',$userId)
					->get();

		if($query->num_rows() > 0){
			return $query->row();
		}else{
			return false;
		}
	}
	
	public function getTriviaUserRelation($trivia){
		$query = $this->db->select("*")
					->from("user_trivia_log")
					->where('id_trivia',$trivia)
					->where('user_id',$this->session->userdata('user_id'))
					->get();

		if($query->num_rows() > 0){
			return $query->row();
		}else{
			return false;
		}
	}
	
	
	public function getUserTriviaAnswer(){
		$query = $this->db->select("trivias_respuestas_empleados.id_respuesta")
					->from("trivias_respuestas_empleados")
					->join('trivias_respuestas TR', 'TR.id_respuesta = trivias_respuestas_empleados.id_respuesta', 'inner')					
					->where('trivias_respuestas_empleados.id_empleado',$this->session->userdata('user_id'))
					->get();
					//echo $this->db->last_query();die;
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}
	
	public function getUserRank(){
		$query = $this->db->select("empleado_id,SUM(puntos_empleados.puntos_empleado_valor) as total_point")
					->from("puntos_empleados")
					->join('empleados EM', 'EM.id_empleado = puntos_empleados.empleado_id', 'left')
					->where('EM.id_empresa',$_SESSION['company']->id_empresa)
					->group_by('empleado_id')
					->order_by('total_point','DESC')
					->get();

		if($query->num_rows() > 0){
			$result = $query->result_array();
			
			$rank = '0';
			
			foreach($result as $k => $r){
				if($r['empleado_id'] == $this->session->userdata('user_id')){
					$rank = $k+1;
				}
			}
			
			return $rank;
		}else{
			return 0;
		}
	}
	
	public function getGeneralRanking($groupId){
		if($groupId != null){
			$query = $this->db->select("empleado_id,SUM(puntos_empleados.puntos_empleado_valor) as total_point,nombre,apellido,imagen_perfil")
					->from("puntos_empleados")
					->join('partidos P', 'P.match_id = puntos_empleados.partido_id', 'inner')
					->join('empleados EM', 'EM.id_empleado = puntos_empleados.empleado_id', 'inner')
					->where_in('P.stage_id',$groupId)
					->where('EM.id_empresa',$_SESSION['company']->id_empresa)					
					->group_by('empleado_id')
					->order_by('total_point','DESC')
					->get();
		}else{
			$query = $this->db->select("empleado_id,SUM(puntos_empleados.puntos_empleado_valor) as total_point,nombre,apellido,imagen_perfil")
					->from("puntos_empleados")
					->join('partidos P', 'P.match_id = puntos_empleados.partido_id', 'left')
					->join('empleados EM', 'EM.id_empleado = puntos_empleados.empleado_id', 'left')
					->where('EM.id_empresa',$_SESSION['company']->id_empresa)					
					->group_by('empleado_id')
					->order_by('total_point','DESC')
					->get();
		}
					
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}
	
	function getUserRankByGroup($groupId){
		$query = $this->db->select("empleado_id,SUM(puntos_empleados.puntos_empleado_valor) as total_point")
					->from("puntos_empleados")
					->join('partidos P', 'P.match_id = puntos_empleados.partido_id', 'inner')
					->join('empleados EM', 'EM.id_empleado = puntos_empleados.empleado_id', 'inner')
					->where_in('P.stage_id',$groupId)
					->where('EM.id_empresa',$_SESSION['company']->id_empresa)		
					->group_by('puntos_empleados.empleado_id')
					->order_by('total_point','DESC')
					->get();

		$resultArray = array();
		$resultArray['total_point'] = 0;
		$resultArray['rank'] = 0;			
				
		if($query->num_rows() > 0){
			$result = $query->result_array();

			$rank = 0;

			foreach($result as $k => $r){
				if($r['empleado_id'] == $this->session->userdata('user_id')){
					$rank = $k+1;
					
					$resultArray['total_point'] = $r['total_point'];
					$resultArray['rank'] = $rank;
				}
			}
			
			return $resultArray;
		}else{
			return $resultArray;
		}
	}
	
	function getUserRankByTrivia($triviaId){
		$userId = $this->session->userdata('user_id');
		$query = $this->db->select("empleado_id,SUM(puntos_empleados.puntos_empleado_valor) as total_point")
					->from("puntos_empleados")
					->where_in('trivia_id',$triviaId)
					->where_in('empleado_id',$userId)
					->get();
					
		$resultArray = array();
		$resultArray['total_point'] = 0;
				
		if($query->num_rows() > 0){
			$result = $query->result_array();

			foreach($result as $k => $r){
				if($r['empleado_id'] == $this->session->userdata('user_id')){
					
					$resultArray['total_point'] = $r['total_point'];
				}
			}
			
			return $resultArray;
		}else{
			return $resultArray;
		}
	}
	
	function getUserPrediction($mId,$userId){
		$query = $this->db->select("pronosticos.*")
					->from("pronosticos")
					->where_in('user_id',$userId)
					->where_in('match_id',$mId)
					->get();

		if($query->num_rows() > 0){
			return $query->row();
		}else{
			return false;
		}
	}
	
	function removePrediction(){
		return $this->db->delete('pronosticos', array('user_id' => $this->session->userdata('user_id')));
	}
	
	function saveUserPredictionLog($mId,$home_p,$away_p,$user){
		$data = array(
					'user_id' 			=> $user,
					'match_id' 			=> $mId,
					'home_prediction' 	=> $home_p,
					'away_prediction' 	=> $away_p,
					'log_date'			=> @date('Y-m-d h:i:s')
					);
		
		if($this->db->insert('user_prediction_log', $data)){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}
	
	function saveUserPrediction($mId,$home_p,$away_p,$user){
		$query = $this->db->select("pronosticos.*")
					->from("pronosticos")
					->where('user_id',$user)
					->where('match_id',$mId)
					->get();

		if($query->num_rows() > 0){
			$result = $query->row();
			
			$this->db->where('prediction_id',$result->prediction_id);
			
			$data = array(
					'home_goals' => $home_p,
					'away_goals' => $away_p,
					);
			
			return $this->db->update('pronosticos',$data);
		}else{
			$data = array(
					'user_id' => $user,
					'match_id' => $mId,
					'home_goals' => $home_p,
					'away_goals' => $away_p,
					);
				
			if($this->db->insert('pronosticos', $data)){
				return $this->db->insert_id();
			}else{
				return false;
			}
		}
	}
	
	public function getTrivia(){
		$date = @date('Y-m-d H:i:s');
		$query = $this->db->select("*")
					->from("trivias")
					->join('trivias_preguntas TP', 'TP.id_trivia = trivias.id_trivia', 'inner')
					->where('inicio <=', $date)
					->where('vencimiento >=', $date)
					->where('finalizada', '0')
					->get();
					
		if($query->num_rows() > 0){
			$result = $query->result_array();
			
			if($result){
				foreach($result as $k => $trivia){
					$query2 = $this->db->select("*")
						->from("trivias_respuestas")
						->where('id_pregunta', $trivia['id_pregunta'])
						->order_by("RAND()")
						->get();
						
					if($query2->num_rows() > 0){
						$result[$k]['answer'] = $query2->result_array();
					}
				}
			}
			
			return $result;
		}else{
			return 0;
		}
	}
	
	public function updateUser($data){
		$this->db->where('id_empleado',$this->session->userdata('user_id'));

		if($this->db->update('empleados',$data)){
			return true;
		}else{
			return false;
		}
	}
	
	public function updateUserCondition($data,$userId){
		$this->db->where('id_empleado',$userId);

		if($this->db->update('empleados',$data)){
			return true;
		}else{
			return false;
		}
	}
	
	function getLoginCondtionText(){
		$query = $this->db->select("mecanica_juego.*")
					->from("mecanica_juego")
					->get();

		if($query->num_rows() > 0){
			return $query->row();
		}else{
			return false;
		}
	}
	
	function getUserTriviaRecord($userId){
		$query = $this->db->select("DISTINCT(id_trivia)")
					->from("user_trivia_log")
					->where('user_id', $userId)
					->get();

		if($query->num_rows() > 0){
			$triviaList = $query->result_array();
			
			if($triviaList){
				foreach($triviaList as $key => $trivia){
					
					$query = $this->db->select("*")
								->from("trivias")
								->where('trivias.id_trivia', $trivia['id_trivia'])
								->get();
								
					if($query->num_rows() > 0){
						$triviaList[$key]['trivia_details'] = $query->row();
					}
					
					$query = $this->db->select("TP.*")
								->from("trivias")
								->join('trivias_preguntas TP', 'TP.id_trivia = trivias.id_trivia', 'inner')
								->where('trivias.id_trivia', $trivia['id_trivia'])
								->get();
			
					if($query->num_rows() > 0){
						$triviaList[$key]['trivia_question'] = $query->result_array();
						
						if(count($triviaList[$key]['trivia_question']) > 0){
							foreach($triviaList[$key]['trivia_question'] as $k => $question){
								$query2 = $this->db->select("*")
									->from("trivias_respuestas")
									->where('id_pregunta', $question['id_pregunta'])
									->get();
									
								if($query2->num_rows() > 0){
									$triviaList[$key]['trivia_question'][$k]['answer'] = $query2->result_array();
								}
							}
						}
					}
				}
			}
			
			return $triviaList;
		}else{
			return false;
		}
	}
	
	function getPremios($company,$id){		
		$query = $this->db->select("*")
					->from("empresas_imagenes")
					->where('empresas_imagenes.tipo_imagen',$id)
					->where('empresas_imagenes.id_empresa',$company)
					->get();

		if($query->num_rows() > 0){
			return $query->row();
		}
	}
	
	function getBasesCondiciones($company,$id){		
		$query = $this->db->select("*")
					->from("empresas_imagenes")
					->where('empresas_imagenes.tipo_imagen',$id)
					->where('empresas_imagenes.id_empresa',$company)
					->get();

		if($query->num_rows() > 0){
			return $query->row();
		}
	}
	
	function getJugadores(){		
		$query = $this->db->select("*")
					->from("jugadores")
					->order_by("nombre_jugador", "asc")
					->get();

		if($query->num_rows() > 0){
			return $query->result_array();
		}
	}
	
	function getEquipos(){		
		$query = $this->db->select("*")
					->from("equipos")
					->where('team_id > 0')
					->where('team_id < 33')
					->order_by("name", "asc")
					->get();

		if($query->num_rows() > 0){
			return $query->result_array();
		}
	}
	
	function getFirstMatchTime(){
		$query = $this->db->select("kickoff")
					->from("partidos")
					->where('match_id',1)
					->get();

		if($query->num_rows() > 0){
			return $query->row();
		}
	}
}