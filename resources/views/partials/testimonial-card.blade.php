<div class="d-flex flex-column justify-content-between">
    <!-- Star Ratings -->
    <div class="col-10 mx-auto" style="min-height: 180px;">
        <div class="d-flex align-items-center mb-1">
            <span class="me-2 fw-bold">Service Speed:</span>
            @for ($i = 1; $i <= 5; $i++)
                <i class="fa{{ $i <= $testimonial->service_speed ? 's' : 'r' }} fa-star text-warning"></i>
            @endfor
        </div>
        <div class="d-flex align-items-center mb-1">
            <span class="me-2 fw-bold">Egg Quality:</span>
            @for ($i = 1; $i <= 5; $i++)
                <i class="fa{{ $i <= $testimonial->egg_quality ? 's' : 'r' }} fa-star text-warning"></i>
            @endfor
        </div>
        <div class="d-flex align-items-center">
            <span class="me-2 fw-bold">Egg Size Accuracy:</span>
            @for ($i = 1; $i <= 5; $i++)
                <i class="fa{{ $i <= $testimonial->egg_size_accuracy ? 's' : 'r' }} fa-star text-warning"></i>
            @endfor
        </div>
        <!-- Comment -->
        <p class="card-text fst-italic text-dark mt-3">
            “{{ $testimonial->comment }}”
        </p>
    </div>

    <div class="col-9 mx-auto mt-3">
        <!-- Date -->
        <small class="text-muted">
            <i>Posted on {{ \Carbon\Carbon::parse($testimonial->created_at)->format('F d, Y') }}</i>
        </small>
    </div>
</div>
