 <?php

 class Pages extends CI_Controller
 {
 	
 	public function view($page = 'login')
 	
 	{
 		if(!file_exists(APPPATH.'views/users/'.$page.'.php'))
 			show_404();
 
	 	$data['title'] = ucfirst($page);

	 	$this->load->view('templates/header');
	 	$this->load->view('users/'.$page, $data);
 		$this->load->view('templates/footer');
 	}
}