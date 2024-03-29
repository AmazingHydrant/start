<?php
class PDODB
{
    private $host;
    private $port;
    private $user;
    private $pass;
    private $dbname;
    private $charset;
    private $dsn;
    /**
     * @var PDO $pdo
     */
    private $pdo;
    /**
     * PDO instance
     */
    private static $instance;
    /**
     * init PDO
     */
    public function __construct()
    {
        if (self::$instance != null) {
            return self::$instance;
        }
        $this->initParam();
        $this->initDSN();
        $this->initPDO();
        $this->Attribute();
    }
    // /**
    //  * using Singleton
    //  */
    // public static function getInstance($option)
    // {
    //     if (!(self::$instance instanceof self)) {
    //         self::$instance = new self($option);
    //     }
    //     return self::$instance;
    // }
    /**
     * init Patams
     * @param array $option [pass, dbname]
     */
    private function initParam()
    {
        $option = C('DB');
        $this->host = isset($option['host']) ?  $option['host'] : '127.0.0.1';
        $this->port = isset($option['port']) ?  $option['port'] : '3306';
        $this->user = isset($option['user']) ?  $option['user'] : 'root';
        $this->pass = isset($option['pass']) ?  $option['pass'] : null;
        $this->dbname = isset($option['dbname']) ?  $option['dbname'] : null;
        $this->charset = isset($option['charset']) ?  $option['charset'] : 'utf8';
        if (!($this->host && $this->port && $this->user && $this->pass && $this->dbname)) {
            die("DB初始化參數不完整！");
        }
    }
    /**
     * init pdo dsn
     */
    private function initDSN()
    {
        $this->dsn = "mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=$this->charset;";
    }
    /**
     * new PDO connect mysql
     */
    private function initPDO()
    {
        try {
            $this->pdo = new PDO($this->dsn, $this->user, $this->pass);
        } catch (PDOException $e) {
            echo "connect error: {$e->getMessage()}";
            die;
        }
    }
    /**
     * set error mode to PDOException
     * ATTR_EMULATE_PREPARES to false
     */
    private function Attribute()
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    /**
     * process PDOException error message
     * @param PDOException $e
     */
    private function sqlErr($e)
    {
        echo "sql error: {$e->getMessage()}";
    }
    /**
     * fetch col sql query
     * @param string $sql
     * @param array $val
     */
    public function fetchCol($sql, $val = null)
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($val);
            $res = $stmt->fetchColumn();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            $this->sqlErr($e);
            return false;
        }
        return $res;
    }
    /**
     * fetch col sql query
     * @param string $sql
     * @param array $val
     */
    public function fetchRow($sql, $val)
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($val);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (PDOException $e) {
            $this->sqlErr($e);
            return false;
        }
        return $res;
    }
    /**
     * fetch all sql query
     * @param string $sql
     * @param array $val
     */
    public function fetchAll($sql, $val)
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($val);
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (PDOException $e) {
            $this->sqlErr($e);
            return false;
        }
        return $res;
    }
    /**
     * sql query
     * @param string $sql
     * @param array $val
     */
    public function query($sql, $val)
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute($val);
            $stmt->closeCursor();
        } catch (PDOException $e) {
            $this->sqlErr($e);
            return false;
        }
        return $res;
    }
}
