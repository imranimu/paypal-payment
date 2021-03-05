<!DOCTYPE HTML>
<?php include('header.php');
      include('paypal_config.php');
      $_SESSION['EXPRESS_MARK'] = NULL;
?>
   <div class="span5">
            <!--Form containing item parameters and seller credentials needed for SetExpressCheckout Call-->
            <form class="form" action="paypal_ec_redirect.php" method="POST">
               <div class="row-fluid">
                  <div class="span6 inner-span">
                        <!--Demo Product details -->
                        <table>
                        <tr><h3> DIGITAL SLR CAMERA </h3></tr>
                        <tr><img src="img/camera.jpg" width="300" height="250"/></tr>
                        <tr><td><p class="lead"> Buyer Credentials:</p></td></tr>
                        <tr><td>Email-id:&nbsp;&nbsp;&nbsp;<input type="text" id="buyer_email" name="buyer_email" readonly/> </td></tr>
                        <tr><td>Password:<input type="text" id="buyer_password" name="buyer_password" readonly/></td></tr>
                        </table>
                  </div>
                  <div class="span6 inner-span">
                        <p class="lead"> Item Specifications:</p>
                        <table>
                        <tr><td>Item Name:</td><td><input type="text" name="L_PAYMENTREQUEST_0_NAME0" value="DSLR Camera" readonly/></td></tr>
                        <tr><td>Item ID: </td><td><input type="text" name="L_PAYMENTREQUEST_0_NUMBER0" value="A0123" readonly/></td></tr>
                        <tr><td>Description:</td><td><input type="text" name="L_PAYMENTREQUEST_0_DESC0" value="Autofocus Camera" readonly/></td></tr>
                        <tr><td>Quantity:</td><td><input type="text" name="L_PAYMENTREQUEST_0_QTY0" value="1" readonly/></td></tr>
                        <tr><td>Price:</td><td><input type="text" name="PAYMENTREQUEST_0_ITEMAMT" value="10.00" readonly/></td></tr>
                        <tr><td>Tax:</td><td><input type="text" name="PAYMENTREQUEST_0_TAXAMT"  value="2" readonly/></td></tr>
                        <tr><td>Shipping Amount:</td><td><input type="text" name="PAYMENTREQUEST_0_SHIPPINGAMT" value="5" readonly/></td></tr>
                        <tr><td>Handling Amount:</td><td><input type="text" name="PAYMENTREQUEST_0_HANDLINGAMT" value="1" readonly/></td></tr>
                        <tr><td>Shipping Discount:</td><td><input type="text" name="PAYMENTREQUEST_0_SHIPDISCAMT" value="-3" readonly/></td></tr>
                        <tr><td>Insurance Amount:</td><td><input type="text" name="PAYMENTREQUEST_0_INSURANCEAMT" value="2" readonly/></td></tr>
                        <tr><td>Total Amount:</td><td><input type="text" name="PAYMENTREQUEST_0_AMT" value="17" readonly/></td></tr>
                        <tr><td><input type="hidden" name="LOGOIMG" value=<?php echo('http://'.$_SERVER['HTTP_HOST'].preg_replace('/index.php/','img/logo.jpg',$_SERVER['SCRIPT_NAME'])); ?>/></td></tr>
                        <tr><td>Currency Code:</td><td><select name="currencyCodeType" readonly>
						<option value="USD" selected>USD</option><br></td></tr>
                        <tr><td>Payment Type: </td><td><input type="text" name="paymentType" value="Sale" readonly/><br></td></tr>

                         <tr><td colspan="2"><br/><br/><div id="paymentMethods"></div></td></tr>
						<tr><td> -- OR -- </td></tr>
						<tr><td ><input type="Submit" alt="Proceed to Checkout" class="btn btn-primary btn-large" value="Proceed to Checkout" name="checkout"/></td></tr>
                        </table>
                  </div>
               </div>
            </form>
   </div>

   <!--Script to dynamically choose a seller and buyer account to render on index page-->
    <script src="//www.paypalobjects.com/api/checkout.js" ></script>

   <script type="text/javascript">
      function getRandomNumberInRange(min, max) {
          return Math.floor(Math.random() * (max - min) + min);
      }


      var buyerCredentials = [{"email":"ron@hogwarts.com", "password":"qwer1234"},
                        {"email":"sallyjones1234@gmail.com", "password":"p@ssword1234"},
                        {"email":"joe@boe.com", "password":"123456789"},
                        {"email":"hermione@hogwarts.com", "password":"123456789"},
                        {"email":"lunalovegood@hogwarts.com", "password":"123456789"},
                        {"email":"ginnyweasley@hogwarts.com", "password":"123456789"},
                        {"email":"bellaswan@awesome.com", "password":"qwer1234"},
                        {"email":"edwardcullen@gmail.com", "password":"qwer1234"}];
      var randomBuyer = getRandomNumberInRange(0,buyerCredentials.length);

      document.getElementById("buyer_email").value =buyerCredentials[randomBuyer].email;
      document.getElementById("buyer_password").value =buyerCredentials[randomBuyer].password;


   </script>

   <script type="text/javascript">
        window.onload = function(){

          var CREATE_PAYMENT_URL  = './paypal_ec_redirect.php';
          var formdata = {PAYMENTREQUEST_0_ITEMAMT: 10, PAYMENTREQUEST_0_SHIPPINGAMT : 5,PAYMENTREQUEST_0_TAXAMT: 5, PAYMENTREQUEST_0_AMT: 20 , paymentType:'SALE', PAYMENTREQUEST_0_CURRENCYCODE: 'USD', currencyCodeType: 'USD'};

            paypal.Button.render({

                env: 'sandbox',  // sandbox | production
                locale: 'en_US',
                style: {
                    size: 'small',   // tiny | small | medium
                    color: 'gold',	// gold | blue | silver
                    shape: 'pill',	// pill | rect
                    label: 'checkout' // checkout | credit
                },
                payment: function(resolve) {
                    jQuery.post(CREATE_PAYMENT_URL,formdata,function(data) {
                        console.log("Displaying data here: " + data);
                        resolve(data); // no data.token, b/c data.token is json format
                    });
                },

                onAuthorize: function(data, actions) {

                  var EXECUTE_PAYMENT_URL  = './paypal_ec_redirect.php';

                  jQuery.post(EXECUTE_PAYMENT_URL,
                  {payToken: data.paymentID, payerId: data.payerID},function(response) {
                  // if successful navigate to success page
                  // else
                  if (response === '10486') {
                     actions.restart();

                  }});
                 return actions.redirect();

                },

                onCancel: function(data, actions) {
                    return actions.redirect();
                }

            }, '#paymentMethods');
}
   </script>
  

<?php include('footer.php') ?>
