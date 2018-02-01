/**
-------------------------------------
| gogo js
-------------------------------------
*/
jQuery(function(){
	window.gogo = {
		uId: function(){
			return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c){
				var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
				return v.toString(16);
			}).toUpperCase();
		},
		cart: {
			addProduct: function(arg){
				jQuery.ajax({
					url: 'http://api.gogo.com/cart/add',
					type: 'POST',
					data: {
						'cartID': arg.cartID,
						'productID': arg.productID
					},
					success: function(r){
						console.log(r);
						gogo.cart.getCount();
					}
				});
			},
			deleteProduct: function(arg){
				jQuery.ajax({
					url: 'http://api.gogo.com/cart/delete',
					data: {
						'cartID': arg.cartID,
						'productID': arg.productID
					},
					success: function(r){
						gogo.cart.getCount();
					}
				});
			},
			getCount: function(){
				jQuery.get('http://api.gogo.com/cart/get?getCount&CartID='+localStorage.getItem('cartId'), function(r){
					r = JSON.parse(r);
					if(r.Products !== null){
						jQuery('.cart').addClass('active');
						jQuery('.cart span').text(Object.keys(r.Products).length);
						jQuery('.prodList ul').html('');
						jQuery.each(r.Products, function(){
							jQuery('.prodList ul').append('<li><span class="price">'+this.Price+' PLN</span><span product-id="'+this.ID+'" class="delete"></span>'+this.Title+'</li>');
						});						
						jQuery('.prodList ul').append('<li><span class="price">'+r.TotalPrice+' PLN</span>Total: </li>');
						
						jQuery('.prodList .delete').click(function(){
							gogo.cart.deleteProduct({
								'cartID': localStorage.getItem('cartId'),
								'productID': jQuery(this).attr('product-id')
							});
						});
					}
					var prodList = jQuery('.container .prodList');
					jQuery('.cart.active').mouseenter(function(){
						prodList.show();
					});
					jQuery('.products-section').mouseenter(function(){
						prodList.hide();
					});
				});
			},
		},
		
		pagination: function(){
			jQuery.get('http://api.gogo.com/products?getCount', function(r){
				r = JSON.parse(r);
				jQuery('.pagination').html('');
				for(var i=1;i<=Math.ceil(r[0].products/3);i++){
					jQuery('.pagination').append('<div class="page">'+i+'</div>');
				}
				jQuery('.products h1').text(r[0].products+' Product(s) found');
				jQuery('.pagination .page:first').addClass('current');
				jQuery('.pagination .page').click(function(){
					jQuery('.pagination .page').removeClass('current');
					jQuery(this).addClass('current');
					gogo.products.getAll({
						'from': (jQuery(this).text() - 1) * 3,
						'limit': 3
					});
				});
			});
		},
		buyNow: function(arg){
			jQuery('.productsList .addToCart').remove();
			var product = jQuery('.productsList .product.buying .image').clone();
			product.addClass('addToCart');
			jQuery('.productsList').after(product);			
			jQuery('.addToCart').css({
				'top': jQuery('.productsList .product.buying').offset().top,
				'left': jQuery('.productsList .product.buying').offset().left,
			}).stop().animate({
				'opacity': '0.5',
				'top': jQuery('.cart').offset().top,
				'left': jQuery('.cart').offset().left - 150,
			}, 1500, function(){
				gogo.cart.addProduct({
					'cartID': localStorage.getItem('cartId'),
					'productID': arg
				});
				jQuery('.addToCart').remove();
			});
		},
		init: function(arg){
			jQuery('.prodList').css({
				'top': jQuery('.cart').offset().top + 40,
				'left': jQuery('.cart').offset().left - 170,
			});
			if(localStorage.getItem('cartId') === null){
				localStorage.setItem('cartId', gogo.uId());
			}
	
			gogo.cart.getCount();
			gogo.products.getAll({
				'from': 0,
				'limit': 3
			});
			gogo.pagination();
		},
		products:{
			getAll: function(arg){
				jQuery.get('http://api.gogo.com/products?from='+arg.from+'&limit='+arg.limit, function(r){
					r = JSON.parse(r);
					if(r.Products.length > 0){
						jQuery('.productsList').html('');
						jQuery.each(r.Products, function(){
							jQuery('.productsList').append(''
								+'<div class="product">'
									+'<div class="image"></div>'
									+'<div class="name">'+this.Title+'</div>'
									+'<div class="price">'+this.Price+' PLN</div>'
									+'<div product-id="'+this.ID+'" class="buy">Buy</div>'
								+'</div>'
							+'');
						});						
						jQuery('.productsList .buy').click(function(){
							jQuery(this).closest('.product').addClass('buying');
							setTimeout(function(){
								jQuery('.buying').removeClass('buying');
							}, 1000*3);
							gogo.buyNow(jQuery(this).attr('product-id'));
						});
					}
				});
			}
		}
	}
	gogo.init();
});