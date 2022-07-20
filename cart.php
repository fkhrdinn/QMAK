<?php
	include 'dbconnect.php';
	include 'header.php';

	if(empty($_SESSION['EMAIL']))
	{
		header("Location:login.php");
	}
	$number = 0;

	$email = $_SESSION['EMAIL'];
    $customerQuery = "SELECT * FROM CUSTOMERS WHERE EMAIL = '$email'";
    $customerResult = oci_parse($dbconn, $customerQuery);
    oci_execute($customerResult);
	$customerID = NULL;

    while($row = oci_fetch_array($customerResult))
    {
        $customerID = $row['CUSTOMER_ID'];  
    }

	$cartQuery = "SELECT * FROM CARTS JOIN PRODUCTS USING (PRODUCT_ID) JOIN MEDIA USING (PRODUCT_ID) WHERE CARTS.CUSTOMER_ID = '$customerID' AND CARTS.PAID_AT IS NULL";
	$cartResult = oci_parse($dbconn, $cartQuery);
	$cartStatus = oci_execute($cartResult);
?>

<section class="h-100 gradient-custom">
	<div class="container py-5">
		<div class="row d-flex justify-content-center my-4">
			<div class="col-md-8">
				<div class="card mb-4">
					<div class="card-header py-3">
						<h5 class="mb-0">Item in carts</h5>
					</div>
					<div class="card-body" id="cart-items">
						<form id="deleteCart" action="" method="POST"></form>
						<form action="cartPay.php" method="POST">
						<?php while($row = oci_fetch_array($cartResult))
						{
							?>
								<span id="check-cart">
								<!-- Single item -->
								<div class="row">
									<div class="d-none">
										<input type="number" value="<?php echo $row['CART_ID']; ?>" name="cartID[]">
									</div>
									<div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
									<!-- Image -->
										<div class="bg-image hover-overlay hover-zoom ripple rounded" data-mdb-ripple-color="light">
											<img src="Media/ProductPhotos/<?php echo $row['MEDIA_NAME']; ?>"
											class="w-100" alt="Blue Jeans Jacket" />
										</div>
										<!-- Image -->
									</div>

									<div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
										<!-- Data -->
										<p><strong><?php echo $row['PRODUCT_NAME']; ?></strong></p>
										<p><?php echo $row['PRODUCT_DESCRIPTION']; ?></p>
							
										<button name="removeCart" value = "<?php echo $row['CART_ID']; ?>" type="submit" class="btn btn-danger btn-sm me-1 mb-2" data-mdb-toggle="tooltip" title="Remove item">
											<i class="fs-5 bi bi-trash"></i>
										</button>
										<!-- Data -->
									</div>

									<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
										<!-- Quantity -->
										<div class="d-flex mb-4" style="max-width: 300px">
											<button class="btn btn-primary px-3 me-2" type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown();updateCarts()">
												<i class="fs-5 bi bi-dash"></i>
											</button>

											<div class="form-outline">
												<input onchange="updateCarts();" id="form<?php echo $number ?>" min="1" name="quantity[]" value="1" type="number" class="form-control" />
											</div>

											<button class="btn btn-primary px-3 ms-2" type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp();updateCarts()">
												<i class="fs-5 bi bi-plus"></i>
											</button>
										</div>
										<!-- Quantity -->

										<!-- Price -->
										<p class="text-start text-md-center">
											<strong id="cart-price<?php echo $number ?>"><?php echo "RM ". number_format($row['PRODUCT_PRICE'], 2); ?></strong>
										</p>
										<!-- Price -->
									</div>
								</div>
								</span>
							<?php
							$number++;
						}?>
						<div id="success">
							<p class="text-center text-success"><?php if(isset($_GET['message'])) { echo $_GET['message']; }?></p>
						</div>
						<!-- <hr class="my-4" /> -->
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="card mb-4">
					<div class="card-header py-3">
						<h5 class="mb-0">Summary</h5>
					</div>
				<div class="card-body">
					<div class="card bg-primary text-white rounded-3">
                  		<div class="card-body">
                    		<div class="d-flex justify-content-between align-items-center mb-4">
                      			<h5 class="mb-0">Card details</h5>
                    		</div>

                      		<div class="form-outline form-white mb-4">
                        		<input type="text" id="cardName" class="form-control form-control" placeholder="Cardholder's Name" />
                        		<label class="form-label" for="cardName">Cardholder's Name</label>
							</div>

                      		<div class="form-outline form-white mb-4">
                        		<input type="text" id="cardNumber" class="form-control form-control" placeholder="1234 5678 9012 3457" minlength="19" maxlength="19" />
								<label class="form-label" for="cardNumber">Card Number</label>
                      		</div>

                      		<div class="row mb-4">
                        		<div class="col-md-6">
                          			<div class="form-outline form-white">
                            			<input type="text" id="exp" class="form-control form-control" placeholder="MM/YYYY" id="exp" minlength="7" maxlength="7" />
										<label class="form-label" for="exp">Expiration</label>
                          			</div>
                        		</div>

                        		<div class="col-md-6">
                          			<div class="form-outline form-white">
                            			<input type="password" id="CVV" class="form-control form-control" placeholder="&#9679;&#9679;&#9679;" minlength="3" maxlength="3" />
										<label class="form-label" for="CVV">CVV</label>
                          			</div>
                        		</div>
                      		</div>
							  	<ul class="list-group list-group-flush">						
									<li class="list-group-item d-flex justify-content-between align-items-center border-0 mb-3 rounded">
										<div>
											<strong>Total amount</strong>
										</div>
										<span><strong id="total-price"></strong></span>
										<input type="number" class="d-none" id="totalPrice" name="totalPrice">
									</li>
								</ul>

								<button type="submit" id="button-pay" onclick="updateCarts();" name="form-checkout" class="btn btn-dark btn-lg btn-block">
									Pay Now
								</button>
							</div>
						</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	setTimeout(() => {
    const box = document.getElementById('success');
    box.style.display = 'none';
    }, 3000);
	
	var check = document.getElementById("check-cart")
	if(check)
	{
		var button = document.getElementById("button-pay").removeAttribute("disabled");
	}else
	{
		var button = document.getElementById("button-pay").setAttribute("disabled", "disabled");
	}

	function updateCarts()
	{
		var priceRows = document.getElementsByClassName("text-start text-md-center")
		var quantityRows = document.getElementsByClassName("form-outline")
		var totalPrice = 0
		var totalRow = document.getElementById("total-price")
		

		for(var i = 0; i < priceRows.length; i++)
		{
			var cartRow = parseFloat(document.getElementById("cart-price" + i).innerText.replace('RM', ''))
			var quantityRow = parseFloat(document.getElementById("form" + i).value)
			totalPrice = totalPrice + (cartRow * quantityRow)
		}

		totalRow.innerText = "RM " + totalPrice.toFixed(2)
		document.getElementById("totalPrice").setAttribute("value" , totalPrice.toFixed(2))
	}
	
	updateCarts()
</script>