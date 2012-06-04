<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends CI_Controller {

	function index(){
         redirect('stats/analyze');
	}
    
    function analyze(){
        //Load library.
        $this->load->library('StatsIgniter');
        
        //Configure filetypes to scan (without .)
        $arr_filetypes = array(
                                'php',
                                'js',
                                'css'
        );
        
        //Configure other directories to scan (relatives to index.php)
        $arr_dirs = array(
                                '../js',
                                '../css'
        );
        
        //Configurate class
        $options = array(
            'controllers_dir'   =>  'controllers',              //Path to controllers
            'models_dir'        =>  'models',                   //Path to models
            'views_dir'         =>  'views',                    //Path to views
            'language'          =>  'english',                  //Options: spanish | english
            'show_controllers'  =>  true,                       //Show controllers stats
            'show_models'       =>  true,                       //Show models stats
            'show_views'        =>  true,                       //Show views stats
            'count_lines'       =>  true,                       //Count line numbers
            'file_types'        =>  $arr_filetypes,  
            'other_dir'         =>  $arr_dirs,
        );
        
        //Call class
        $stats = new StatsIgniter($options);
        
        //Obtain files
        $arr_files = $stats->analyze();
        
        //Configure view
        $data = array();
        $data['language'] = $stats->get_language();
        $data['arr_files'] = $arr_files;
        
        //Load view
        $this->load->view('stats/analyze', $data);
    }
    
    function ajax_load_code(){
        //Load helper
        $this->load->helper('file');
        
        //Obtain the file to show.
        $file_data = read_file(APPPATH.urldecode($this->input->post('file')));
        
        //Configure view
        $data = array();
        $data['file_data'] = $file_data;
        $data['type'] = $this->input->post('type');
        
        //Load view
        $this->load->view('stats/ajax_load_code', $data);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */