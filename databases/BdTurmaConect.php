<?php


class BdTurmaConect {
    public $host = "localhost";
    public $user = "root";
    public $password = "";
    public $database = "bdturma90";

    function connectDB() {
        // Tratamento de exceções

        try {
            $this -> conn = new PDO("mysql:host={$this->host};dbname={$this->database};",
            $this->user,$this->password,
            array(PDO::MYSQL_ATTR_INIT_COMMAND =>"SET NAMES utf8"));

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->conn->query('SET NAMES utf8');
        }
        
        catch(PDOException $e) {
            echo "Não foi possivel conectar ao Servidor.\n"."<br>";
            echo "Mensagem: ".utf8_encode($e->getMessage ())."\n";
        }
    }

    // Método para executar instruções usadas nas inserções
    // e modificações de dados

    function executeQuery($query) {
        try {
            $conn = $this->connectDB();
            $resultado = $this->conn->prepare($query);

            if (!$resultado->execute()){
                $resultado = "Não foi possivel executar a instrução";
            }
            else {
                $resultado = array('sucesso'=>1);
            }
        }

        catch (PDOException $e) {
            die(print_R($e->getMessage() ) );
        }
        
        return $resultado;
    }

    // Metódo para executar instruções usadas nas consultas dos dados

    function executeSelectQuery($query) {
        try {
            $conn = $this->connectDB();
            $resultado = $this->conn->query($query);
            $resultado -> execute();

            while ($linha = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $resultset[] = $linha;
            }
            if(!empty($resultset)) {
                return $resultset;
            }
        }

        catch (PDOException $e) {
            die(print_r($e->getMessage() ) );
        }
    }

    function executeProcedure($query,$array) {
        try {
            $resultset=[];
            $conn = $this->connectDB();
            // prepare para execução da stored procedure
            $stmt = $this->conn->prepare($query);

            // Passagem dos parâmetros

            foreach ($array as $key => $value) {
                $stmt -> bindValue($key, $value);
            }

            // Executar a stored procedure
            $stmt -> execute();

            while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultset[] = $linha;
            }
        }
        
        catch (PDOException $e) {
            die(print_r($e ->getMessage() ) );
        }
        return $resultset;
    }





}







?>