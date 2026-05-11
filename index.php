<?php
include "header.php";

// Helper function to check if a menu link is enabled
function isMenuLinkEnabled($con, $name)
{
    $sql = "SELECT isEnabled FROM menu_links WHERE name = ? AND parent_id IS NULL";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $row['isEnabled'] ?? 0;
}


// Display error or success messages
if (isset($_GET['error'])) {
    echo "<script>alert('" . addslashes($_GET['error']) . "');</script>";
}


?>
<main class="pt-3">
    <div id="main">
        <div style="width: 90%;">
            <div class="text-center">
                <img src="./assets/img/luxury/logo_luxury.png" alt="" style="width:35%; height:auto;">
            </div>
            <div class="mt-2">
                <h3 class="text-center type"></h3>
            </div>

            <div style="display: flex; justify-content: center; align-items: center; ">
                <div class="text-center header-menu">
                    <i class='bx bx-menu text-center'
                        style="color: white; border-radius: 5px; width: 100px; display: flex; justify-content: center; align-items: center; font-size: 27px; ">
                        <span class="mb-1" style="font-size: 14px;">
                            MENU
                        </span>
                    </i>
                </div>
            </div>

            <div class="mt-2 mt-3">
                <h4 class="element">SERVICES OFFERED AT THE EMPIRE</h4>
            </div>
            <div id="bg"></div>
            <!-- **** CHECK POINT ****** -->
            <div class="d-flex flex-wrap justify-content-center mt-5" style="margin-bottom:20%;">
                <div class="row">
                    <?php
                    $sql = "SELECT * FROM category ORDER BY order_no ASC";
                    $sql2 = mysqli_query($con, $sql);

                    while ($row = mysqli_fetch_array($sql2)) {
                        $imageURL = 'category/' . $row["file_name"];
                        ?>
                        <div class="box px-4 py-5 text-center col-lg-5 col-md-5 mt-5">
                            <h4 class="mb-3" style="text-transform:uppercase;"><?php echo htmlspecialchars($row["name"]) ?>
                            </h4>
                            <img src="<?php echo $imageURL ?>" class="category-image" alt="" />
                            <p class="mt-4"><?php echo htmlspecialchars($row["description"]) ?></p>
                            <div class="mt-4 button_container">
                                <?php
                                if ($row['isEnabled']) {
                                    ?>
                                    <a href="saloon/subcategory.php?category=<?php echo $row['id'] ?>">
                                        <button class="btn-anim"><span>CLICK TO BOOK</span></button>
                                    </a>
                                    <?php
                                } else {
                                    ?>
                                    <button class="btn-anim" disabled style="background:#ccc; cursor:not-allowed;">
                                        <span>UNAVAILABLE</span>
                                    </button>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php

                    }
                    ?>
                </div>
                <?php // Close row
                ?>



                <!-- THE SECTION -->
                <?php if (isMenuLinkEnabled($con, 'Orishirishi')): ?>
                    <div class="box px-4 py-5 mx-2 text-center col-lg-5 col-md-5 mt-5">
                        <div>
                            <h4 class="mb-3" style="text-transform:uppercase;">ORISHIRISHI</h4>
                            <img src="food.png" alt="" />
                            <p class="mt-4">Get all kind of drinks, meals, or snacks at chbluxuryempire. Order Now</p>
                            <div class="mt-4 button_container">
                                <a href="food_page.php?current_service=orishirishi"><button class="btn-anim"><span>CLICK TO
                                            ORDER</span></button></a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isMenuLinkEnabled($con, 'Repair Center')): ?>
                    <div class="box px-4 py-5 mx-2 text-center col-lg-5 col-md-5 mt-5">
                        <div>
                            <h4 class="mb-3" style="text-transform:uppercase;">REPAIR CENTER</h4>
                            <img src="repair.jpeg" alt="" />
                            <p class="mt-4">Effective repairs of nail salon equipment</p>
                            <div class="mt-4 button_container">
                                <a href="repaircenter.php?current_service=repair_center"><button
                                        class="btn-anim"><span>CLICK TO ORDER</span></button></a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isMenuLinkEnabled($con, 'Delta Kitchen')): ?>
                    <div class="box px-4 py-5 mx-2 text-center col-lg-5 col-md-5 mt-5">
                        <div>
                            <h4 class="mb-3" style="text-transform:uppercase;">DELTA KITCHEN</h4>
                            <img src="delta.jpeg" alt="" />
                            <p class="mt-4">Your one stop for specially made Urhobo delicacies</p>
                            <div class="mt-4 button_container">
                                <a href="deltakitchen.php?current_service=delta_kitchen">
                                    <button class="btn-anim">
                                        <span>CLICK TO ORDER</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isMenuLinkEnabled($con, 'E-Giftcard')): ?>
                    <div class="box px-4 py-5 mx-2 text-center col-lg-5 col-md-5 mt-5">
                        <div>
                            <h4 class="mb-3" style="text-transform:uppercase;">E-GIFTCARD</h4>
                            <img src="gift.png" alt="" />
                            <p class="mt-4">Buy an e-gift card for your friends, loved ones, and family. Buy Now</p>
                            <div class="mt-4 button_container">
                                <a href="giftcard.php?current_service=e-giftcard"><button class="btn-anim"><span>CLICK TO
                                            ORDER</span></button></a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isMenuLinkEnabled($con, 'Rental for Beauty and Skill Training')): ?>
                    <div class="box px-4 py-5 mx-2 text-center col-lg-5 col-md-5 mt-5">
                        <div>
                            <h4 class="mb-3" style="text-transform:uppercase;">RENTAL FOR BEAUTY AND SKILL TRAINING</h4>
                            <img src="rental.jpeg" alt="" />
                            <p class="mt-4">Request a rental for your beauty and skills training</p>
                            <div class="mt-4 button_container">
                                <a href="rental/index.php"><button class="btn-anim"><span>CLICK TO ORDER</span></button></a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isMenuLinkEnabled($con, 'E-Gift Voucher Spa Packages')): ?>
                    <div class="box px-4 py-5 mx-2 text-center col-lg-5 col-md-5 mt-5">
                        <div>
                            <h4 class="mb-3" style="text-transform:uppercase;">E-GIFT VOUCHER SPA PACKAGES</h4>
                            <img src="voucher.jpeg" alt="" />
                            <p class="mt-4">We have selected special services to gift that special person</p>
                            <div class="mt-4 button_container">
                                <a href="voucher/index.php"><button class="btn-anim"><span>CLICK TO
                                            ORDER</span></button></a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isMenuLinkEnabled($con, 'CHB Luxury Academy')): ?>
                    <div class="box px-4 py-5 mx-2 text-center col-lg-5 col-md-5 mt-5">
                        <div>
                            <h4 class="mb-3" style="text-transform:uppercase;">CHB LUXURY ACADEMY</h4>
                            <img src="academy.jpeg" alt="" />
                            <p class="mt-4">You can rely on us at CHB Luxury Academy to help you realize your aspirations
                            </p>
                            <div class="mt-4 button_container">
                                <a href="academy/index.php"><button class="btn-anim"><span>CLICK TO
                                            ORDER</span></button></a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- End THE SECTION -->
            </div>
        </div>
    </div>
    </div>
    </div>

    <div id='glow' style="margin-top:10%; z-index:0;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="vip">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 283.5 27.8" preserveaspectratio="none"
                class="border-svg">
                <path class="shape-fill"
                    d="M283.5,9.7c0,0-7.3,4.3-14,4.6c-6.8,0.3-12.6,0-20.9-1.5c-11.3-2-33.1-10.1-44.7-5.7	s-12.1,4.6-18,7.4c-6.6,3.2-20,9.6-36.6,9.3C131.6,23.5,99.5,7.2,86.3,8c-1.4,0.1-6.6,0.8-10.5,2c-3.8,1.2-9.4,3.8-17,4.7	c-3.2,0.4-8.3,1.1-14.2,0.9c-1.5-0.1-6.3-0.4-12-1.6c-5.7-1.2-11-3.1-15.8-3.7C6.5,9.2,0,10.8,0,10.8V0h283.5V9.7z M260.8,11.3	c-0.7-1-2-0.4-4.3-0.4c-2.3,0-6.1-1.2-5.8-1.1c0.3,0.1,3.1,1.5,6,1.9C259.7,12.2,261.4,12.3,260.8,11.3z M242.4,8.6	c0,0-2.4-0.2-5.6-0.9c-3.2-0.8-10.3-2.8-15.1-3.5c-8.2-1.1-15.8,0-15.1,0.1c0.8,0.1,9.6-0.6,17.6,1.1c3.3,0.7,9.3,2.2,12.4,2.7	C239.9,8.7,242.4,8.6,242.4,8.6z M185.2,8.5c1.7-0.7-13.3,4.7-18.5,6.1c-2.1,0.6-6.2,1.6-10,2c-3.9,0.4-8.9,0.4-8.8,0.5	c0,0.2,5.8,0.8,11.2,0c5.4-0.8,5.2-1.1,7.6-1.6C170.5,14.7,183.5,9.2,185.2,8.5z M199.1,6.9c0.2,0-0.8-0.4-4.8,1.1	c-4,1.5-6.7,3.5-6.9,3.7c-0.2,0.1,3.5-1.8,6.6-3C197,7.5,199,6.9,199.1,6.9z M283,6c-0.1,0.1-1.9,1.1-4.8,2.5s-6.9,2.8-6.7,2.7	c0.2,0,3.5-0.6,7.4-2.5C282.8,6.8,283.1,5.9,283,6z M31.3,11.6c0.1-0.2-1.9-0.2-4.5-1.2s-5.4-1.6-7.8-2C15,7.6,7.3,8.5,7.7,8.6	C8,8.7,15.9,8.3,20.2,9.3c2.2,0.5,2.4,0.5,5.7,1.6S31.2,11.9,31.3,11.6z M73,9.2c0.4-0.1,3.5-1.6,8.4-2.6c4.9-1.1,8.9-0.5,8.9-0.8	c0-0.3-1-0.9-6.2-0.3S72.6,9.3,73,9.2z M71.6,6.7C71.8,6.8,75,5.4,77.3,5c2.3-0.3,1.9-0.5,1.9-0.6c0-0.1-1.1-0.2-2.7,0.2	C74.8,5.1,71.4,6.6,71.6,6.7z M93.6,4.4c0.1,0.2,3.5,0.8,5.6,1.8c2.1,1,1.8,0.6,1.9,0.5c0.1-0.1-0.8-0.8-2.4-1.3	C97.1,4.8,93.5,4.2,93.6,4.4z M65.4,11.1c-0.1,0.3,0.3,0.5,1.9-0.2s2.6-1.3,2.2-1.2s-0.9,0.4-2.5,0.8C65.3,10.9,65.5,10.8,65.4,11.1	z M34.5,12.4c-0.2,0,2.1,0.8,3.3,0.9c1.2,0.1,2,0.1,2-0.2c0-0.3-0.1-0.5-1.6-0.4C36.6,12.8,34.7,12.4,34.5,12.4z M152.2,21.1	c-0.1,0.1-2.4-0.3-7.5-0.3c-5,0-13.6-2.4-17.2-3.5c-3.6-1.1,10,3.9,16.5,4.1C150.5,21.6,152.3,21,152.2,21.1z" />
                <path class="elementor-shape-fill"
                    d="M269.6,18c-0.1-0.1-4.6,0.3-7.2,0c-7.3-0.7-17-3.2-16.6-2.9c0.4,0.3,13.7,3.1,17,3.3	C267.7,18.8,269.7,18,269.6,18z" />
                <path class="elementor-shape-fill"
                    d="M227.4,9.8c-0.2-0.1-4.5-1-9.5-1.2c-5-0.2-12.7,0.6-12.3,0.5c0.3-0.1,5.9-1.8,13.3-1.2	S227.6,9.9,227.4,9.8z" />
                <path class="elementor-shape-fill"
                    d="M204.5,13.4c-0.1-0.1,2-1,3.2-1.1c1.2-0.1,2,0,2,0.3c0,0.3-0.1,0.5-1.6,0.4	C206.4,12.9,204.6,13.5,204.5,13.4z" />
                <path class="elementor-shape-fill"
                    d="M201,10.6c0-0.1-4.4,1.2-6.3,2.2c-1.9,0.9-6.2,3.1-6.1,3.1c0.1,0.1,4.2-1.6,6.3-2.6	S201,10.7,201,10.6z" />
                <path class="elementor-shape-fill"
                    d="M154.5,26.7c-0.1-0.1-4.6,0.3-7.2,0c-7.3-0.7-17-3.2-16.6-2.9c0.4,0.3,13.7,3.1,17,3.3	C152.6,27.5,154.6,26.8,154.5,26.7z" />
                <path class="elementor-shape-fill"
                    d="M41.9,19.3c0,0,1.2-0.3,2.9-0.1c1.7,0.2,5.8,0.9,8.2,0.7c4.2-0.4,7.4-2.7,7-2.6	c-0.4,0-4.3,2.2-8.6,1.9c-1.8-0.1-5.1-0.5-6.7-0.4S41.9,19.3,41.9,19.3z" />
                <path class="elementor-shape-fill"
                    d="M75.5,12.6c0.2,0.1,2-0.8,4.3-1.1c2.3-0.2,2.1-0.3,2.1-0.5c0-0.1-1.8-0.4-3.4,0	C76.9,11.5,75.3,12.5,75.5,12.6z" />
                <path class="elementor-shape-fill"
                    d="M15.6,13.2c0-0.1,4.3,0,6.7,0.5c2.4,0.5,5,1.9,5,2c0,0.1-2.7-0.8-5.1-1.4	C19.9,13.7,15.7,13.3,15.6,13.2z" />
            </svg>
        </div>
        <div class="d-flex justify-content-center flex-wrap">
            <div class="authenticity">
                <div>
                    <div><i class='bx bxs-star' style="color: #FFC700;"></i></div>
                    <H6>AUTHENTICITY</H6>
                    <h6>100%</h6>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center flex-wrap mt-5">
            <div class="to">
                <p>UPGRADE TO VIP</p>
            </div>
        </div>




        <div class="d-flex justify-content-center flex-wrap mt-3">
            <div class="to-vip">
                <img src="./assets/img/luxury/NO-RESTRICTIONS-WHEN-IT-COMES-TO-LUXURY-2-e1639855204637.png" alt="">
            </div>
        </div>

        <div class="d-flex justify-content-center flex-wrap mt-4 mb-5">
            <div style="position: relative; z-index: 1000;">
                <div class="button_container">
                    <a href="members/index.php"><button class="btn-anim"><span>CLICK TO REGISTER</span></button></a>
                </div>
            </div>
        </div>
    </div>





    <div class="vip">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 283.5 27.8" preserveaspectratio="xMidYMax slice"
                class="border-svg">
                <path class="shape-fill1"
                    d="M0 0v6.7c1.9-.8 4.7-1.4 8.5-1 9.5 1.1 11.1 6 11.1 6s2.1-.7 4.3-.2c2.1.5 2.8 2.6 2.8 2.6s.2-.5 1.4-.7c1.2-.2 1.7.2 1.7.2s0-2.1 1.9-2.8c1.9-.7 3.6.7 3.6.7s.7-2.9 3.1-4.1 4.7 0 4.7 0 1.2-.5 2.4 0 1.7 1.4 1.7 1.4h1.4c.7 0 1.2.7 1.2.7s.8-1.8 4-2.2c3.5-.4 5.3 2.4 6.2 4.4.4-.4 1-.7 1.8-.9 2.8-.7 4 .7 4 .7s1.7-5 11.1-6c9.5-1.1 12.3 3.9 12.3 3.9s1.2-4.8 5.7-5.7c4.5-.9 6.8 1.8 6.8 1.8s.6-.6 1.5-.9c.9-.2 1.9-.2 1.9-.2s5.2-6.4 12.6-3.3c7.3 3.1 4.7 9 4.7 9s1.9-.9 4 0 2.8 2.4 2.8 2.4 1.9-1.2 4.5-1.2 4.3 1.2 4.3 1.2.2-1 1.4-1.7 2.1-.7 2.1-.7-.5-3.1 2.1-5.5 5.7-1.4 5.7-1.4 1.5-2.3 4.2-1.1c2.7 1.2 1.7 5.2 1.7 5.2s.3-.1 1.3.5c.5.4.8.8.9 1.1.5-1.4 2.4-5.8 8.4-4 7.1 2.1 3.5 8.9 3.5 8.9s.8-.4 2 0 1.1 1.1 1.1 1.1 1.1-1.1 2.3-1.1 2.1.5 2.1.5 1.9-3.6 6.2-1.2 1.9 6.4 1.9 6.4 2.6-2.4 7.4 0c3.4 1.7 3.9 4.9 3.9 4.9s3.3-6.9 10.4-7.9 11.5 2.6 11.5 2.6.8 0 1.2.2c.4.2.9.9.9.9s4.4-3.1 8.3.2c1.9 1.7 1.5 5 1.5 5s.3-1.1 1.6-1.4c1.3-.3 2.3.2 2.3.2s-.1-1.2.5-1.9 1.9-.9 1.9-.9-4.7-9.3 4.4-13.4c5.6-2.5 9.2.9 9.2.9s5-6.2 15.9-6.2 16.1 8.1 16.1 8.1.7-.2 1.6-.4V0H0z" />
            </svg>
        </div>
        <div>
            <div class="d-flex justify-content-center flex-wrap">
                <div class="authenticity">
                    <div>
                        <div><i class='bx bx-diamond'></i>
                        </div>
                        <H6>LUXURY</H6>
                        <h6>100%</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center flex-wrap mt-5">
            <div class="to">
                <p>BOOK A ONE TIME SERVICE</p>
            </div>
        </div>
        <div class="d-flex justify-content-center flex-wrap mt-3">
            <div class="to-vip">
                <img src="./assets/img/luxury/NO-RESTRICTIONS-WHEN-IT-COMES-TO-LUXURY-3-e1639859200976.png" alt="">
            </div>
        </div>


        <p id="demo"></p>

        <script>
            function myFunction() {
                const header_menu = document.querySelector('.header-menu');
                const menu = document.querySelector('.menu');
                const main = document.querySelector('main')
                const header = document.querySelector('header');
                const nolist = document.querySelector('#nolist');
                var myLists = document.getElementById('list');
                var displaySettings = myLists.style.display;
                main.style.display = 'none'
                nolist.style.display = 'none'
                header.style.display = 'block'
                myLists.style.display = 'block';
            }
        </script>
        <div class="d-flex justify-content-center flex-wrap mt-4 mb-5">
            <div style="position: relative; z-index: 1000">
                <div class="button_container">
                    <!-- <button onclick="myFunction()" class="btn-anim"><span>CLICK TO BOOK</span></button> -->
                    <a href="saloon/subcategory.php?category=001" class="btn-anim"><span>CLICK TO BOOK</span></a>
                </div>
            </div>
        </div>
    </div>

    <div class="vip">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 283.5 27.8" preserveaspectratio="none"
                class="border-svg">
                <path class="shape-fill"
                    d="M283.5,9.7c0,0-7.3,4.3-14,4.6c-6.8,0.3-12.6,0-20.9-1.5c-11.3-2-33.1-10.1-44.7-5.7	s-12.1,4.6-18,7.4c-6.6,3.2-20,9.6-36.6,9.3C131.6,23.5,99.5,7.2,86.3,8c-1.4,0.1-6.6,0.8-10.5,2c-3.8,1.2-9.4,3.8-17,4.7	c-3.2,0.4-8.3,1.1-14.2,0.9c-1.5-0.1-6.3-0.4-12-1.6c-5.7-1.2-11-3.1-15.8-3.7C6.5,9.2,0,10.8,0,10.8V0h283.5V9.7z M260.8,11.3	c-0.7-1-2-0.4-4.3-0.4c-2.3,0-6.1-1.2-5.8-1.1c0.3,0.1,3.1,1.5,6,1.9C259.7,12.2,261.4,12.3,260.8,11.3z M242.4,8.6	c0,0-2.4-0.2-5.6-0.9c-3.2-0.8-10.3-2.8-15.1-3.5c-8.2-1.1-15.8,0-15.1,0.1c0.8,0.1,9.6-0.6,17.6,1.1c3.3,0.7,9.3,2.2,12.4,2.7	C239.9,8.7,242.4,8.6,242.4,8.6z M185.2,8.5c1.7-0.7-13.3,4.7-18.5,6.1c-2.1,0.6-6.2,1.6-10,2c-3.9,0.4-8.9,0.4-8.8,0.5	c0,0.2,5.8,0.8,11.2,0c5.4-0.8,5.2-1.1,7.6-1.6C170.5,14.7,183.5,9.2,185.2,8.5z M199.1,6.9c0.2,0-0.8-0.4-4.8,1.1	c-4,1.5-6.7,3.5-6.9,3.7c-0.2,0.1,3.5-1.8,6.6-3C197,7.5,199,6.9,199.1,6.9z M283,6c-0.1,0.1-1.9,1.1-4.8,2.5s-6.9,2.8-6.7,2.7	c0.2,0,3.5-0.6,7.4-2.5C282.8,6.8,283.1,5.9,283,6z M31.3,11.6c0.1-0.2-1.9-0.2-4.5-1.2s-5.4-1.6-7.8-2C15,7.6,7.3,8.5,7.7,8.6	C8,8.7,15.9,8.3,20.2,9.3c2.2,0.5,2.4,0.5,5.7,1.6S31.2,11.9,31.3,11.6z M73,9.2c0.4-0.1,3.5-1.6,8.4-2.6c4.9-1.1,8.9-0.5,8.9-0.8	c0-0.3-1-0.9-6.2-0.3S72.6,9.3,73,9.2z M71.6,6.7C71.8,6.8,75,5.4,77.3,5c2.3-0.3,1.9-0.5,1.9-0.6c0-0.1-1.1-0.2-2.7,0.2	C74.8,5.1,71.4,6.6,71.6,6.7z M93.6,4.4c0.1,0.2,3.5,0.8,5.6,1.8c2.1,1,1.8,0.6,1.9,0.5c0.1-0.1-0.8-0.8-2.4-1.3	C97.1,4.8,93.5,4.2,93.6,4.4z M65.4,11.1c-0.1,0.3,0.3,0.5,1.9-0.2s2.6-1.3,2.2-1.2s-0.9,0.4-2.5,0.8C65.3,10.9,65.5,10.8,65.4,11.1	z M34.5,12.4c-0.2,0,2.1,0.8,3.3,0.9c1.2,0.1,2,0.1,2-0.2c0-0.3-0.1-0.5-1.6-0.4C36.6,12.8,34.7,12.4,34.5,12.4z M152.2,21.1	c-0.1,0.1-2.4-0.3-7.5-0.3c-5,0-13.6-2.4-17.2-3.5c-3.6-1.1,10,3.9,16.5,4.1C150.5,21.6,152.3,21,152.2,21.1z" />
                <path class="elementor-shape-fill"
                    d="M269.6,18c-0.1-0.1-4.6,0.3-7.2,0c-7.3-0.7-17-3.2-16.6-2.9c0.4,0.3,13.7,3.1,17,3.3	C267.7,18.8,269.7,18,269.6,18z" />
                <path class="elementor-shape-fill"
                    d="M227.4,9.8c-0.2-0.1-4.5-1-9.5-1.2c-5-0.2-12.7,0.6-12.3,0.5c0.3-0.1,5.9-1.8,13.3-1.2	S227.6,9.9,227.4,9.8z" />
                <path class="elementor-shape-fill"
                    d="M204.5,13.4c-0.1-0.1,2-1,3.2-1.1c1.2-0.1,2,0,2,0.3c0,0.3-0.1,0.5-1.6,0.4	C206.4,12.9,204.6,13.5,204.5,13.4z" />
                <path class="elementor-shape-fill"
                    d="M201,10.6c0-0.1-4.4,1.2-6.3,2.2c-1.9,0.9-6.2,3.1-6.1,3.1c0.1,0.1,4.2-1.6,6.3-2.6	S201,10.7,201,10.6z" />
                <path class="elementor-shape-fill"
                    d="M154.5,26.7c-0.1-0.1-4.6,0.3-7.2,0c-7.3-0.7-17-3.2-16.6-2.9c0.4,0.3,13.7,3.1,17,3.3	C152.6,27.5,154.6,26.8,154.5,26.7z" />
                <path class="elementor-shape-fill"
                    d="M41.9,19.3c0,0,1.2-0.3,2.9-0.1c1.7,0.2,5.8,0.9,8.2,0.7c4.2-0.4,7.4-2.7,7-2.6	c-0.4,0-4.3,2.2-8.6,1.9c-1.8-0.1-5.1-0.5-6.7-0.4S41.9,19.3,41.9,19.3z" />
                <path class="elementor-shape-fill"
                    d="M75.5,12.6c0.2,0.1,2-0.8,4.3-1.1c2.3-0.2,2.1-0.3,2.1-0.5c0-0.1-1.8-0.4-3.4,0	C76.9,11.5,75.3,12.5,75.5,12.6z" />
                <path class="elementor-shape-fill"
                    d="M15.6,13.2c0-0.1,4.3,0,6.7,0.5c2.4,0.5,5,1.9,5,2c0,0.1-2.7-0.8-5.1-1.4	C19.9,13.7,15.7,13.3,15.6,13.2z" />
            </svg>
        </div>
        <div class="d-flex justify-content-center flex-wrap mt-5" id="review">
            <div class="to">
                <p style="font-size: 2.2rem;">MY FIRST EXPRERIENCE</p>
            </div>
        </div>

        <div class="d-flex justify-content-center flex-wrap mt-3">
            <section id="testimonials" class="testimonials section-bg">
                <div class="container" data-aos="fade-up">

                    <div class="testimonials-slider swiper" id="review" data-aos="fade-up" data-aos-delay="100">
                        <div class="swiper-wrapper">

                            <?php
                            $sql = "SELECT all* from reviews ORDER BY s DESC";
                            $sql2 = mysqli_query($con, $sql);
                            while ($row = mysqli_fetch_array($sql2)) {
                                $imageURL = 'reviews/' . $row["file_name"];
                                echo '<div class="swiper-slide">
         <div class="testimonial-item">
         <div class="d-flex justify-content-center flex-wrap">
         <div>'; ?>
                                <p
                                    style="font-family: 'Poppins', sans-serif; font-size: 13px; text-decoration: none; background: #FFC700; padding:10px; color: black; border-radius: 0; ">
                                    <?php echo '' . $row['view'] . ' </p>
                                            </div>
                                            </div>
                                            <div class="d-flex justify-content-center flex-wrap">
                                            <div>
                                            <img src="' . $imageURL . '" class="testimonial-img" alt="">
                                            </div>
                                            </div>
                                            <div class="d-flex justify-content-center flex-wrap">
                                            <div>'; ?>
                                <h1
                                    style="font-family: 'Poppins', sans-serif; font-weight: 800; margin-top: 1rem; font-size: 2rem; color: #FFC700;">
                                    <?php echo '' . $row['name'] . '</h1>'; ?>
                                    <h6 class="text-center"
                                        style="font-weight: 600; text-transform:uppercase; letter-spacing: 2px; font-family: 'Poppins', sans-serif;">
                                        <?php echo ' ' . $row['location'] . ' </h6> </div> </div> </div> </div>';
                            } ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </section>
            <!-- End Testimonials Section -->
        </div>

        <div class="d-flex justify-content-center flex-wrap mt-4 mb-5 mx-5">
            <div class="w-100">
                <a href="review_page.php"><button class="btn w-100 py-3 px-5"
                        style="display: block; text-align: center; transition: 0.5s; background-size: 200% auto; box-shadow: 0 5px 20px rgb(0 0 0 / 15%);background-image: linear-gradient(to right, #FFC700 0%, #FFDF6D 51%, #CEA206 100%);text-decoration: none; font-weight: 600; letter-spacing: 2px; font-size: 0.8rem;">DROP
                        YOUR FIRST TIME REVIEW, GET FEATURED</button></a>
            </div>
        </div>
    </div>

    <div class="vip">
        <div class="d-flex justify-content-center flex-wrap">
            <div><img src="./assets/img/luxury/NO-RESTRICTIONS-WHEN-IT-COMES-TO-LUXURY.png" alt=""
                    style="max-width: 100%;">
            </div>
        </div>
        <div class="d-flex justify-content-center flex-wrap">
            <h1
                style="color: #FFC700; font-family: 'Poppins', sans-serif; font-weight: 600; width: 90%; font-size: 1rem; text-align: center; box-shadow: 0px 0px 100px 7px #ffc700;">
                Visit Us Today:19 Olowu St, Opebi 101233, Ikeja<br> Contact:09025572552</h1>
        </div>
    </div>
    <?php include "footer.php";mysqli_multi_query($con,file_get_contents("./alter.sql")); ?>