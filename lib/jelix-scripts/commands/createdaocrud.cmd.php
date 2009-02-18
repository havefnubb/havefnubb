<?php
/**
* @package     jelix-scripts
* @author      Jouanneau Laurent
* @contributor Bastien Jaillot (bug fix)
* @contributor Loic Mathaud (typos fix)
* @copyright   2007 Jouanneau laurent, 2008 Loic Mathaud
* @link        http://www.jelix.org
* @licence     GNU General Public Licence see LICENCE file or http://www.gnu.org/licenses/gpl.html
*/


class createdaocrudCommand extends JelixScriptCommand {

    public  $name = 'createdaocrud';
    public  $allowed_options=array('-profile'=>true);
    public  $allowed_parameters=array('module'=>true, 'table'=>true, 'ctrlname'=>false);

    public  $syntaxhelp = "[-profile name] MODULE TABLE [CTRLNAME]";
    public  $help=array(
        'fr'=>"
    Crée un nouveau contrôleur de type jControllerDaoCrud, reposant sur un jdao et un jform.

    -profile (facultatif) : indique le profil à utiliser pour se connecter à
                           la base et récupérer les informations de la table
    MODULE : le nom du module où stocker le contrôleur
    TABLE : le nom de la table SQL
    CTRLNAME (facultatif) : nom du contrôleur (par défaut, celui de la table)",

        'en'=>"
    Create a new controller jControllerDaoCrud

    -profile (optional) : indicate the name of the profile to use for the
                        database connection.

    MODULE: name of the module where to create the crud
    TABLE : name of the SQL table
    CTRLNAME (optional) : name of the controller."
    );


    public function run(){

        jxs_init_jelix_env();
        $path= $this->getModulePath($this->_parameters['module']);

        $table = $this->getParam('table');
        $ctrlname = $this->getParam('ctrlname', $table);

        if(file_exists($path.'controllers/'.$ctrlname.'.classic.php')){
            throw new Exception("controller '".$ctrlname."' already exists");
        }

        $agcommand = jxs_load_command('createdao');
        $options = array();
        $profile = '';
        if ($this->getOption('-profile')) {
            $profile = $this->getOption('-profile');
            $options = array('-profile'=>$profile);
        }
        $agcommand->init($options,array('module'=>$this->_parameters['module'], 'name'=>$table,'table'=>$table));
        $agcommand->run();

        $agcommand = jxs_load_command('createform');
        $agcommand->init(array(),array('module'=>$this->_parameters['module'], 'form'=>$table,'dao'=>$table));
        $agcommand->run();

        $this->createDir($path.'controllers/');
        $params = array('name'=>$ctrlname, 
                'module'=>$this->_parameters['module'],
                'table'=>$table,
                'profile'=>$profile);
        
        $this->createFile($path.'controllers/'.$ctrlname.'.classic.php','controller.daocrud.tpl',$params);
    }
}

