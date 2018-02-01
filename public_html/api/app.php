<?php	
	/**
	-------------------------------------
	| App
	-------------------------------------
	*/	
	
	class App{
		
		public function __construct(){
			$this->setRouter();
		}
		
		public function setRouter(){
			$this->router = new Router;
			
			/** ----------------------------------
			| product router
			| @return: null
			----------------------------------- **/	
			
			$this->router->get('', 'index');
			$this->router->get('products', 'get-products');
			$this->router->get('product/', 'get-product');
			$this->router->get('product/delete/', 'delete-product');		
			
			$this->router->post('product/create', 'add-product');
			$this->router->post('product/update', 'update-product');
			
			/** ----------------------------------
			| cart router
			| @return: null
			----------------------------------- **/	
			$this->router->post('cart/add', 'add-cart');
			$this->router->get('cart/delete', 'delete-cart');
			$this->router->get('cart/get', 'get-cart');
	
			$this->config = require 'config/config.php';
			$this->app['database'] = new query(Connection::make($this->config['database']));
			
			$this->db = current( (Array)$this->app['database']);
			if(count((array)$this->db) == 0 OR !isset($this->db['code'])){
				$this->doRequest(
					$this->router->doRequest(
						Request::uri(),
						Request::method()
					)
				);
			}else{
				echo json_encode(array(
					'message' => '-- Please check configuration --'
				));
			}
		}
		public function doRequest($arg){
			if($arg['code'] == '404'){
				echo json_encode($arg);
			}
			switch($arg['uri']){
				case 'delete-product':
					/** ----------------------------------
					| product - delete
					| @return: json
					----------------------------------- **/	
					$product = $this->app['database']->isProductExists(array(
						'Table' => 'Products',
						'ID' => $arg['id']
					));					
					if(count($product['product']) == 0){
						echo json_encode(array(
							'message' => 'This Product does no longer exists!'
						));
					}else{
						$product = $this->app['database']->deleteProduct(array(
							'Table' => 'Products',
							'ID' => $arg['id']
						));
						echo ($product['code'] == 200)?json_encode(array('message' => 'Product ID [ '.json_encode($product['productId']).' ] successfully deleted!')):json_encode(array(
							'message' => 'This Product does no longer exists!'
						));
					}
				break;
				case 'update-product':
					/** ----------------------------------
					| product - update
					| @return: json
					----------------------------------- **/	
					$req = array();
					if(!isset($_POST['ID']) OR empty($_POST['ID'])){
						echo json_encode(array(
							'code' => 300,
							'message' => 'Product ID is missing!'
						));
					}else{
						$req['ID'] = $_POST['ID'];
						$product = $this->app['database']->isProductExists(array(
							'Table' => 'Products',
							'ID' => $req['ID']
						));					
						if(count($product['product']) == 0){
							echo json_encode(array(
								'message' => 'This Product does no longer exists!'
							));
						}else{
							if(isset($_POST['title']) AND !empty($_POST['title'])){
								$req['Title'] = $_POST['title'];
							}
							if(isset($_POST['price']) AND !empty($_POST['price']) AND is_numeric($_POST['price'])){
								$req['Price'] = $_POST['price'];
							}							
							$updateProduct = $this->app['database']->updateProduct(array(
								'Table' => 'Products',
								'ID' => $req['ID'],
								'Title' => $req['Title'],
								'Price' => $req['Price']
							));
							echo ($product['code'] == 200)?json_encode(array('message' => 'Product ID [ '.json_encode($req['ID']).' ] successfully updated!')):json_encode(array(
								'message' => 'This Product does no longer exists!'
							));
						}
					}
				break;				
				case 'add-product':
					/** ----------------------------------
					| product - add
					| @return: json
					----------------------------------- **/	
					if(
						!isset($_POST['title']) OR 
						empty($_POST['title']) OR 
						!isset($_POST['price']) OR 
						empty($_POST['price']) OR 
						!is_numeric($_POST['price'])
					){
						echo json_encode(array(
							'code' => 300,
							'message' => 'Missing parameter!'
						));
					}else{
						$addProduct = $this->app['database']->addProduct(array(
							'Table' => 'Products',
							'Title' => $_POST['title'],
							'Price' => $_POST['price']
						));					
						echo ($addProduct['code'] == 200)?json_encode(array(
							'id' => $addProduct['productId'],
							'message' => 'Product successfully inserted!'
						)):json_encode(array(
							'code' => $addProduct['code'],
							'message' => $addProduct['message']
						));
					}
				break;
				case 'get-products':
					/** ----------------------------------
					| product - get all products
					| @return: json
					----------------------------------- **/	
					if(isset($_GET['getCount'])){
						$products = $this->app['database']->getCount(array(
							'Table' => 'Products'
						));
						echo ($products['code'] == 200)?(count($products['products']) > 0)?json_encode($products['products']):json_encode(array(
							'message' => 'This Product does no longer exists!'
						)):json_encode($products);
					}else{
						$products = $this->app['database']->getProducts(array(
							'Table' => 'Products',
							'From' => (isset($_GET['from']) AND !empty($_GET['from']))?$_GET['from']:'0',
							'Limit' => (isset($_GET['limit']) AND !empty($_GET['limit']))?$_GET['limit']:'5'
						));
						echo ($products['code'] == 200)?(count($products['products']) > 0)?json_encode(array('Products' => $products['products'])):json_encode(array(
							'message' => 'No Products have been found!'
						)):json_encode($products);
					}
				break;
				case 'get-product':
					/** ----------------------------------
					| product - get single product
					| @return: json
					----------------------------------- **/	
					$product = $this->app['database']->getProduct(array(
						'Table' => 'Products',
						'ID' => $arg['id']
					));
					echo ($product['code'] == 200)?(count($product) > 0)?json_encode($product['product']):json_encode(array(
						'message' => 'This Product does no longer exists!'
					)):json_encode($product);
				break;
				
				/** ----------------------------------
				| cart
				----------------------------------- **/
				
				case 'get-cart':
				
					/** ----------------------------------
					| cart - get products
					| @return: json
					----------------------------------- **/	
					if(!isset($_GET['CartID']) OR empty($_GET['CartID'])){
						echo json_encode(array(
							'code' => 300,
							'message' => 'Missing parameter!'
						));
					}else{
						$cart = $this->app['database']->getCart(array(
							'Table' => 'Carts',
							'CartID' => $_GET['CartID']
						));
						if(count($cart['cart']) == 0){
							echo json_encode(array(
								'CartID' => $_GET['CartID'],
								'Products' => []
							));
						}else{
							$cart = $cart['cart'][0];
							echo json_encode(array(
								'CartID' => $_GET['CartID'],
								'Products' => json_decode($cart['Products'], true)
							));
						}
					}
				break;
				case 'delete-cart':				
					/** ----------------------------------
					| cart - delete a product
					| @return: json
					----------------------------------- **/	
					if(
						!isset($_GET['cartID']) OR 
						empty($_GET['cartID']) OR 
						!isset($_GET['productID']) OR 
						empty($_GET['productID']) OR 
						!is_numeric($_GET['productID'])
					){
						echo json_encode(array(
							'code' => 300,
							'message' => 'Missing parameter!'
						));
					}else{
						$cart = $this->app['database']->getCart(array(
							'Table' => 'Carts',
							'CartID' => $_GET['cartID']
						));
						if(count(json_decode($cart['cart'][0]['Products'], true)) == 0){
							echo json_encode(array(
								'code' => 300,
								'message' => 'Empty Cart!'
							));
						}else{
							$productID = $_GET['productID'];
							$cart = $cart['cart'][0];
							$cartProducts = isset($cart['Products'])?json_decode($cart['Products'], true):array();							
							unset($cartProducts[$productID]);
							$addToCart = $this->app['database']->addToCart(array(
								'Table' => 'Carts',
								'CartID' => $_GET['cartID'],
								'Products' => json_encode($cartProducts)
							));
							echo ($addToCart['code'] == 200)?json_encode(array(
								'id' => $productID,
								'message' => 'Product successfully deleted from cart!'
							)):json_encode(array(
								'code' => $addToCart['code'],
								'message' => $addToCart['message']
							));
						}
					}
				break;
				case 'add-cart':
					/** ----------------------------------
					| cart - add to cart
					| @return: json
					----------------------------------- **/	
					if(
						!isset($_POST['cartID']) OR 
						empty($_POST['cartID']) OR 
						!isset($_POST['productID']) OR 
						empty($_POST['productID']) OR 
						!is_numeric($_POST['productID'])
					){
						echo json_encode(array(
							'code' => 300,
							'message' => 'Missing parameter!'
						));
					}else{
						$cart = $this->app['database']->getCart(array(
							'Table' => 'Carts',
							'CartID' => $_POST['cartID']
						));
						if(count($cart['cart']) == 0){
							$CartID = $this->app['database']->createCart(array(
								'Table' => 'Carts',
								'CartID' => $_POST['cartID']
							));
						}else{							
							$productID = $_POST['productID'];
							$cart = $cart['cart'][0];
							$cartProducts = isset($cart['Products'])?json_decode($cart['Products'], true):array();
							if(count($cartProducts) < 3){
								$product = $this->app['database']->getProduct(array(
									'Table' => 'Products',
									'ID' => $productID
								));						
								$cartProducts[$productID] = array(
									'ID' => $productID,
									'Title' => $product['product'][0]['Title'],
									'Price' => $product['product'][0]['Price']
								);
								$addToCart = $this->app['database']->addToCart(array(
									'Table' => 'Carts',
									'CartID' => $_POST['cartID'],
									'Products' => json_encode($cartProducts)
								));
								echo ($addToCart['code'] == 200)?json_encode(array(
									'id' => $productID,
									'message' => 'Product successfully inserted to cart!'
								)):json_encode(array(
									'code' => $addToCart['code'],
									'message' => $addToCart['message']
								));
							}else{
								echo json_encode(array(
									'code' => 300,
									'message' => 'You reached cart limitation !'
								));
							}
						}
					}
				break;
				default:
					echo json_encode(array(
						'code' => 200,
						'message' => '--'
					));
				break;
			}
		}
	}
?>