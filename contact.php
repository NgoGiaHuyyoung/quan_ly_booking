<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jay Homestay - CONTACT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <style>
        .pop:hover {
            border-top-color: var(--teal_hover) !important;
            transform: scale(1.03);
            transition: all 0.3s;
        }
    </style>
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">CONTACT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Quam optio impedit soluta. Pariatur unde delectus animi
            at voluptate obcaecati tempore.
        </p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">

                <div class="bg-white rounded shadow p-4">
                    <iframe class="w-100 rounded mb-4" height="320px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.550094511646!2d106.79159707373665!3d10.845701257914802!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317527158a0a5b81%3A0xf45c5d34ac580517!2zUGjDom4gaGnhu4d1IFRyxrDhu51uZyDEkOG6oWkgaOG7jWMgR1RWVCB04bqhaSBUcC4gSOG7kyBDaMOtIE1pbmg!5e0!3m2!1svi!2s!4v1728455644576!5m2!1svi!2s" loading="lazy"></iframe>

                    <h5>Address</h5>
                    <a href="https://maps.app.goo.gl/rhEaotwoXyag3adq5" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2">
                        <i class="bi bi-geo-alt-fill"></i> Phân hiệu Trường Đại học GTVT tại Tp. Hồ Chí Minh
                    </a>

                    <h5 class="mt-4">Call us</h5>
                    <a href="tel: +0938646294" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +0938646294
                    </a>
                    <br>
                    <a href="tel: +0938646294" class="d-inline-block  text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +0938646294
                    </a>

                    <h5 class="mt-4">Email</h5>
                    <a href="mailto: ask.Jaywebdev@gmail.com " class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-envelope-fill"></i>
                        ask.Jaywebdev@gmail.com
                    </a>

                    <h5 class="mt-4">Follow us us</h5>
                    <a href="#" class="d-inline-block text-dark fs-5 me-2">
                        <i class="bi bi-twitter me-1"></i>
                    </a>
                    <a href="#" class="d-inline-block text-dark fs-5 me-2">
                        <i class="bi bi-facebook me-1"></i>
                    </a>
                    <a href="#" class="d-inline-block text-dark fs-5 ">
                        <i class="bi bi-instagram me-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4">
                    <form>
                        <h5>Send a message</h5>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Name</label>
                            <input type="text" class="form-control shawdow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Email</label>
                            <input type="email" class="form-control shawdow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Subject</label>
                            <input type="text" class="form-control shawdow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Message</label>
                            <textarea class="form-control shadow-none" rows="5" style="resize: none;"></textarea>
                        </div>
                        <Button type="submit" class="btn text-white custom-bg mt-3">SEND</Button>
                    </form>
                </div>
            </div>
        </div>

        <?php require('inc/footer.php') ?>




</body>

</html>