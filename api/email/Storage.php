<?php

class Storage {

    private $connection;

    /**
    * Konstruktor - Erzeugt ein neues Storage-Objekt
    */
    public function __construct($name = 'yoggi', $host = '192.168.0.241',$port ='3306', $user = 'root', $pass = 'analkette12') {
        try {
            $this->connection = new PDO("mysql:host=$host;port=$port;dbname=$name;charset=utf8", $user, $pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch (PDOException $exception) {
            die($exception->getMessage());
        }
    }

    /**
    * Destruktor - Wickelt das Objekt vor dessen Vernichtung ab
    */
    public function __destruct() {
        $this->connection = null;
    }

    public function select($sql,$array = array(), $fetchMode = PDO::FETCH_ASSOC) {
  		$stmt = $this->connection->prepare($sql);
  		foreach($array as $key => $value) {
			     $stmt->bindValue("$key", $value);
      }
  		$stmt->execute();
  		return $stmt->fetchAll($fetchMode);
  	}

    public function insert($table, $data) {

  		ksort($data);

  		$fieldNames = implode(',', array_keys($data));
  		$fieldValues = ':' . implode(', :', array_keys($data));

  		$stmt = $this->connection->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");

  		foreach($data as $key => $value) {
  			$stmt->bindValue(":$key", $value);
  		}

  		$stmt->execute();
  	}

	public function update($table, $data, $where) {

		ksort($data);

		$fieldDetails = NULL;
		foreach($data as $key => $value) {
			$fieldDetails .= "$key = :$key,";
		}
		$fieldDetails = rtrim($fieldDetails, ',');

		$stmt = $this->connection->prepare("UPDATE $table SET $fieldDetails WHERE $where");

		foreach($data as $key => $value) {
			$stmt->bindValue(":$key", $value);
		}

		$stmt->execute();
	}

  public function delete($table, $where) {
		$stmt = $this->connection->prepare("DELETE FROM $table WHERE $where");
    $stmt->execute();
  }

  public function empty_truncate($table){
    $stmt = $this->connection->prepare("TRUNCATE $table");
    $stmt->execute();
  }
}
