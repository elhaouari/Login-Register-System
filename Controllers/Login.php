<?php
include 'Controllers/Controller.php';

class Login extends Controller{

    private $_username;
    private $_email;
    private $_password;
    private $_remember;
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
        if ($this->isLogin()) {
            return $_SESSION['login']['username'];
        }
        return $this->_username;
    }
    private function password()
    {
        return $this->_password;
    }
    public function email()
    {
        if ($this->isLogin()) {
            return $_SESSION['login']['email'];
        }
        return $this->_email;
    }
    public function remember()
    {
        return $this->_remember;
    }

    /**
    * setters information
    */
    public function setUsername($username)
    {
        if (!empty($username))
        {
            if (filter_var($username, FILTER_VALIDATE_EMAIL))
            {
                $this->setEmail($username);
            }
            else if (strlen($username) < self::LENGTH_USERNAME) {
                $this->_errors['username'] = 'Username must be atleast 4 characters.';
            }
            else {
                $this->_username = $username;
            }
        }
        else
        {
            $this->_errors['username'] = 'Username or email is required.';
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
    public function setRemember($remember)
    {
        $this->_remember = $remember;
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
        return isset($this->_errors[$key]) ?
             $this->_errors[$key] : null;
    }
    public function hasError()
    {
        return !empty($this->_errors);
    }

    /**
    *  login in
    */
    public function login()
    {
        $query = 'SELECT * FROM users WHERE ';
        $execute = array();
        if (!empty($this->username()))
        {
            $query .= 'username = :username ';
            $execute['username'] = $this->username();
        }
        else
        {
            $query .= 'email = :email ';
            $execute['email'] = $this->email();
        }

        $prepare = $this->db->prepare($query);
        $prepare->execute($execute);

        if ($user = $prepare->fetch(PDO::FETCH_ASSOC))
        {
            if (password_verify($this->password(), $user['password']))
            {
                $this->hydrate($user);
                $_SESSION['login']['username'] = $this->username();
                $_SESSION['login']['email']    = $this->email();
                if ($this->remember())
                {
                    $this->rememberMe();
                }
                return true;
            }
            else
            {
                $this->_errors['login'] = 'password is incorrect!';
                return false;
            }
        }
        else
        {
            $this->_errors['login'] = 'Username/email or password is incorrect!';
            return false;
        }

    }

    /**
    * login by cookies
    */
    public function loginByCookies()
    {
        if (!isset($_COOKIE['username'], $_COOKIE['hash']))
        {
            return false;
        }

        $query = 'SELECT * FROM users
                  WHERE `username` = :username AND `password` = :password';

        $execute = array();
        $execute['username'] = $_COOKIE['username'];
        $execute['password'] = $_COOKIE['hash'];

        $prepare = $this->db->prepare($query);
        $prepare->execute($execute);

        if ($user = $prepare->fetch(PDO::FETCH_ASSOC))
        {
            $this->hydrate($user);
            $_SESSION['login']['username'] = $this->username();
            $_SESSION['login']['email']    = $this->email();
            return true;
        }

        return false;
    }

    /**
    * save cookies for remember me
    */
    public function rememberMe()
    {
        if ($this->isLogin())
        {
            setcookie('username', $this->username(), time() + 365*24*3600, null, null, false, true);
            setcookie('hash', $this->password(), time() + 365*24*3600, null, null, false, true);
        }
    }

    /**
    * log out from web system
    */
    public function logout()
    {
        if ($this->isLogin()) {
            unset($_SESSION);
            session_destroy();
        }
    }

    /**
    * is user login ins
    */
    public function isLogin()
    {
        return isset($_SESSION['login'],
                     $_SESSION['login']['username'],
                     $_SESSION['login']['email']);
    }
}
