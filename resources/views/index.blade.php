<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<center>
<form action = "/signup" method = "post">
<input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
<h4 style="text-align:center;">Signup</h4>
<input type="text" name="username" placeholder="username" value="NAMV" />
<input type="text" name="name" placeholder="name" value="arun" />
<input type="text" name="date_of_birth" placeholder="data of birth" value="04121998" />
<input type="text" name="street_name" placeholder="street name" value="somanur street" />
<input type="text" name="address_line_1" placeholder="address line 1" value="somanur line 1" />
<input type="text" name="address_line_2" placeholder="address line 2" value="somanur line 2" />
<input type="text" name="city" placeholder="city" value="somanur city" />
<input type="text" name="pincode" value="641668" />
<input type="text" name="state" value="TN" />
<input type="text" name="country" value="IN" />
<input type="text" name="phone_number" value="6383224535" />
<input type="text" name="email_id" value="arunmozhi52892@gmail.com" />
<input type="text" name="password" value="Arun@412" />

<button type="submit">Add User</button>
</form></center>
<center>
<form action = "/verify_otp" method = "post">
<input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
<h4 style="text-align:center;">Verify OTP</h4>
<input type="text" name="email_id" value="arunmozhi52892@gmail.com" />
<input type="text" name="otp" value="123456" />
<button type="submit">verify otp</button>
</form></center>
<center>
<form action = "/login" method = "post">
<input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
<h4 style="text-align:center;">Login</h4>
<input type="text" name="email_id" value="arunmozhi52892@gmail.com" />
<input type="text" name="password" value="Arun@412" />
<button type="submit">login</button>
</form></center>
<center>
<form action = "/update_user" method = "post">
<input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
<h4 style="text-align:center;">Update user</h4>
<input type="text" name="username" value="NAMV" />
<input type="text" name="name" value="arun" />
<input type="text" name="date_of_birth" value="04121998" />
<input type="text" name="address" value="somanur" />
<input type="text" name="phone_number" value="6383224535" />
<input type="text" name="email_id" value="arunmozhi52892@gmail.com" />
<button type="submit">Update User</button>
</form></center>
<center>
<form action = "/reset_password" method = "post">
<input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
<h4 style="text-align:center;">Reset Password</h4>
<input type="text" name="email_id" value="arunmozhi52892@gmail.com" />
<input type="text" name="password" value="Arun@412" />
<button type="submit">Change Password</button>
</form></center>
<center>
<form action = "/add_product" enctype="multipart/form-data" method = "post">
<input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
<h4 style="text-align:center;">Add Product</h4>
<input type="text" name="brand_name" value="" />
<input type="text" name="category_name" value="" />
<input type="text" name="product_name" value="" />
<input type="file" name="image"/>
<input type="text" name="product_link" value="" />
<input type="text" name="product_mrp" value="" />
<input type="text" name="product_discount" value="" />
<input type="text" name="stock_quantity" value="" />
<button type="submit">Add Product</button>
</form></center>
<center>
    <form action = "/view_product" method = "get">
    <input type="text" name="user_id" value="" />
        <h4 style="text-align:center;">View Product</h4>
        <button type="submit">View Product</button>
    </form>
</center>
<center>
<form action = "/update_product" enctype="multipart/form-data" method = "post">
<input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
<h4 style="text-align:center;">Update Product</h4>
<input type="text" name="brand_name" value="" />
<input type="text" name="category_name" value="" />
<input type="text" name="product_name" value="" />
<input type="file" name="image"/>
<input type="text" name="product_link" value="" />
<input type="text" name="product_mrp" value="" />
<input type="text" name="product_discount" value="" />
<input type="text" name="stock_quantity" value="" />

<button type="submit">Update Product</button>
</form></center>
<center>
    <form action = "/remove_product" method = "post">
        <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
        <h4 style="text-align:center;">Remove Product</h4>
        <input type="text" name="user_id" value="4" />
        <input type="text" name="product_id" value="" />
        <button type="submit">Remove product</button>
    </form>
</center>
<center>
    <form action = "/add_cart" method = "post">
        <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
        <h4 style="text-align:center;">Add Cart</h4>
        <input type="text" name="user_id" value="4" />
        <input type="text" name="product_id" value="1" />
        <input type="text" name="quantity" value="1" />
        <button type="submit">Add Cart</button>
    </form>
</center>
<center>
    <form action = "/view_cart" method = "get">
        <h4 style="text-align:center;">View Cart</h4>
        <input type="text" name="user_id" value="4" />
        <button type="submit">View Cart</button>
    </form>
</center>
<center>
    <form action = "/add_quantity" method = "post">
        <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
        <h4 style="text-align:center;">Update Quantity</h4>
        <input type="text" name="user_id" value="" />
        <input type="text" name="cart_id" value="" />
        <input type="text" name="quantity" value="" />
        <button type="submit">Update Quantity</button>
    </form>
</center>
<center>
    <form action = "/remove_cart" method = "post">
        <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
        <h4 style="text-align:center;">Remove Cart</h4>
        <input type="text" name="user_id" value="" />
        <input type="text" name="cart_id" value="" />
        <button type="submit">Remove Cart</button>
    </form>
</center>
<center>
    <form action = "/order_details" method = "any">
        <h4 style="text-align:center;">Place Order</h4>
        <input type="text" name="user_id" value="4" />
        <input type="text" name="order_payment_method" value="Google Pay" />
        <button type="submit">Place Order</button>
    </form>
</center>
<!-- <center>
    <form action = "/orders" method = "get">
        <h4 style="text-align:center;">View orders</h4>
        <input type="text" name="user_id" value="4" />
        <button type="submit">View orders</button>
    </form>
</center> -->
</body>
</html>