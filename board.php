<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
<?php

	// DB接続設定
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//データベース内にテーブルを作成
$sql = "CREATE TABLE IF NOT EXISTS board"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"//投稿番号
. "name char(32),"//名前
. "comment TEXT,"//コメント
. "date TEXT,"//日付
. "password TEXT"//パスワード
.");";
$stmt = $pdo->query($sql);

    
    //削除機能
    if (!empty($_POST["delete"]) && !empty($_POST["delpassword"])) {
        $delete=$_POST["delete"];
        $delpassword=$_POST["delpassword"];

        //入力したデータレコードを削除
        $id=$delete;
        $password=$delpassword;
        $sql='delete from board where id=:id && password=:password';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        
        }
        
    

   
    //投稿機能
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){
        if(!empty($_POST["hidden_num"])){

             $hidden_num=$_POST["hidden_num"]; 
             //投稿の変更
             $id=$hidden_num;//変更したい投稿番号
	         $name=$_POST["name"];//変更したコメント
             $comment=$_POST["comment"]; //変更したコメント
             $password=$_POST["password"];//変更したパスワード
             $date=date("Y年m月d日H時i分");
	         $sql = 'UPDATE board SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id';
             $stmt = $pdo->prepare($sql);
	         $stmt->bindParam(':name', $name, PDO::PARAM_STR);
             $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
             $stmt->bindParam(':date', $date, PDO::PARAM_STR);
             $stmt->bindParam(':password', $password, PDO::PARAM_STR);
             $stmt->bindParam(':id', $id, PDO::PARAM_INT);
             $stmt->execute();
        }else{
            $sql=$pdo->prepare("INSERT INTO board(name,comment,date,password)VALUES(:name, :comment, :date, :password)");
            $sql->bindParam(':name', $name, PDO::PARAM_STR);
            $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql->bindParam(':date', $date, PDO::PARAM_STR);
            $sql->bindParam(':password', $password, PDO::PARAM_STR);
            $name=$_POST["name"];
            $comment=$_POST["comment"];
            $date=date("Y年m月d日H時i分");
            $password=$_POST["password"];
            $sql->execute();//クエリを実行（$comment, $name,$date,$passwardの中身をバインド）
        }
    }

    //編集機能
    if (!empty($_POST["edit"]) && !empty($_POST["edipassword"])) {
        $edit=$_POST["edit"];
        $edipassword=$_POST["edipassword"];

        $id=$edit;
        $password=$edipassword;

        //編集番号を取得
        $sql='SELECT*FROM board where id=:id && password=:password';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $result=$stmt->fetchAll();
        foreach ($result as $row) {
            //$rowの中にはテーブルのカラム名が入る
            $editbango=$row['id'];
            $editname=$row['name'];
            $editcomment=$row['comment'];
            $editpass=$row['password'];
           
        }
    }
    
    ?>

    <form action="" method="post">
    <input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)){echo $editname;}?>">
    <br>
    <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($editcomment)){echo $editcomment;} ?>">
    <br>
    <input type="text" name="password" placeholder="パスワード" value="<?php if(isset($editpass)){echo $editpass;}?>">
    <input type="hidden" name="hidden_num" value="<?php if(isset($editbango)){echo $editbango;}?>">
    <input type="submit" name="submit1">
    </form>

    <form action="" method="post">
    <input type="text" name="delete" placeholder="削除対象番号">
    <br>
    <input type="text" name="delpassword" placeholder="パスワード">
    <input type="submit" name="submit2">
    </form>

    <form action="" method="post">
    <input type="text" name="edit" placeholder="編集対象番号">
    <br>
    <input type="text" name="edipassword" placeholder="パスワード">
    <input type="submit" name="submit3">
    <br>
    </form>

<?php

    //テーブルのデータを表示
    $sql='SELECT*FROM board';
    $stmt=$pdo->query($sql);
    $result=$stmt->fetchAll();

    foreach($result as $row){
        echo $row['id'].'<>';
        echo $row['name'].'<>';
        echo $row['comment'].'<>';
        echo $row['date'].'<br>';
        //echo $row['password'].'<>'.'<br>';
        echo "<hr>";
}
?>
