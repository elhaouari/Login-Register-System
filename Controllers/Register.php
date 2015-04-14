<?php
include 'Controllers/Controller.php';

class Register extends Controller{

    private $_username;
    private $_email;
    private $_password;
    private $_errors = array();

    const LENGTH_USERNAME = 4;
    const LENGTH_PASSWORD = 8;

    public function __construct(array $data = null)
    {
        parent::__construct();

        $this->hydrate($data);
    }

    /**
    * getters informations
    */
    public function username()
    {
        return $this->_username;
    }
    public function password()
    {
        return $this->_password;
    }
    public function email()
    {
        return $this->_email;
    }

    /**
    * setters information
    */
    public function setUsername($username)
    {
        if (!empty($username))
        {
            if (strlen($username) < self::LENGTH_USERNAME) {
                $this->_errors['username'] = 'Username must be atleast 4 characters.';
            }
            else {
                $this->_username = $username;
            }
        }
        else
        {
            $this->_errors['username'] = 'Username  is required.';
        }
    }
    public function setPassword($password)
    {
        if (strlen($password) < self::LENGTH_PASSWORD) {
            $this->_errors['password'] = 'Password must be atleast 8 characters.';
        }
        else {
            $this->_password = $password;
        }
    }
    public function setEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->_email = $email;
        }
        else
        {
            $this->_errors['email'] = 'Please enter a valid email address.';
        }
    }

    public function save()
    {
        $query  = 'INSERT INTO `users` (`username`, `email`, `password`)
                   VALUES (:username, :email, :password)';

        $execute = array();
        $execute['username'] = $this->username();
        $execute['password'] = password_hash($this->password(), PASSWORD_DEFAULT);
        $execute['email']    = $this->email();

        if ($this->isUsernameExists())
        {
            $this->_errors['username'] = 'Username alride exists!';
            return false;
        }
        if ($this->isEmailExists())
        {
            $this->_errors['email'] = 'Email alride exists!';
            return false;
        }

        $prepare = $this->db->prepare($query);

        if ($prepare->execute($execute))
        {
            return true;
        }
        else
        {
            $this->_errors['register'] = 'Something rang...!';
            return false;
        }

    }

    private function isUsernameExists()
    {
        $query  = 'SELECT * FROM `users` WHERE `username` = :username';

        $execute = array();
        $execute['username'] = $this->username();

        $prepare = $this->db->prepare($query);
        $prepare->execute($execute);

        if ($prepare->rowCount())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function isEmailExists()
    {
        $query  = 'SELECT * FROM `users` WHERE `email` = :email';

        $execute = array();
        $execute['email'] = $this->email();

        $prepare = $this->db->prepare($query);
        $prepare->execute($execute);

        if ($prepare->rowCount())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
    * getter errors
    */
    public function getErrors()
    {
        return $this->_errors;
    }
    public function getError($key)
    {
        return isset($this->_errors[$key]) ? $this->_errors[$key] : null;
    }
    public function hasError()
    {
        return !empty($this->_errors);
    }

}
