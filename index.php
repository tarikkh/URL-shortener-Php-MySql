<?php
//  is receive q
if (isset($_GET["q"])) {

    $shortcut=htmlentities($_GET["q"]);
    try {
    $con= new PDO("mysql:host=localhost;dbname=bitly;charset=utf8","root","");

    } catch (Exception $e) {
        die("Error:".$e->getMessage());
    }
    $sql="SELECT count(*) as x from links where shortcut=?";
    $req=$con->prepare($sql) or die(print_r($con->errorInfo()));
    $req->execute(array($shortcut));
    while($row=$req->fetch()){
        if ($row["x"]!=1) {
            header("location:../project/?error=true&message=the unknown URL address");
            exit();
        }

    }
    //redirect
    $sql="select * from links where shortcut= ? ";
    $req=$con->prepare($sql) or die(print_r($con->errorInfo()));
    $req->execute(array($shortcut));
    while ($row=$req->fetch()) {
        header("location:".$row["url"]);
        exit();
    }
}

// is send the form
if (isset($_POST["url"])) {
    // variable
    $url = $_POST["url"];
    // is a link ?
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        //not a link
        header("Location:../project/?error=true&message=The address url not valid ");
        exit();
    }
    //shortcut
    $shortcut=crypt($url,time());
    //already  send
    try{
        $con = new PDO("mysql:host=localhost;dbname=bitly;charset=utf8","root","");
    }catch(Exception $e){
        die("Error:".$e->getMessage());
    }
    $sql="SELECT COUNT(*) AS x from links where url = ?";
    $req=$con->prepare($sql) or die(print_r($con->errorInfo()));
    $req->execute(array($url));
    while ($row=$req->fetch()) {
        if ($row["x"] !=0) {
            header("location:../project/?error=true&message=address already shortened");
            exit();
        }
    }
    $sql="INSERT INTO links(url,shortcut) VALUES(?,?);";
    $req=$con->prepare($sql) or die(print_r($con->errorInfo()));
    $req->execute(array($url , $shortcut));
    header("location:../project/?short=".$shortcut);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../project/styles/default.css" type="text/css">
    <link rel="shortcut icon" href="img/favico.png" type="image/x-icon">
    <title>Express url shortener</title>
</head>

<body>
    <section id="hello">
        <div class="container">
            <header>
                <img src="img/logo.png" alt="logo" id="logo">
            </header>
            <h1>A long URL? shorten it</h1>
            <h2>much better and shorter than the others</h2>
            <form action="../project/" method="post">
                <input type="url" name="url" placeholder="paste a link to shorten" required>
                <input type="submit" value="Shorten">
            </form>

            <?php
            if (isset($_GET["error"]) && isset($_GET["message"])) { ?>
                <div class="center">
                    <div class="result">
                        <b> <?php echo htmlspecialchars($_GET["message"]);
                                                                     ?>
                        </b>
                    </div>
                </div>
                <?php } elseif(isset($_GET["short"])){ ?>
                <div class="center">
                    <div class="result">
                        <b>Shortened URL:</b>
                        http:/localhost/www/project/?q=<?php echo htmlspecialchars($_GET["short"]);?>
                    </div>
                    <?php }?>
        </div>
    </section>
    <section id="brands">
        <div class="container">
            <h3>these brands trust us</h3>
            <img src="img/1.png" alt="Entrepreneur Magazine's logo" class="picture">
            <img src="img/2.png" alt="Kaiser Permanente logo" class="picture">
            <img src="img/3.png" alt="PBS logo" class="picture">
            <img src="img/4.png" alt="Montaye logo" class="picture">
        </div>
    </section>
    <footer>
        <img src="img/logo2.png" alt="logo">
        <br>
        2023&copy;bitly
        <br>
        <a href="#">Contact</a>
        -
        <a href="#">in regards to</a>
    </footer>
</body>

</html>