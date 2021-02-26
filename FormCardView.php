<?php

require 'Template.php';

class FormCardView extends Template
{
    private $allCategories = [];

    private $info;

    private $error;

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
     * @return array
     */
    public function getAllCategories()
    {
        return $this->allCategories;
    }

    /**
     * @param array $allCategories
     */
    public function setAllCategories($allCategories)
    {
        $this->allCategories = $allCategories;
    }

    /**
     * FormCardView constructor.
     */
    public function __construct()
    {
        $this->styles = "
            #description{
                height:100px;
            }
        ";
        parent::__construct();

    }

    public function makeContent()
    {
        $options = "";
        foreach ($this->allCategories as $category) {
             $options .= "<option value=\"".$category->getId()."\">".$category->getLabel()."</option>";
        }

        $message = "";
        if (!is_null($this->info)) {
            $message .= "
                <p class=\"mt-3 alert alert-success\">$this->info</p>
            ";
        } elseif (!is_null($this->error)) {
            $message .= "
                <p class=\"mt-3 alert alert-danger\">$this->error</p>
            ";
        }

        $html = "
            <div class=\"row\">
            <div class=\"col-md-2\"></div>
            <div class=\"col-md-8 col-sm-12 pt-4\">
                ".$message."
                <h2 class=\"pb-2\">Nouvelle fiche</h2>
                <form method=\"POST\" action=\"?action=create\">
                    <div class=\"form-group\">
                        <label for=\"label\">Libellé:</label>
                        <input type=\"text\" name=\"label\" class=\"form-control\" id=\"label\" placeholder=\"Entrer le libellé ici...\">
                    </div>
                    <div class=\"form-group\">
                        <label for=\"description\">Description:</label>
                        <input type=\"text\" name=\"description\" class=\"form-control\" id=\"description\" placeholder=\"Entrer la description ici...\">
                    </div>
                    <div class=\"form-group\">
                        <label for=\"categories\">Description:</label>
                        <select name=\"categories[]\" id=\"categories\" class=\"form-control\" multiple>
                            ".$options."
                        </select>
                    </div>

                    <button type=\"submit\" class=\"btn btn-primary\">Enregistrer</button>
                </form>
            </div>
        </div>
        ";

        $this->content = $html;
    }
}