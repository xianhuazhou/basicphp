<?php require 'config/config.php' ?>
<!doctype html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>订单系统</title>
    <link href="css/app.css?1" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <?php
      if (isset($errors)) {
          echo "提交订单失败，错误信息如下：<hr>";
          foreach ($errors as $error) {
              echo $error . "<br>";
          }
      }
    ?>
    <h2>订单详细资料：</h2>
    <form method="post" action="order.php" id="f">
      姓名*：<input type="text" name="username" value=""><span id="username_error" class="error"></span><br>
      手机*：<input type="text" name="mobile" maxlength="11" value=""><span id="mobile_error" class="error"></span><br>
      地址*：<input type="text" name="address" size="80" value=""><span id="address_error" class="error"></span><br>
      邮编：<input type="text" name="zip" size="80" value=""><span id="zip_error" class="error"></span><br>
      订单数量：
      <select name="type" size="1">
        <?php foreach ($config['settings']['type'] as $k => $v) { ?>
            <option value="<?php echo $k ?>"><?php echo $v ?></option> 
        <?php } ?>
      </select>
      <br>
      留言：<textarea name="user_comment" cols="10" rows="5"></textarea><br>
      <input type="submit" value="提交订单">
    </form>
  </body>
  <script src="js/jquery.js"></script>
  <script src="js/app.js?6"></script>
</html>
