@extends('frontend.layout.app')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/showcase.css') }}">
@endsection

@section('content')
<!-- Showcase Hero -->
<section class="showcase-hero">
    <div class="showcase-hero-bg"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <h1 class="showcase-hero-title">Student Showcase</h1>
        <p class="showcase-hero-subtitle">Witness the incredible portfolios, showreels, and award-winning projects created by our students across VFX, Gaming, and UI/UX.</p>
        
        <div class="showcase-filters">
            <button class="showcase-filter-btn active" data-filter="all">All Projects</button>
            @foreach($showcaseCategories as $category)
                <button class="showcase-filter-btn" data-filter="{{ $category->slug }}">{{ $category->name }}</button>
            @endforeach
        </div>
    </div>
</section>

<!-- Showcase Grid Section -->
<section class="showcase-section">
    <div class="showcase-container">
        <div class="showcase-grid" id="showcaseGrid">
            @forelse($showcaseProjects as $project)
                <div class="showcase-card" data-category="{{ $project->category->slug }}">
                    <div class="showcase-card-image-wrap">
                        @if($project->thumbnail)
                            <img src="{{ asset($project->thumbnail->storage_key) }}" alt="{{ $project->title }}" class="showcase-card-image" loading="lazy">
                        @else
                            <div style="background: #1f2937; width: 100%; height: 100%; display:flex; align-items:center; justify-content:center;">
                                <span style="color:#6b7280;">No Thumbnail</span>
                            </div>
                        @endif
                        
                        @if($project->video_url)
                        <div class="showcase-play-btn">
                            <a href="{{ $project->video_url }}" target="_blank" class="showcase-play-icon" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="#0073e6" stroke="none"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="showcase-card-content">
                        <div class="showcase-student-info">
                            <h3 class="showcase-student-name">{{ $project->student_name }}</h3>
                            <span class="showcase-category-badge">{{ $project->category->name }}</span>
                        </div>
                        <h4 style="color: #fff; margin: 0 0 10px 0; font-size: 1.1rem; font-weight: 500;">{{ $project->title }}</h4>
                        <p class="showcase-description">{{ $project->short_description }}</p>
                    </div>
                </div>
            @empty
                <div class="premium-empty-state" style="grid-column: 1 / -1; background: linear-gradient(145deg, #1f2937, #111827); padding: 4rem 2rem; border-radius: 16px; text-align: center; border: 1px solid rgba(255,255,255,0.05); box-shadow: 0 20px 40px rgba(0,0,0,0.4);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#4b5563" stroke-width="1.5" style="margin-bottom: 1.5rem;">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    </svg>
                    <h3 style="color: #fff; font-size: 1.5rem; margin-bottom: 1rem; font-weight: 600;">Exciting Projects Coming Soon</h3>
                    <p style="color: #9ca3af; max-width: 500px; margin: 0 auto; line-height: 1.6;">Our students are hard at work crafting amazing portfolios and showreels. Check back shortly to witness their incredible creations.</p>
                </div>
            @endforelse

        </div>
    </div>
</section>
@endsection

@section('custom_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Showcase Category Filtering
    const filterBtns = document.querySelectorAll('.showcase-filter-btn');
    const showcaseCards = document.querySelectorAll('.showcase-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const filter = btn.getAttribute('data-filter');
            
            // Filter cards
            showcaseCards.forEach(card => {
                const category = card.getAttribute('data-category');
                
                if (filter === 'all' || filter === category) {
                    card.style.display = 'flex';
                    // small animation effect
                    card.style.animation = 'none';
                    card.offsetHeight; /* trigger reflow */
                    card.style.animation = null;
                    card.style.opacity = '0';
                    setTimeout(() => { card.style.opacity = '1'; }, 50);
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endsection
