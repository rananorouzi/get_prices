<?php

/**
 * Database class
 */
class Database
{
    /**
     * string
     */
    private $servername;
    private $username;
    private $password;
    private $dbname;

    function __construct()
    {
        $this->servername = 'localhost';
        $this->username = 'root';
        $this->password = '';
        $this->dbname = 'get_prices';
    }

    /**
     * @return bool|mysqli
     */
    function connect()
    {
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($conn->connect_error) {
            file_put_contents(dirname(__DIR__, 1) . '/files/debug/db.log', '[' . date('Y/m/d - H:i:s') . '] - Connection failed: ' . $conn->connect_error.PHP_EOL, FILE_APPEND);
            return false;
        }
        return $conn;
    }

    /**
     * @param array $data
     * @param string $table
     * @return bool
     */
    function insert(array $data, string $table): bool
    {
        if (!empty($data) && !empty($table)) {
            if ($conn = $this->connect()) {
                $keys = '';
                $val = '';
                $flag = false;
                $specialChars = array("'", '"', '%');
                $specialCharRreplace = array("\'", '\"', '\%');

                foreach ($data as $key => $dataRow) {
                    if (!is_numeric($key) && !empty($key) && !empty($dataRow)) {
                        $flag = true;
                        $dataRow = str_replace($specialChars, $specialCharRreplace, $dataRow);
                        if (!empty($keys) && !empty($val)) {
                            $keys .= ',`' . $key . '`';
                            $val .= ',\'' . $dataRow . '\'';
                        } else {
                            $keys .= '`' . $key . '`';
                            $val .= '\'' . $dataRow . '\'';
                        }
                    }
                }

                if ($flag) {
                    $sql = "INSERT INTO $table ($keys) VALUES ($val)";
                    if ($conn->query($sql) === TRUE) {
                        return true;
                    } else {
                        file_put_contents(dirname(__DIR__, 1) . '/files/debug/db.log', '[' . date('Y/m/d - H:i:s') . ']  -Error: ' . $sql . '<br>' . $conn->error.PHP_EOL, FILE_APPEND);
                    }
                }
                $conn->close();
            }
        }
        return false;
    }

    /**
     * @param array $data
     * @param string $table
     * @param array $where
     * @return bool
     */
    function update(array $data, string $table, array $where): bool
    {
        if (!empty($data) && !empty($table)) {
            if ($conn = $this->connect()) {
                $sql = "UPDATE $table SET ";
                $flag = false;
                $whereFlag = false;
                foreach ($data as $key => $dataRow) {
                    if (!is_numeric($key) && !empty($key)) {
                        if ($flag) {
                            $sql .= ',`' . $key . '` = ' . $dataRow . ' ';
                        } else {
                            $sql .= '`' . $key . '` = ' . $dataRow . ' ';
                        }
                        $flag = true;
                    }
                }
                if (!empty($where)) {
                    $whereS = '';
                    foreach ($where as $key1 => $val1) {
                        if (!empty($key1) && !empty($val1)) {
                            $whereS .= '`' . $key1 . '` = ' . $val1 . ' ';
                            $whereFlag = true;
                        }
                    }
                }
                if ($whereFlag) {
                    $sql .= 'WHERE ' . $whereS;
                }
                if ($flag) {
                    if ($conn->query($sql) === TRUE) {
                        return true;
                    } else {
                        file_put_contents(dirname(__DIR__, 1) . '/files/debug/db.log', '[' . date('Y/m/d - H:i:s') . ']  - Error: ' . $sql . '<br>' . $conn->error.PHP_EOL, FILE_APPEND);
                    }
                }
                $conn->close();
            }
        }
        return false;
    }


    /**
     * @param string $val
     * @param string $key
     * @param string $table
     * @return bool
     */
    function find(string $val, string $key, string $table): bool
    {
        if (empty($val) || empty($key) || empty($table)) {
            return false;
        }
        if ($conn = $this->connect()) {
            $sql = "SELECT $key FROM $table WHERE $key = $val";

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                return true;
            }
            $conn->close();
        }
        return false;
    }


    /**
     * @param string $table
     * @param int $page
     * @param int $perPage
     * @return array
     */
    function getAllFromTable(string $table, int $page, int $perPage): array
    {
        $resultArr = array();
        if (empty($table)) {
            return $resultArr;
        }
        $offset = $perPage * ($page - 1);
        if ($conn = $this->connect()) {
            $sql = "SELECT * FROM $table LIMIT $perPage OFFSET $offset";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $resultArr = $result->fetch_all(MYSQLI_ASSOC);
            }
            $conn->close();

        }
        return $resultArr;
    }
}
