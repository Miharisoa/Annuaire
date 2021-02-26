<?php

require('Connection.php');
require('Entity/Section.php');
require('Entity/Category.php');
require('Entity/Sheet.php');
require('Model/SectionModel.php');
require('Model/CategoryModel.php');
require('Model/SheetModel.php');


class Controller
{
    private $sectionModel;
    private $categoryModel;
    private $sheetModel;

    public function __construct()
    {
        $this->sectionModel = new SectionModel();
        $this->categoryModel = new CategoryModel();
        $this->sheetModel = new SheetModel();
    }

    function homeAction($message = null)
    {
        require 'IndexView.php';
        $template = new IndexView();
        if (!is_null($message)) {
            $info = $message;
            $template->setInfo($info);
        }
        $sections = $this->sectionModel->getSections();
        $sectionCategories = $this->categoryModel->getCategories();
        $categoryId = null;
        if (isset($_GET['category'])) {
            $categoryId = $_GET['category'];
        }
        $sheets = $this->sheetModel->getAll($categoryId);
        //require('homeView.php');
        $template->setCategoryId($categoryId);
        $template->setSections($sections);
        $template->setSectionCategories($sectionCategories);
        $template->setSheets($sheets);
        $template->makeContent();
        $template->getView();
    }

    function createCardAction()
    {
        require 'FormCardView.php';
        $template = new FormCardView();
        //$categories = $this->categoryModel->getCategories();
        $allCategories = $this->categoryModel->getAll();
        $template->setAllCategories($allCategories);

        if (isset($_POST['label']) && isset($_POST['description']) && isset($_POST['categories'])) {
            $label = $_POST['label'];
            $description = $_POST['description'];
            $cardCategories = $_POST['categories'];
            $sheet = new Sheet();
            $sheet->setLabel($label);
            $sheet->setDescription($description);
            $sheet->setCategories($cardCategories);
            $rep = $this->sheetModel->insert($sheet);
            if ($rep == true) {
                $info = "Fiche sauvegardé avec succès";
                $template->setInfo($info);
            } else {
                $error = "Il y a une erreur lors de l'enregistrement";
                $template->setError($error);
            }
        }
        //require('createCardView.php');
        $template->makeContent();
        $template->getView();
    }


    function manageSectionAction()
    {
        require 'CategoryView.php';
        $template = new CategoryView();
        //$page = 0;
        if (isset($_POST['taf_section'])) {
            $action = $_POST['taf_section'];
            if ($action == "createsection" && isset($_POST['label'])) {
                $label = $_POST['label'];
                if (strlen($label) < 1) {
                    $error = "Le nom d'une section est obligatoire";
                    $template->setError($error);
                }
                else {
                    $nouvelleSection = new Section();
                    $nouvelleSection->setLabel($label);
                    $this->sectionModel->insert($nouvelleSection);
                    $info = "Nouvelle section enregistrée";
                    $template->setInfo($info);
                }
            } elseif ($action == "updatesection" && isset($_POST['id']) && isset($_POST['label'])) {
                $id = $_POST['id'];
                $label = $_POST['label'];
                $this->sectionModel->update($id, $label);
                $info = "Section mise à jour";
                $template->setInfo($info);
            }
        }

        $sections = $this->sectionModel->getSections();
        $categories = $this->categoryModel->getCategories();
        $total = $this->categoryModel->getTotal();

        $template->setTotal($total);
        $template->setCategories($categories);
        $template->setSections($sections);
        $template->makeContent();
        $template->getView();

    }

    function manageCategoryAction($message = null)
    {
        require 'CategoryView.php';
        $template = new CategoryView();
        if (!is_null($message)) {
            $info = $message;
            $template->setInfo($info);
        }

        if (isset($_POST['taf_category'])) {
            $taf = $_POST['taf_category'];
            if ($taf == "create" && isset($_POST['label']) && isset($_POST['section'])) {
                $label = $_POST['label'];
                $section_id = $_POST['section'];
                $category = new Category();
                $category->setLabel($label);
                $category->setSection($section_id);
                $this->categoryModel->insert($category);
                $info = "Catégorie enregistrée avec succès";
                $template->setInfo($info);
            } elseif ($taf == "update" && isset($_POST['id']) && isset($_POST['label']) && isset($_POST['section'])) {
                $id = $_POST['id'];
                $label = $_POST['label'];
                $section_id = $_POST['section'];
                $this->categoryModel->update($id, $label, $section_id);
                $info = "Catégorie mise à jour avec succès";
                $template->setInfo($info);
            }
        }

        $sections = $this->sectionModel->getSections();
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $template->setPage($page);
        }

        $total = $this->categoryModel->getTotal();
        $categories = $this->categoryModel->getCategories($template->getPage());
        $template->setTotal($total);
        $template->setCategories($categories);
        $template->setSections($sections);
        $template->makeContent();
        $template->getView();

    }

    function removeCategoryAction()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $response = $this->categoryModel->delete($id);

            if ($response) {
                $info = "Catégorie supprimée";
            } else {
                $error = "Il y a une erreur lors de la suppression";
            }

        } else {
            $error = "Identifiant introuvable";
        }

    }

    function removeSheetAction()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $response = $this->sheetModel->delete($id);
            if ($response) {
                return true;
            }
        }
        return false;
    }

    function updateSheetAction()
    {
        if (isset($_POST['id']) && isset($_POST['label']) && isset($_POST['description'])) {
            $id = $_POST['id'];
            $label = $_POST['label'];
            $description = $_POST['description'];
            $this->sheetModel->update($id, $label, $description);
            return true;
        }
        return false;

    }

    function removeSheetCategoryAction()
    {
        $catId = $_GET['cat'];
        $sheetId = $_GET['sheet_id'];
        $this->sheetModel->removeCategory($sheetId, $catId);
        return true;
    }

    function addSheetCategoryAction()
    {
        if (isset($_POST['id']) && isset($_POST['categories'])) {
            $categories = $_POST['categories'];
            $sheet = $_POST['id'];
            foreach ($categories as $catId) {
                $this->sheetModel->addCategory($sheet, $catId);
            }
        } else echo 'There is some errors';
    }
}
