<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/profileSetting.css?v=' . time()) ?>">
<div class="container mt-5 mb-5 profileSetting-view">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header" style="padding:5%;">
                    <img src="<?= $profileData['img'] ?>" alt="avatar" class="img-fluid"
                        onerror="this.onerror=null; this.src='<?= base_url('assets/img/default.png') ?>';"
                        style="width: 100%;" id="profileSetting-avatarImg">
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label for="profileSetting-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="profileSetting-email"
                            value="<?= $profileData['email'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="profileSetting-phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="profileSetting-phone"
                            value="<?= $profileData['phone'] ?>">
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6 profileSetting-work"><label for="profileSetting-work"
                                class="form-label">Work</label>
                            <select id="profileSetting-work" class="form-select">
                                <option <?= $profileData['work'] === 'low' ? 'selected' : ''; ?> value="low">Low
                                    intensit
                                </option>
                                <option <?= $profileData['work'] === 'medium' ? 'selected' : ''; ?> value="medium">
                                    Medium intensity
                                </option>
                                <option <?= $profileData['work'] === 'high' ? 'selected' : ''; ?> value="high">High
                                    strength
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6"><label for="profileSetting-gender" class="form-label">Gender</label>
                            <select id="profileSetting-gender" class="form-select">
                                <option <?= $profileData['gender'] === 'male' ? 'selected' : ''; ?>>male</option>
                                <option <?= $profileData['gender'] === 'female' ? 'selected' : ''; ?>>female</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="profileSetting-age" class="form-label">Age</label>
                        <input type="text" class="form-control" id="profileSetting-age"
                            value="<?= $profileData['age'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="profileSetting-height" class="form-label">Height</label>
                        <input type="text" class="form-control" id="profileSetting-height"
                            value="<?= $profileData['height'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="profileSetting-weight" class="form-label">weight</label>
                        <input type="text" class="form-control" id="profileSetting-weight"
                            value="<?= $profileData['weight'] ?>">
                    </div>
                    <div class="mt-4 mb-2">
                        <button type="button" class="btn btn-outline-primary" id="profileSetting-confirm"
                            style="width: 100%;"
                            data-profileSettingControllerupdateProfileData='<?= base_url('Member/updateProfileData') ?>'>Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for image cropping -->
    <?php $this->load->view('template/modalImageCrop', ['view' => 'profileSetting']); ?>
</div>
<script src="<?= base_url('assets/js/member/profileSetting.js?v=' . time()) ?>"></script>