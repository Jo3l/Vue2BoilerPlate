<?php 
/* 
 * @Author Rory Standley <rorystandley@gmail.com> 
 * @Version 1.3 
 * @Package Database 
 */ 
class Database{ 
    /*  
     * Create variables for credentials to mysql database 
     * The variables have been declared as private. This 
     * means that they will only be available with the  
     * Database class 
     */ 
    private $db_host = "127.0.0.1";  // Change as required 
    private $db_user = "editor";  // Change as required 
    private $db_pass = "extrabot22";  // Change as required 
    private $db_name = "fedpival";    // Change as required 
    /* 
     * Extra variables that are required by other function such as boolean con variable 
     */ 
    private $con = false; // Check to see if the connection is active 
    private $result = array(); // Any results from a query will be stored here 
    private $myQuery = "";// used for debugging process with SQL return 
    private $numResults = "";// used for returning the number of rows 
     
    // Function to make connection to database 
    public function connect(){ 
        if(!$this->con){ 
            $myconn = @($GLOBALS["___mysqli_ston"] = mysqli_connect($this->db_host, $this->db_user, $this->db_pass));  // mysql_connect() with variables defined at the start of Database class 
            ((bool)mysqli_set_charset($myconn, "utf8")); 
            if($myconn){ 
                $seldb = @mysqli_select_db($myconn, $this->db_name); // Credentials have been pass through mysql_connect() now select the database 
                if($seldb){ 
                    $this->con = true; 
                    return true;  // Connection has been made return TRUE 
                }else{ 
                    array_push($this->result,mysqli_error($GLOBALS["___mysqli_ston"]));  
                    return false;  // Problem selecting database return FALSE 
                }   
            }else{ 
                array_push($this->result,mysqli_error($GLOBALS["___mysqli_ston"])); 
                return false; // Problem connecting return FALSE 
            }   
        }else{   
            return true; // Connection has already been made return TRUE  
        }       
    } 
     
