<x-layout.guest>
    <div class="d-flex align-items-center justify-content-center min-vh-100 container">
        <div class="" style="max-width: 500px; width: 100%;">
            <div class="text-center">
                <div class="mb-4">
                    <a href="index.html" class="d-inline-block mb-4">
                        <img src="./assets/images/logo-icon.svg" alt="" width="36">
                        <span class="ms-2"><img src="./assets/images/logo.svg" alt=""></span>
                    </a>
                </div>

                <h1 class="display-1 fw-bold text-primary mb-2">404</h1>
                <h2 class="card-title h4 mb-3">Page Not Found</h2>
                <p class="text-muted mb-4">Sorry, the page you're looking for doesn't exist or has been moved.</p>

                <a href="{{ url()->previous() }}" class="btn btn-primary">Go Back</a>
            </div>
        </div>
    </div>
</x-layout.guest>
