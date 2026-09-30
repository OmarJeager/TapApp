@extends('layouts.admin')

@section('content')
	@php($user = auth()->user())

	<div class="container-fluid py-4">
		<div class="row justify-content-center">
			<div class="col-xl-10">
				<div class="card border-0 shadow-sm">
					<div class="card-header bg-white py-3">
						<h4 class="mb-0">My Profile</h4>
						<small class="text-muted">View and update your account information.</small>
					</div>

					<form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
						@csrf
						@method('PUT')

						<div class="card-body">
							@if (session('success'))
								<div class="alert alert-success">{{ session('success') }}</div>
							@endif
							@if ($errors->any())
								<div class="alert alert-danger">
									<ul class="mb-0">
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif

							<div class="row g-4">
								<div class="col-md-4 text-center">
									<div class="profile-frame mx-auto mb-3">
										<img id="profile-preview"
											 src="{{ $user->profile_photo_url ?? ($user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('images/default-avatar.png')) }}"
											 alt="Profile photo" class="rounded-circle w-100 h-100 object-fit-cover">
									</div>
									<label for="profile_photo" class="btn btn-outline-primary">Change profile photo</label>
									<input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="d-none">
									<div class="form-text">JPG, PNG or WEBP. Maximum 2 MB.</div>
								</div>

								<div class="col-md-8">
									<div class="row g-3">
										@foreach ($user->getFillable() as $field)
											@continue(in_array($field, ['password', 'remember_token', 'profile_photo']))
											<div class="col-md-6">
												<label for="{{ $field }}" class="form-label text-capitalize">{{ str_replace('_', ' ', $field) }}</label>
												<input id="{{ $field }}" name="{{ $field }}" type="{{ str_contains($field, 'email') ? 'email' : 'text' }}"
													   value="{{ old($field, $user->{$field}) }}" class="form-control @error($field) is-invalid @enderror">
												@error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
											</div>
										@endforeach

										<div class="col-md-6">
											<label for="password" class="form-label">New password</label>
											<input id="password" name="password" type="password" class="form-control">
											<div class="form-text">Leave blank to keep your current password.</div>
										</div>
										<div class="col-md-6">
											<label for="password_confirmation" class="form-label">Confirm password</label>
											<input id="password_confirmation" name="password_confirmation" type="password" class="form-control">
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-footer bg-white text-end">
							<button type="submit" class="btn btn-primary px-4">Save profile</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<style>
		.profile-frame { width: 180px; height: 180px; padding: 6px; border: 4px solid #0d6efd; border-radius: 50%; background: #fff; }
		.object-fit-cover { object-fit: cover; }
	</style>

	<script>
		document.getElementById('profile_photo').addEventListener('change', function (event) {
			const file = event.target.files[0];
			if (file) document.getElementById('profile-preview').src = URL.createObjectURL(file);
		});
	</script>
@endsection