    // Function to disconnect from the database 
    public function disconnect(){ 
        // If there is a connection to the database 
        if($this->con){ 
            // We have found a connection, try to close it 
            if(@((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res)){ 
                // We have successfully closed the connection, set the connection variable to false 
                $this->con = false; 
                // Return true tjat we have closed the connection 
                return true; 
            }else{ 
                // We could not close the connection, return false 
                return false; 
            } 
        } 
    } 
     
    public function sql($sql){ 
        $query = @mysqli_query($GLOBALS["___mysqli_ston"], $sql); 
        $this->myQuery = $sql; // Pass back the SQL 
        if($query){ 
            // If the query returns >= 1 assign the number of rows to numResults 
            $this->numResults = mysqli_num_rows($query); 
            // Loop through the query results by the number of rows returned 
            for($i = 0; $i < $this->numResults; $i++){ 
                $r = mysqli_fetch_array($query); 
                   $key = array_keys($r); 
                   for($x = 0; $x < count($key); $x++){ 
                       // Sanitizes keys so only alphavalues are allowed 
                       if(!is_int($key[$x])){ 
                           if(mysqli_num_rows($query) >= 1){ 
                               $this->result[$i][$key[$x]] = $r[$key[$x]]; 
                        }else{ 
                            $this->result = null; 
                        } 
                    } 
                } 
            } 
            return true; // Query was successful 
        }else{ 
            array_push($this->result,mysqli_error($GLOBALS["___mysqli_ston"])); 
            return false; // No rows where returned 
        } 
    } 
     
    public function getCount($table) { 
        $query = @mysqli_query($GLOBALS["___mysqli_ston"], 'SELECT COUNT(*) FROM '.$table); 
        $this->myQuery = $sql; // Pass back the SQL 
        if($query){ 
            $this->result = mysqli_fetch_array($query)[0]; 
            return true; // Query was successful 
        }else{ 
            array_push($this->result,mysqli_error($GLOBALS["___mysqli_ston"])); 
            return false; // No rows where returned 
        } 
    } 
     
    // Function to SELECT from the database 
    public function select($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null){ 
        // Create query from the variables passed to the function 
        $q = 'SELECT '.$rows.' FROM '.$table; 
        if($join != null){ 
            $q .= ' JOIN '.$join; 
        } 
        if($where != null){ 
            $q .= ' WHERE '.$where; 
        } 
        if($order != null){ 
            $q .= ' ORDER BY '.$order; 
        } 
        if($limit != null){ 
            $q .= ' LIMIT '.$limit; 
        } 
        $this->myQuery = $q; // Pass back the SQL 
        // Check to see if the table exists 
        if($this->tableExists($table)){ 
            // The table exists, run the query 
            $query = @mysqli_query($GLOBALS["___mysqli_ston"], $q); 
            if($query){ 
                // If the query returns >= 1 assign the number of rows to numResults 
                $this->numResults = mysqli_num_rows($query); 
                // Loop through the query results by the number of rows returned 
                for($i = 0; $i < $this->numResults; $i++){ 
                    $r = mysqli_fetch_array($query); 
                    $key = array_keys($r); 
                    for($x = 0; $x < count($key); $x++){ 
                        // Sanitizes keys so only alphavalues are allowed 
                        if(!is_int($key[$x])){ 
                            if(mysqli_num_rows($query) >= 1){ 
                                $this->result[$i][$key[$x]] = $r[$key[$x]]; 
                            }else{ 
                                $this->result = null; 
                            } 
                        } 
                    } 
                } 
                return true; // Query was successful 
            }else{ 
                array_push($this->result,mysqli_error($GLOBALS["___mysqli_ston"])); 
                return false; // No rows where returned 
            } 
          }else{ 
              return false; // Table does not exist 
        } 
    } 
     
    // Function to insert into the database 
    public function insert($table,$params=array()){ 
        // Check to see if the table exists 
         if($this->tableExists($table)){ 
             $sql='INSERT INTO `'.$table.'` (`'.implode('`, `',array_keys($params)).'`) VALUES ("' . implode('", "', $params) . '")'; 
            $this->myQuery = $sql; // Pass back the SQL 
            // Make the query to insert to the database 
            if($ins = @mysqli_query($GLOBALS["___mysqli_ston"], $sql)){ 
                array_push($this->result,((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res)); 
                return true; // The data has been inserted 
            }else{ 
                array_push($this->result,mysqli_error($GLOBALS["___mysqli_ston"])); 
                return false; // The data has not been inserted 
            } 
        }else{ 
            return false; // Table does not exist 
        } 
    } 
     
    //Function to delete table or row(s) from database 
    public function delete($table,$where = null){ 
        // Check to see if table exists 
         if($this->tableExists($table)){ 
             // The table exists check to see if we are deleting rows or table 
             if($where == null){ 
                $delete = 'DROP TABLE '.$table; // Create query to delete table 
            }else{ 
                $delete = 'DELETE FROM '.$table.' WHERE '.$where; // Create query to delete rows 
            } 
            // Submit query to database 
            if($del = @mysqli_query($GLOBALS["___mysqli_ston"], $delete)){ 
                array_push($this->result,mysqli_affected_rows($GLOBALS["___mysqli_ston"])); 
                $this->myQuery = $delete; // Pass back the SQL 
                return true; // The query exectued correctly 
            }else{ 
                array_push($this->result,mysqli_error($GLOBALS["___mysqli_ston"])); 
                   return false; // The query did not execute correctly 
            } 
        }else{ 
            return false; // The table does not exist 
        } 
    } 
     
    // Function to update row in database 
    public function update($table,$params=array(),$where){ 
        // Check to see if table exists 
        if($this->tableExists($table)){ 
            // Create Array to hold all the columns to update 
            $args=array(); 
            foreach($params as $field=>$value){ 
                // Seperate each column out with it's corresponding value 
                $args[]=$field.'="'.$value.'"'; 
            } 
            // Create the query 
            $sql='UPDATE '.$table.' SET '.implode(',',$args).' WHERE '.$where; 
            // Make query to database 
            $this->myQuery = $sql; // Pass back the SQL 
            if($query = @mysqli_query($GLOBALS["___mysqli_ston"], $sql)){ 
                array_push($this->result,mysqli_affected_rows($GLOBALS["___mysqli_ston"])); 
                return true; // Update has been successful 
            }else{ 
                array_push($this->result,mysqli_error($GLOBALS["___mysqli_ston"])); 
                return false; // Update has not been successful 
            } 
        }else{ 
            return false; // The table does not exist 
        } 
    } 
     
    // Private function to check if table exists for use with queries 
    private function tableExists($table){ 
        $tablesInDb = @mysqli_query($GLOBALS["___mysqli_ston"], 'SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"'); 
        if($tablesInDb){ 
            if(mysqli_num_rows($tablesInDb)==1){ 
                return true; // The table exists 
            }else{ 
                array_push($this->result,$table." does not exist in this database"); 
                return false; // The table does not exist 
            } 
        } 
    } 
     
    // Public function to return the data to the user 
    public function getResult(){ 
        $val = $this->result; 
        $this->result = array(); 
        return $val; 
    } 

    //Pass the SQL back for debugging 
    public function getSql(){ 
        $val = $this->myQuery; 
        $this->myQuery = array(); 
        return $val; 
    } 

    //Pass the number of rows back 
    public function numRows(){ 
        $val = $this->numResults; 
        $this->numResults = array(); 
        return $val; 
    } 

    // Escape your string 
    public function escapeString($data){ 
        return mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $data); 
    } 
} 