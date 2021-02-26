<?php


class Template
{
    protected $head;

    protected  $nav;

    protected $content;

    protected $modals;

    protected $scripts;

    protected $styles;

    /**
     * Template constructor.
     */
    public function __construct()
    {
        $this->head = "
            
                <title>Annuaire</title>
                <link rel=\"stylesheet\" href=\"assets/css/bootstrap.min.css\">
                <link rel=\"stylesheet\" href=\"assets/css/font-awesome.min.css\">
                <link rel=\"stylesheet\" href=\"assets/css/style.css\">
                <style>
                    .listing {
                        overflow-y:  auto;
                        max-height:500px;
                    }
                    .listing::-webkit-scrollbar-track {
                        border: 1px solid lightgray;
                        border-radius: 2px;
                        padding: 2px 0;
                        background-color: lightgray;
                    }
            
                    .listing::-webkit-scrollbar {
                        width: 10px;
                    }
            
                    .listing::-webkit-scrollbar-thumb {
                        border-radius: 10px;
                        box-shadow: inset 0 0 6px rgba(0,0,0,.3);
                        background-color: darkgray;
                        border: none;
                    }                  
                </style>
           
        ";

        $this->nav = "
            <nav class=\"navbar navbar-expand-sm bg-info text-white\">
                    <h1><a href=\"/\" class=\"text-white\">Annuaire</a></h1>
                    <ul class=\"nav\">
                        <li class=\"nav-item\">
                            <a class=\"nav-link text-white\" href=\"/\">Accueil</a>
                        </li>
                        <li class=\"nav-item\">
                            <a class=\"nav-link text-white\" href=\"?action=categories\">Catégorie</a>
                        </li>
            
                    </ul>
                </nav>";
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    function getView()
    {
        echo "
            <html>
                <head>
                    $this->head
                    <style>
                        $this->styles;
                    </style>
                </head>
                <body>
                      $this->nav
                      <main class=\"container\">$this->content</main>
                      $this->modals
                      <script src=\"assets/js/jquery.min.js\"></script>
                      <script src=\"assets/js/popper.min.js\"></script>
                      <script src=\"assets/js/bootstrap.min.js\"></script>
                      $this->scripts
                </body>
            </html>
        ";
    }

}