<?php
namespace Medal\Controller;

use Medal\Model\Land;
use Medal\Model\Person;
use RuntimeException;

abstract class PersonController implements Controller
{
    /** @var \Medal\Library\View */
    protected $view;
    public function setView(\Medal\Library\View $view)
    {
        $this->view = $view;
    }
    
    abstract public function showAllAction();
      
    public function editAction()
    {
        //$id = intval($_GET['ID']);
        $id = intval($this->getID());
        $person = Person::findFirst($id);
        $land = Land::findAll('');
        $this->view->setArray($land, "LAND");
        $this->view->setVars([
            'PERSON' => $person,
        ]);
        
    }
    
    public function deleteAction()
    {
        try {
            $id = $this->getID();
            Person::delete($id);
            $this->view->setVars(['deleted' => 'Successfully deleted']);
        }
        catch (RuntimeException $e) {
            $this->view->setVars([
                'deleted' => 'Unable to delete',
            ]);
        }
    }
    
    public function addNewAction()
    {
        $land = Land::findAll('');
        $this->view->setArray($land, "LAND");
    }
    
   
    
    private function mapRequestToObject()
    {
        $person = new Person();
        $person->ID = $_POST['ID'];
        $person->Name = $_POST['name'];
        $person->Vorname = $_POST['vorname'];
        $person->Curriculum_DEU = $_POST['curriculum_deu'];
        $person->Curriculum_PL = $_POST['curriculum_pl'];
        $person->NationID = $_POST['nationID'];
        $person->Image = $_POST['image'];
        $person->Image_Copyright = $_POST['image_copyright'];
        $person->Wikipedia = $_POST['wikipedia'];
        $person->Medaleur = (empty($_POST['medaleur']) ? '0' : '1');
        $person->Autor = (empty($_POST['autor']) ? '0' : '1');
        $person->Deleted = '0';
        return $person;
    }
    
    public function editDoneAction()
    {
        $person = $this->mapRequestToObject();
        
        try
        {
            $person->save();
            $this->view->setVars([
                'updated' => "Successfull",
            ]);
        }
        catch (RuntimeException $e) {
            $errorMessage = $e->getMessage();
            $this->view->setVars([
                'updated' => $errorMessage,
            ]);
        }
    }
    
    public function addNewDoneAction()
    {
        $person = $this->mapRequestToObject();
        
//         var_dump($person);
        try 
        {
            $person->save();
        }
        catch (RuntimeException $e) {
            $errorMessage = $e->getMessage();
            $this->view->setVars([
                'added' => $errorMessage,
            ]);
        }
    }
    
    private function getID()
    {
        $url      = (isset($_GET['_url']) ? $_GET['_url'] : '');
        $urlParts = explode('/', $url);
        return $urlParts[2];
    }
    
   
    
}