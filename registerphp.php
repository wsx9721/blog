<?php
$servername = "localhost";
$username = "root";
$password = "";
try {
    $pdo = new PDO("mysql:host=$servername;dbname=blog",$username,$password);
}
catch(PDOException $e) {
    $data="数据库连接失败".$e->getMessage();
    header(http_response_code(500));
    die(json_encode($data));
}

if(isset($_POST['name'])&&isset($_POST['psw'])) {

    session_start();

    $name=$_POST['name'];
    $psw=$_POST['psw'];

    if ($name == "" || $psw == "") {
        $data="请确认信息完整性";
        header(http_response_code(401));
        die(json_encode($data));
    }
    else {
        $sql = "SELECT * FROM USER WHERE name = :name";
        $res=$pdo->prepare($sql);
        $res->bindValue(':name',$name);
        $res->execute();
        $result=$res->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $data="用户名已存在";
            header(http_response_code(422));
            die(json_encode($data));
        }
        else {
            $sql = "INSERT INTO USER (`name`, `pwd`)
            VALUES (:name,:psw)";
            $res=$pdo->prepare($sql);
            $res->bindValue(':name',$name);
            $res->bindValue(':psw',$psw);
            $res->execute();
            $pdo->exec($sql);

            $sql = "INSERT INTO RESULT (`name`)
            VALUES (:name)";
            $res=$pdo->prepare($sql);
            $res->bindValue(':name',$name);
            $res->execute();
            $pdo->exec($sql);

            echo json_encode("注册成功");
            $_SESSION['name']=$name;
        }
    }
}
 ?>
