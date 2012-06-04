<?php
    
class StatsIgniter {
    
    //Definimos la configuración.
    protected $_options = array(
        'controllers_dir'   =>  'controllers',              //Path to controllers
        'models_dir'        =>  'models',                   //Path to models
        'views_dir'         =>  'views',                    //Path to views
        'language'          =>  'spanish',                  //Options: spanish | english
        'show_controllers'  =>  true,                       //Show controllers stats
        'show_models'       =>  true,                       //Show models stats
        'show_views'        =>  true,                       //Show views stats
        'count_lines'       =>  true,                       //Count line numbers
        'file_types'        =>  array('php', 'js', 'css'),  //Configure filetypes
        'other_dir' =>  array('../js', '../css'),           //Other folders to scan
    );
    
    protected $_arr_language = array(
            'english' => array(
                'tab_file' => "By File",
                'tab_extension' => "By FileType",
                'table_search' => "Search",
                'table_show' => "Display _MENU_ records",
                'header_extension' => "FileType",
                'header_nombre' => "FileName",
                'header_ruta' => "FilePath",
                'header_num_files' => "# Files",
                'header_num_lineas' => "# Lines",
                'footer_showing' => "Showing _START_ to _END_ of _TOTAL_ entries",
                'footer_filtered' => "(filtered from _MAX_ total entries)",
                'footer_first' => "First",
                'footer_previo' => "Previous",
                'footer_siguiente' => "Next",
                'footer_ultima' => 'Last',
                'footer_total_lineas' => "Total Lines"
            ),
            'spanish' => array(
                'tab_file' => "Por Archivo",
                'tab_extension' => "Por Extensi&oacute;n",
                'table_search' => "Buscar",
                'table_show' => "Mostrar _MENU_ entradas",
                'header_extension' => "Tipo de Archivo",
                'header_nombre' => "Nombre del Archivo",
                'header_ruta' => "Ruta del Archivo",
                'header_num_files' => "N&uacute;mero de Archivos",
                'header_num_lineas' => "N&uacute;m. L&iacute;neas",
                'footer_showing' => "Mostrando del _START_ al _END_ de un total de _TOTAL_ entradas",
                'footer_filtered' => "(filtrados de un total de _MAX_ entradas)",
                'footer_first' => "Primera",
                'footer_previo' => "Ant.",
                'footer_siguiente' => "Sig.",
                'footer_ultima' => '&Uacute;ltima',
                'footer_total_lineas' => "Total de L&iacute;neas"
            )
    );
    
    
    public function __construct($options=array()) {
        //Seteamos las opciones de la app
        foreach($options AS $clave=>$valor){
            $this->_options[$clave] = $valor;
        }
    }
    
    public function analyze(){
        //Sacamos TODOS los archivos.
        $mapa_directorios = $this->_get_map();
        $arr_salida = $this->_get_files($mapa_directorios);
        
        return $arr_salida;       
    }
    
    public function get_language(){
        return $this->_arr_language[$this->_options['language']];
    }
    
    private function _get_num_lines($file){
        //Inicializamos el array de salida
        return @count(file(APPPATH.$file));
    }
    
    private function _get_files($mapa_directorios, $ruta=''){
        //Inicializamos el array de salida.
        $arr_salida = array();
        
        //Cargamos el lector de archivos
        $CI =& get_instance();
        $CI->load->helper('file');
        
        foreach($mapa_directorios as $directorio=>$mapa){
            //Si es un array, seguimos recorriendo
            if (is_array($mapa)){              
                $arr_aux = $this->_get_files($mapa, $ruta."/".$directorio);
                $arr_salida = array_merge($arr_salida, $arr_aux);
            }
            //Si solo es un archivo, lo metemos al array
            else{
                $extension = $this->_get_extension($mapa);
                //Ahora debemos filtrar las extensiones.
                if(in_array($extension, $this->_options['file_types'])){
                    
                    $archivo = array();
                    $archivo['path'] = $ruta."/".$mapa;
                    $archivo['extension'] = $extension;
                    $archivo['name'] = $mapa;
                    //Si nos piden contar las líneas
                    if($this->_options['count_lines']){
                        $archivo['num_lines'] = $this->_get_num_lines($ruta."/".$mapa);    
                    }
                    else{
                        $archivo['num_lines'] = '-';
                    }
                    
                    //Rellenamos la salida.
                    $arr_salida[] = $archivo;    
                }
            }        
        }
        
        //Devolvemos la salida.
        return $arr_salida;
    }
    
    private function _get_map(){
        //Inicializamos el array de salida.
        $arr_salida = array();
        
        //Controladores
        if($this->_options['show_controllers']){
            $arr_salida[$this->_options['controllers_dir']] = $this->_get_directory_map($this->_options['controllers_dir']);
        }
        //Modelos
        if($this->_options['show_models']){
            $arr_salida[$this->_options['models_dir']] = $this->_get_directory_map($this->_options['models_dir']);
        }
        //Views
        if($this->_options['show_views']){
            $arr_salida[$this->_options['views_dir']] = $this->_get_directory_map($this->_options['views_dir']);
        }
        
        //Otros directorios
        foreach($this->_options['other_dir'] AS $dir){
            $arr_salida[$dir] = $this->_get_directory_map($dir);
        }
        
        return $arr_salida;
    }
    
    private function _get_directory_map($directory){
        //Inicializamos el array de salida
        $arr_salida = array();
        
        //Obtenemos la instancia de CI
        $CI =& get_instance();
        //Cargamos la función para trabajar con archivos
        $CI->load->helper('directory');
        
        return directory_map(APPPATH.$directory);
    }
    
    private function _get_extension($file){
        $arr_file = explode(".", $file);
        return end($arr_file);
    }
}
    
?>