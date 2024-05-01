<div class="container login-view" style="margin-top: 30px;">
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-8">
            <form>
                <div class="mb-3">
                    <label for="login-email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="login-email" aria-describedby="login-emailHelp"
                        value="<?= $rememberMeEmail ?>">
                    <div id="login-emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="login-password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="login-password">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="login-checkMeOut">
                    <label class="form-check-label" for="login-checkMeOut">Check me out</label>
                </div>
                <button type="button" id="login-login" class="btn btn-outline-primary"
                    data-loginControllerAuth='<?= base_url('Users/auth'); ?>'>Login</button>
            </form>
            <div class="mt-3 text-center">
                Don't have an account? <a href="<?= base_url('register') ?>" class="text-primary">Register</a>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/login.js?v=' . time()) ?>"></script>

<!-- base_url()結果 : http://[::1]/imdeting/

本機開發環境中的 URL，其中 [::1] 是 IPv6 環回位址，
等效於 IPv4 中的 localhost 或 127.0.0.1。 
通常用於在本機上執行的 Web 伺服器（例如，Apache、Nginx）。 -->