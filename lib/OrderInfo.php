<?php
class OrderInfo {
    const LIMIT_PER_PAGE = 20;

    private $conn;
    private $errors;
    private $requiredFields = array('address', 'mobile', 'username', 'zip');
    private $mailConfig;
    private $settings;

    public function __construct($dbConfig, $mailConfig, $settings) {
        $this->conn = mysql_connect($dbConfig['host'], $dbConfig['username'], $dbConfig['password']);
        mysql_select_db($dbConfig['database'], $this->conn);
        $this->table = $dbConfig['table'];
        $this->mailConfig = $mailConfig;
        $this->settings = $settings;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function create($row) {
        if (!$this->validate($row)) {
            return false;
        }
        $sql = "INSERT INTO " . $this->table . "(username, mobile, address, zip, type, created_at, user_comment) 
            VALUES(" . 
            $this->quote($row['username']) . ", ".
            $this->quote($row['mobile']) . ", " . 
            $this->quote($row['address']) . ", " . 
            $this->quote($row['zip']) . ", " . 
            intval($row['type']) . ", " . 
            time() . ", " . 
            $this->quote($row['user_comment']) . 
        ")";
        mysql_query($sql, $this->conn);
        $error = mysql_error($this->conn);
        if ($error) {
            $this->errors['.system'] = $error;
            return 0;
        }

        $this->sendMail($row);

        return mysql_affected_rows($this->conn);
    }

    private function sendMail($row) {
        $mail = $this->mailConfig;
        $row['created_at'] = date('Y-m-d H:i:s');
        $row['type'] = $this->settings['type'][$row['type']];
        $body = preg_replace('/\{(\w+)\}/e', '$row["\\1"]', $mail['body']);

        $message = Swift_Message::newInstance()
            ->setCharset('UTF-8')
            ->setSubject($mail['subject'])
            ->setFrom(array($mail['from_email'] => $mail['from_name']))
            ->setTo(array($mail['to']))
            ->setBody($body);

        $transport = Swift_SmtpTransport::newInstance($mail['host'], $mail['port'])
            ->setUsername($mail['username'])
            ->setPassword($mail['password']);

        $mailer = Swift_Mailer::newInstance($transport);
        return $mailer->send($message);
    }

    private function quote($value) {
        return "'" . mysql_real_escape_string($value) . "'";
    }

    public function validate($row) {
        foreach ($this->requiredFields as $field) {
            $method = 'validate' . ucfirst($field);
            $this->$method($row[$field]);
        }

        return !$this->hasErrors();
    }

    public function validateAddress($address) {
        if ($address == '') {
            $this->errors['address'] = '地址输入不能为空';
            return false;
        }
        return true;
    }

    public function validateMobile($mobile) {
        if ($mobile == '') {
            $this->errors['mobile'] = '手机号码输入有误';
            return false;
        }

        if (strlen($mobile) != 11 || !is_numeric($mobile)) {
            $this->errors['mobile'] = '手机号码输入有误';
            return false;
        }

        return true;
    }

    public function validateUsername($username) {
        if ($username == '') {
            $this->errors['username'] = '用户名输入不能为空';
            return false;
        }

        if (!preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$username)) {
            $this->errors['username'] = '用户名必须是中文字符';
            return false;
        }

        $sql = "SELECT COUNT(*) FROM " . $this->table . " 
            WHERE username = " . $this->quote($username) . " AND created_at > " . (time() - 86400);
        $res = mysql_query($sql, $this->conn);
        echo mysql_error();
        $row = mysql_fetch_row($res);
        if ($row[0] >= 1) {
            $this->errors['username'] = '该用户已经提交订单';
            return false;
        }

        return true;
    }

    public function validateZip($zip) {
        if ($zip != '') {
            if (!is_numeric($zip) || strlen($zip) != 6) {
                $this->errors['zip'] = '邮编输入不正确';
                return false;
            }
        }

        return true;
    }

    public function validateField($field, $value) {
        $method = 'validate' . ucfirst($field);
        return $this->$method($value);
    }

    public function hasError($field) {
        return isset($this->errors[$field]);
    }

    public function getError($field) {
        return $this->hasError($field) ? $this->errors[$field] : '';
    }

    public function hasErrors() {
        return count($this->errors) > 0;
    }

    public function delete($id) {
        mysql_query("DELETE FROM " . $this->table . " WHERE id = " . (int)$id);
        return mysql_affected_rows($this->conn);
    }

    public function getCount() {
        $res = mysql_query("SELECT COUNT(*) FROM " . $this->table);
        $row = mysql_fetch_row($res);
        return $row[0];
    }

    public function getRows($page) {
        $offset = ($page - 1) * self::LIMIT_PER_PAGE;
        $res = mysql_query("SELECT * FROM " . $this->table . " 
            ORDER BY id DESC 
            LIMIT " . $offset . ", " . self::LIMIT_PER_PAGE 
        , $this->conn);
        $rows = array();
        while ($row = mysql_fetch_assoc($res)) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function __destruct() {
        if ($this->conn) {
            mysql_close($this->conn);
        }
    }
}
