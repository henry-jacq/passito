<div class="h-100 d-flex align-items-center justify-content-center row user-select-none min-vh-100">
    <div class="py-3 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
        <form class="user-login-form" method="post" autocomplete="off">
            <div class="form-control px-4 py-3 bg-dark shadow-lg bg-opacity-25">
                <img src="/assets/brand/passito-logo.png" alt="logo" class="img-fluid mx-auto d-block mb-2" width="63" height="63">
                <h4 class="fw-light text-center mb-4">Passito</h4>
                <h5 class="fw-semi-bold mb-4">Login</h5>
                <label for="user" class="form-label">Digital ID</label>
                <input type="text" id="user" name="user" class="form-control mb-3 bg-transparent" required="">
                <label for="pass" class="form-label">Password</label>
                <div class="input-group mb-3">
                    <input type="password" id="pass" name="password" class="form-control bg-transparent" required="">
                    <span class="input-group-text bg-transparent focus-ring focus-ring-prime" id="icon-click" role="button" tabindex="0">
                        <i class="bi-eye-slash" id="icon"></i>
                    </span>
                </div>
                <div class="row mb-3">
                    <div class="col text-start">
                        <div class="form-check">
                            <input class="form-check-input bg-transparent" type="checkbox" id="rememberMe" role="button">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                    </div>
                    <div class="col text-end">
                        <a href="/forgot-password">Forgot password?</a>
                    </div>
                </div>
                <div class="d-grid gap-3">
                    <button type="submit" class="btn btn-login text-body border-0 bg-prime bg-opacity-75 focus-ring focus-ring-prime">Login now!</button>
                    <p class="text-center text-muted">Want to join Passito? <a href="/register">Register now</a>.</p>
                </div>
            </div>
        </form>
    </div>
</div>