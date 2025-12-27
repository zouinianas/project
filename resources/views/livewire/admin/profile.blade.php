<div>
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
            <div class="pd-20 card-box height-100-p">
                <div class="profile-photo">
                    <a href="javascript:;" onclick="event.preventDefault();document.getElementById('profilePictureFile').click();" class="edit-avatar">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <img src="{{ $user->picture }}" alt="" class="avatar-photo" id="profilePicturePreview">
                    <input type="file" name="profilePictureFile" id="profilePictureFile" class="d-none" style="opacity: 0">
                </div>
                <h5 class="text-center h5 mb-0">{{ $user->name }}</h5>
                <p class="text-center text-muted font-14">
                    {{ $user->email }}
                </p>

                <div class="profile-social">
                    <h5 class="mb-20 h5 text-blue">Social Links</h5>
                    <ul class="clearfix">
                        <!-- ... (les boutons sociaux restent inchangés) ... -->
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
            <div class="card-box height-100-p overflow-hidden">
                <div class="profile-tab height-100-p">
                    <div class="tab height-100-p">
                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item">
                                <a wire:click="selectTab('personal_details')" class="nav-link {{ $tab == 'personal_details' ? 'active' : ''}}" data-toggle="tab" href="#personal_details" role="tab">Personal details</a>
                            </li>
                            <li class="nav-item">
                                <a wire:click="selectTab('update_password')" class="nav-link {{ $tab == 'update_password' ? 'active' : ''}}" data-toggle="tab" href="#update_password" role="tab">Update Password</a>
                            </li>
                            <li class="nav-item">
                                <a wire:click="selectTab('social_links')" class="nav-link {{ $tab == 'social_links' ? 'active' : ''}}" data-toggle="tab" href="#social_links" role="tab">Social Links</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Personal Details Tab -->
                            <div class="tab-pane fade {{ $tab == 'personal_details' ? 'show active' : '' }}" id="personal_details" role="tabpanel">
                                <div class="pd-20">
                                    <form wire:submit.prevent="updatePersonalDetails">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Full name</label>
                                                    <input type="text" class="form-control" wire:model="name" placeholder="Enter full name">
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Email</label>
                                                    <input type="text" class="form-control" wire:model="email" placeholder="Enter email address" disabled>
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Username</label>
                                                    <input type="text" class="form-control" wire:model="username" placeholder="Enter username">
                                                    @error('username')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="bio">Bio</label>
                                                    <textarea wire:model="bio" id="bio" cols="4" rows="4" class="form-control" placeholder="Type your bio..."></textarea>
                                                    @error('bio')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save"></i> Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Update Password Tab -->
                            <div class="tab-pane fade {{ $tab == 'update_password' ? 'show active' : '' }}" id="update_password" role="tabpanel">
                                <div class="pd-20">
                                    <form wire:submit.prevent="updatePassword">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Current Password</label>
                                                    <input type="password" class="form-control" wire:model="current_password" placeholder="Enter current password">
                                                    @error('current_password')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">New Password</label>
                                                    <input type="password" class="form-control" wire:model="new_password" placeholder="New password">
                                                    @error('new_password')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Confirm New Password</label>
                                                    <input type="password" class="form-control" wire:model="new_password_confirmation" placeholder="Confirm new password">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update Password</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Social Links Tab -->
                            <div class="tab-pane fade {{ $tab == 'social_links' ? 'show active' : '' }}" id="social_links" role="tabpanel">
                                <div class="pd-20">
                                    <form wire:submit.prevent="updateSocialLinks">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for=""><b>Facebook</b></label>
                                                    <input type="text" class="form-control" wire:model="facebook_url" placeholder="Facebook URL">
                                                    @error('facebook_url')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for=""><b>Github</b></label>
                                                    <input type="text" class="form-control" wire:model="github_url" placeholder="Github URL">
                                                    @error('github_url')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for=""><b>LinkedIn</b></label>
                                                    <input type="text" class="form-control" wire:model="linkedin_url" placeholder="LinkedIn URL">
                                                    @error('linkedin_url')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for=""><b>Youtube</b></label>
                                                    <input type="text" class="form-control" wire:model="youtube_url" placeholder="Youtube URL">
                                                    @error('youtube_url')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for=""><b>Twitter</b></label>
                                                    <input type="text" class="form-control" wire:model="twitter_url" placeholder="Twitter URL">
                                                    @error('twitter_url')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for=""><b>Instagram</b></label>
                                                    <input type="text" class="form-control" wire:model="instagram_url" placeholder="Instagram URL">
                                                    @error('instagram_url')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('profilePictureFile').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('profilePicturePreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
