
    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-secondary mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <h5 class="text-secondary text-uppercase mb-4">Get In Touch</h5>
                <p class="mb-4">Questions about listings or switching supplier? Use the details below — we aim to reply within a few working days.</p>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>{if $address}{$address}{else}—{/if}</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>{if $email}<a class="text-secondary" href="mailto:{$email}">{$email}</a>{else}—{/if}</p>
                {if $tel}<p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>{$tel}</p>{/if}
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Recently Evaluated</h5>
                        <div class="d-flex flex-column justify-content-start">
                            {foreach $articles6a as $article}
                                <a class="text-secondary mb-2" href="{$article.url}"><i class="fa fa-angle-right mr-2"></i>{$article.title}</a>
                            {/foreach}
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Recently Updated</h5>
                        <div class="d-flex flex-column justify-content-start">
                            {foreach $articles6b as $article}
                                <a class="text-secondary mb-2" href="{$article.url}"><i class="fa fa-angle-right mr-2"></i>{$article.title}</a>
                            {/foreach}
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Newsletter</h5>
                        <p>Stay updated with ways to reduce your energy bills</p>
                        <form action="">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Your Email Address">
                                <div class="input-group-append">
                                    <button class="btn btn-primary">Sign Up</button>
                                </div>
                            </div>
                        </form>
                        <h6 class="text-secondary text-uppercase mt-4 mb-3">Follow Us</h6>
                        <div class="d-flex">
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a class="btn btn-primary btn-square" href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top mx-xl-5 py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-secondary">
                    &copy; <a class="text-primary" href="home">{$site_host|default:$base_url}</a>. All Rights Reserved.
                    &nbsp;&nbsp;<a class="text-primary" href="about">About</a>&nbsp;&nbsp;
                   |&nbsp; &nbsp;  <a class="text-primary" href="privacy-policy">Privacy</a>&nbsp;&nbsp;| &nbsp;
                    <a class="text-primary" href="terms-conditions">Terms</a> 
                 </p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                <img class="img-fluid" src="img/payments.png" alt="">
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>