<?php

class Model{
	
	public $conn = null;

    public function __construct(){
        return $this->connect();
    }

	public function connect(){
        $DATABASE = [
            "servername"=>"mysql.hibots.com.br",
            "username"=>"hibots19",
            "password"=>"simplex1234",
            "dbname"=>"hibots19"
         ];
		try {
            $conn = new PDO("mysql:host=".$DATABASE["servername"].";dbname=".$DATABASE["dbname"],$DATABASE["username"],$DATABASE["password"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         	$this->sucess = true;        
        }catch(PDOException $e){
            $conn = null;
            $this->sucess = false;
            $this->error = $e->getMessage();
        }
		$this->conn = $conn;
		$this->db_connected = $conn;
		return $this->sucess; 
	}

	public function dbConnected(){
		return $this->db_connected;
	}

	public function getConnection(){
		return $this->conn;
	}

	private function command($prepare,$table,$dados){
        try {
            $stmt = $this->conn->prepare($prepare);
            $stmt->execute($dados);
            $this->sucess = $stmt->rowCount();
        } catch(PDOException $e) {
        	$this->sucess = false;
            $this->error = $e->getMessage();
        }
        return $this->sucess;
    }
	
	public function insert($table,$dados){
        $prepare = "INSERT INTO `$table`(".implode(',', array_keys($dados)).") VALUES (".implode(',',$dados).");";
        echo $prepare;
        return $this->command($prepare,$table,$dados);
    }

    public function update($table,$clause,$dados){
        foreach(array_keys($dados) as $d){
        	$prepare_dados[] = " `$d` = ".$dados[$d];
        }
        foreach(array_keys($clause) as $c){
        	$prepare_clause .= " `$c` = ".$clause[$c];
        }
    	$prepare = "update `$table` set ".implode(',',$prepare_dados)." where ".implode(',',$prepare_clause);
        return $this->command($prepare,$table,$dados);
    }
    public function delete($table,$clause){
        foreach(array_keys($clause) as $c){
        	$prepare_clause .= " `$c` = ".$clause[$c];
        }
        $prepare = "delete from `$table` where ".implode(',',$prepare_clause);
        return $this->command($prepare,$table,$dados);
    }

    public function get($table,$params,$clause){
        foreach(array_keys($params) as $p){
        	$prepare_params[] = " `$p` = ".$params[$p];
        }
        foreach(array_keys($clause) as $c){
        	$prepare_clause .= " `$c` = ".$clause[$c];
        }
    	$prepare = "select ".implode(',',$prepare_params)." from `$table` where ".implode(',',$prepare_clause);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function query($query){
        $statement = $this->conn->query($query);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}


?>