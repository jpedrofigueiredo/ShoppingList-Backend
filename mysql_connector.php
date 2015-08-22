 <?php
    class DataBase
        {
        var $server, $username, $password, $database;

        function __construct($mysql_config)
            {
            $this->server = $mysql_config['host'];
			$this->database = $mysql_config['db'];
			$this->username = $mysql_config['user'];
			$this->password = $mysql_config['password'];
            }

        function save($itemName, $itemCount)
            {
				if(empty($itemName)||empty($itemCount)) {
					die(json_encode(array('code' => 'error', 'comment' => 'parameter missing')));
				}
            //connect to db
            $handler = new mysqli($this->server, $this->username, $this->password, $this->database);
			
			//check if connection successful
			if ($handler->connect_error) {
				die(json_encode(array('code' => 'error', 'comment' => $handler->connect_error)));
			}
			
			//prepare query
			$stmt = $handler->prepare("INSERT into ShoppingList(item,count) VALUES(?,?)");
			$stmt->bind_param('ss', $itemName, $itemCount);

			//execute query and check if successful
			if ($stmt->execute()){
				$result = json_encode(array('code' => 'success', 'comment' => $itemName.' saved'));
			} else {
				$result = json_encode(array('code' => 'error', 'comment' => $stmt->error));
			}
		
			//close connection
			$stmt->close();
			
			//return result
			return $result;
            }

        function update($itemName, $itemCount)
            {
			if(empty($itemName)||empty($itemCount)){
				die(json_encode(array('code' => 'error', 'comment' => 'parameter missing')));
				}
            //connect to db
            $handler = new mysqli($this->server, $this->username, $this->password, $this->database);
			
			//check if connection successful
			if ($handler->connect_error) {
				die(json_encode(array('code' => 'error', 'comment' => $handler->connect_error)));
			}
			
			//prepare query
			$stmt = $handler->prepare("UPDATE ShoppingList SET count = ? WHERE item = ?");
			$stmt->bind_param('ss', $itemCount, $itemName);

			//execute query and check if successful
			if ($stmt->execute()){
				$result = json_encode(array('code' => 'success', 'comment' => $itemName.' updated'));
			} else {
				$result = json_encode(array('code' => 'error', 'comment' => $stmt->error));
			}
		
			//close connection
			$stmt->close();
						
			//return result
			return $result;
            }
			
        function delete($itemName)
            {
			if(empty($itemName)){
				die(json_encode(array('code' => 'error', 'comment' => 'parameter missing')));
			}
			//connect to db
            $handler = new mysqli($this->server, $this->username, $this->password, $this->database);
			
			//check if connection successful
			if ($handler->connect_error) {
				die(json_encode(array('code' => 'error', 'comment' => $handler->connect_error)));
			}
			
			//prepare query
			$stmt = $handler->prepare("DELETE FROM ShoppingList WHERE item = ?");
			$stmt->bind_param('s', $itemName);

			//execute query and check if successful
			if ($stmt->execute()){
				$result = json_encode(array('code' => 'success', 'comment' => $itemName.' deleted'));
			} else {
				$result = json_encode(array('code' => 'error', 'comment' => $stmt->error));
			}
		
			//close connection
			$stmt->close();
						
			//return result
			return $result;
            }
			
		function exists($itemName)
            {
			if(empty($itemName)){
				die(json_encode(array('code' => 'error', 'comment' => 'parameter missing')));
				}
			//connect to db
            $handler = new mysqli($this->server, $this->username, $this->password, $this->database);
			
			//check if connection successful
			if ($handler->connect_error) {
				die(json_encode(array('code' => 'error', 'comment' => $handler->connect_error)));
			}
			
			//prepare query
			$stmt = $handler->prepare("SELECT item FROM ShoppingList WHERE item = ?");
			$stmt->bind_param('s', $itemName);
			//execute query
			$stmt->execute();
			
			//bind the result
			$stmt->store_result();
			if ($stmt->num_rows > 0){
				$itemExists = true;
			} else {
				$itemExists = false;
			}
			
			//close connection
			$stmt->close();
			return $itemExists;
            
        }
			
		function listall()
            {
			//connect to db
            $handler = new mysqli($this->server, $this->username, $this->password, $this->database);
			
			//check if connection successful
			if ($handler->connect_error) {
				die(json_encode(array('code' => 'error', 'comment' => $handler->connect_error)));
			}
			
			//prepare query
			$stmt = $handler->prepare("SELECT item, count FROM ShoppingList ORDER BY item ASC");
			//execute query
			$stmt->execute();
			
			//bind the result
			$stmt->bind_result($item_name, $item_count);
			
			//create array
			$stack = array();
			
			//put all row into array
			while ($stmt->fetch()) {
				$listdata = array('item' => $item_name, 'count' => $item_count);
				array_push($stack, $listdata);
			}
			
			//close connection
			$stmt->close();
			
			//array to json
			return json_encode($stack);
            }
        
		function clear()
            {
			//connect to db
            $handler = new mysqli($this->server, $this->username, $this->password, $this->database);
			
			//check if connection successful
			if ($handler->connect_error) {
				die(json_encode(array('code' => 'error', 'comment' => $handler->connect_error)));
			}
			
			//prepare query
			$stmt = $handler->prepare("TRUNCATE ShoppingList");
			$stmt->bind_param('s', $itemName);

			//execute query and check if successful
			if ($stmt->execute()){
				$result = json_encode(array('code' => 'success', 'comment' => 'list cleared'));
			} else {
				$result = json_encode(array('code' => 'error', 'comment' => $stmt->error));
			}
		
			//close connection
			$stmt->close();
						
			//return result
			return $result;
            }
		
		}

?> 
