<?php
//this is our mysql-class
class dba
{
    var $database = "";
    var $link_id  = 0;
    var $query_id = 0;
    var $record   = array();
    var $errdesc  = "";
    var $errno    = 0;
    var $show_error = 1;
    var $server   = "";
    var $user     = "";
    var $password = "";
    
    // $dba->connect(); - create an mysql-link
    function connect()
    {
        if (0 == $this->link_id) {
            $this->link_id = @mysql_connect($this->server, $this->user, $this->password);
            if (!$this->link_id) {
                $this->print_error("Link-ID == false, connect failed");
            }
            if ($this->database != "") {
                $this->select_db($this->database);
            }
        }
    }
    // $dba->geterrdesc(); - return the mysql-error
    function geterrdesc()
    {
            $this->error = mysql_error();
            return $this->error;
    }
    // $dba->geterrno(); - return the mysql-error number
    function geterrno()
    {
            $this->errno = mysql_errno();
            return $this->errno;
    }
    // $dba->select_db( $database); - select a database to work with
    function select_db($database = "")
    {
        if ($database!="") {
            $this->database = $database;
        }
        if (!@mysql_select_db($this->database, $this->link_id)) {
            $this->print_error("Can not use database ".$this->database);
        }
    }
    // $dba->query( $query_string); - executes your query
    function query($query_string)
    {
            $this->query_id = mysql_query($query_string, $this->link_id);
        if (!$this->query_id) {
            $this->print_error("Invalid SQL-Query: ".$query_string);
            @mysql_query("ROLLBACK", $this->link_id);
            die();
        }
            return $this->query_id;
    }
    // $dba->fetch_assoc( $query_id); - fetches another row of results
    function fetch_assoc($query_id = -1)
    {
        if ($query_id != -1) {
            $this->query_id=$query_id;
        }
            $this->record = mysql_fetch_assoc($this->query_id);
            return $this->record;
    }
    // $dba->free_result( $query_id); - ???
    function free_result($query_id = -1)
    {
        if ($query_id != -1) {
            $this->query_id = $query_id;
        }
            return @mysql_free_result($this->query_id);
    }
    // $dba->query_first( $query_string); - ???
    function query_first($query_string)
    {
            $this->query($query_string);
            $returnarray=$this->fetch_assoc($this->query_id);
            $this->free_result($this->query_id);
            return $returnarray;
    }
    // $dba->num_rows( $query_id); - returns the number of rows in the query
    function num_rows($query_id = -1)
    {
        if ($query_id != -1) {
            $this->query_id = $query_id;
        }
            return mysql_num_rows($this->query_id);
    }
    // $dba->insert_id(); - Liefert die ID einer vorherigen INSERT-Operation
    function insert_id()
    {
            return mysql_insert_id($this->link_id);
    }
    // $dba->GetPrimaryCol( $table);
    function GetPrimaryCol($table)
    {
        $result = $this->query("DESCRIBE ".$table);
        $row = $this->fetch_assoc($result);
        //row[0] holds the primary fieldname. get it and return it
        return $row[0];
    }
    // $dba->print_error( $msg); - prints an error
    function print_error($msg)
    {
            $this->errdesc = mysql_error();
            $this->errno = mysql_errno();

            $message = "<table width=\"50%\"><tr><td bgcolor=\"red\">\n";
            $message .= "Database error: $msg\n<br>";
            $message .= "mysql error: $this->errdesc\n<br>";
            $message .= "mysql error number: $this->errno\n<br>";
            $message .= "Date: ".date("d.m.Y @ H:i")."\n<br>";
            $message .= "Script: ".getenv("REQUEST_URI")."\n<br>";
            $message .= "Referer: ".getenv("HTTP_REFERER")."\n<br><br>";

        if ($this->show_error) {
            $message = "$message";
        } else {
            $message = "\n<!-- $message -->\n";
        }
            
        print "<html><head><title>Error!</title></head><body>\n";
        print "<font face=\"Verdana\" size=\"2\">";
        print $message;
        print "</font>\n";
        print "</body></html>";
    }
}
