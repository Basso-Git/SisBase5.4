<?php

namespace App\Security;

class DatabaseConnection
{
    private static $instance;
    private $conn;

    public function __construct($user, $pass, $dbConnection, $entityManager)
    {
        $dbConfig = $this->getDatabaseConfig($dbConnection, $entityManager);
        try {
            $this->conn = oci_connect($user, $pass, $dbConfig, 'UTF8');

            if ($this->conn == true && $user != "pmautino") {
                $this->setUserRole();
            }
        } catch (\Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public function getConn()
    {
        return $this->conn;
    }

    public static function getInstance($user, $pass, $dbConnection, $entityManager)
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($user, $pass, $dbConnection, $entityManager);
        }
        return self::$instance;
    }

    public function getUserOracle($username)
    {
        $sql = "select U.* from USUARIOS U where U.usuario ='" . $username . "'";
        $stid = oci_parse($this->conn, $sql);

        if (!$stid) {
            $e = oci_error($this->conn);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $r = oci_execute($stid);

        if (!$r) {
            $e = oci_error($stid);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $response = [];
        while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $response[] = $row;
        }

        oci_free_statement($stid);
        return $response;
    }

    private function getDatabaseConfig($dbConnection, $entityManager)
    {
        $db = '';
        if ($dbConnection == 'neosys') {
            $connectionParams = $entityManager->getConnection()->getParams();
            $host = $connectionParams['host'];

            $db = "(DESCRIPTION=( ADDRESS_LIST= (ADDRESS= (PROTOCOL=TCP) (HOST=$host) (PORT=1521)))"
                . "( CONNECT_DATA= (SID=NEOSYS) ))";
        }

        return $db;
    }

    private function setUserRole()
    {
        $sql = 'set role ROL_USUARIO IDENTIFIED BY "MP987Basso"';
        $stid = oci_parse($this->conn, $sql);

        if (!$stid) {
            $e = oci_error($this->conn);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $r = oci_execute($stid);

        if (!$r) {
            $e = oci_error($stid);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        oci_free_statement($stid);
    }
}
