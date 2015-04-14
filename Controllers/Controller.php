<?php
session_start();

abstract class Controller {

    protected $db;

    public function __construct()
    {
        $config = include 'config.php';
        $this->db = new PDO('mysql:host='. $config['db_host'] .
                            ';dbname='. $config['db_name'],
                            $config['db_user'],
                            $config['db_pass']);
    }

    public function view($view, $data = null)
    {

        if (is_array($data)) {
            extract($data);
        }
        $file = "view/$view.view.php";
        if (file_exists($file))
        {

            include 'view/inc/header.php';
            include $file;
            include 'view/inc/footer.php';
        }
    }

    public function hydrate(array $data = null)
    {
        if ($data != null)
        {
            foreach ($data as $key => $value)
            {
                $methodName = 'set' . ucfirst($key);
                if (method_exists($this, $methodName))
                {
                    static::$methodName($value);
                }
            }
        }
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }
}
