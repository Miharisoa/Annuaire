<?php

/*require('../Entity/Section.php');
require('../Entity/Category.php');
require('../Connection.php');*/

class CategoryModel
{
    protected $table;

    function __construct()
    {
        $this->table = "categorie";
    }

    function insert(Category $category)
    {
        $db = Connection::getInstance();
        $sql = "INSERT INTO `".$this->table."`(`label`,`section_id`) VALUES ('".$category->getLabel()."','".$category->getSection()."')";
        return ($db->query($sql) == TRUE) ? json_encode(['response'=>true]): json_encode(['response'=>false]);
    }

    function getTotal()
    {
        $db = Connection::getInstance();
        $sql = "SELECT COUNT(*) AS total FROM `categorie`";
        $nb = 0;
        $req = $db->query($sql);
        while ($data = $req->fetch()) {
            $nb = $data['total'];
        }
        return $nb;
    }

    function getAll()
    {
        $db = Connection::getInstance();
        $sql = "SELECT c.id AS cat_id,c.label AS cat_label, s.label AS section_label, s.id AS section_id 
                FROM $this->table AS c JOIN section as s ON c.section_id = s.id 
                ORDER BY c.label";
        $req = $db->query($sql);
        $categories = [];
        while ($data = $req->fetch()) {
            $category = new Category();
            $category->setLabel($data['cat_label']);
            $category->setId($data['cat_id']);
            $section = new Section();
            $section->setLabel($data['section_label']);
            $section->setId($data['section_id']);
            $category->setSection($section);
            $categories[] = $category;
        }
        return $categories;
    }

    function getCategories($page = 0)
    {
        $db = Connection::getInstance();
        $debut = $page*5;
        $sql = "SELECT c.id AS cat_id,c.label AS cat_label, s.label AS section_label, s.id AS section_id 
                FROM $this->table AS c JOIN section as s ON c.section_id = s.id 
                ORDER BY c.label
                LIMIT $debut,5;";
        $req = $db->query($sql);
        $categories = [];
        while ($data = $req->fetch()) {
            $category = new Category();
            $category->setLabel($data['cat_label']);
            $category->setId($data['cat_id']);
            $section = new Section();
            $section->setLabel($data['section_label']);
            $section->setId($data['section_id']);
            $category->setSection($section);
            $categories[] = $category;
        }
        return $categories;
    }

    function update($id, $label, $section)
    {
        $db = Connection::getInstance();
        $sql = "UPDATE `$this->table` SET `label`='$label', `section_id`=$section  WHERE id=$id;";
        if ($db->query($sql) == TRUE) return true;
        else return false;
    }

    function delete($id) {
        $db = Connection::getInstance();
        $sql = "DELETE FROM `fiche_categorie` WHERE categorie_id = $id";
        $db->exec($sql);
        $subquery = "DELETE FROM `$this->table` WHERE id=$id";
        $db->exec($subquery);
        return true;
    }

}
/*
$categoryModel = new CategoryModel();

$category = new Category();
$category->setLabel("Hardware");
$category->setSection(2);

//echo $categoryModel->insert($category);

$req = $categoryModel->getCategories();

$categories = [];
while ($data = $req->fetch()) {
    $category = new Category();
    $category->setLabel($data['cat_label']);
    $category->setId($data['cat_id']);
    $section = new Section();
    $section->setLabel($data['section_label']);
    $section->setId($data['section_id']);
    $category->setSection($section);
    $categories[] = $category;
}

echo json_encode($categories);
*/


