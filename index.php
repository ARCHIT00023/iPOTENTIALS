<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
    #ques {
        min-height: 433px;
    }

    .carousel-container {
        width: 100%;
        margin: auto;
    }

    .carousel-inner img {
        height: 50vh;
        width: 100%;
        object-fit: cover;
    }

    .card-img-top {
        height: 200px;
        object-fit: cover;
    }

    .card-body {
        height: 150px;
    }
    </style>
    <title>Welcome to iDiscuss - Coding Forums</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php';?>
    <?php include 'partials/_header.php';?>

    <!-- Welcome section -->
    <div class="container my-4">
        <h2 class="text-center">Welcome</h2>
    </div>

    <!-- Slider starts here -->
    <div id="carouselExampleIndicators" class="carousel slide carousel-container" data-ride="carousel">
        <ol class="carousel-indicators" id="carousel-indicators">
            <!-- Indicators will be inserted here dynamically -->
        </ol>
        <div class="carousel-inner" id="carousel-inner">
            <!-- Dynamic images will be inserted here -->
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- Category container starts here -->
    <div class="container my-4" id="ques">
        <h2 class="text-center my-2">iDiscuss - Browse Categories</h2> <!-- Reduced margin here -->
        <div class="row my-4">
            <!-- Fetch categories -->
            <?php
                $sql = "SELECT * FROM `categories`";
                $result = mysqli_query($conn, $sql);
                while ($row=mysqli_fetch_assoc($result))
                {
                    $id=$row['category_id'];
                    $cat=$row['category_name'];
                    $desc=$row['category_description'];
                    echo  '<div class="col-md-4 my-2">
                            <div class="card" style="width: 18rem;">
                                <img class="card-img-top"
                                    src="img/card-'.$id.'.jfif"
                                    alt="Image for this category">
                                <div class="card-body">
                                    <h5 class="card-title"><a href="threadlist.php?catid='.$id.'">'.$cat.'</a></h5>
                                    <p class="card-text">'.substr($desc,0,50).'....</p>
                                    <a href="threadlist.php?catid='.$id.'" class="btn btn-primary">Explore</a>
                                </div>
                            </div>
                        </div>';
                }
            ?>
        </div>
    </div>

    <?php include 'partials/_footer.php';?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const apiKey = 'XYZ'; // Replace with your Unsplash API key
        const apiUrl = `https://api.unsplash.com/photos/random?count=3&query=car&client_id=${apiKey}`;
        const carouselInner = document.getElementById('carousel-inner');
        const carouselIndicators = document.getElementById('carousel-indicators');

        axios.get(apiUrl)
            .then(response => {
                const images = response.data;
                images.forEach((image, index) => {
                    // Create carousel item
                    const div = document.createElement('div');
                    div.className = `carousel-item${index === 0 ? ' active' : ''}`;
                    div.innerHTML = `
                            <img class="d-block w-100" src="${image.urls.regular}" alt="Slide ${index + 1}">
                        `;
                    carouselInner.appendChild(div);

                    // Create carousel indicator
                    const li = document.createElement('li');
                    li.setAttribute('data-target', '#carouselExampleIndicators');
                    li.setAttribute('data-slide-to', index.toString());
                    if (index === 0) {
                        li.className = 'active';
                    }
                    carouselIndicators.appendChild(li);
                });
            })
            .catch(error => {
                console.error('Error fetching images:', error);
            });
    });
    </script>
</body>

</html>
