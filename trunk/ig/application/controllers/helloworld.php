<?php
class HelloWorld extends CI_Controller{

	function __construct(){

		// Загружаем родительский контроллер

		parent::__construct();

	}

	function index(){
echo "FFFRFFFF<br>";
		$data['title']='My first application created with Code Igniter';

		$data['message']='Hello world!';

		// Загружаем вьювер «helloworld»

		$this->load->view('helloworld',$data);

	}

}
