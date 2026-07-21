<x-layouts.app title="Login">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card shadow-lg border-0 p-4">
                    <div class="card-body">
                        <h2 class="mb-4 fw-bold text-primary text-center">reset password</h2>
                        <form action="{{route('password.request')}}" method="POST" id="loginForm">
                            @csrf
                            @if (session()->has('status'))
                            <div class="alert alert-danger">{{session()->get('status')}}</div>
                                
                            @endif
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control rounded-pill" id="loginEmail" 
                                placeholder="Enter your email" name="email"  autofocus>
                            </div>
                            @error('email')
                            <div class="text-success">{{$message}}</div>
                                
                            @enderror
                                         <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold">reset</button>

                            </div>
                        </div>
                    </div>
                </div>
    </div>
</x-layouts.app>