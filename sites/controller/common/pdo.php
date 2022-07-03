<?php

class ControllerCommonpdo extends Controller
{

    public function index ()
    {
        $user_group_id = 1;
        
        $stmt = $this->db->prepare('SELECT user_group_id, username, password FROM dg_user WHERE user_group_id = :user_group_id');
        
        /* , PDO::PARAM_STR */
        /* , PDO::PARAM_STR, 12 */
        $stmt->bindParam(':user_group_id', $user_group_id, PDO::PARAM_INT);
        $stmt->execute();
        
        while ($row = $stmt->fetch()) {
            echo "user_group_id : " . $row['user_group_id'] . "<br />\n";
            echo "Username : " . $row['username'] . "<br />\n";
        }
        
        echo "<hr>";
        $stmt = $this->db->prepare('SELECT user_group_id, username, password FROM dg_user WHERE user_group_id = ? ');
        
        $stmt->bindParam(1, $user_group_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // var_dump($stmt->fetch());
        
        while ($row = $stmt->fetch()) {
            echo "user_group_id : " . $row['user_group_id'] . "<br />\n";
            echo "Username : " . $row['username'] . "<br />\n";
        }
        
        /* Call a stored procedure with an INOUT parameter */
        /*
         * $colour = 'red';
         * $sth = $dbh->prepare('CALL puree_fruit(?)');
         * $sth->bindParam(1, $colour, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,
         * 12);
         * $sth->execute();
         * print("After pureeing fruit, the colour is: $colour");
         *
         *
         *
         *
         * PDOStatement::execute()
         * PDOStatement::fetch()
         * PDOStatement::fetchAll()
         * PDOStatement::fetchColumn()
         *
         *
         *
         * function readData($dbh) {
         * $sql = 'SELECT name, colour, calories FROM fruit';
         * try {
         * $stmt = $dbh->prepare($sql);
         * $stmt->execute();
         *
         * /* Bind by column number
         */
        /*
         * $stmt->bindColumn(1, $name);
         * $stmt->bindColumn(2, $colour);
         *
         * /* Bind by column name
         */
        /*
         * $stmt->bindColumn('calories', $cals);
         *
         * while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
         * $data = $name . "\t" . $colour . "\t" . $cals . "\n";
         * print $data;
         * }
         * }
         * catch (PDOException $e) {
         * print $e->getMessage();
         * }
         * }
         * readData($dbh);
         */
    }
}