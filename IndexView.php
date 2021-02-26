<?php

require 'Template.php';

class IndexView extends Template
{
    protected $info = null;

    protected $sections = [];

    protected $sectionCategories = [];

    protected $sheets = [];

    /**
     * @return array
     */
    public function getSheets()
    {
        return $this->sheets;
    }

    /**
     * @param array $sheets
     */
    public function setSheets($sheets)
    {
        $this->sheets = $sheets;
    }

    protected $categoryId;


    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param mixed $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * @return array
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param array $sections
     */
    public function setSections($sections)
    {
        $this->sections = $sections;
    }

    /**
     * @return array
     */
    public function getSectionCategories()
    {
        return $this->sectionCategories;
    }

    /**
     * @param array $sectionCategories
     */
    public function setSectionCategories($sectionCategories)
    {
        $this->sectionCategories = $sectionCategories;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * IndexView constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->content = "";

        $this->scripts = "
            <script>
                function hydraterModalSheet(objet) {
                    $('#update_sheet_id').val(objet.id);
                    $('#update_sheet_label').val(objet.label);
                    $('#update_sheet_description').val(objet.description);
                }
            
                function hydraterModalAddCategory(id){
                    $('#sheet_id').val(id);
                }
            
                function changeTitle(titre) {
                    $('#listing_titre').text(titre);
                }
            </script>
        ";

    }

    public function makeListing()
    {

        $listing = "
            <p class=\"alert alert-light\"><h4>Aucun résultat</h4></p>
        ";

        if (count($this->sheets) > 0) {
            $listing = "
                <div class=\"listing\">
                    <ul class=\"mt-2 list-group\">";

            foreach ($this->sheets as $sheet) {
                $listing .="
                    <li class=\"list-group-item mt-3\">
                         <h4>".$sheet->getLabel()."</h4>
                         <ul class=\"nav small\">
                            <li class=\"nav-item\">
                                <a href=\"#\" onclick=\"hydraterModalAddCategory('".$sheet->getId()."')\"
                                   data-toggle=\"modal\" data-target=\"#addcategory_sheet_form\">
                                    <i class=\"fa fa-plus-square\"></i> Catégories</a>
                            </li>
                ";

                foreach ($sheet->getCategories() as $cat) {
                    $listing .="<li class=\"nav-item text-grey\">
                                            &nbsp; | ".$cat->getLabel();
                    if (count($sheet->getCategories()) > 1) {
                        $listing .="
                            <a href=\"?sheet_id=".$sheet->getId()."&cat=".$cat->getId()."\"
                                                   class=\"text-danger\">
                                                    <i class=\"fa fa-remove\"></i></a>
                        ";
                    }

                    $listing .= "</li>";
                } // end sheet categories

                $listing .= "
                    </ul>
                    <p>".
                        $sheet->getDescription()
                    ."</p>
                    
                    <span class=\"float-right\">
                        <a data-toggle=\"modal\" data-target=\"#update_sheet_form\" href=\"#\"
                           class=\"text-white btn btn-sm btn-primary\"
                           onclick=\"hydraterModalSheet({id:".$sheet->getId().",label:'".$sheet->getLabel()."',description:'".addslashes($sheet->getDescription())."'});\">
                            <i class=\"fa fa-edit\"></i>
                        </a>
                        <a onClick=\"javascript: return confirm('Voulez-vous vraiment supprimer cette fiche?');\"
                           href=\"?id=".$sheet->getId()."\" class=\"btn btn-sm btn-danger\"><i
                                    class=\"fa fa-trash\"></i></a>
                    </span>
                    </li>
                ";
            } // end sheets

            $listing .="</ul> </div>";
        }

        return $listing;
    }

    public function makeCategoriesPart()
    {
        $html = "
            <ul class=\"list-group list-group-flush mt-4\">
                <li class=\"list-group-item\"><a href=\"/\">Afficher Tout</a></li>
        ";

        foreach ($this->sections as $section) {
            $html .= "
                <li class=\"list-group-item\">
                    <a href=\"#\" data-toggle=\"collapse\" data-target=\"#demo".$section->getId()."\">
                        ".$section->getLabel()."
                        <i class=\"fa fa-caret-down\"></i></a>
                     <div id=\"demo".$section->getId()."\" class=\"collapse\">
                            <ul class=\"list-group px-5\">
            ";

            foreach ($section->getCategories() as $category) {
                $html .= "
                    <li ><a href=\"?category=".$category->getId()."\">".$category->getLabel()."</a></li>
                ";
            }

            $html .= "</ul>
                        </div>
                    </li>";
        }

        $html .= "</ul>";
        return $html;
    }

    public function makeModals()
    {
        $options = "";
        foreach ($this->sectionCategories as $cat) {
            $options .= "<option value=".$cat->getId().">".$cat->getLabel()."</option>";
        }

        $html = "
            <div id=\"update_sheet_form\" class=\"modal\">
                <div class=\"modal-dialog\">
                    <div class=\"modal-content\">
            
                        <div class=\"modal-body\">
                            <h4>Modification fiche</h4>
                            <form action=\"?action=update\" method=\"POST\">
                                <input type=\"hidden\" id=\"update_sheet_id\" name=\"id\">
                                <label for=\"update_sheet_label\" class=\"px-2\">Libellé :</label>
                                <input type=\"text\" class=\"form-control form-control\" id=\"update_sheet_label\" name=\"label\" />
                                <br />
                                <label for=\"update_sheet_description\" class=\"px-2\">Description :</label>
                                <textarea class=\"form-control\" name=\"description\" id=\"update_sheet_description\" cols=\"30\" rows=\"5\"></textarea>
            
                                <button type=\"submit\" class=\"mt-2 btn btn-primary btn-sm\">Enregistrer</button>
                            </form>
                        </div>
            
                    </div>
                </div>
            </div>
        ";

        $html .= "
            <div id=\"addcategory_sheet_form\" class=\"modal\">
                <div class=\"modal-dialog\">
                    <div class=\"modal-content\">
            
                        <div class=\"modal-body\">
                            <h4>Ajout catégorie fiche</h4>
                            <form action=\"?action=add_sheet_category\" method=\"POST\">
                                <input type=\"hidden\" id=\"sheet_id\" name=\"id\">
                                <label for=\"categories\" class=\"px-2\">Catégories :</label>
                                <select name=\"categories[]\" id=\"categories\" class=\"form-control\" multiple>
                                    ".$options."
                                </select>
                                <button type=\"submit\" class=\"mt-2 btn btn-primary btn-sm\">Enregistrer</button>
                            </form>
                        </div>
            
                    </div>
                </div>
            </div>
        ";

        return $html;
    }

    public function makeContent()
    {
        $this->modals = $this->makeModals();
        $listing = $this->makeListing();
        $categoriesHtml = $this->makeCategoriesPart();

        if (!is_null($this->info)) {
            $this->content .= "
                <p class=\"mt-3 alert alert-success\">$this->info</p>
            ";
        }

        $this->content.="
            <div class=\"row\">
                <div class=\"col-md-8 pt-5\">
                    <a href=\"?action=create\"><i class=\"fa fa-plus-square\"></i> Ajouter une nouvelle fiche</a>
                    $listing
                </div>
                <div class=\"col-md-4 p-5\">
                    <h2><a href=\"?action=categories\">Catégories</a></h2>
                     $categoriesHtml                
                </div>
            </div>
        ";

    }

}