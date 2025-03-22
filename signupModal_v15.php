<!-- Signup Modal -->
<div class="modal" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="viewLog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title text-center">Sign Up</h3>
            </div>
            <div class="modal-body">
                <form action="signup.php" method="post" class="text-center" id="signUpForm">
                    <div class='step' data-step="1">
                        <h3 class="page-header">User Information</h3>
                        <div class="form-group">
                            <label for="firstname">First Name*</label>
                            <input type="text" id="signup_firstname" class="form-control" name="firstname" />
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name*</label>
                            <input type="text" id="signup_lastname" class="form-control" name="lastname" />
                        </div>
                        <div class="form-group">
                            <label for="user-nationality">Nationality</label>
                            <input type="text" id="signup_user-nationality" class="form-control" name="user-nationality" />
                        </div>
                        <div class="form-group">
                            <label for="email">Email*</label>
                            <input type="text" id="signup_email" class="form-control" name="email" />
                        </div>
                        <div class="form-group">
                            <label for="phone">Cellular Phone</label>
                            <input type="text" id="signup_phone" class="form-control" name="phone" />
                        </div>
                        <div class="form-group">
                            <input type="button" class="form-control btn btn-primary col-md-6 step-btn" value="Next"
                                data-increment="1">
                        </div>
                    </div>

                    <div class="step" data-step="2">
                        <h3 class="page-header">Log in Information</h3>
                        <div class="form-group">
                            <label for="username">Username*<span id="usernameTaken"></span></label>
                            <input type="text" id="signup_username" class="form-control" name="username"
                                placeholder="Choose a username" />
                        </div>
                        <div class="form-group">
                            <label for="password">Password*</label>
                            <input type="password" id="signup_password" class="form-control" name="password"
                                placeholder="Enter a password" />
                        </div>
                        <div class="form-group">
                            <label for="confpassword">Confirm Password*</label>
                            <input type="password" id="signup_confpassword" class="form-control" name="confpassword"
                                placeholder="Re-enter password" />
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="user-agreement-check"> I hereby agree to the <a
                                    data-toggle="modal" data-target="#user-agreement">Terms of Use.</a>
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="button" class="form-control btn btn-primary col-md-6 step-btn" value="Back"
                                data-increment="-1">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="button" id="signup_loginBtn" class="form-control btn btn-primary"
                                value="Sign Up Now!" onclick="signUp(this.form);">
                        </div>
                    </div>
                    <p class="outer-top-xxs outer-bottom-xxs">* Required Information</p>
                </form>
            </div>
            <div class="modal-footer">
                Already have an account? <button class="btn btn-primary btn-sm" data-dismiss="modal"
                    data-toggle="modal" data-target="#loginModal">Log In</button>
            </div>
        </div>
    </div>
</div>