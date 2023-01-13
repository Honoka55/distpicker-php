<!doctype html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" />
    <meta name="renderer" content="webkit" />
    <meta name="force-rendering" content="webkit" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>运行例</title>
</head>

<body>
    <?php
    require "require.php";
    printjs($link);
    ?>
    <form name="example" action="" method="post">
        <label>请输入地址</label>
        <?php printselects($link); ?>
        <input type="submit" name="update" id="update" value="提交" />
        <?php
        if (!empty($_POST["update"])) {
            $selectdivcode = $_POST["selectdivcode"];
            echo $divcoun;
            // mysqli_query($link, "UPDATE [your table] SET divcode = '$selectdivcode' WHERE [your condition]");
        }
        ?>
    </form>
</body>