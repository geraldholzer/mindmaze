<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" href="../css/main.css">

</head>

<body>
    <?php
    session_start();
    include("navbar.php");
    ?>
    <div class="container">
        <div class="row">
            <h1>Neue Frage einreichen</h1>
        </div>
        <div class="row mt-4">
            <Form>
                <div class="container">
                    <div class="form-group col-5 mt-4">
                        <label for="selectModul">Modul</label>
                        <select class="form-control" id="selectModul">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div class="form-group col-5 mt-4">
                        <label for="selectModul">Modul</label>
                        <input type="text" class="form-control" id="textQuestion" placeholder="Frage">
                    </div>
                    <div class="form-group col-5 mt-4">
                        <label for="textQuestion">Modul</label>
                        <input type="text" class="form-control" id="textQuestion" placeholder="Frage">
                    </div>
                    <div class="form-group col-5 mt-4">
                        <label for="textAnswer">Modul</label>
                        <input type="text" class="form-control" id="textAnswer" placeholder="Antwort">
                    </div>
                </div>
            </Form>
        </div>
    </div>
</body>

</html>