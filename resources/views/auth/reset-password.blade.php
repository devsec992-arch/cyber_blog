<x-layouts.app title="Register">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-5">
        <div class="card shadow-lg border-0 p-4">
          <div class="card-body">
            <h2 class="mb-4 fw-bold text-primary text-center">reset password</h2>
            <form action="{{route('password.update')}}" method="POST">
              @csrf
                              <input type="hidden" value="{{ $request->route('token')}}" name="token">

              @if (session()->has('status'))
              <div class="alert alert-danger">{{session()->get('status')}}</div>
                  
              @endif
           
              <div class="mb-3">
                <label for="registerEmail" class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control rounded-pill" id="registerEmail" 
                placeholder="Your email" name="email" value="{{$request->email }}">
              </div>
              <div class="mb-3">
                <label for="registerPassword" class="form-label fw-semibold">Password</label>
                <input type="password" class="form-control rounded-pill" id="registerPassword" placeholder="Strong password here" name="password" required>
              </div>
              <div class="mb-4">
                <label for="registerPasswordConfirm" class="form-label fw-semibold">Confirm Password</label>
                <input type="password" class="form-control rounded-pill" id="registerPasswordConfirm" placeholder="Repeat the password again" name="password_confirmation" required>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold">reset</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-layouts.app>