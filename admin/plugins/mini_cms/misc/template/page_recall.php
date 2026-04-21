<section id="features">
    <div class="container px-5 my-5">
        <div class="row gx-5 block-row p_<?= $page_id ?> ">
            <?php
            $counter = $page_counter;

            // $quote_counter = 0;

            if (isset($_SESSION['loggedin'])) {
            ?>
                <div class="text-right">

                    <a href="admin/index.php?p=editPage&idToMod=<?= $page_id ?>&count=<?= $counter ?>" class="btn btn-primary btn-sm"><b><?= $fe_edit ?></b></a>
                </div>
            <?php
            }
            if ($one) {
                $page_id = $page_req;
            }
            $json_file = 'admin/inc/pages/' . $page_id . '.json';
            $data = file_get_contents($json_file);
            $json_arr = json_decode($data, true);

            for ($i = 1; $i <= $counter; $i++) {

                $bootstrap = $json_arr[$i]['block' . $i . '_bootstrap'];

            ?>




                <div class="block block<?= $i ?> <?= $page_layout ?> p_<?= $page_id ?> <?= $bootstrap ?>" style="background-color:<?= $json_arr[$i]['block' . $i . '_bg'] ?> !important; color:<?= $json_arr[$i]['block' . $i . '_text'] ?> !important;">

                    <?php

                    if ($json_arr[$i]['block' . $i . '_type'] == "text") {


                        echo $json_arr[$i]['block' . $i . ''];
                    } else if ($json_arr[$i]['block' . $i . '_type'] == "img") {
                        $pict = $json_arr[$i]['block' . $i];
                    ?>

                        <img src="uploads/img/<?= $pict ?>">
                        <?php
                    } else if ($json_arr[$i]['block' . $i . '_type'] == "quote") {

                        $quote_counter++;

                        $mc->table = 'mc_quotes';
                        if ($mc->countAll() > 0) {
                            $mc->table = 'mc_quotes';
                            $quote_stmt = $mc->showAll('id');

                        ?>
                            <div class="slideshow-container">
                                <?php
                                while ($quote_row = $quote_stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($quote_row);
                                ?>
                                    <div class="mySlides">
                                        <q><?= $quote_row['quote'] ?></q>
                                        <p class="author"><?= $quote_row['author'] ?></p>
                                    </div>
                                <?php
                                }
                                ?>

                                <a class="prev" onclick="plusSlides(-1)">❮</a>
                                <a class="next" onclick="plusSlides(1)">❯</a>

                            </div>

                            <div class="dot-container">
                                <span class="dot" onclick="currentSlide(1)"></span>
                                <span class="dot" onclick="currentSlide(2)"></span>
                                <span class="dot" onclick="currentSlide(3)"></span>
                            </div>
                        <?php
                        } else {
                        ?>

                            <p><?= $quote_noquote ?></p>

                        <?php
                        }
                    } else if ($json_arr[$i]['block' . $i . '_type'] == "info") {
                        $info = $json_arr[$i]['block' . $i . '_info'];
                        if ($page_id == 17) {

                            $pageArray = [4, 8, 12, 16, 19, 23];

                            $order_a = "order-2 order-md-1";
                            $order_b = "order-1 order-md-2";
                            if (in_array($i, $pageArray)) {
                                $order_a = "order-2";
                                $order_b = "order-1 text-end";
                            }
                        ?>
                            <div class="row">
                                <div class="col-3 info_img <?= $order_a ?>">
                                    <img src="uploads/img/<?= $info ?>">
                                </div>
                                <div class="col-9 info_text <?= $order_b ?>">
                                    <?php
                                    echo $json_arr[$i]['block' . $i . '_desc'];
                                    ?>
                                </div>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="row">
                                <div class="col-5 info_img <?= $order_a ?>">
                                    <img src="uploads/img/<?= $info ?>">
                                </div>
                                <div class="col-7 info_text <?= $order_b ?>">
                                    <?php
                                    echo $json_arr[$i]['block' . $i . '_desc'];
                                    ?>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    <?php
                    } else if ($json_arr[$i]['block' . $i . '_type'] == "script") {
                        $file_req = $json_arr[$i]['block' . $i . '_file'];
                        require 'assets/themes/' . $mc_settings['mc_theme'] . '/script/' . $file_req;
                    } else if ($json_arr[$i]['block' . $i . '_type'] == "post") {

                        $post->table = 'post';
                        if ($json_arr[$i]['block' . $i . '_cat'] != 'none') {
                            $post->category_id = $json_arr[$i]['block' . $i . '_cat'];
                            $stmt1 = $post->showAllWhere('created', ['category_id'], 3);
                        } else {
                            $stmt1 = $post->showAllLimitDesc('created', 3);
                        }
                    ?>

                        <div class="row gx-5">
                            <h3>Ultime notizie</h3>
                            <?php
                            while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {

                                extract($row);

                                $time = $row['created'];
                                $newTime = date("d/m/Y", strtotime($time));
                            ?>
                                <div class="col-lg-4 mb-5">
                                    <div class="card h-100 shadow border-0">
                                        <?php
                                        if ($row['main_img'] != NULL) {
                                        ?>
                                            <img class="card-img-top" src="uploads/img/<?= $main_img ?>">
                                        <?php
                                        }
                                        ?>
                                        <div class="card-body p-4">
                                            <!-- <div class="badge bg-primary bg-gradient rounded-pill mb-2"></div> -->
                                            <!-- <a class="text-decoration-none link-dark stretched-link" href="post.php?id=<?= $id ?>&title=<?= $post_title ?>"> -->
                                            <h5 class="card-title mb-3"><?= $title ?></h5>
                                            <!-- </a> -->

                                            <p class="card-text mb-0">
                                                <?php
                                                $post->content = $row['content'];
                                                $post->post_link = 'post.php?id=' . $row['id'] . '&origin=' . $file_name . '';
                                                $post->limit = 120;
                                                $post->more = $blog_more;
                                                echo $post->readMore();
                                                ?>
                                                <a href="post.php?id=<?= $id ?>&title=<?= $post_title ?>"><?= $blog_continue ?></a>

                                            </p>
                                        </div>
                                        <div class="card-footer p-4 pt-0 bg-transparent border-top-0">
                                            <div class="d-flex align-items-end justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <div class="small">
                                                        <div class="text-muted"><?= $newTime ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    <?php
                    } else if ($json_arr[$i]['block' . $i . '_type'] == "gallery") {

                        $mc->table = 'mc_galleries';
                        $mc->id = $json_arr[$i]['block' . $i];

                        $stmt_gallery = $mc->showAllWhere('id', ['id']);
                        $row_gallery = $stmt_gallery->fetch(PDO::FETCH_ASSOC);
                        extract($row_gallery);

                        $title_gallery = ucfirst($row_gallery['gallery_name']);
                    ?>

                        <div id="titleCarousel<?= $i ?>" class="d-none">
                            <h2><?= $title_gallery ?></h2>
                        </div>

                        <div id="myCarousel<?= $i ?>" class="carousel slide gallery" data-bs-ride="carousel" data-bs-interval="3000">
                            <!-- Indicatori -->
                            <div class="carousel-indicators">
                                <?php
                                $dirCarousel = "uploads/gallery/g_" . $json_arr[$i]['block' . $i];
                                $files = glob($dirCarousel . "/*");
                                foreach ($files as $idx => $file) {
                                ?>
                                    <button type="button" data-bs-target="#myCarousel<?= $i ?>" data-bs-slide-to="<?= $idx ?>" <?= $idx == 0 ? 'class="active" aria-current="true"' : '' ?> aria-label="Slide <?= $idx + 1 ?>"></button>
                                <?php } ?>
                            </div>

                            <!-- Slides -->
                            <div class="carousel-inner">
                                <?php
                                $numberArr = ['first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth'];
                                $number = $numberArr[$i] ?? 'custom';

                                foreach ($files as $idx => $file) {
                                    $imgName = basename($file);
                                    $active = $idx == 0 ? 'active' : '';
                                ?>
                                    <div class="carousel-item <?= $active ?>">
                                        <a href="<?= $dirCarousel . '/' . $imgName ?>">
                                            <img class="gallery <?= $number ?>-slide d-block w-100" src="<?= $dirCarousel . '/' . $imgName ?>" alt="<?= $number ?> slide">
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- Controlli -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel<?= $i ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#myCarousel<?= $i ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                    <?php
                    }

                    ?>
                </div>
            <?php
            }

            ?>
        </div>
    </div>
</section>