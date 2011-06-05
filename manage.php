<?php
require 'config/app.php';
session_start();
function reload() {
    echo '<!doctype html><html><head><title>Loading...</title><meta http-equiv="refresh" content="0; url=manage.php"></head><body>Loading...</body></html>';
    exit;
}

if (isset($_GET['logout'])) {
    echo 'blabla';
    unset($_SESSION['login']);
    reload();
}

if (isset($_POST['login'])) {
    if ($_POST['user'] == $config['admin']['user'] && $_POST['pass'] == $config['admin']['pass']) {
        $_SESSION['login'] = $_POST['user'];
    } else {
        echo '登录失败！';
        exit;
    }
}
?>
<!doctype html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>订单系统管理</title>
    <link href="css/app.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
<?php if (!isset($_SESSION['login'])) { ?>
    <h3>管理员登录</h3>
    <form method="post" action="manage.php">
    <input type="hidden" name="login" value="y">
    用户名：<input type="text" name="user">
    密码：<input type="password" name="pass">
    <input type="submit" value="登录">
    </form>
<?php } else { ?>
<?php
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $orderInfo->delete($_GET['id']);
    reload();
}

if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
} else {
    $page = 1;
}
if ($page < 1) $page = 1;
$total = $orderInfo->getCount();
$pages = ceil($total / OrderInfo::LIMIT_PER_PAGE);
?>
    <div id="container">
    <h3>订单列表  <a href="manage.php?logout=y">退出登录</a></h3>
     <table cellspacing="1" cellpadding="3">
        <tr>
          <th>姓名</th>
          <th>手机</th>
          <th>地址</th>
          <th>邮编</th>
          <th>数量</th>
          <th>留言</th>
          <th>时间</th>
          <th>管理</th>
        </tr>
        <?php foreach ($orderInfo->getRows($page) as $row) { ?>
        <tr>
          <td><?php echo h($row['username']) ?></td>
          <td><?php echo h($row['mobile']) ?></td>
          <td><?php echo h($row['address']) ?></td>
          <td><?php echo h($row['zip']) ?></td>
          <td><?php echo $config['settings']['type'][$row['type']] ?></td>
          <td><?php echo nl2br(h($row['user_comment'])) ?></td>
          <td><?php echo date('Y-m-d H:i:s', $row['created_at']) ?></td>
          <td><a href="manage.php?action=delete&id=<?php echo $row['id'] ?>" onclick="return confirm('确定删除？')">删除</a></td>
        <tr>
        <?php } ?>
        <tr>
           <td colspan="8">
              页码：
              <?php for ($p = 1; $p <= $pages; $p++) { ?>
                <?php if ($p == $page) { ?>
                   <?php echo $p ?>
                <?php } else { ?>
                  <a href="manage.php?page=<?php echo $p ?>"><?php echo $p ?></a>
                <?php } ?>
              <?php } ?>
           </td>
        </tr>
     </table>
     </div>
<?php } ?>
  </body>
  <script src="js/jquery.js"></script>
  <script src="js/app.js"></script>
</html>
