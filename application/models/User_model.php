<?php
class User_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}

	public function login($username = '', $password = '') {

		// $sql->query("SELECT * FROM User where username = $username && password = $password");
    	
    	$data['username'] = $username;
    	$query = $this->db->get_where('user', array(
    		'username' => $username, 
    		//'password' => password_hash($password, PASSWORD_BCRYPT),
    	));
    	var_dump(password_hash($password, PASSWORD_BCRYPT));
    	if($query->num_rows() === 1)
    		if(password_verify($password,$query->row()->password)) return $query->row()->password;
    	return false; 	
  	}

  	public function register($username ='', $password = '', $role = 'user') {
    	$query = $this->db->get_where('user', array('username' => $username));
    	if($query->num_rows() !== 0) return "Duplicated User";
    	$data = array(
      		'username' => $username,
      		'password' => password_hash($password, PASSWORD_BCRYPT),
      		'role' => $role,
    	);
    	$result = $this->db->insert('user',$data);
    	if($result){
    		return $this->db->get_where('user', array('id' => $this->db->insert_id()))->row()->password;
    	}
  	}

  	public function validate_cookie($username = '', $password = ''){
  		$query = $this->db->get_where('User', array('username' => $username, 'password' => $password));
  		if($query->num_rows() === 1)
  			return true;
  		return false;
  	}

  	public function get_role($username = ''){
  		$query = $this->db->get_where('user', array('username' => $username));
  		if($query->num_rows() == 0) return NULL;
  		return $query->row()->role;
  	}

  	public function get_all_users(){
  		return $this->db->query("SELECT * FROM user")->result();
  	}

  	public function update($id, $data){
  		$this->db->update('user', array( 'username' => $data['username'], 'password' => password_hash($data['password'], PASSWORD_BCRYPT), 'role' => $data['role']));
  	}
}
