<?php	
	/**
	-------------------------------------
	| Queries manager
	-------------------------------------
	*/	
	class query{
		protected $pdo;
		
		public function __construct($pdo){
			$this->pdo = $pdo; 
		}
		
		public function isProductExists($array){
			try{
				$sql = 'select ID from '.$array['Table'].' where ID = '.$array['ID'].' ';
				$query = $this->pdo->prepare($sql);
				$query->execute();
				return array(
					'code' => 200,
					'product' => $query->fetchAll(\PDO::FETCH_ASSOC)
				);
			}catch(PDOException $e){
				return array(
					'code' => 300,
					'title' => 'queryBuilder!',
					'message' => $e->getMessage()
				);
			}
		}
		
		
		public function addProduct($array){
			try{
				$sql = 'insert into '.$array['Table'].' (Title, Price) values ("'.$array['Title'].'", '.$array['Price'].') ';
				$query = $this->pdo->prepare($sql);
				$query->execute();
				return array(
					'code' => 200,
					'productId' => $this->pdo->lastInsertId()
				);
			}catch(PDOException $e){
				return array(
					'code' => 300,
					'title' => 'queryBuilder!',
					'message' => $e->getMessage()
				);
			}
		}
		
		public function getCount($array){
			try{
				$sql = 'select count(ID) as products from '.$array['Table'].'';
				$query = $this->pdo->prepare($sql);
				$query->execute();
				return array(
					'code' => 200,
					'products' => $query->fetchAll(\PDO::FETCH_ASSOC)
				);
			}catch(PDOException $e){
				return array(
					'code' => 300,
					'title' => 'queryBuilder!',
					'message' => $e->getMessage()
				);
			}
		}
		
		public function getProducts($array){
			try{
				$sql = 'select * from '.$array['Table'].' order by ID desc limit '.$array['From'].', '.$array['Limit'].'';
				$query = $this->pdo->prepare($sql);
				$query->execute();
				return array(
					'code' => 200,
					'products' => $query->fetchAll(\PDO::FETCH_ASSOC)
				);
			}catch(PDOException $e){
				return array(
					'code' => 300,
					'title' => 'queryBuilder!',
					'message' => $e->getMessage()
				);
			}
		}
		
		public function getProduct($array){
			try{
				$sql = 'select * from '.$array['Table'].' where ID = '.$array['ID'].' ';
				$query = $this->pdo->prepare($sql);
				$query->execute();
				return array(
					'code' => 200,
					'product' => $query->fetchAll(\PDO::FETCH_ASSOC)
				);
			}catch(PDOException $e){
				return array(
					'code' => 300,
					'title' => 'queryBuilder!',
					'message' => $e->getMessage()
				);
			}
		}
		
		public function updateProduct($array){
			try{
				$sql = 'update '.$array['Table'].' set
				Title = "'.$array['Title'].'", Price = '.$array['Price'].'
				where ID = '.$array['ID'];
				$query = $this->pdo->prepare($sql);
				$query->execute();
				return array(
					'code' => 200,
					'productId' => $array['ID']
				);
			}catch(PDOException $e){
				return array(
					'code' => 300,
					'title' => 'queryBuilder!',
					'message' => $e->getMessage()
				);
			}
		}
		
		public function deleteProduct($array){
			try{				
				$sql = 'delete from '.$array['Table'].' where ID = '.$array['ID'];
				$query = $this->pdo->prepare($sql);
				$query->execute();
				return array(
					'code' => 200,
					'productId' => $array['ID']
				);
			}catch(PDOException $e){
				return array(
					'code' => 300,
					'title' => 'queryBuilder!',
					'message' => $e->getMessage()
				);
			}
		}
				
		public function getCart($array){
			try{
				$sql = 'select * from '.$array['Table'].' where CartID = "'.$array['CartID'].'" ';
				$query = $this->pdo->prepare($sql);
				$query->execute();
				return array(
					'code' => 200,
					'cart' => $query->fetchAll(\PDO::FETCH_ASSOC)
				);
			}catch(PDOException $e){
				return array(
					'code' => 300,
					'title' => 'queryBuilder!',
					'message' => $e->getMessage()
				);
			}
		}
		
		public function createCart($array){
			try{
				$sql = 'insert into '.$array['Table'].' (CartID) values ("'.$array['CartID'].'") ';
				$query = $this->pdo->prepare($sql);
				$query->execute();
				return array(
					'code' => 200,
					'CartID' => $this->pdo->lastInsertId()
				);
			}catch(PDOException $e){
				return array(
					'code' => 300,
					'title' => 'queryBuilder!',
					'message' => $e->getMessage()
				);
			}
		}
		
		public function addToCart($array){
			try{
				$sql = "update ".$array['Table']." set
				Products = '".$array['Products']."'
				where CartID = '".$array['CartID']."' ";
				$query = $this->pdo->prepare($sql);
				$query->execute();
				return array(
					'code' => 200
				);
			}catch(PDOException $e){
				return array(
					'code' => 300,
					'title' => 'queryBuilder!',
					'message' => $e->getMessage()
				);
			}
		}
	}
?>