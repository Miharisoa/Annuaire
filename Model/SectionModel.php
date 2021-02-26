<?php
/*
require('../Entity/Section.php');
require('../Entity/Category.php');
require('../Connection.php');*/

class SectionModel
{
    protected $table;

    function __construct()
    {
        $this->table = "section";
    }

    function insert(Section $section)
    {
        $db = Connection::getInstance();
        $sql = "INSERT INTO `".$this->table."`(`label`) VALUES ('".$section->getLabel()."')";
        return ($db->query($sql) == TRUE) ? json_encode(['response'=>true]): json_encode(['response'=>false]);
    }

    function getSections()
    {
        $db = Connection::getInstance();
        $sql = "SELECT s.id,s.label FROM $this->table AS s ORDER BY s.label";
        $req = $db->query($sql);

        $sections = [];
        while ($data = $req->fetch()) {
            $section = new Section();
            $section->setId($data['id']);
            $section->setLabel($data['label']);

            $subquery = "SELECT id, label FROM categorie AS c WHERE  c.section_id=" . $section->getId();
            $response_subquery = $db->query($subquery);
            $categories = [];
            while ($item = $response_subquery->fetch()) {
                $category = new Category();
                $category->setLabel($item['label']);
                $category->setId($item['id']);
                $categories[] = $category;
            }

            $section->setCategories($categories);
            $sections[] = $section;
            //var_dump($data);
        }
        return $sections;
    }

    function update($id, $label)
    {
        $db = Connection::getInstance();
        $sql = "UPDATE `$this->table` SET `label`='$label' WHERE id=$id;";
        if ($db->query($sql) == TRUE) return true;
        else return false;
    }

}

/*$sectionModel = new SectionModel();
$section = new Section();
$section->setLabel("Informatique3");
//echo $sectionModel->insert($section);

$sections = $sectionModel->getSections();
//$req = $sectionModel->getSections();


echo json_encode($sections);*/