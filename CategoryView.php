<?php

require 'Template.php';

class CategoryView extends Template
{

    private $page;

    private $categories = [];

    private $sections = [];

    private $info;

    private $error;

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

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
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
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
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    private $total;

    /**
     * CategoryView constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->page = 0;
        $this->scripts = "
            <script>
                function hydraterModalSection(id,label) {
                    $('#modal_section_id').val(id);
                    $('#modal_section_label').val(label);
                }
        
                function hydraterModalCategory(id,label, section) {
                    $('#update_category_id').val(id);
                    $('#update_category_label').val(label);
                    $('#update_category_section_label').val(section);
                }
            </script>
        ";
    }

    public function makePagination()
    {
        $html = "
            <ul class=\"pagination\">
        ";
        if ($this->page>0) {
            $html .= "
                <li class=\"page-item\"><a class=\"page-link\" href=\"?action=categories&page=".($this->page-1)."\">
                                <i class=\"fa fa-arrow-left\"></i> Précédent </a></li>  
            ";
        }

        if (($this->page+1)<ceil($this->total/5)) {
           $html .= "
                <li class=\"page-item\"><a class=\"page-link\" href=\"?action=categories&page=".($this->page+1)."\">
                    Suivant <i class=\"fa fa-arrow-right\"></i>
                </a></li>
           ";
        }
        return $html .= "</ul>";
    }

    public function makeSectionPart()
    {
        $sectionHtml = "";
        foreach ($this->sections as $section) {
            $sectionHtml .= "
                <div class=\"col-sm-12 pt-3\">
                    <h6>".$section->getLabel()."&nbsp;
                        <a href=\"#\" data-toggle=\"modal\" data-target=\"#myModalSection\"
                                 onclick=\"hydraterModalSection('".$section->getId()."','".$section->getLabel()."');\">
                            <i class=\"fa fa-pencil\"></i></a>
                    </h6>
                </div>
            ";
        }
        return sprintf("
            
            <div class=\"row\"><h4>Section</h4></div>
            <div class=\"row\">
                <ul class=\"nav\">
                    <li class=\"nav-item\">
                        <a data-toggle=\"collapse\" data-target=\"#section_form\" class=\"nav-link\" href=\"#\">
                            <i class=\"fa fa-plus-square\"></i> Ajouter une nouvelle section</a>
                    </li>
                </ul>
                <div id=\"section_form\" class=\"collapse\">
                    <form class=\"form-inline\" action=\"?action=categories\" method=\"POST\">
                        <input type=\"hidden\" name=\"taf_section\" value=\"createsection\">
                        <label for=\"label\" class=\"px-2\">Libellé:</label>
                        <input type=\"text\" class=\"form-control form-control-sm mx-2\" id=\"label\"
                               name=\"label\" placeholder=\"Nom de la section...\">

                        <button type=\"submit\" class=\"btn btn-primary btn-sm\">Enregistrer</button>
                    </form>

                </div>
            </div>
            <div class=\"row\">
                %s
            </div>
                
        ", $sectionHtml);
    }

    public function makeCategoryPart()
    {
        $pagination = $this->makePagination();
        $tbodyHtml = "";
        foreach ($this->categories as $category) {
            $tbodyHtml .= "
                <tr>
                    <td>".$category->getLabel()."</td>
                    <td>".$category->getSection()->getLabel()."</td>
                    <td>
                        <a href=\"#\" class=\"text-success\" data-toggle=\"modal\" data-target=\"#update_category_form\"
                           onclick=\"hydraterModalCategory('".$category->getId()."','".$category->getLabel()."','".$category->getSection()->getId()."');\">
                            <i class=\"fa fa-pencil\"></i>
                        </a>
                    </td>
                    <td><a onClick=\"javascript: return confirm('Voulez-vous vraiment supprimer cette catégorie?')\"
                    href=\"?action=delete_category&id=".$category->getId()."\" class=\"text-danger\">
                            <i class=\"fa fa-trash\"></i></a></td>
                </tr>
            ";
        }

        $html = "
            <div class=\"row\"><h4>Catégories</h4></div>
            <div class=\"row bg-light p-2\">
                <ul class=\"nav\">
                    <li class=\"nav-item\">
                        <a data-toggle=\"modal\" data-target=\"#category_form\" class=\"nav-link\" href=\"#\">
                            <i class=\"fa fa-plus-square\"></i> Ajouter une nouvelle catégorie</a>
                    </li>

                </ul>
            </div>
            <div class=\"row\">
                <table class=\"table\">
                    <thead>
                    <th>Libellé</th>
                    <th>Section</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                    </thead>
                    <tbody>
                        ".$tbodyHtml."
                    </tbody>
                </table>
                ".$pagination."
            </div>
        ";
        
        return $html;

    }

    public function makeModals()
    {
        $options = "";
        foreach ($this->sections as $section) {
            $options .="
                <option value=\"".$section->getId()."\">".$section->getLabel()."</option>
            ";
        }

        $html = "
            <div class=\"modal\" id=\"myModalSection\">
                <div class=\"modal-dialog\">
                    <div class=\"modal-content\">
        
                        <div class=\"modal-body\">
                            <h4>Mise à jour section</h4>
                            <form method=\"post\" action=\"?action=categories\">
                                <input type=\"hidden\" name=\"taf_section\" value=\"updatesection\">
                                <input type=\"hidden\" id=\"modal_section_id\" name=\"id\">
                                <label for=\"label3\">Libellé</label>
                                <input type=\"text\" class=\"form-control\" id=\"modal_section_label\" name=\"label\">
                                <button type=\"submit\" class=\"mt-2 btn-sm btn btn-primary\">Mettre à jour</button>
                            </form>
                        </div>
        
                    </div>
                </div>
            </div>
            
            
            <div id=\"category_form\" class=\"modal\">
                <div class=\"modal-dialog\">
                    <div class=\"modal-content\">
        
                        <div class=\"modal-body\">
                            <h4>Ajout nouvelle catégorie</h4>
                            <form action=\"?action=categories\" method=\"POST\">
                                <input type=\"hidden\" name=\"taf_category\" value=\"create\">
                                <label for=\"label2\" class=\"px-2\">Libellé :</label>
                                <input type=\"text\" class=\"form-control form-control\" id=\"label2\" name=\"label\"
                                       placeholder=\"Libellé de la catégorie...\">
                                <label for=\"create_category_section_label\">Section :</label>
                                <select name=\"section\" id=\"create_category_section_label\" class=\"form-control\">
                                    ".$options."
                                </select>
                                <button type=\"submit\" class=\"mt-2 btn btn-primary btn-sm\">Enregistrer</button>
                            </form>
                        </div>
        
                    </div>
                </div>
            </div>
            
            <div id=\"update_category_form\" class=\"modal\">
                <div class=\"modal-dialog\">
                    <div class=\"modal-content\">
        
                        <div class=\"modal-body\">
                            <h4>Modification catégorie</h4>
                            <form action=\"?action=categories\" method=\"POST\">
                                <input type=\"hidden\" id=\"update_category_id\" name=\"id\">
                                <input type=\"hidden\" name=\"taf_category\" value=\"update\">
                                <label for=\"update_category_label\" class=\"px-2\">Libellé :</label>
                                <input type=\"text\" class=\"form-control form-control\" id=\"update_category_label\" name=\"label\" />
                                <label for=\"update_category_section_label\">Section :</label>
                                <select name=\"section\" id=\"update_category_section_label\" class=\"form-control\">
                                    ".$options."
                                </select>
                                <button type=\"submit\" class=\"mt-2 btn btn-primary btn-sm\">Enregistrer</button>
                            </form>
                        </div>
        
                    </div>
                </div>
            </div>
        ";

        $this->modals = $html;
    }

    public function makeContent()
    {
        $this->makeModals();
        $sectionHtml = $this->makeSectionPart();
        $categoryHtml = $this->makeCategoryPart();

        if (!is_null($this->info)) {
            $this->content .= "
                <p class=\"mt-3 alert alert-success\">$this->info</p>
            ";
        } elseif (!is_null($this->error)) {
            $this->content .= "
                <p class=\"mt-3 alert alert-danger\">$this->error</p>
            ";
        }

        $this->content .= "
            <div class=\"row pt-3\">
                <div class=\"col-md-4\"> 
                    ".$sectionHtml."
                </div>
                <div class=\"col-md-8\">".$categoryHtml."</div>
            </div>
        ";
    }
}